<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\City;
use App\Models\District;
use Livewire\Component;
use Livewire\WithPagination;

class SearchItems extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';
    public $type = '';
    public $city = '';
    public $district = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'type' => ['except' => ''],
        'city' => ['except' => ''],
        'district' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
    }

    public function updatedType()
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

    public function resetFilters()
    {
        $this->reset(['search', 'categoryId', 'type', 'city', 'district']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all();
        $cities = City::all();
        $districts = $this->city ? District::where('city_id', $this->city)->get() : collect();

        $query = Item::with(['category', 'user', 'city', 'district'])
            ->where('status', 'available');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->city) {
            $query->where('city_id', $this->city);
        }

        if ($this->district) {
            $query->where('district_id', $this->district);
        }

        $items = $query->latest()->paginate(9);

        return view('livewire.search-items', [
            'items' => $items,
            'categories' => $categories,
            'cities' => $cities,
            'districts' => $districts,
        ])->layout('layouts.app');
    }
}
