<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
 
class ItemIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $filterStatus = '';
    public $confirmDeleteId = null;
 
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
 
    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }
 
    public function deleteItem(): void
    {
        if (!$this->confirmDeleteId) return;
 
        $item = Item::findOrFail($this->confirmDeleteId);
        // cascade: requests + chat rooms + messages deleted by FK constraints
        $item->delete();
 
        $this->confirmDeleteId = null;
        session()->flash('success', 'Đã xóa món đồ.');
    }
 
    public function forceStatus(int $id, string $status): void
    {
        Item::findOrFail($id)->update(['status' => $status]);
        session()->flash('success', 'Đã cập nhật trạng thái món đồ.');
    }
 
    public function render()
    {
        $items = Item::with(['user', 'category'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);
 
        return view('livewire.admin.item-index', compact('items'))
            ->layout('layouts.admin');
    }
}
