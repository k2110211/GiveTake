<?php
 
namespace App\Livewire;
 
use App\Models\ChatRoom as ChatRoomModel;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Review;
use Livewire\Attributes\Computed;
use Livewire\Component;
 
class SubmitReview extends Component
{
    public $itemRequestId;
    public $rating = 0;
    public $comment = '';
    public $showModal = false;
 
    public function mount($itemRequestId)
    {
        $this->itemRequestId = $itemRequestId;
    }
 
    #[Computed]
    public function itemRequest()
    {
        return ItemRequest::with(['item.user', 'user'])->findOrFail($this->itemRequestId);
    }
 
    #[Computed]
    public function hasReviewed()
    {
        return Review::where('item_request_id', $this->itemRequestId)
            ->where('reviewer_id', auth()->id())
            ->exists();
    }
 
    #[Computed]
    public function reviewee()
    {
        $req = $this->itemRequest;
        // If current user is the giver, they review the requester
        // If current user is the requester, they review the giver
        if (auth()->id() === $req->item->user_id) {
            return $req->user; // giver reviews requester
        }
        return $req->item->user; // requester reviews giver
    }
 
    public function openModal()
    {
        if ($this->hasReviewed) {
            return;
        }
        $this->rating = 5;
        $this->comment = '';
        $this->showModal = true;
    }
 
    public function setRating($stars)
    {
        $this->rating = (int) $stars;
    }
 
    public function submitReview()
    {
        $req = $this->itemRequest;
 
        // Authorize: only giver or requester
        if (auth()->id() !== $req->item->user_id && auth()->id() !== $req->user_id) {
            abort(403);
        }
 
        // Prevent duplicate
        if ($this->hasReviewed) {
            return;
        }
 
        $this->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min'      => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max'      => 'Đánh giá tối đa là 5 sao.',
            'comment.max'     => 'Nhận xét không được vượt quá 500 ký tự.',
        ]);
 
        Review::create([
            'item_request_id' => $this->itemRequestId,
            'reviewer_id'     => auth()->id(),
            'reviewee_id'     => $this->reviewee->id,
            'rating'          => $this->rating,
            'comment'         => $this->comment ?: null,
        ]);
 
        // Recalculate reviewee's trust_score from all reviews
        $reviewee = $this->reviewee;
        $avgRating = Review::where('reviewee_id', $reviewee->id)->avg('rating');
        $reviewee->update(['trust_score' => round($avgRating, 2)]);
 
        // Award karma to the reviewer for completing the transaction
        auth()->user()->increment('karma_points', 10);
 
        // Mark item as completed once BOTH parties have reviewed
        $bothReviewed = Review::where('item_request_id', $this->itemRequestId)->count() >= 2;
        if ($bothReviewed) {
            $req->item->update(['item_status_id' => 4]);
        }
 
        $this->showModal = false;
        unset($this->hasReviewed);
        unset($this->itemRequest);
        unset($this->reviewee);

        session()->flash('review_success', 'Đánh giá của bạn đã được ghi nhận! Cảm ơn bạn đã tham gia cộng đồng chia sẻ.');
    }
 
    public function render()
    {
        return view('livewire.submit-review', [
            'hasReviewed'  => $this->hasReviewed,
            'reviewee'     => $this->reviewee,
            'itemRequest'  => $this->itemRequest,
        ]);
    }
}
