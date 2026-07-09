<?php
 
use App\Livewire\Admin\CategoryIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ItemIndex;
use App\Livewire\Admin\ReviewIndex;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Admin\UserShow;
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
 
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
 
// ─── Admin Routes ────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', UserIndex::class)->name('users');
    Route::get('/users/{id}', UserShow::class)->name('users.show');
    Route::get('/items', ItemIndex::class)->name('items');
    Route::get('/categories', CategoryIndex::class)->name('categories');
    Route::get('/reviews', ReviewIndex::class)->name('reviews');
    Route::get('/transactions', TransactionIndex::class)->name('transactions');
});
 
require __DIR__.'/auth.php';
