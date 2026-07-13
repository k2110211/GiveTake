<?php
 
namespace Tests\Feature;
 
use App\Livewire\ItemDetail;
use App\Models\Category;
use App\Models\Item;
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
}
