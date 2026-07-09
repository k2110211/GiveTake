<?php
 
namespace Tests\Feature;
 
use App\Livewire\PostItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;
 
class PostItemTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get('/items/create')
            ->assertRedirect(route('login'));
    }
 
    public function test_authenticated_user_can_view_create_form(): void
    {
        $user = User::factory()->create();
 
        $this->actingAs($user)
            ->get('/items/create')
            ->assertStatus(200)
            ->assertSee('Đăng tin chia sẻ món đồ mới');
    }
 
    public function test_validation_fails_without_required_inputs(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
 
        Livewire::test(PostItem::class)
            ->call('save')
            ->assertHasErrors([
                'title' => 'required',
                'description' => 'required',
                'categoryId' => 'required',
                'city' => 'required',
                'district' => 'required',
                'images' => 'required'
            ]);
    }
 
    public function test_validation_fails_if_exchange_wish_is_empty_for_exchange_type(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
 
        Livewire::test(PostItem::class)
            ->set('type', 'exchange')
            ->call('save')
            ->assertHasErrors([
                'exchangeWish' => 'required_if'
            ]);
    }
 
    public function test_can_publish_new_item_successfully(): void
    {
        Storage::fake('public');
 
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
        
        $this->actingAs($user);
 
        $file = UploadedFile::fake()->image('jacket.jpg');
 
        Livewire::test(PostItem::class)
            ->set('title', 'Áo khoác gió hiệu Uniqlo')
            ->set('description', 'Áo khoác gió hiệu Uniqlo màu xanh navy, phù hợp mặc trời lạnh nhẹ.')
            ->set('categoryId', $category->id)
            ->set('type', 'give')
            ->set('city', 'Hồ Chí Minh')
            ->set('district', 'Quận 1')
            ->set('images', [$file])
            ->call('save')
            ->assertHasNoErrors();
 
        $item = Item::first();
        $this->assertNotNull($item);
        $this->assertEquals('Áo khoác gió hiệu Uniqlo', $item->title);
        $this->assertEquals($user->id, $item->user_id);
        $this->assertCount(1, $item->images);
        
        // Assert storage has the file
        $fileName = basename($item->images[0]);
        Storage::disk('public')->assertExists('items/' . $fileName);
    }
}
