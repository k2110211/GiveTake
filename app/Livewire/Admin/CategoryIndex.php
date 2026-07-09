<?php
 
namespace App\Livewire\Admin;
 
use App\Models\Category;
use Livewire\Component;
 
class CategoryIndex extends Component
{
    public $name = '';
    public $editingId = null;
    public $editName = '';
    public $confirmDeleteId = null;
 
    public function createCategory()
    {
        $this->validate(['name' => 'required|string|max:100|unique:categories,name']);
 
        Category::create([
            'name' => $this->name,
            'slug' => \Illuminate\Support\Str::slug($this->name),
        ]);
 
        $this->name = '';
        session()->flash('success', 'Đã thêm danh mục mới.');
    }
 
    public function startEdit(int $id): void
    {
        $this->editingId = $id;
        $this->editName  = Category::findOrFail($id)->name;
    }
 
    public function saveEdit(): void
    {
        $this->validate(['editName' => 'required|string|max:100']);
 
        $cat = Category::findOrFail($this->editingId);
        $cat->update([
            'name' => $this->editName,
            'slug' => \Illuminate\Support\Str::slug($this->editName),
        ]);
 
        $this->editingId = null;
        session()->flash('success', 'Đã cập nhật danh mục.');
    }
 
    public function cancelEdit(): void
    {
        $this->editingId = null;
    }
 
    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }
 
    public function deleteCategory(): void
    {
        if (!$this->confirmDeleteId) return;
 
        Category::findOrFail($this->confirmDeleteId)->delete();
        $this->confirmDeleteId = null;
        session()->flash('success', 'Đã xóa danh mục.');
    }
 
    public function render()
    {
        $categories = Category::withCount('items')->orderBy('name')->get();
 
        return view('livewire.admin.category-index', compact('categories'))
            ->layout('layouts.admin');
    }
}
