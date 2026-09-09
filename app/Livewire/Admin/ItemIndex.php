<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Item;
use App\Models\ItemStatus;
use Livewire\Component;
use Livewire\WithPagination;
 
class ItemIndex extends Component
{
    use WithPagination;
 
    public $search = '';
    public $filterStatus = '';
    public $activeTab = 'all';
    public $confirmDeleteId = null;
    public $previewItemId = null;
    public $rejectItemId = null;
    public $rejectionReason = '';
 
    public function mount(): void
    {
        $tabParam = request('tab');
        $statusParam = request('status');

        if ($tabParam === 'pending' || $statusParam == ItemStatus::PENDING) {
            $this->activeTab = 'pending';
        } elseif (Item::where('item_status_id', ItemStatus::PENDING)->exists()) {
            $this->activeTab = 'pending';
        } else {
            $this->activeTab = 'all';
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
 
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
        $item->delete();
 
        if ($this->previewItemId === $this->confirmDeleteId) {
            $this->previewItemId = null;
        }

        $this->confirmDeleteId = null;
        session()->flash('success', 'Đã xóa món đồ thành công.');
    }
 
    public function forceStatus(int $id, int $statusId): void
    {
        Item::findOrFail($id)->update(['item_status_id' => $statusId]);
        session()->flash('success', 'Đã cập nhật trạng thái món đồ.');
    }

    public function approveItem(int $id): void
    {
        $item = Item::findOrFail($id);
        $item->update([
            'item_status_id' => ItemStatus::AVAILABLE,
            'approved_at' => now(),
            'rejection_reason' => null
        ]);

        session()->flash('success', "Đã duyệt món đồ \"{$item->title}\" thành công! Món đồ đã hiển thị công khai.");
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectItemId = $id;
        $this->rejectionReason = '';
    }

    public function selectQuickReason(string $reason): void
    {
        $this->rejectionReason = $reason;
    }

    public function confirmReject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:3|max:500'
        ], [
            'rejectionReason.required' => 'Vui lòng nhập hoặc chọn lý do từ chối.',
            'rejectionReason.min' => 'Lý do phải có ít nhất 3 ký tự.',
            'rejectionReason.max' => 'Lý do không được vượt quá 500 ký tự.'
        ]);

        if (!$this->rejectItemId) return;

        $item = Item::findOrFail($this->rejectItemId);
        $item->update([
            'item_status_id' => ItemStatus::REJECTED,
            'rejection_reason' => $this->rejectionReason
        ]);

        $itemTitle = $item->title;
        $this->rejectItemId = null;
        $this->rejectionReason = '';

        session()->flash('success', "Đã từ chối duyệt món đồ \"{$itemTitle}\".");
    }

    public function previewItem(int $id): void
    {
        $this->previewItemId = $id;
    }

    public function closePreview(): void
    {
        $this->previewItemId = null;
    }
 
    public function render()
    {
        $countPending = Item::where('item_status_id', ItemStatus::PENDING)->count();
        $countAvailable = Item::where('item_status_id', ItemStatus::AVAILABLE)->count();
        $countRejected = Item::where('item_status_id', ItemStatus::REJECTED)->count();
        $countAll = Item::count();

        $query = Item::with(['user', 'category', 'status', 'city', 'district', 'type']);

        if ($this->activeTab === 'pending') {
            $query->where('item_status_id', ItemStatus::PENDING);
        } elseif ($this->activeTab === 'available') {
            $query->where('item_status_id', ItemStatus::AVAILABLE);
        } elseif ($this->activeTab === 'rejected') {
            $query->where('item_status_id', ItemStatus::REJECTED);
        } elseif ($this->filterStatus) {
            $query->where('item_status_id', $this->filterStatus);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"));
            });
        }

        $items = $query->latest()->paginate(15);

        $previewItem = $this->previewItemId 
            ? Item::with(['user', 'category', 'status', 'city', 'district', 'type'])->find($this->previewItemId) 
            : null;

        return view('livewire.admin.item-index', compact(
            'items', 
            'countPending', 
            'countAvailable', 
            'countRejected', 
            'countAll',
            'previewItem'
        ))->layout('layouts.admin');
    }
}
