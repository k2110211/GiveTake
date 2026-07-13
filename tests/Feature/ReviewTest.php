<?php
 
namespace Tests\Feature;
 
use App\Livewire\SubmitReview;
use App\Models\Category;
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class ReviewTest extends TestCase
{
    use RefreshDatabase;
 
    private function createApprovedScenario(): array
    {
        $giver = User::factory()->create(['karma_points' => 0, 'trust_score' => 0]);
        $requester = User::factory()->create(['karma_points' => 0, 'trust_score' => 0]);
        $category = Category::create(['name' => 'Books']);
 
        $item = Item::create([
            'user_id' => $giver->id,
            'category_id' => $category->id,
            'title' => 'PHP 8 Programming Tips',
            'description' => 'Great PHP book.',
            'images' => [],
            'type_id' => 1,
            'item_status_id' => 3,
            'city_id' => 1,
            'district_id' => 1,
        ]);
 
        $itemRequest = ItemRequest::create([
            'item_id' => $item->id,
            'user_id' => $requester->id,
            'message' => 'I would love this book!',
            'request_status_id' => 2,
        ]);
 
        $chatRoom = ChatRoom::create(['item_request_id' => $itemRequest->id]);
 
        return compact('giver', 'requester', 'item', 'itemRequest', 'chatRoom');
    }
 
    public function test_giver_can_open_review_modal(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->call('openModal')
            ->assertSet('showModal', true)
            ->assertSet('rating', 5);
    }
 
    public function test_giver_can_submit_review_for_requester(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 4)
            ->set('comment', 'Very polite person, exchange went smoothly.')
            ->call('submitReview')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);
 
        $this->assertDatabaseHas('reviews', [
            'item_request_id' => $scenario['itemRequest']->id,
            'reviewer_id'     => $scenario['giver']->id,
            'reviewee_id'     => $scenario['requester']->id,
            'rating'          => 4,
        ]);
    }
 
    public function test_reviewer_earns_10_karma_points(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 5)
            ->call('submitReview');
 
        $this->assertEquals(10, $scenario['giver']->fresh()->karma_points);
    }
 
    public function test_reviewee_trust_score_is_updated(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 3)
            ->call('submitReview');
 
        $this->assertEquals(3.00, $scenario['requester']->fresh()->trust_score);
    }
 
    public function test_duplicate_review_is_prevented(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        // First review
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 5)
            ->call('submitReview');
 
        // Second attempt — should be a no-op, not crash
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 1)
            ->call('submitReview');
 
        $this->assertEquals(1, Review::where('item_request_id', $scenario['itemRequest']->id)->count());
    }
 
    public function test_item_marked_completed_when_both_parties_review(): void
    {
        $scenario = $this->createApprovedScenario();
 
        // Giver reviews requester
        $this->actingAs($scenario['giver']);
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 5)
            ->call('submitReview');
 
        $this->assertEquals(3, $scenario['item']->fresh()->item_status_id);
 
        // Requester reviews giver
        $this->actingAs($scenario['requester']);
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 4)
            ->call('submitReview');
 
        $this->assertEquals(4, $scenario['item']->fresh()->item_status_id);
    }
 
    public function test_review_requires_rating(): void
    {
        $scenario = $this->createApprovedScenario();
        $this->actingAs($scenario['giver']);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 0)
            ->call('submitReview')
            ->assertHasErrors(['rating' => 'min']);
    }
 
    public function test_stranger_cannot_submit_review(): void
    {
        $scenario = $this->createApprovedScenario();
        $stranger = User::factory()->create();
        $this->actingAs($stranger);
 
        Livewire::test(SubmitReview::class, ['itemRequestId' => $scenario['itemRequest']->id])
            ->set('rating', 5)
            ->call('submitReview')
            ->assertStatus(403);
    }
}
