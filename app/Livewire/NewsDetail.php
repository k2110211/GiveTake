<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;

class NewsDetail extends Component
{
    public $news;

    public function mount($id)
    {
        $this->news = News::with('user')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.news-detail')->layout('layouts.app');
    }
}
