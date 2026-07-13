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
            ->assertSee('Tìm kiếm theo danh mục');
    }
 
    public function test_can_filter_items_by_search_keyword(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Books']);
        $city = \App\Models\City::firstOrCreate(['name' => 'Hà Nội']);
        $district = \App\Models\District::firstOrCreate(['city_id' => $city->id, 'name' => 'Cầu Giấy']);
        
        $item1 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Math Book 10',
            'description' => 'A great math book',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);
 
        $item2 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Physics Book 10',
            'description' => 'A great physics book',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);
 
        Livewire::test(\App\Livewire\SearchItems::class)
            ->set('search', 'Math')
            ->assertSee('Math Book 10')
            ->assertDontSee('Physics Book 10');
    }
 
    public function test_can_filter_items_by_type(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Books']);
        $city = \App\Models\City::firstOrCreate(['name' => 'Hà Nội']);
        $district = \App\Models\District::firstOrCreate(['city_id' => $city->id, 'name' => 'Cầu Giấy']);
        
        $item1 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Give Item',
            'description' => 'A gift',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);
 
        $item2 = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Exchange Item',
            'description' => 'An exchange',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 2,
            'item_status_id' => 1,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);
 
        Livewire::test(\App\Livewire\SearchItems::class)
            ->set('type', '1')
            ->assertSee('Give Item')
            ->assertDontSee('Exchange Item');
    }
}
