<?php
 
namespace Tests\Feature;
 
use App\Livewire\Dashboard;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class DashboardTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }
 
    public function test_dashboard_renders_successfully_for_authenticated_users(): void
    {
        $user = User::factory()->create(['karma_points' => 150]);
        $this->actingAs($user);
 
        $this->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Bảng quản trị của tôi')
            ->assertSee('150');
    }
 
    public function test_can_approve_request_successfully(): void
    {
        $owner = User::factory()->create();
        $requester1 = User::factory()->create();
        $requester2 = User::factory()->create();
 
        $category = Category::create(['name' => 'Books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $req1 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester1->id,
            'message' => 'I would love to learn Laravel from this book.',
            'request_status_id' => 1
        ]);
 
        $req2 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester2->id,
            'message' => 'Please give me this book for my exam.',
            'request_status_id' => 1
        ]);
 
        $this->actingAs($owner);
 
        Livewire::test(Dashboard::class)
            ->call('approveRequest', $req1->id)
            ->assertHasNoErrors();
 
        // 1. Assert request is approved
        $this->assertEquals(2, $req1->refresh()->request_status_id);
 
        // 2. Assert item is reserved
        $this->assertEquals(3, $item->refresh()->item_status_id);
 
        // 3. Assert other request is rejected
        $this->assertEquals(3, $req2->refresh()->request_status_id);
 
        // 4. Assert chat room is created
        $this->assertDatabaseHas('chat_rooms', [
            'item_request_id' => $req1->id
        ]);
    }
 
    public function test_can_reject_request_successfully(): void
    {
        $owner = User::factory()->create();
        $requester = User::factory()->create();
 
        $category = Category::create(['name' => 'Books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to learn Laravel from this book.',
            'request_status_id' => 1
        ]);
 
        $this->actingAs($owner);
 
        Livewire::test(Dashboard::class)
            ->call('rejectRequest', $req->id)
            ->assertHasNoErrors();
 
        $this->assertEquals(3, $req->refresh()->request_status_id);
        $this->assertEquals(1, $item->refresh()->item_status_id); // item should still be available
    }
 
    public function test_requester_can_cancel_request_successfully(): void
    {
        $owner = User::factory()->create();
        $requester = User::factory()->create();
 
        $category = Category::create(['name' => 'Books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 1,
            'city_id' => 1,
            'district_id' => 1
        ]);
 
        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to learn Laravel from this book.',
            'request_status_id' => 1
        ]);
 
        $this->actingAs($requester);
 
        Livewire::test(Dashboard::class)
            ->call('cancelRequest', $req->id)
            ->assertHasNoErrors();
 
        $this->assertDatabaseMissing('item_requests', [
            'id' => $req->id
        ]);
    }
}
