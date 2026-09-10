<?php

namespace Tests\Feature;

use App\Livewire\ChatRoom;
use App\Livewire\Layout\Navigation;
use App\Livewire\PostItem;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\ChatRoom as ChatRoomModel;
use App\Models\City;
use App\Models\District;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class GoLiveFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $city;
    protected $district;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('post-item:' . 1);
        RateLimiter::clear('item-request:' . 1);
        RateLimiter::clear('chat-send:' . 1);

        $this->user = User::factory()->create(['karma_points' => 50]);
        $this->category = Category::firstOrCreate(['name' => 'Thời trang'], ['slug' => 'thoi-trang']);
        $this->city = City::firstOrCreate(['name' => 'Hà Nội']);
        $this->district = District::firstOrCreate(['name' => 'Hoàn Kiếm', 'city_id' => $this->city->id]);
    }

    // ─── 1. ERROR PAGES ────────────────────────────────────────────────────────
    public function test_custom_404_error_page_renders_properly(): void
    {
        $response = $this->get('/non-existent-page-url-xyz');
        $response->assertStatus(404);
        $response->assertSee('Cho & Nhận', false);
        $response->assertSee('404');
        $response->assertSee('Không tìm thấy trang');
        $response->assertSee('Về trang chủ');
    }

    public function test_custom_403_error_page_renders_for_forbidden_access(): void
    {
        $this->actingAs($this->user);
        $response = $this->get('/admin');
        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Khu vực giới hạn truy cập');
    }

    // ─── 2. FAVICON & OPENGRAPH META ──────────────────────────────────────────
    public function test_app_layout_contains_favicon_and_opengraph(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('favicon.svg');
        $response->assertSee('og:site_name', false);
        $response->assertSee('Cho &amp; Nhận', false);
    }

    public function test_item_detail_pushes_dynamic_opengraph_tags(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Áo sơ mi lụa tơ tằm cổ điển cao cấp',
            'description' => 'Món đồ cần chia sẻ cho bạn nào cần sử dụng đi làm công sở.',
            'thumbnail' => 'items/sample_silk_shirt.jpg',
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $response = $this->get(route('item.detail', $item->id));
        $response->assertStatus(200);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('Áo sơ mi lụa tơ tằm cổ điển cao cấp');
        $response->assertSee('property="og:image"', false);
        $response->assertSee('sample_silk_shirt.jpg');
    }

    // ─── 3. RAFFLE DATE PICKER & AUTO-DRAW ARTISAN COMMAND ────────────────────
    public function test_can_post_raffle_with_future_end_date(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $futureDate = now()->addDays(3)->format('Y-m-d\TH:i');

        Livewire::test(PostItem::class)
            ->set('title', 'Máy sấy tóc ion âm bảo vệ tóc')
            ->set('description', 'Máy sấy tóc còn rất mới, tặng lại bạn nào may mắn nhất')
            ->set('categoryId', $this->category->id)
            ->set('type', 3)
            ->set('minKarma', 10)
            ->set('raffleEndsAt', $futureDate)
            ->set('city', $this->city->id)
            ->set('district', $this->district->id)
            ->set('thumbnail', UploadedFile::fake()->image('dryer.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('items', [
            'title' => 'Máy sấy tóc ion âm bảo vệ tóc',
            'type_id' => 3,
            'min_karma' => 10,
        ]);

        $item = Item::where('title', 'Máy sấy tóc ion âm bảo vệ tóc')->first();
        $this->assertNotNull($item->raffle_ends_at);
        $this->assertTrue($item->raffle_ends_at->isFuture());
    }

    public function test_post_raffle_rejects_past_end_date(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $pastDate = now()->subDay()->format('Y-m-d\TH:i');

        Livewire::test(PostItem::class)
            ->set('title', 'Máy sấy tóc ion âm bảo vệ tóc')
            ->set('description', 'Máy sấy tóc còn rất mới, tặng lại bạn nào may mắn nhất')
            ->set('categoryId', $this->category->id)
            ->set('type', 3)
            ->set('minKarma', 10)
            ->set('raffleEndsAt', $pastDate)
            ->set('city', $this->city->id)
            ->set('district', $this->district->id)
            ->set('thumbnail', UploadedFile::fake()->image('dryer.jpg'))
            ->call('save')
            ->assertHasErrors(['raffleEndsAt']);
    }

    public function test_artisan_command_draws_expired_raffles(): void
    {
        $giver = User::factory()->create();
        $participant1 = User::factory()->create(['karma_points' => 100]);
        $participant2 = User::factory()->create(['karma_points' => 100]);

        // Create expired raffle
        $raffleItem = Item::create([
            'user_id' => $giver->id,
            'category_id' => $this->category->id,
            'title' => 'Loa bluetooth mini xịn sò hết hạn quay',
            'description' => 'Mô tả chi tiết quà tặng quay thưởng cho thành viên',
            'thumbnail' => 'items/speaker.jpg',
            'type_id' => 3,
            'min_karma' => 20,
            'raffle_ends_at' => now()->subMinutes(10),
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        // Add 2 participants
        $req1 = ItemRequest::create([
            'item_id' => $raffleItem->id,
            'user_id' => $participant1->id,
            'message' => 'Mình đăng ký tham gia quay số!',
            'request_status_id' => 1,
        ]);

        $req2 = ItemRequest::create([
            'item_id' => $raffleItem->id,
            'user_id' => $participant2->id,
            'message' => 'Hi vọng mình sẽ may mắn nhận được loa!',
            'request_status_id' => 1,
        ]);

        // Run artisan command
        Artisan::call('raffle:draw-expired');

        $raffleItem->refresh();
        $this->assertEquals(3, $raffleItem->item_status_id); // Reserved / in progress
        $this->assertNotNull($raffleItem->winner_id);
        $this->assertTrue(in_array($raffleItem->winner_id, [$participant1->id, $participant2->id]));

        // Check requests updated
        $winningReq = ItemRequest::where('item_id', $raffleItem->id)->where('request_status_id', 2)->first();
        $this->assertNotNull($winningReq);
        $this->assertEquals($raffleItem->winner_id, $winningReq->user_id);

        $losingReq = ItemRequest::where('item_id', $raffleItem->id)->where('request_status_id', 3)->first();
        $this->assertNotNull($losingReq);

        // Check chat room created and system announcement dispatched
        $chatRoom = ChatRoomModel::where('item_request_id', $winningReq->id)->first();
        $this->assertNotNull($chatRoom);

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $giver->id,
        ]);
    }

    // ─── 4. RELATIVE IMAGE STORAGE & ACCESSORS ────────────────────────────────
    public function test_relative_image_accessor_and_compatibility(): void
    {
        // Case 1: Relative path stored
        $item1 = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test Item Relative Path',
            'description' => 'Mô tả chi tiết sản phẩm test đường dẫn ảnh',
            'thumbnail' => 'items/photo_123.jpg',
            'images' => ['items/photo_sub1.jpg', 'items/photo_sub2.jpg'],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $this->assertStringContainsString('/storage/items/photo_123.jpg', $item1->thumbnail);
        $this->assertStringContainsString('/storage/items/photo_sub1.jpg', $item1->images[0]);

        // Case 2: External Unsplash URL remains unmodified
        $externalUrl = 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format';
        $item2 = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test Item External URL',
            'description' => 'Mô tả chi tiết sản phẩm test đường dẫn ngoài',
            'thumbnail' => $externalUrl,
            'images' => [$externalUrl],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $this->assertEquals($externalUrl, $item2->thumbnail);
        $this->assertEquals($externalUrl, $item2->images[0]);

        // Case 3: Old absolute URL pointing to old domain storage is sanitized
        $oldAbsoluteUrl = 'http://old-domain.com/storage/items/old_pic.jpg';
        $item3 = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Test Item Legacy Absolute URL',
            'description' => 'Mô tả chi tiết sản phẩm test domain cũ',
            'thumbnail' => $oldAbsoluteUrl,
            'images' => [$oldAbsoluteUrl],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $this->assertStringNotContainsString('old-domain.com', $item3->thumbnail);
        $this->assertStringContainsString('/storage/items/old_pic.jpg', $item3->thumbnail);
    }

    // ─── 5. NOTIFICATION BELL & BADGE ─────────────────────────────────────────
    public function test_navigation_counts_unread_notifications_properly(): void
    {
        $giver = $this->user;
        $requester = User::factory()->create();

        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $this->category->id,
            'title' => 'Bàn học sinh thanh lý',
            'description' => 'Bàn còn chắc chắn, tặng lại cho các bạn sinh viên',
            'thumbnail' => 'items/desk.jpg',
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        // 1. Incoming pending request on giver's item
        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Em xin bàn này ạ!',
            'request_status_id' => 1,
        ]);

        // 2. An unread chat message from requester to giver
        $chatRoom = ChatRoomModel::create(['item_request_id' => $req->id]);
        ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $requester->id,
            'message' => 'Chào anh, anh đã duyệt cho em chưa ạ?',
            'is_read' => false,
        ]);

        // When logged in as Giver:
        $this->actingAs($giver);

        \Livewire\Volt\Volt::test('layout.navigation')
            ->assertViewHas('totalNotifications', 2)
            ->assertViewHas('pendingRequestsCount', 1)
            ->assertViewHas('unreadMessagesCount', 1)
            ->assertSee('2'); // Badge counter visible
    }

    // ─── 6. RATE LIMITING & ANTI-SPAM ─────────────────────────────────────────
    public function test_chat_room_rate_limiting(): void
    {
        $requester = User::factory()->create();
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Ghế xoay văn phòng',
            'description' => 'Mô tả ghế xoay văn phòng chất lượng cao',
            'thumbnail' => 'items/chair.jpg',
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id,
        ]);

        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Xin ghế',
            'request_status_id' => 2,
        ]);
        $room = ChatRoomModel::create(['item_request_id' => $req->id]);

        $this->actingAs($this->user);

        // Manually exhaust rate limiter for user
        for ($i = 0; $i < 30; $i++) {
            RateLimiter::hit('chat-send:' . $this->user->id, 60);
        }

        Livewire::test(ChatRoom::class, ['roomId' => $room->id])
            ->set('newMessage', 'Tin nhắn thứ 31 bị chặn spam')
            ->call('sendMessage')
            ->assertHasErrors(['newMessage']);
    }
}
