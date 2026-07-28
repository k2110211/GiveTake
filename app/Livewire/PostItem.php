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
    public $type = 1;
    public $exchangeWish = '';
    public $minKarma = 0;
    public $city = '';
    public $district = '';
    public $thumbnail;
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
            'type' => 'required|exists:types,id',
            'exchangeWish' => 'required_if:type,2|nullable|string|max:200',
            'minKarma' => 'required_if:type,3|integer|min:0|max:9999',
            'city' => 'required|exists:cities,id',
            'district' => 'required|exists:districts,id',
            'thumbnail' => 'required|image|max:2048', // 1 main thumbnail image, max 2MB
            'images' => 'nullable|array|max:3', // max 3 optional description images
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
            'type.exists' => 'Hình thức chọn không hợp lệ.',
            'exchangeWish.required_if' => 'Vui lòng nhập mong muốn nhận lại khi chọn hình thức trao đổi.',
            'minKarma.required_if' => 'Vui lòng nhập điểm Karma tối thiểu khi chọn quay thưởng.',
            'minKarma.integer' => 'Điểm Karma phải là số nguyên.',
            'minKarma.min' => 'Điểm Karma không được nhỏ hơn 0.',
            'city.required' => 'Vui lòng chọn tỉnh / thành phố.',
            'city.exists' => 'Tỉnh / thành phố không hợp lệ.',
            'district.required' => 'Vui lòng chọn quận / huyện.',
            'district.exists' => 'Quận / huyện không hợp lệ.',
            'thumbnail.required' => 'Vui lòng tải lên 1 hình ảnh đại diện chính.',
            'thumbnail.image' => 'Tệp tải lên phải là hình ảnh (jpg, png, jpeg...).',
            'thumbnail.max' => 'Hình ảnh đại diện không được vượt quá 2MB.',
            'images.max' => 'Bạn chỉ được đăng tối đa 3 hình ảnh mô tả bổ sung.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh (jpg, png, jpeg, webp...).',
            'images.*.max' => 'Mỗi hình ảnh mô tả không được vượt quá 2MB.'
        ];
    }
 
    public function save()
    {
        $this->validate();
 
        // Save main thumbnail image
        $thumbnailPath = $this->thumbnail->store('items', 'public');
        $thumbnailUrl = asset('storage/' . $thumbnailPath);
 
        // Save description images
        $imagePaths = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                if ($image) {
                    $path = $image->store('items', 'public');
                    $imagePaths[] = asset('storage/' . $path);
                }
            }
        }
 
        $item = Item::create([
            'user_id' => auth()->id(),
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $thumbnailUrl,
            'images' => $imagePaths,
            'type_id' => $this->type,
            'exchange_wish' => (int)$this->type === 2 ? $this->exchangeWish : null,
            'min_karma' => (int)$this->type === 3 ? (int)$this->minKarma : 0,
            'item_status_id' => 1,
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
        $types = \App\Models\Type::all();
 
        return view('livewire.post-item', [
            'categories' => $categories,
            'districts' => $districts,
            'cities' => $cities,
            'types' => $types
        ])->layout('layouts.app');
    }
}
