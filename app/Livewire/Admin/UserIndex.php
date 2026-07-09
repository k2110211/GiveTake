<?php
 
namespace App\Livewire\Admin;
 
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
 
class UserIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $filterStatus = ''; // '' | 'admin' | 'banned'
 
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
 
    public function toggleBan(int $userId): void
    {
        $user = User::findOrFail($userId);
        if ($user->is_admin) return; // Cannot ban admins
        $user->update(['is_banned' => !$user->is_banned]);
        session()->flash('success', $user->is_banned
            ? "Đã khóa tài khoản {$user->name}."
            : "Đã mở khóa tài khoản {$user->name}."
        );
    }
 
    public function promoteAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_admin' => true, 'is_banned' => false]);
        session()->flash('success', "Đã nâng quyền Admin cho {$user->name}.");
    }
 
    public function render()
    {
        $query = User::withCount(['items', 'receivedReviews'])
            ->when($this->search, fn($q) => $q->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus === 'admin', fn($q) => $q->where('is_admin', true))
            ->when($this->filterStatus === 'banned', fn($q) => $q->where('is_banned', true))
            ->latest();
 
        return view('livewire.admin.user-index', [
            'users' => $query->paginate(20)
        ])->layout('layouts.admin');
    }
}
