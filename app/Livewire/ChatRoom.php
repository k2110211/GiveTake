<?php
 
namespace App\Livewire;
 
use App\Models\ChatMessage;
use App\Models\ChatRoom as ChatRoomModel;
use App\Models\ItemRequest;
use App\Models\ItemStatus;
use App\Models\RequestStatus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
 
class ChatRoom extends Component
{
    public $roomId;
    public $newMessage = '';
 
    public function mount($roomId)
    {
        $this->roomId = $roomId;
 
        // Authorize: only the giver or requester of the item request can access
        $room = ChatRoomModel::with(['itemRequest.item.user', 'itemRequest.user'])
            ->findOrFail($roomId);
 
        $userId     = auth()->id();
        $giverId    = $room->itemRequest->item->user_id;
        $requesterId = $room->itemRequest->user_id;
 
        if ($userId !== $giverId && $userId !== $requesterId) {
            abort(403, 'Bạn không có quyền truy cập phòng chat này.');
        }
    }
 
    #[Computed]
    public function room()
    {
        return ChatRoomModel::with([
            'itemRequest.item.user',
            'itemRequest.item.category',
            'itemRequest.item.type',
            'itemRequest.item.status',
            'itemRequest.user',
            'itemRequest.status',
            'messages.user'
        ])->findOrFail($this->roomId);
    }
 
    #[Computed]
    public function otherParticipant()
    {
        $room        = $this->room;
        $giverId     = $room->itemRequest->item->user_id;
 
        if (auth()->id() === $giverId) {
            return $room->itemRequest->user;
        }
        return $room->itemRequest->item->user;
    }
 
    public function approveRequest()
    {
        $room = $this->room;
        $itemRequest = $room->itemRequest;
        $item = $itemRequest->item;
 
        // Authorize: Only the giver/owner can approve
        if (auth()->id() !== $item->user_id) {
            abort(403, 'Chỉ chủ sở hữu món đồ mới có quyền chấp nhận yêu cầu.');
        }
 
        if ($item->item_status_id === ItemStatus::COMPLETED) {
            session()->flash('error', 'Món đồ này đã hoàn thành giao dịch.');
            return;
        }
 
        if ($itemRequest->request_status_id === RequestStatus::ACCEPTED) {
            session()->flash('info', 'Yêu cầu này đã được chấp nhận trước đó.');
            return;
        }
 
        if ($item->item_status_id === ItemStatus::RESERVED && $itemRequest->request_status_id !== RequestStatus::ACCEPTED) {
            session()->flash('error', 'Món đồ này hiện đang trong giao dịch với một người khác.');
            return;
        }
 
        \Illuminate\Support\Facades\DB::transaction(function () use ($itemRequest, $item) {
            // 1. Update this request to approved
            $itemRequest->update(['request_status_id' => RequestStatus::ACCEPTED]);
 
            // 2. Update item to reserved
            $item->update(['item_status_id' => ItemStatus::RESERVED]);
 
            // 3. Reject other pending requests for the same item
            ItemRequest::where('item_id', $item->id)
                ->where('id', '!=', $itemRequest->id)
                ->where('request_status_id', RequestStatus::PENDING)
                ->update(['request_status_id' => RequestStatus::REJECTED]);
 
            // 4. Send a system message in the chat
            $actionText = (int)$item->type_id === 2 ? 'trao đổi' : 'tặng';
            ChatMessage::create([
                'chat_room_id' => $this->roomId,
                'user_id'      => auth()->id(),
                'message'      => "🎉 [Hệ thống] Chủ món đồ đã chấp nhận {$actionText} món đồ này với bạn! Hãy hẹn thời gian và địa điểm giao nhận nhé.",
                'is_read'      => false
            ]);
        });
 
        unset($this->room);
        session()->flash('success', 'Bạn đã chấp nhận trao món đồ này thành công! Trạng thái món đồ đã chuyển sang "Đang trao đổi".');
    }
 
    public function rejectRequest()
    {
        $room = $this->room;
        $itemRequest = $room->itemRequest;
        $item = $itemRequest->item;
 
        if (auth()->id() !== $item->user_id) {
            abort(403, 'Chỉ chủ sở hữu món đồ mới có quyền từ chối yêu cầu.');
        }
 
        if ($itemRequest->request_status_id === RequestStatus::REJECTED) {
            return;
        }
 
        $itemRequest->update(['request_status_id' => RequestStatus::REJECTED]);
 
        unset($this->room);
        session()->flash('success', 'Đã từ chối yêu cầu này.');
    }
 
