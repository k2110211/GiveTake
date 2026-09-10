<?php
 
namespace App\Livewire;
 
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
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
        $item = $this->item;
        if (in_array((int)$item->item_status_id, [ItemStatus::PENDING, ItemStatus::REJECTED])) {
            $canView = auth()->check() && (auth()->user()->is_admin || auth()->id() === $item->user_id);
            if (!$canView) {
                abort(404);
            }
        }
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

    #[Computed]
    public function myRequest()
    {
        if (!auth()->check()) {
            return null;
        }
        return ItemRequest::with('chatRoom')
            ->where('item_id', $this->itemId)
            ->where('user_id', auth()->id())
            ->first();
    }

    public function openRequestModal()
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ((int)$this->item->item_status_id !== ItemStatus::AVAILABLE) {
            session()->flash('error', 'Món đồ này hiện chưa sẵn sàng để nhận yêu cầu!');
            return;
        }

        if ($this->item->user_id === auth()->id()) {
            session()->flash('error', 'Bạn không thể xin đồ của chính mình!');
            return;
        }

        if ($this->hasRequested) {
            session()->flash('error', 'Bạn đã gửi yêu cầu xin món đồ này rồi!');
            return;
        }

        if ((int)$this->item->type_id === 3 && $this->item->raffle_ends_at && $this->item->raffle_ends_at->isPast()) {
            session()->flash('error', 'Lượt quay thưởng này đã hết hạn đăng ký tham gia!');
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

        if ((int)$this->item->item_status_id !== ItemStatus::AVAILABLE) {
            session()->flash('error', 'Món đồ này hiện chưa sẵn sàng để nhận yêu cầu!');
            return;
        }

        if ($this->item->user_id === auth()->id() || $this->hasRequested) {
            return;
        }

        $executed = \Illuminate\Support\Facades\RateLimiter::attempt(
            'item-request:' . auth()->id(),
            $maxAttempts = 10,
            function () {},
            $decaySeconds = 60
        );

        if (!$executed) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn('item-request:' . auth()->id());
            session()->flash('error', "Bạn đang gửi yêu cầu quá nhanh. Vui lòng thử lại sau {$seconds} giây.");
            return;
        }

        if ((int)$this->item->type_id === 3 && $this->item->raffle_ends_at && $this->item->raffle_ends_at->isPast()) {
            session()->flash('error', 'Lượt quay thưởng này đã hết hạn đăng ký tham gia!');
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

        $itemRequest = ItemRequest::create([
            'item_id' => $this->item->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
            'request_status_id' => 1
        ]);

        $chatRoom = null;
        if ((int)$this->item->type_id !== 3) {
            $chatRoom = \App\Models\ChatRoom::firstOrCreate([
                'item_request_id' => $itemRequest->id
            ]);
        }

        $this->showRequestModal = false;
        $itemTypeId = (int)$this->item->type_id;

        unset($this->hasRequested);
        unset($this->myRequest);
        unset($this->item);

        if ($itemTypeId === 3) {
            session()->flash('success', 'Bạn đã đăng ký tham gia quay thưởng thành công! Hãy chờ chủ bài viết chốt kết quả nhé.');
            return;
        }

        session()->flash('success', 'Yêu cầu của bạn đã được gửi thành công! Phòng chat đã được mở để bạn trao đổi với người tặng.');
        return $this->redirect(route('chat.room', ['roomId' => $chatRoom->id]), navigate: true);
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
        $requestsList = collect();
        if (auth()->check() && $this->item->user_id === auth()->id()) {
            $requestsList = ItemRequest::with(['user', 'chatRoom', 'status'])
                ->where('item_id', $this->itemId)
                ->get();
            if ((int)$this->item->type_id !== 3) {
                foreach ($requestsList as $req) {
                    if (!$req->chatRoom) {
                        \App\Models\ChatRoom::firstOrCreate(['item_request_id' => $req->id]);
                    }
                }
                $requestsList->load('chatRoom');
            }
        }

        return view('livewire.item-detail', [
            'item' => $this->item,
            'hasRequested' => $this->hasRequested,
            'myRequest' => $this->myRequest,
            'requestsCount' => $requestsCount,
            'requestsList' => $requestsList
        ])->layout('layouts.app');
    }
}
