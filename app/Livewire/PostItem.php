<?php
 
namespace App\Livewire;
 
use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;
 
class PostItem extends Component
{
    use WithFileUploads;
 
    public $title = '';
    public $description = '';
    public $categoryId = '';
    public $type = 'give';
    public $exchangeWish = '';
    public $city = '';
    public $district = '';
    public $images = [];
 
    public function updatedCity()
    {
        $this->district = '';
    }
 
    protected function rules()
    {
        return [
            'title' => 'required|string|min:10|max:100',
            'description' => 'required|string|min:20|max:1000',
            'categoryId' => 'required|exists:categories,id',
            'type' => 'required|in:give,exchange',
            'exchangeWish' => 'required_if:type,exchange|nullable|string|max:200',
            'city' => 'required|exists:cities,id',
            'district' => 'required|exists:districts,id',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|max:2048' // max 2MB
        ];
    }
 
    protected function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài đăng.',
            'title.min' => 'Tiêu đề phải dài ít nhất 10 ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết.',
            'description.min' => 'Mô tả phải dài ít nhất 20 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'categoryId.required' => 'Vui lòng chọn danh mục.',
            'categoryId.exists' => 'Danh mục chọn không hợp lệ.',
            'type.required' => 'Vui lòng chọn hình thức.',
            'type.in' => 'Hình thức chọn không hợp lệ.',
            'exchangeWish.required_if' => 'Vui lòng nhập mong muốn nhận lại khi chọn hình thức trao đổi.',
            'city.required' => 'Vui lòng chọn tỉnh / thành phố.',
            'city.exists' => 'Tỉnh / thành phố không hợp lệ.',
            'district.required' => 'Vui lòng chọn quận / huyện.',
            'district.exists' => 'Quận / huyện không hợp lệ.',
            'images.required' => 'Vui lòng đăng ít nhất 1 hình ảnh sản phẩm.',
            'images.min' => 'Vui lòng đăng ít nhất 1 hình ảnh sản phẩm.',
            'images.max' => 'Bạn chỉ được đăng tối đa 5 hình ảnh.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh (jpg, png, jpeg, webp...).',
            'images.*.max' => 'Mỗi hình ảnh không được vượt quá 2MB.'
        ];
    }
 
    public function save()
    {
        $this->validate();
 
        $imagePaths = [];
        foreach ($this->images as $image) {
            $path = $image->store('items', 'public');
            $imagePaths[] = asset('storage/' . $path);
        }
 
        $item = Item::create([
            'user_id' => auth()->id(),
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'images' => $imagePaths,
            'type' => $this->type,
            'exchange_wish' => $this->type === 'exchange' ? $this->exchangeWish : null,
            'status' => 'available',
            'city_id' => $this->city,
            'district_id' => $this->district
        ]);
 
        session()->flash('success', 'Món đồ đã được đăng tải thành công!');
        return $this->redirect(route('item.detail', ['id' => $item->id]), navigate: true);
    }
 
    public function render()
    {
        $categories = Category::all();
        $cities = \App\Models\City::all();
        $districts = $this->city ? \App\Models\District::where('city_id', $this->city)->get() : collect();
 
        return view('livewire.post-item', [
            'categories' => $categories,
            'districts' => $districts,
            'cities' => $cities
        ])->layout('layouts.app');
    }
}
