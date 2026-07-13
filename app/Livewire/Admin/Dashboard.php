<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Review;
use App\Models\User;
use Livewire\Component;
 
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_users'        => User::where('is_admin', false)->count(),
            'banned_users'       => User::where('is_banned', true)->count(),
            'total_items'        => Item::count(),
            'available_items'    => Item::where('item_status_id', 1)->count(),
            'reserved_items'     => Item::whereIn('item_status_id', [2, 3])->count(),
            'completed_items'    => Item::where('item_status_id', 4)->count(),
            'total_requests'     => ItemRequest::count(),
            'pending_requests'   => ItemRequest::where('request_status_id', 1)->count(),
            'approved_requests'  => ItemRequest::where('request_status_id', 2)->count(),
            'total_reviews'      => Review::count(),
            'avg_rating'         => round(Review::avg('rating') ?? 0, 2),
        ];
 
        $recentItems = Item::with(['user', 'category', 'city'])
            ->latest()->take(5)->get();
 
        $recentRequests = ItemRequest::with(['item', 'user'])
            ->latest()->take(5)->get();
 
        return view('livewire.admin.dashboard', compact('stats', 'recentItems', 'recentRequests'))
            ->layout('layouts.admin');
    }
}
