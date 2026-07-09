<?php
 
namespace App\Livewire;
 
use App\Models\Item;
use App\Models\ItemRequest;
use Livewire\Component;
use Livewire\Attributes\Computed;
 
class ItemDetail extends Component
{
    public $itemId;
    public $message = '';
    public $showRequestModal = false;
 
    public function mount($id)
    {
        $this->itemId = $id;
    }
 
    #[Computed]
    public function item()
    {
        return Item::with(['user', 'category', 'requests'])->findOrFail($this->itemId);
    }
 
    #[Computed]
    public function hasRequested()
    {
        if (!auth()->check()) {
            return false;
        }
        return ItemRequest::where('item_id', $this->itemId)
            ->where('user_id', auth()->id())
            ->exists();
    }
 
    public function openRequestModal()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }
 
        if ($this->item->user_id === auth()->id()) {
            session()->flash('error', 'Bạn không thể xin đồ của chính mình!');
            return;
        }
 
        if ($this->hasRequested) {
            session()->flash('error', 'Bạn đã gửi yêu cầu xin món đồ này rồi!');
            return;
        }
 
        $this->message = '';
        $this->showRequestModal = true;
    }
 
    public function submitRequest()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }
 
        if ($this->item->user_id === auth()->id()) {
            return;
        }
 
        if ($this->hasRequested) {
            return;
        }
 
        $this->validate([
            'message' => 'required|string|min:10|max:500'
        ], [
            'message.required' => 'Vui lòng nhập lời nhắn giới thiệu.',
            'message.min' => 'Lời nhắn phải có ít nhất 10 ký tự.',
            'message.max' => 'Lời nhắn không được vượt quá 500 ký tự.'
        ]);
 
        ItemRequest::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
            'status' => 'pending'
        ]);
 
        $this->showRequestModal = false;
        session()->flash('success', 'Yêu cầu của bạn đã được gửi thành công! Người tặng sẽ xem xét và phản hồi bạn.');
    }
 
    public function render()
    {
        return view('livewire.item-detail', [
            'item' => $this->item,
            'hasRequested' => $this->hasRequested
        ])->layout('layouts.app');
    }
}
