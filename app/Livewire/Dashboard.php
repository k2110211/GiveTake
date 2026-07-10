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
 
        // Update request status to approved
        $request->update(['status' => 'approved']);
 
        // Update item status to reserved
        $request->item->update(['status' => 'reserved']);
 
        // Reject all other pending requests for the same item
        ItemRequest::where('item_id', $request->item_id)
            ->where('id', '!=', $requestId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);
 
        // Automatically create a ChatRoom to initiate conversation
        ChatRoom::firstOrCreate([
            'item_request_id' => $requestId
        ]);
 
        session()->flash('success', 'Bạn đã đồng ý tặng món đồ này! Phòng chat đã được khởi tạo để trao đổi chi tiết.');
    }
 
    public function rejectRequest($requestId)
    {
        $request = ItemRequest::with('item')->findOrFail($requestId);
 
        if ($request->item->user_id !== auth()->id()) {
            abort(403);
        }
 
        $request->update(['status' => 'rejected']);
 
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
        $myItems = Item::with(['category', 'requests.user', 'requests.chatRoom', 'city', 'district'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
 
        // Requests I've sent
        $sentRequests = ItemRequest::with(['item.user', 'item.category', 'item.city', 'item.district', 'chatRoom'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
 
        // Stats calculations
        $myItemsIds = $myItems->pluck('id')->toArray();
        $stats = [
            'karma' => $user->karma_points,
            'posted_count' => $myItems->count(),
            'successful_count' => $myItems->where('status', 'completed')->count(),
            'pending_received_count' => ItemRequest::whereIn('item_id', $myItemsIds)
                ->where('status', 'pending')
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
