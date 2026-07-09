<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
 
class ReviewIndex extends Component
{
    use WithPagination;
 
    public $filterRating = '';
    public $confirmDeleteId = null;
 
    public function updatingFilterRating(): void { $this->resetPage(); }
 
    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }
 
    public function deleteReview(): void
    {
        if (!$this->confirmDeleteId) return;
        Review::findOrFail($this->confirmDeleteId)->delete();
        $this->confirmDeleteId = null;
        session()->flash('success', 'Đã xóa đánh giá.');
    }
 
    public function render()
    {
        $reviews = Review::with(['reviewer', 'reviewee', 'itemRequest.item'])
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->latest()
            ->paginate(20);
 
        $ratingStats = Review::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->pluck('count', 'rating');
 
        return view('livewire.admin.review-index', compact('reviews', 'ratingStats'))
            ->layout('layouts.admin');
    }
}
