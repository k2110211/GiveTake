<?php
 
namespace App\Livewire;
 
use App\Models\ChatMessage;
use App\Models\ChatRoom as ChatRoomModel;
use App\Events\MessageSent;
use Livewire\Attributes\Computed;
use Livewire\Component;
 
class ChatRoom extends Component
{
    public $roomId;
    public $newMessage = '';
 
    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->roomId},.message.sent" => 'onMessageReceived',
        ];
    }
 
    public function mount($roomId)
    {
        $this->roomId = $roomId;
 
        // Authorize: only the giver or requester of the item request can access
        $room = ChatRoomModel::with(['itemRequest.item.user', 'itemRequest.user'])
            ->findOrFail($roomId);
 
        $userId = auth()->id();
        $giverId = $room->itemRequest->item->user_id;
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
            'itemRequest.user',
            'messages.user'
        ])->findOrFail($this->roomId);
    }
 
    #[Computed]
    public function otherParticipant()
    {
        $room = $this->room;
        $giverId = $room->itemRequest->item->user_id;
        $requesterId = $room->itemRequest->user_id;
 
        if (auth()->id() === $giverId) {
            return $room->itemRequest->user;
        }
        return $room->itemRequest->item->user;
    }
 
    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|min:1|max:2000'
        ], [
            'newMessage.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'newMessage.max' => 'Tin nhắn không được vượt quá 2000 ký tự.'
        ]);
 
        $message = ChatMessage::create([
            'chat_room_id' => $this->roomId,
            'user_id' => auth()->id(),
            'message' => trim($this->newMessage),
            'is_read' => false
        ]);
 
        // Broadcast the message sent event
        broadcast(new MessageSent($message))->toOthers();
 
        // Mark all unread messages from the other party as read
        ChatMessage::where('chat_room_id', $this->roomId)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
 
        $this->newMessage = '';
        
        $this->dispatch('message-sent');
    }
 
    public function onMessageReceived($event)
    {
        // Re-read and refresh message list
        $this->markAsRead();
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
        // Mark messages as read on each render
        $this->markAsRead();
 
        return view('livewire.chat-room', [
            'room' => $this->room,
            'otherParticipant' => $this->otherParticipant
        ])->layout('layouts.app');
    }
}
