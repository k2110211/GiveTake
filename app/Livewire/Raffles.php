<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\City;
use App\Models\District;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Raffles extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';
    public $city = '';
    public $district = '';
    public $statusFilter = 'available'; // 'all', 'available', 'completed'

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'city' => ['except' => ''],
        'district' => ['except' => ''],
        'statusFilter' => ['except' => 'available'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
    }

    public function updatedCity()
    {
        $this->district = '';
        $this->resetPage();
    }

    public function updatedDistrict()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'categoryId', 'city', 'district', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all();
        $cities = City::all();
        $districts = $this->city ? District::where('city_id', $this->city)->get() : collect();

        // Query only Lucky Draw items (type_id = 3)
        $query = Item::with(['category', 'user', 'city', 'district', 'status', 'winner', 'requests'])
            ->where('type_id', 3);

        if ($this->statusFilter === 'available') {
            $query->where('item_status_id', 1); // Đang có sẵn / Đang diễn ra
        } elseif ($this->statusFilter === 'completed') {
            $query->whereIn('item_status_id', [2, 3, 4]); // Đã trao / Đang chốt kết quả
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->city) {
            $query->where('city_id', $this->city);
        }

        if ($this->district) {
            $query->where('district_id', $this->district);
        }

        $items = $query->latest()->paginate(9);

        // Stats for hero banner
        $totalRaffles = Item::where('type_id', 3)->count();
        $activeRaffles = Item::where('type_id', 3)->where('item_status_id', 1)->count();
        $completedRaffles = Item::where('type_id', 3)->whereIn('item_status_id', [2, 3, 4])->count();

        return view('livewire.raffles', [
            'items' => $items,
            'categories' => $categories,
            'cities' => $cities,
            'districts' => $districts,
            'totalRaffles' => $totalRaffles,
            'activeRaffles' => $activeRaffles,
            'completedRaffles' => $completedRaffles,
        ])->layout('layouts.app');
    }
}
