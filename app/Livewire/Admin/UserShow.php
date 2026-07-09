<?php
 
namespace App\Livewire\Admin;
 
use App\Models\User;
use Livewire\Component;
 
class UserShow extends Component
{
    public $userId;
    public $editKarma;
    public $editTrustScore;
    public $editingStats = false;
 
    public function mount($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        $this->editKarma = $user->karma_points;
        $this->editTrustScore = $user->trust_score;
    }
 
    public function saveStats()
    {
        $this->validate([
            'editKarma'      => 'required|integer|min:0',
            'editTrustScore' => 'required|numeric|min:0|max:5',
        ]);
 
        User::findOrFail($this->userId)->update([
            'karma_points' => $this->editKarma,
            'trust_score'  => $this->editTrustScore,
        ]);
 
        $this->editingStats = false;
        session()->flash('success', 'Đã cập nhật Karma & Trust Score.');
    }
 
    public function toggleBan()
    {
        $user = User::findOrFail($this->userId);
        if ($user->is_admin) return;
        $user->update(['is_banned' => !$user->is_banned]);
    }
 
    public function render()
    {
        $user = User::with(['items.category', 'receivedReviews.reviewer', 'itemRequests.item'])
            ->findOrFail($this->userId);
 
        return view('livewire.admin.user-show', compact('user'))
            ->layout('layouts.admin');
    }
}
