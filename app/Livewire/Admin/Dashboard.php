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
            'available_items'    => Item::where('status', 'available')->count(),
            'reserved_items'     => Item::where('status', 'reserved')->count(),
            'completed_items'    => Item::where('status', 'completed')->count(),
            'total_requests'     => ItemRequest::count(),
            'pending_requests'   => ItemRequest::where('status', 'pending')->count(),
            'approved_requests'  => ItemRequest::where('status', 'approved')->count(),
            'total_reviews'      => Review::count(),
            'avg_rating'         => round(Review::avg('rating') ?? 0, 2),
        ];
 
        $recentItems = Item::with(['user', 'category'])
            ->latest()->take(5)->get();
 
        $recentRequests = ItemRequest::with(['item', 'user'])
            ->latest()->take(5)->get();
 
        return view('livewire.admin.dashboard', compact('stats', 'recentItems', 'recentRequests'))
            ->layout('layouts.admin');
    }
}
