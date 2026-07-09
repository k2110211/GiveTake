<?php
 
namespace App\Livewire\Admin;
 
use App\Models\ItemRequest;
use Livewire\Component;
use Livewire\WithPagination;
 
class TransactionIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $filterStatus = '';
 
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
 
    public function render()
    {
        $requests = ItemRequest::with(['item.user', 'user', 'chatRoom'])
            ->when($this->search, fn($q) => $q->whereHas('item', fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            ))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);
 
        return view('livewire.admin.transaction-index', compact('requests'))
            ->layout('layouts.admin');
    }
}
