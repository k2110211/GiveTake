<?php
 
namespace Tests\Feature;
 
use App\Livewire\Admin\CategoryIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ItemIndex;
use App\Livewire\Admin\ReviewIndex;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserIndex;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;
 
    private function makeAdmin(): User
    {
        return User::factory()->create(['is_admin' => true, 'is_banned' => false]);
    }
 
    private function makeUser(): User
    {
        return User::factory()->create(['is_admin' => false, 'is_banned' => false]);
    }
 
    // ─── Access Control ──────────────────────────────────────────────────
 
    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }
 
    public function test_regular_user_cannot_access_admin(): void
    {
        $this->actingAs($this->makeUser())
            ->get('/admin')
            ->assertStatus(403);
    }
 
    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin')
            ->assertOk();
    }
 
    public function test_admin_can_access_users_page(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/users')
            ->assertOk();
    }
 
    public function test_admin_can_access_items_page(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/items')
            ->assertOk();
    }
 
    public function test_admin_can_access_categories_page(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/categories')
            ->assertOk();
    }
 
    public function test_admin_can_access_reviews_page(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/reviews')
            ->assertOk();
    }
 
    public function test_admin_can_access_transactions_page(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/transactions')
            ->assertOk();
    }
 
    // ─── User Management ────────────────────────────────────────────────
 
    public function test_admin_can_ban_user(): void
    {
        $admin  = $this->makeAdmin();
        $target = $this->makeUser();
 
        $this->actingAs($admin);
 
        Livewire::test(UserIndex::class)
            ->call('toggleBan', $target->id);
 
        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_banned' => true]);
    }
 
    public function test_admin_can_unban_user(): void
    {
        $admin  = $this->makeAdmin();
        $target = User::factory()->create(['is_banned' => true, 'is_admin' => false]);
 
        $this->actingAs($admin);
 
        Livewire::test(UserIndex::class)
            ->call('toggleBan', $target->id);
 
        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_banned' => false]);
    }
 
    public function test_admin_cannot_ban_another_admin(): void
    {
        $admin  = $this->makeAdmin();
        $admin2 = $this->makeAdmin();
 
        $this->actingAs($admin);
 
        Livewire::test(UserIndex::class)
            ->call('toggleBan', $admin2->id);
 
        // Admin2 should remain unbanned
        $this->assertDatabaseHas('users', ['id' => $admin2->id, 'is_banned' => false]);
    }
 
    public function test_admin_can_promote_user_to_admin(): void
    {
        $admin  = $this->makeAdmin();
        $target = $this->makeUser();
 
        $this->actingAs($admin);
 
        Livewire::test(UserIndex::class)
            ->call('promoteAdmin', $target->id);
 
        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_admin' => true]);
    }
 
    // ─── Category Management ────────────────────────────────────────────
 
    public function test_admin_can_create_category(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);
 
        Livewire::test(CategoryIndex::class)
            ->set('name', 'Đồ điện tử')
            ->call('createCategory');
 
        $this->assertDatabaseHas('categories', ['name' => 'Đồ điện tử']);
    }
 
    public function test_admin_can_delete_category(): void
    {
        $admin = $this->makeAdmin();
        $cat   = Category::create(['name' => 'Test Cat', 'slug' => 'test-cat']);
        $this->actingAs($admin);
 
        Livewire::test(CategoryIndex::class)
            ->call('confirmDelete', $cat->id)
            ->call('deleteCategory');
 
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }
 
    public function test_admin_can_edit_category(): void
    {
        $admin = $this->makeAdmin();
        $cat   = Category::create(['name' => 'Old Name', 'slug' => 'old-name']);
        $this->actingAs($admin);
 
        Livewire::test(CategoryIndex::class)
            ->call('startEdit', $cat->id)
            ->set('editName', 'New Name')
            ->call('saveEdit');
 
        $this->assertDatabaseHas('categories', ['id' => $cat->id, 'name' => 'New Name']);
    }
 
    // ─── Item Management ────────────────────────────────────────────────
 
    public function test_admin_can_delete_item(): void
    {
        $admin = $this->makeAdmin();
        $owner = $this->makeUser();
        $cat   = Category::create(['name' => 'Test']);
        $item  = Item::create([
            'user_id' => $owner->id, 'category_id' => $cat->id,
            'title' => 'Test Item', 'description' => 'desc',
            'type_id' => 1, 'item_status_id' => 1, 'city_id' => 1, 'district_id' => 1,
            'images' => [],
        ]);
 
        $this->actingAs($admin);
 
        Livewire::test(ItemIndex::class)
            ->call('confirmDelete', $item->id)
            ->call('deleteItem');
 
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
 
    public function test_admin_can_force_change_item_status(): void
    {
        $admin = $this->makeAdmin();
        $owner = $this->makeUser();
        $cat   = Category::create(['name' => 'Test']);
        $item  = Item::create([
            'user_id' => $owner->id, 'category_id' => $cat->id,
            'title' => 'Test Item', 'description' => 'desc',
            'type_id' => 1, 'item_status_id' => 1, 'city_id' => 1, 'district_id' => 1,
            'images' => [],
        ]);
 
        $this->actingAs($admin);
 
        Livewire::test(ItemIndex::class)
            ->call('forceStatus', $item->id, 4);
 
        $this->assertEquals(4, $item->fresh()->item_status_id);
    }
}
