<?php
 
namespace Tests\Feature;
 
use App\Livewire\ItemDetail;
use App\Models\Category;
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class ItemDetailTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_item_detail_renders_successfully(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Fashion']);
        $item = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Vintage Jacket',
            'description' => 'A very nice jacket in good condition.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $this->get('/items/' . $item->id)
            ->assertStatus(200)
            ->assertSee('Vintage Jacket')
            ->assertSee('Hồ Chí Minh');
    }
 
    public function test_guest_user_is_redirected_on_clicking_request_button(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Fashion']);
        $item = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Vintage Jacket',
            'description' => 'A very nice jacket in good condition.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('openRequestModal')
            ->assertRedirect(route('login'));
    }
 
    public function test_logged_in_user_can_submit_request(): void
    {
        $owner = User::factory()->create();
        $requester = User::factory()->create();
        
        $category = Category::create(['name' => 'Fashion']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Vintage Jacket',
            'description' => 'A very nice jacket in good condition.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $this->actingAs($requester);
 
        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('openRequestModal')
            ->assertSet('showRequestModal', true)
            ->set('message', 'Hello, I really want to request this item for my son.')
            ->call('submitRequest')
            ->assertSet('showRequestModal', false)
            ->assertHasNoErrors();
 
        $this->assertDatabaseHas('item_requests', [
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'Hello, I really want to request this item for my son.',
            'request_status_id' => 1
        ]);

        $createdReq = ItemRequest::where('item_id', $item->id)->where('user_id', $requester->id)->first();
        $this->assertDatabaseHas('chat_rooms', [
            'item_request_id' => $createdReq->id
        ]);
    }
 
    public function test_user_cannot_request_own_item(): void
    {
        $owner = User::factory()->create();
        $category = Category::create(['name' => 'Fashion']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Vintage Jacket',
            'description' => 'A very nice jacket in good condition.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $this->actingAs($owner);
 
        Livewire::test(ItemDetail::class, ['id' => $item->id])
            ->call('openRequestModal')
            ->assertSet('showRequestModal', false)
            ->assertSee('Bạn không thể xin đồ của chính mình!');
    }

    public function test_guest_or_stranger_cannot_view_pending_item_detail(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $category = Category::create(['name' => 'Fashion']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Pending Jacket',
            'description' => 'Waiting for review',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => \App\Models\ItemStatus::PENDING,
            'city_id' => 1,
            'district_id' => 1
        ]);

        // Guest gets 404
        $this->get('/items/' . $item->id)->assertStatus(404);

        // Stranger gets 404
        $this->actingAs($stranger)
            ->get('/items/' . $item->id)
            ->assertStatus(404);
    }

    public function test_owner_and_admin_can_view_pending_item_with_banner(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Pending Book',
            'description' => 'Novel',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => \App\Models\ItemStatus::PENDING,
            'city_id' => 1,
            'district_id' => 1
        ]);

        // Owner can view with pending banner
        $this->actingAs($owner)
            ->get('/items/' . $item->id)
            ->assertStatus(200)
            ->assertSee('Đang chờ Ban Quản Trị phê duyệt');

        // Admin can view with pending banner
        $this->actingAs($admin)
            ->get('/items/' . $item->id)
            ->assertStatus(200)
            ->assertSee('Đang chờ Ban Quản Trị phê duyệt');
    }
}
