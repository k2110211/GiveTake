<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\News;
use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $categories = Category::all();
        $newsList = News::with('user')->latest()->take(5)->get();

        // Count stats for counters
        $totalItems = Item::whereNotIn('item_status_id', [\App\Models\ItemStatus::PENDING, \App\Models\ItemStatus::REJECTED])->count();
        $totalUsers = User::count();
        $totalCompleted = Item::where('item_status_id', 4)->count();

        return view('livewire.home', [
            'categories' => $categories,
            'newsList' => $newsList,
            'totalItems' => $totalItems,
            'totalUsers' => $totalUsers,
            'totalCompleted' => $totalCompleted,
        ])->layout('layouts.app');
    }
}
