<?php

use App\Livewire\Actions\Logout;
use App\Models\ChatMessage;
use App\Models\ItemRequest;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function with(): array
    {
        if (!auth()->check()) {
            return [
                'unreadMessagesCount' => 0,
                'pendingRequestsCount' => 0,
                'totalNotifications' => 0,
                'latestUnreadMessages' => collect(),
            ];
        }

        $userId = auth()->id();

        // Count unread chat messages for this user
        $unreadMessagesCount = ChatMessage::where('is_read', false)
            ->where('user_id', '!=', $userId)
            ->whereHas('chatRoom.itemRequest', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('item', function ($iq) use ($userId) {
                      $iq->where('user_id', $userId);
                  });
            })
            ->count();

        // Get recent unread messages with sender info
        $latestUnreadMessages = $unreadMessagesCount > 0 
            ? ChatMessage::with(['user', 'chatRoom.itemRequest.item'])
                ->where('is_read', false)
                ->where('user_id', '!=', $userId)
                ->whereHas('chatRoom.itemRequest', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhereHas('item', function ($iq) use ($userId) {
                          $iq->where('user_id', $userId);
                      });
                })
                ->latest()
                ->take(4)
                ->get()
            : collect();

        // Count pending incoming requests for items owned by this user
        $pendingRequestsCount = ItemRequest::whereHas('item', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('request_status_id', 1)
            ->count();

        return [
            'unreadMessagesCount' => $unreadMessagesCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'totalNotifications' => $unreadMessagesCount + $pendingRequestsCount,
            'latestUnreadMessages' => $latestUnreadMessages,
        ];
    }
}; ?>

