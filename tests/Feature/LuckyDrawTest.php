<?php

namespace Tests\Feature;

use App\Livewire\ItemDetail;
use App\Livewire\PostItem;
use App\Models\Category;
use App\Models\City;
use App\Models\District;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class LuckyDrawTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $city;
    protected $district;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['karma_points' => 50]);
        $this->category = Category::firstOrCreate(['name' => 'Thời trang'], ['slug' => 'thoi-trang']);
        $this->city = City::firstOrCreate(['name' => 'Hồ Chí Minh']);
        $this->district = District::firstOrCreate(['name' => 'Quận 1', 'city_id' => $this->city->id]);
    }

    public function test_can_post_lucky_draw_item_with_min_karma(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user);

        Livewire::test(PostItem::class)
            ->set('title', 'Đồng hồ quay thưởng may mắn')
            ->set('description', 'Món đồ quay thưởng dành cho các bạn đủ điểm karma')
            ->set('categoryId', $this->category->id)
            ->set('type', 3) // Quay thưởng
            ->set('minKarma', 20)
            ->set('city', $this->city->id)
            ->set('district', $this->district->id)
            ->set('thumbnail', UploadedFile::fake()->image('thumbnail.jpg'))
            ->call('save');

        $this->assertDatabaseHas('items', [
            'title' => 'Đồng hồ quay thưởng may mắn',
            'type_id' => 3,
            'min_karma' => 20,
        ]);
    }

    public function test_user_with_enough_karma_can_register_lucky_draw(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Tai nghe Sony quay thưởng',
            'description' => 'Mô tả tai nghe sony quay thưởng',
            'thumbnail' => 'http://example.com/thumb.jpg',
            'images' => [],
            'type_id' => 3,
            'min_karma' => 30,
            'item_status_id' => 1,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id
        ]);

        $participant = User::factory()->create(['karma_points' => 40]);
        $this->actingAs($participant);

        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('openRequestModal')
            ->call('submitRequest');

        $this->assertDatabaseHas('item_requests', [
            'item_id' => $item->id,
            'user_id' => $participant->id,
            'request_status_id' => 1
        ]);
    }

    public function test_user_without_enough_karma_cannot_register_lucky_draw(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Laptop quay thưởng',
            'description' => 'Mô tả laptop quay thưởng',
            'thumbnail' => 'http://example.com/thumb.jpg',
            'images' => [],
            'type_id' => 3,
            'min_karma' => 100,
            'item_status_id' => 1,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id
        ]);

        $poorKarmaUser = User::factory()->create(['karma_points' => 10]);
        $this->actingAs($poorKarmaUser);

        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('openRequestModal');

        $this->assertDatabaseMissing('item_requests', [
            'item_id' => $item->id,
            'user_id' => $poorKarmaUser->id,
        ]);
    }

    public function test_owner_can_draw_random_winner(): void
    {
        $item = Item::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Bàn phím cơ quay thưởng',
            'description' => 'Mô tả bàn phím cơ quay thưởng',
            'thumbnail' => 'http://example.com/thumb.jpg',
            'images' => [],
            'type_id' => 3,
            'min_karma' => 10,
            'item_status_id' => 1,
            'city_id' => $this->city->id,
            'district_id' => $this->district->id
        ]);

        $userA = User::factory()->create(['karma_points' => 20]);
        $userB = User::factory()->create(['karma_points' => 20]);

        ItemRequest::create(['item_id' => $item->id, 'user_id' => $userA->id, 'message' => 'Xin 1 vé', 'request_status_id' => 1]);
        ItemRequest::create(['item_id' => $item->id, 'user_id' => $userB->id, 'message' => 'Xin 1 vé', 'request_status_id' => 1]);

        $this->actingAs($this->user);

        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('drawWinner');

        $freshItem = $item->fresh();
        $this->assertNotNull($freshItem->winner_id);
        $this->assertContains($freshItem->winner_id, [$userA->id, $userB->id]);
        $this->assertEquals(3, $freshItem->item_status_id);

        $winningRequest = ItemRequest::where('item_id', $item->id)->where('user_id', $freshItem->winner_id)->first();
        $this->assertEquals(2, $winningRequest->request_status_id); // Approved

        $this->assertDatabaseHas('chat_rooms', [
            'item_request_id' => $winningRequest->id
        ]);
    }
}
