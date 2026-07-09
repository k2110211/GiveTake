<?php

use App\Livewire\ChatRoom as ChatRoomComponent;
use App\Livewire\Dashboard;
use App\Livewire\Home;
use App\Livewire\ItemDetail;
use App\Livewire\PostItem;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/items/create', PostItem::class)->middleware('auth')->name('item.create');
Route::get('/items/{id}', ItemDetail::class)->name('item.detail');

Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
 
Route::get('/chat/{roomId}', ChatRoomComponent::class)
    ->middleware('auth')
    ->name('chat.room');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