<nav x-data="{ 
        open: false, 
        scrolled: false, 
        darkMode: localStorage.getItem('theme') === 'dark'
     }"
     x-init="
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 });
        window.addEventListener('theme-changed', (e) => { darkMode = e.detail.isDark });
     "
     :class="scrolled ? 'glass-nav shadow-sm' : 'bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800'"
     class="sticky top-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" wire:navigate class="flex items-center space-x-2 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:shadow-teal-500/40 transition-all duration-200 group-hover:scale-105">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-gray-900 dark:text-white font-extrabold text-base tracking-tight leading-none">
                                Cho<span class="text-teal-500 dark:text-teal-400"> & </span>Nhận
                            </span>
                            <span class="text-[9px] text-gray-500 dark:text-gray-400 font-semibold tracking-wider uppercase mt-0.5 leading-none hidden sm:inline">
                                Chia sẻ cộng đồng
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 lg:ms-8 lg:flex">
                    <a href="{{ route('home') }}" wire:navigate
                       class="inline-flex items-center px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                              {{ request()->routeIs('home')
                                  ? 'text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40'
                                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Trang chủ
                    </a>

                    <a href="{{ route('search') }}" wire:navigate
                       class="inline-flex items-center px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                              {{ request()->routeIs('search') && !request('type')
                                  ? 'text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40'
                                  : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Khám phá
                    </a>

                    <a href="{{ route('raffles') }}" wire:navigate
                       class="inline-flex items-center px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                              {{ request()->routeIs('raffles')
                                  ? 'text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40'
                                  : 'text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-200 hover:bg-purple-50 dark:hover:bg-purple-950/30' }}">
                        <span class="text-sm mr-1">🎲</span>
                        Quay Thưởng
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate
                           class="inline-flex items-center px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 relative
                                  {{ request()->routeIs('dashboard')
                                      ? 'text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40'
                                      : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Quản lý
                            @if($totalNotifications > 0)
                                <span class="ms-1.5 px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-rose-500 text-white leading-tight">
                                    {{ $totalNotifications }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('item.create') }}" wire:navigate
                           class="inline-flex items-center px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                  {{ request()->routeIs('item.create')
                                      ? 'text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40'
                                      : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Đăng tin
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side: Auth / User Menu -->
            <div class="hidden lg:flex lg:items-center lg:ms-6 space-x-2 lg:space-x-3">
                <!-- Theme Switcher Button -->
                <button onclick="toggleTheme()" class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200" aria-label="Toggle theme">
                    <!-- Sun icon -->
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon icon -->
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{ openNotifications: false }" @click.outside="openNotifications = false" @close.stop="openNotifications = false">
                        <button @click="openNotifications = !openNotifications"
                                class="relative p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200 focus:outline-none"
                                aria-label="Thông báo">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>

                            @if($totalNotifications > 0)
                                <span class="absolute top-1 right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-extrabold text-white shadow-sm ring-2 ring-white dark:ring-gray-900 animate-pulse">
                                    {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div x-show="openNotifications"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700 py-3 z-50 text-left"
                             style="display: none;">

                            <!-- Header -->
                            <div class="px-4 pb-2.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                    <span class="mr-2">🔔</span> Thông báo & Tin nhắn
                                </h4>
                                @if($totalNotifications > 0)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-bold">
                                        {{ $totalNotifications }} mới
                                    </span>
                                @endif
                            </div>

                            <!-- List -->
                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700/50">
                                @if($pendingRequestsCount > 0)
                                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-start p-3.5 hover:bg-teal-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="w-8 h-8 rounded-xl bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 flex items-center justify-center flex-shrink-0 mr-3 text-sm">
                                            🎁
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-900 dark:text-gray-100">
                                                Có {{ $pendingRequestsCount }} yêu cầu nhận đồ mới
                                            </p>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                Nhấn để vào trang Quản lý và duyệt yêu cầu người nhận.
                                            </p>
                                        </div>
                                    </a>
                                @endif

                                @if($latestUnreadMessages->isNotEmpty())
                                    @foreach($latestUnreadMessages as $msg)
                                        <a href="{{ route('chat.room', ['roomId' => $msg->chat_room_id]) }}" wire:navigate class="flex items-start p-3.5 hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                            <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs flex-shrink-0 mr-3">
                                                {{ substr($msg->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $msg->user->name ?? 'Người dùng' }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400">
                                                        {{ $msg->created_at->diffForHumans(null, true) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-300 truncate mt-0.5">
                                                    {{ $msg->message }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @if($totalNotifications === 0)
                                    <div class="py-8 text-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-xs">Bạn chưa có thông báo mới</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="px-4 pt-2 border-t border-gray-100 dark:border-gray-700 text-center">
                                <a href="{{ route('dashboard') }}" wire:navigate class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">
                                    Xem tất cả trong trang Quản lý →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Karma badge -->
                    <div class="flex items-center px-3 py-1.5 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 text-xs font-bold">
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ auth()->user()->karma_points ?? 0 }}
                    </div>

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="group inline-flex items-center px-2.5 py-1.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition-all duration-200">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center text-white font-bold text-xs mr-2 shadow-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="max-w-[100px] lg:max-w-[120px] truncate"></span>

                                <svg class="ms-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Hồ sơ cá nhân') }}
                            </x-dropdown-link>

                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Đăng xuất') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" wire:navigate
                           class="px-3 lg:px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" wire:navigate
                           class="px-3 lg:px-4 py-2 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-200">
                            Tham gia ngay
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Controls (Theme Switcher + Hamburger) -->
            <div class="-me-2 flex items-center space-x-1 lg:hidden">
                <!-- Mobile Theme Switcher Icon -->
                <button onclick="toggleTheme()" class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200" aria-label="Toggle theme">
                    <!-- Sun icon -->
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon icon -->
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                    <!-- Mobile Notification Bell -->
                    <a href="{{ route('dashboard') }}" wire:navigate class="relative p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200" aria-label="Thông báo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($totalNotifications > 0)
                            <span class="absolute top-1 right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-extrabold text-white shadow-sm ring-2 ring-white dark:ring-gray-900">
                                {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
                            </span>
                        @endif
                    </a>
                @endauth

                <!-- Hamburger Button -->
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-all duration-200">
                    <svg class="h-6 w-6 transition-transform duration-200" :class="open ? 'rotate-90' : ''" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">

        <div class="p-4 space-y-1">
            <a href="{{ route('home') }}" wire:navigate
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Trang chủ
            </a>
            <a href="{{ route('search') }}" wire:navigate
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('search') && !request('type') ? 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Khám phá
            </a>
            <a href="{{ route('raffles') }}" wire:navigate
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('raffles') ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300' : 'text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/30' }}">
                <span class="text-base mr-3">🎲</span>
                Quay Thưởng
            </a>

            @auth
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Quản lý
                    </div>
                    @if($totalNotifications > 0)
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-rose-500 text-white">
                            {{ $totalNotifications }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('item.create') }}" wire:navigate
                   class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('item.create') ? 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Đăng tin
                </a>
            @endauth
        </div>

        <!-- Mobile User Section -->
        @auth
            <div class="border-t border-gray-100 dark:border-gray-800 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm mr-3">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800">
                        Hồ sơ cá nhân
                    </a>
                    <button wire:click="logout" class="w-full flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                        Đăng xuất
                    </button>
                </div>
            </div>
        @else
            <div class="border-t border-gray-100 dark:border-gray-800 p-4 space-y-2">
                <a href="{{ route('login') }}" wire:navigate class="block w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}" wire:navigate class="block w-full text-center px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600">
                    Tham gia ngay
                </a>
            </div>
        @endauth
    </div>
</nav>
