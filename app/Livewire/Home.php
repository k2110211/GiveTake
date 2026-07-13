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
        $totalItems = Item::count();
        $totalUsers = User::count();
        $totalCompleted = Item::where('status', 'completed')->count();

        return view('livewire.home', [
            'categories' => $categories,
            'newsList' => $newsList,
            'totalItems' => $totalItems,
            'totalUsers' => $totalUsers,
            'totalCompleted' => $totalCompleted,
        ])->layout('layouts.app');
    }
}
