<?php
 
namespace App\Livewire;
 
use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
 
class Home extends Component
{
    use WithPagination;
 
    public $search = '';
    public $categoryId = '';
    public $type = '';
    public $city = '';
    public $district = '';
    
    public $readyToLoad = false;

    public function mount()
    {
        if (app()->environment('testing')) {
            $this->readyToLoad = true;
        }
    }

    public function loadItems()
    {
        $this->readyToLoad = true;
    }
 
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
        $cities = \App\Models\City::all();
        $districts = $this->city ? \App\Models\District::where('city_id', $this->city)->get() : collect();

        // Count stats for counters
        $totalItems = Item::count();
        $totalUsers = \App\Models\User::count();
        $totalCompleted = Item::where('status', 'completed')->count();

        if ($this->readyToLoad) {
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

            $items = $query->latest()->paginate(6);
        } else {
            $items = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6);
        }
 
        return view('livewire.home', [
            'items' => $items,
            'categories' => $categories,
            'districts' => $districts,
            'cities' => $cities,
            'totalItems' => $totalItems,
            'totalUsers' => $totalUsers,
            'totalCompleted' => $totalCompleted
        ])->layout('layouts.app');
    }
}
