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
        return Item::with(['user', 'category', 'requests.status', 'city', 'district', 'type', 'status'])->findOrFail($this->itemId);
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

        if ((int)$this->item->type_id === 3 && auth()->user()->karma_points < $this->item->min_karma) {
            session()->flash('error', "Bạn cần có tối thiểu {$this->item->min_karma} điểm Karma để tham gia quay thưởng món đồ này!");
            return;
        }

        $this->message = (int)$this->item->type_id === 3 
            ? 'Tôi muốn đăng ký tham gia quay thưởng nhận món đồ này.' 
            : '';
        $this->showRequestModal = true;
    }

    public function submitRequest()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->item->user_id === auth()->id() || $this->hasRequested) {
            return;
        }

        if ((int)$this->item->type_id === 3 && auth()->user()->karma_points < $this->item->min_karma) {
            session()->flash('error', "Điểm Karma của bạn không đủ để tham gia quay thưởng!");
            return;
        }

        $this->validate([
            'message' => 'required|string|min:5|max:500'
        ], [
            'message.required' => 'Vui lòng nhập lời nhắn.',
            'message.min' => 'Lời nhắn phải có ít nhất 5 ký tự.',
            'message.max' => 'Lời nhắn không được vượt quá 500 ký tự.'
        ]);

        ItemRequest::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
            'request_status_id' => 1
        ]);

        $this->showRequestModal = false;
        unset($this->hasRequested);
        unset($this->item);

        $msg = (int)$this->item->type_id === 3 
            ? 'Bạn đã đăng ký tham gia quay thưởng thành công! Hãy chờ chủ bài viết chốt kết quả nhé.'
            : 'Yêu cầu của bạn đã được gửi thành công! Người tặng sẽ xem xét và phản hồi bạn.';

        session()->flash('success', $msg);
    }

    public function drawWinner()
    {
        if (!auth()->check() || $this->item->user_id !== auth()->id()) {
            return;
        }

        if ((int)$this->item->type_id !== 3) {
            return;
        }

        if ($this->item->winner_id) {
            session()->flash('error', 'Món đồ này đã được quay thưởng rồi!');
            return;
        }

        $requests = ItemRequest::where('item_id', $this->itemId)
            ->where('request_status_id', 1)
            ->get();

        if ($requests->isEmpty()) {
            session()->flash('error', 'Chưa có ai đăng ký tham gia quay thưởng!');
            return;
        }

        // Randomly pick a winner
        $winningRequest = $requests->random();
        $winnerUser = $winningRequest->user;

        \Illuminate\Support\Facades\DB::transaction(function () use ($winningRequest, $winnerUser) {
            // Update winning request to Approved (id = 2)
            $winningRequest->update(['request_status_id' => 2]);

            // Update other pending requests to Rejected (id = 3)
            ItemRequest::where('item_id', $this->itemId)
                ->where('id', '!=', $winningRequest->id)
                ->where('request_status_id', 1)
                ->update(['request_status_id' => 3]);

            // Update Item status to Reserved/Exchange in progress (id = 3) and set winner_id
            $this->item->update([
                'winner_id' => $winnerUser->id,
                'item_status_id' => 3
            ]);

            // Create Chat Room if not exists
            \App\Models\ChatRoom::firstOrCreate([
                'item_request_id' => $winningRequest->id
            ]);
        });

        unset($this->item);
        session()->flash('success', "🎉 Chúc mừng! Người trúng thưởng là {$winnerUser->name}. Phòng chat đã được tự động tạo!");
    }

    public function render()
    {
        $requestsCount = ItemRequest::where('item_id', $this->itemId)->count();
        $requestsList = (auth()->check() && $this->item->user_id === auth()->id())
            ? ItemRequest::with('user')->where('item_id', $this->itemId)->get()
            : collect();

        return view('livewire.item-detail', [
            'item' => $this->item,
            'hasRequested' => $this->hasRequested,
            'requestsCount' => $requestsCount,
            'requestsList' => $requestsList
        ])->layout('layouts.app');
    }
}