    public function confirmReceived()
    {
        $room = $this->room;
        $itemRequest = $room->itemRequest;
        $item = $itemRequest->item;
        $userId = auth()->id();
 
        // Must be a participant (either requester or giver)
        if ($userId !== $item->user_id && $userId !== $itemRequest->user_id) {
            abort(403, 'Bạn không có quyền thao tác trên giao dịch này.');
        }
 
        if ($itemRequest->request_status_id !== RequestStatus::ACCEPTED) {
            session()->flash('error', 'Giao dịch chưa ở trạng thái được chấp nhận.');
            return;
        }
 
        \Illuminate\Support\Facades\DB::transaction(function () use ($itemRequest, $item) {
            // 1. Update request status to COMPLETED (5)
            $itemRequest->update(['request_status_id' => RequestStatus::COMPLETED]);
 
            // 2. Update item status to COMPLETED (4)
            $item->update(['item_status_id' => ItemStatus::COMPLETED]);
 
            // 3. Award Karma points (+15) to giver
            $item->user->increment('karma_points', 15);
 
            // 4. Send system announcement message
            $actorName = auth()->user()->name;
            ChatMessage::create([
                'chat_room_id' => $this->roomId,
                'user_id'      => auth()->id(),
                'message'      => "🎉 [Hệ thống] {$actorName} đã xác nhận giao nhận thành công! Món đồ chính thức hoàn tất trao tặng. Cảm ơn hai bạn đã lan tỏa tinh thần sẻ chia! Hãy để lại đánh giá cho đối phương bên dưới nhé.",
                'is_read'      => false
            ]);
        });
 
        unset($this->room);
        session()->flash('success', 'Xác nhận giao nhận thành công! Món đồ đã hoàn tất trao tặng (+15 điểm Karma cho người tặng).');
    }
 
    public function cancelTransaction()
    {
        $room = $this->room;
        $itemRequest = $room->itemRequest;
        $item = $itemRequest->item;
        $userId = auth()->id();
 
        // Must be a participant
        if ($userId !== $item->user_id && $userId !== $itemRequest->user_id) {
            abort(403, 'Bạn không có quyền thao tác trên giao dịch này.');
        }
 
        if ($itemRequest->request_status_id !== RequestStatus::ACCEPTED) {
            session()->flash('error', 'Chỉ có thể hủy giao dịch khi đang ở trạng thái đã chấp nhận.');
            return;
        }
 
        if ($item->item_status_id === ItemStatus::COMPLETED) {
            session()->flash('error', 'Giao dịch đã hoàn tất thành công, không thể hủy.');
            return;
        }
 
        \Illuminate\Support\Facades\DB::transaction(function () use ($itemRequest, $item) {
            // 1. Update this request to CANCELLED (4)
            $itemRequest->update(['request_status_id' => RequestStatus::CANCELLED]);
 
            // 2. Revert item to AVAILABLE (1) and reset winner if raffle
            $item->update([
                'item_status_id' => ItemStatus::AVAILABLE,
                'winner_id'      => null
            ]);
 
            // 3. Reopen other rejected requests for this item back to PENDING (1)
            ItemRequest::where('item_id', $item->id)
                ->where('id', '!=', $itemRequest->id)
                ->where('request_status_id', RequestStatus::REJECTED)
                ->update(['request_status_id' => RequestStatus::PENDING]);
 
            // 4. Send system announcement message
            $actorName = auth()->user()->name;
            ChatMessage::create([
                'chat_room_id' => $this->roomId,
                'user_id'      => auth()->id(),
                'message'      => "⚠️ [Hệ thống] {$actorName} đã hủy giao kèo này. Món đồ đã quay về trạng thái Có sẵn để người đăng có thể chọn người nhận khác.",
                'is_read'      => false
            ]);
        });
 
        unset($this->room);
        session()->flash('success', 'Đã hủy giao dịch thành công. Món đồ đã quay về trạng thái Có sẵn.');
    }
 
    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|min:1|max:2000'
        ], [
            'newMessage.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'newMessage.max'      => 'Tin nhắn không được vượt quá 2000 ký tự.'
        ]);
 
        ChatMessage::create([
            'chat_room_id' => $this->roomId,
            'user_id'      => auth()->id(),
            'message'      => trim($this->newMessage),
            'is_read'      => false
        ]);
 
        // Mark all unread messages from the other party as read
        ChatMessage::where('chat_room_id', $this->roomId)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
 
        $this->newMessage = '';
 
        $this->dispatch('message-sent');
        unset($this->room); // bust computed cache
    }
 
    public function sendQuickReply(string $text)
    {
        $this->newMessage = $text;
        $this->sendMessage();
    }
 
    public function refresh()
    {
        $this->markAsRead();
        unset($this->room);
        $this->dispatch('message-received');
    }
 
    public function markAsRead()
    {
        ChatMessage::where('chat_room_id', $this->roomId)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
 
    public function render()
    {
        $this->markAsRead();
 
        return view('livewire.chat-room', [
            'room'             => $this->room,
            'otherParticipant' => $this->otherParticipant
        ])->layout('layouts.app');
    }
}
