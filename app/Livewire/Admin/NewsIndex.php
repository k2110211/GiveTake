<?php

namespace App\Livewire\Admin;

use App\Models\News;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class NewsIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    
    // Form fields for create/edit
    public $title = '';
    public $summary = '';
    public $content = '';
    public $imageFile;
    public $existingImage = null;

    public $editingId = null;
    public $confirmDeleteId = null;
    public $showForm = false;

    protected $queryString = ['search' => ['except' => '']];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function startEdit($id)
    {
        $this->resetForm();
        $news = News::findOrFail($id);
        $this->editingId = $news->id;
        $this->title = $news->title;
        $this->summary = $news->summary;
        $this->content = $news->content;
        $this->existingImage = $news->image;
        $this->showForm = true;
    }

    public function saveNews()
    {
        $rules = [
            'title' => 'required|string|min:5|max:150',
            'summary' => 'required|string|min:10|max:500',
            'content' => 'required|string|min:20',
            'imageFile' => $this->editingId ? 'nullable|image|max:2048' : 'required|image|max:2048'
        ];

        $this->validate($rules);

        $imageUrl = $this->existingImage;

        if ($this->imageFile) {
            $path = $this->imageFile->store('news', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        if ($this->editingId) {
            $news = News::findOrFail($this->editingId);
            $news->update([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'summary' => $this->summary,
                'content' => $this->content,
                'image' => $imageUrl,
            ]);
            session()->flash('success', 'Đã cập nhật bài đăng tin tức thành công.');
        } else {
            News::create([
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'summary' => $this->summary,
                'content' => $this->content,
                'image' => $imageUrl,
                'user_id' => auth()->id()
            ]);
            session()->flash('success', 'Đã đăng bài tin tức mới thành công.');
        }

        $this->resetForm();
    }

    public function cancelForm()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['title', 'summary', 'content', 'imageFile', 'existingImage', 'editingId', 'showForm']);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteNews()
    {
        if (!$this->confirmDeleteId) return;

        News::findOrFail($this->confirmDeleteId)->delete();
        $this->confirmDeleteId = null;
        session()->flash('success', 'Đã xoá bài tin tức thành công.');
    }

    public function render()
    {
        $query = News::with('user');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('summary', 'like', '%' . $this->search . '%');
        }

        $newsItems = $query->latest()->paginate(10);

        return view('livewire.admin.news-index', [
            'newsItems' => $newsItems
        ])->layout('layouts.admin');
    }
}
