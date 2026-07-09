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
 
    // Define cities and districts data
    public static $locations = [
        'Hồ Chí Minh' => ['Quận 1', 'Quận 3', 'Quận 10', 'Bình Thạnh', 'Thủ Đức'],
        'Hà Nội' => ['Cầu Giấy', 'Đống Đa', 'Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng'],
        'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Liên Chiểu', 'Ngũ Hành Sơn']
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
        $query = Item::with(['category', 'user'])
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
            $query->where('city', $this->city);
        }
 
        if ($this->district) {
            $query->where('district', $this->district);
        }
 
        $items = $query->latest()->paginate(6);
        $categories = Category::all();
        $districts = $this->city ? (self::$locations[$this->city] ?? []) : [];
 
        return view('livewire.home', [
            'items' => $items,
            'categories' => $categories,
            'districts' => $districts,
            'cities' => array_keys(self::$locations)
        ])->layout('layouts.app');
    }
}
