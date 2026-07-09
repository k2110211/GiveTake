<?php
 
namespace Tests\Feature;
 
use App\Livewire\Home;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class HomeComponentTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_home_page_can_be_rendered(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Give & Take', false)
            ->assertSee('Cho Đi Là Nhận Lại');
    }
 
    public function test_can_filter_items_by_search_keyword(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
        
        $item1 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Math Book 10',
            'description' => 'A great math book',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $item2 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Physics Book 10',
            'description' => 'A great physics book',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        Livewire::test(Home::class)
            ->set('search', 'Math')
            ->assertSee('Math Book 10')
            ->assertDontSee('Physics Book 10');
    }
 
    public function test_can_filter_items_by_type(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
        
        $item1 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Give Item',
            'description' => 'A gift',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $item2 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Exchange Item',
            'description' => 'An exchange',
            'images' => [],
            'type' => 'exchange',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        Livewire::test(Home::class)
            ->set('type', 'give')
            ->assertSee('Give Item')
            ->assertDontSee('Exchange Item');
    }
}
