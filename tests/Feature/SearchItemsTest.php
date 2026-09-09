<?php

namespace Tests\Feature;

use App\Livewire\SearchItems;
use App\Models\Category;
use App\Models\City;
use App\Models\District;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_items_page_can_be_rendered(): void
    {
        $this->get('/search')
            ->assertStatus(200)
            ->assertSee('Khám phá kho chia sẻ');
    }

    public function test_search_items_displays_interest_chip_for_normal_item(): void
    {
        $user = User::factory()->create();
        $requester1 = User::factory()->create();
        $requester2 = User::factory()->create();
        $category = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
        $city = City::firstOrCreate(['name' => 'TP. Hồ Chí Minh']);
        $district = District::firstOrCreate(['city_id' => $city->id, 'name' => 'Quận 1']);

        $item = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Vintage Jacket',
            'description' => 'Warm vintage jacket',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1, // Give
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester1->id,
            'message' => 'Request 1',
            'request_status_id' => 1
        ]);

        ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester2->id,
            'message' => 'Request 2',
            'request_status_id' => 1
        ]);

        Livewire::test(SearchItems::class)
            ->assertSee('Vintage Jacket')
            ->assertSee('2 người ngỏ ý');
    }

    public function test_search_items_displays_lucky_draw_chip(): void
    {
        $user = User::factory()->create();
        $requester1 = User::factory()->create();
        $requester2 = User::factory()->create();
        $requester3 = User::factory()->create();
        $category = Category::create(['name' => 'Gadgets', 'slug' => 'gadgets']);
        $city = City::firstOrCreate(['name' => 'Đà Nẵng']);
        $district = District::firstOrCreate(['city_id' => $city->id, 'name' => 'Hải Châu']);

        $item = Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Wireless Earbuds',
            'description' => 'Lucky draw earbuds',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 3, // Lucky Draw
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        foreach ([$requester1, $requester2, $requester3] as $reqUser) {
            ItemRequest::create([
                'item_id' => $item->id,
                'user_id' => $reqUser->id,
                'message' => 'Join raffle',
                'request_status_id' => 1
            ]);
        }

        Livewire::test(SearchItems::class)
            ->assertSee('Wireless Earbuds')
            ->assertSee('3 người quay');
    }

    public function test_search_items_does_not_display_chip_when_zero_requests(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Stationery', 'slug' => 'stationery']);
        $city = City::firstOrCreate(['name' => 'Cần Thơ']);
        $district = District::firstOrCreate(['city_id' => $city->id, 'name' => 'Ninh Kiều']);

        Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Novel Notebook',
            'description' => 'Blank notebook',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        Livewire::test(SearchItems::class)
            ->assertSee('Novel Notebook')
            ->assertDontSee('người ngỏ ý')
            ->assertDontSee('người quay');
    }

    public function test_search_items_never_displays_pending_or_rejected_items(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Toys', 'slug' => 'toys']);
        $city = City::firstOrCreate(['name' => 'Hải Phòng']);
        $district = District::firstOrCreate(['city_id' => $city->id, 'name' => 'Lê Chân']);

        Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Approved Toy Car',
            'description' => 'Clean',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::AVAILABLE,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Secret Pending Drone',
            'description' => 'Waiting for approval',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::PENDING,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        Item::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Secret Rejected Weapon',
            'description' => 'Rejected',
            'thumbnail' => 'http://placehold.co/100x100.jpg',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => ItemStatus::REJECTED,
            'city_id' => $city->id,
            'district_id' => $district->id
        ]);

        Livewire::test(SearchItems::class)
            ->assertSee('Approved Toy Car')
            ->assertDontSee('Secret Pending Drone')
            ->assertDontSee('Secret Rejected Weapon');
    }
}
