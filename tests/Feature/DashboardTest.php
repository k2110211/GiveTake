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
 
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $req1 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester1->id,
            'message' => 'I would love to learn Laravel from this book.',
            'status' => 'pending'
        ]);
 
        $req2 = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester2->id,
            'message' => 'Please give me this book for my exam.',
            'status' => 'pending'
        ]);
 
        $this->actingAs($owner);
 
        Livewire::test(Dashboard::class)
            ->call('approveRequest', $req1->id)
            ->assertHasNoErrors();
 
        // 1. Assert request is approved
        $this->assertEquals('approved', $req1->refresh()->status);
 
        // 2. Assert item is reserved
        $this->assertEquals('reserved', $item->refresh()->status);
 
        // 3. Assert other request is rejected
        $this->assertEquals('rejected', $req2->refresh()->status);
 
        // 4. Assert chat room is created
        $this->assertDatabaseHas('chat_rooms', [
            'item_request_id' => $req1->id
        ]);
    }
 
    public function test_can_reject_request_successfully(): void
    {
        $owner = User::factory()->create();
        $requester = User::factory()->create();
 
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to learn Laravel from this book.',
            'status' => 'pending'
        ]);
 
        $this->actingAs($owner);
 
        Livewire::test(Dashboard::class)
            ->call('rejectRequest', $req->id)
            ->assertHasNoErrors();
 
        $this->assertEquals('rejected', $req->refresh()->status);
        $this->assertEquals('available', $item->refresh()->status); // item should still be available
    }
 
    public function test_requester_can_cancel_request_successfully(): void
    {
        $owner = User::factory()->create();
        $requester = User::factory()->create();
 
        $category = Category::create(['name' => 'Books', 'slug' => 'books']);
        $item = Item::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Laravel Book',
            'description' => 'A comprehensive book on Laravel framework.',
            'images' => [],
            'type' => 'give',
            'status' => 'available',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy'
        ]);
 
        $req = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love to learn Laravel from this book.',
            'status' => 'pending'
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
