<?php
 
namespace App\Livewire;
 
use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\ItemRequest;
use Livewire\Component;
 
class Dashboard extends Component
{
    public $activeTab = 'my-items';

    public function mount()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return $this->redirect(route('admin.dashboard'), navigate: true);
        }
    }
 
    public function approveRequest($requestId)
    {
        $request = ItemRequest::with('item')->findOrFail($requestId);

        if ($request->item->user_id !== auth()->id()) {
            abort(403);
        }

        // Update request status to approved (Đồng ý = 2)
        $request->update(['request_status_id' => 2]);

        // Update item status to reserved (Đang trao đổi = 3)
        $request->item->update(['item_status_id' => 3]);

        // Reject all other pending requests for the same item (Từ chối = 3)
        ItemRequest::where('item_id', $request->item_id)
            ->where('id', '!=', $requestId)
            ->where('request_status_id', 1)
            ->update(['request_status_id' => 3]);

        // Automatically create a ChatRoom to initiate conversation
        $chatRoom = ChatRoom::firstOrCreate([
            'item_request_id' => $requestId
        ]);

        $actionText = (int)$request->item->type_id === 2 ? 'trao đổi' : 'tặng';
        \App\Models\ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id'      => auth()->id(),
            'message'      => "🎉 [Hệ thống] Chủ món đồ đã chấp nhận {$actionText} món đồ này với bạn! Hãy hẹn thời gian và địa điểm giao nhận nhé.",
            'is_read'      => false
        ]);

        session()->flash('success', 'Bạn đã đồng ý tặng món đồ này! Phòng chat đã được khởi tạo để trao đổi chi tiết.');
    }
 
    public function rejectRequest($requestId)
    {
        $request = ItemRequest::with('item')->findOrFail($requestId);
 
        if ($request->item->user_id !== auth()->id()) {
            abort(403);
        }
 
        $request->update(['request_status_id' => 3]);
 
        session()->flash('success', 'Đã từ chối yêu cầu nhận đồ.');
    }
 
    public function cancelRequest($requestId)
    {
        $request = ItemRequest::findOrFail($requestId);
 
        if ($request->user_id !== auth()->id()) {
            abort(403);
        }
 
        $request->delete();
 
        session()->flash('success', 'Đã hủy yêu cầu nhận đồ thành công.');
    }
 
    public function render()
    {
        $user = auth()->user();
 
        // My items with their incoming requests
        $myItems = Item::with(['category', 'requests.user', 'requests.chatRoom', 'requests.status', 'city', 'district', 'type', 'status'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
 
        // Requests I've sent
        $sentRequests = ItemRequest::with(['item.user', 'item.category', 'item.city', 'item.district', 'item.type', 'item.status', 'chatRoom', 'status'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Ensure ChatRooms exist for items
        foreach ($myItems as $item) {
            if ((int)$item->type_id !== 3) {
                foreach ($item->requests as $req) {
                    if (!$req->chatRoom) {
                        $room = ChatRoom::firstOrCreate(['item_request_id' => $req->id]);
                        $req->setRelation('chatRoom', $room);
                    }
                }
            }
        }
        foreach ($sentRequests as $req) {
            if ($req->item && (int)$req->item->type_id !== 3 && !$req->chatRoom) {
                $room = ChatRoom::firstOrCreate(['item_request_id' => $req->id]);
                $req->setRelation('chatRoom', $room);
            }
        }
 
        // Stats calculations
        $myItemsIds = $myItems->pluck('id')->toArray();
        $stats = [
            'karma' => $user->karma_points,
            'posted_count' => $myItems->count(),
            'successful_count' => $myItems->where('item_status_id', 4)->count(),
            'pending_received_count' => ItemRequest::whereIn('item_id', $myItemsIds)
                ->where('request_status_id', 1)
                ->count(),
            'sent_requests_count' => $sentRequests->count(),
        ];
 
        return view('livewire.dashboard', [
            'myItems' => $myItems,
            'sentRequests' => $sentRequests,
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
