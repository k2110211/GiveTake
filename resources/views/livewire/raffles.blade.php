<div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Banner Section for Lucky Draw Page -->
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-purple-600 via-indigo-600 to-purple-900 text-white p-8 sm:p-12 shadow-xl mb-10">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -left-20 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-2xl">
                <span class="bg-white/20 backdrop-blur-md text-[10px] sm:text-xs font-extrabold tracking-widest uppercase px-3 py-1 rounded-full text-white mb-4 inline-flex items-center">
                    <span class="text-sm mr-1.5">🎲</span> Độc Quyền Cộng Đồng Give & Take
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                    Quay Thưởng May Mắn
                </h1>
                <p class="text-sm sm:text-base text-purple-100 mb-6 leading-relaxed">
                    Nơi quy tụ những món đồ quà tặng đặc biệt! Tích lũy điểm Karma uy tín để đăng ký tham gia các lượt quay ngẫu nhiên và nhận món đồ mơ ước.
                </p>

                @auth
                    <div class="inline-flex items-center px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-xs sm:text-sm font-bold text-yellow-300">
                        <svg class="w-4 h-4 mr-2 text-yellow-400 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Điểm Karma của bạn: {{ auth()->user()->karma_points }} điểm
                    </div>
                @endauth
            </div>

            <!-- Stats Badge Grid in Hero -->
            <div class="relative z-10 mt-8 grid grid-cols-3 gap-3 sm:gap-4 max-w-md">
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-xl sm:text-3xl font-extrabold">{{ $totalRaffles }}</div>
                    <div class="text-[10px] sm:text-xs text-purple-200 font-medium mt-0.5">Tổng bài quay</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-xl sm:text-3xl font-extrabold text-emerald-300">{{ $activeRaffles }}</div>
                    <div class="text-[10px] sm:text-xs text-purple-200 font-medium mt-0.5">Đang diễn ra</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-xl sm:text-3xl font-extrabold text-yellow-300">{{ $completedRaffles }}</div>
                    <div class="text-[10px] sm:text-xs text-purple-200 font-medium mt-0.5">Đã chốt quà</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/50 mb-8 space-y-4">
            
            <!-- Status Tabs -->
            <div class="flex items-center space-x-2 border-b border-gray-100 dark:border-gray-700 pb-4">
                <button wire:click="$set('statusFilter', 'available')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $statusFilter === 'available' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🔥 Đang quay thưởng ({{ $activeRaffles }})
                </button>
                <button wire:click="$set('statusFilter', 'completed')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $statusFilter === 'completed' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🎉 Đã trúng thưởng ({{ $completedRaffles }})
                </button>
                <button wire:click="$set('statusFilter', 'all')" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $statusFilter === 'all' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Tất cả
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm tên món đồ quay..." 
                           class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:text-gray-300 placeholder-gray-400 pl-10">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Category Filter -->
                <div>
                    <select wire:model.live="categoryId" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:text-gray-300">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City Filter -->
                <div>
                    <select wire:model.live="city" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:text-gray-300">
                        <option value="">Tất cả tỉnh / thành</option>
                        @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- District Filter -->
                <div>
                    <select wire:model.live="district" @if(!$city) disabled @endif class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:text-gray-300 disabled:opacity-50">
                        <option value="">Tất cả quận / huyện</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Active Filters Reset Button -->
            @if($search || $categoryId || $city || $district || $statusFilter !== 'available')
                <div class="flex justify-end pt-2">
                    <button wire:click="resetFilters" class="text-xs text-rose-600 hover:text-rose-700 font-bold flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Xóa tất cả bộ lọc
                    </button>
                </div>
            @endif
        </div>

        <!-- Lucky Draw Grid Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($items as $item)
                <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700/60 flex flex-col group relative">
                    
                    <!-- Item Image Container -->
                    <div class="relative aspect-[4/3] bg-gray-100 dark:bg-gray-900 overflow-hidden">
                        <img src="{{ $item->thumbnail ?? ($item->images[0] ?? 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=600&q=80') }}" 
                             alt="{{ $item->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Lucky Draw Badge Top Right -->
                        <div class="absolute top-3 right-3 bg-purple-900/80 backdrop-blur-md text-white text-[11px] font-extrabold px-3 py-1 rounded-full border border-purple-400/30 flex items-center shadow-lg">
                            <span class="mr-1">🎲</span> >= {{ $item->min_karma }} Karma
                        </div>

                        <!-- Status Badge Top Left -->
                        <div class="absolute top-3 left-3">
                            @if($item->item_status_id == 1)
                                <span class="bg-emerald-500 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full shadow-md">
                                    Đang diễn ra
                                </span>
                            @else
                                <span class="bg-gray-700 text-gray-200 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full shadow-md">
                                    Đã kết thúc
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Item Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <!-- Category & Location -->
                            <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 mb-2">
                                <span class="font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider text-[11px]">
                                    {{ $item->category->name }}
                                </span>
                                <span>{{ $item->district?->name }}, {{ $item->city?->name }}</span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-base leading-snug line-clamp-2 group-hover:text-purple-600 transition-colors">
                                {{ $item->title }}
                            </h3>

                            <!-- Winner info if completed -->
                            @if($item->winner_id && $item->winner)
                                <div class="mt-3 p-2.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/40 text-xs font-bold text-purple-800 dark:text-purple-300 flex items-center">
                                    <span class="mr-1.5">🎉</span> Người trúng: <span class="ml-1 text-purple-900 dark:text-purple-100 font-extrabold underline">{{ $item->winner->name }}</span>
                                </div>
                            @else
                                <div class="mt-3 text-xs font-semibold text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ $item->requests->count() }} người đã đăng ký quay
                                </div>
                            @endif
                        </div>

                        <!-- Footer User & Detail Link -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate max-w-[110px]">
                                    {{ $item->user->name }}
                                </span>
                            </div>

                            <a href="{{ route('item.detail', ['id' => $item->id]) }}" 
                               wire:navigate
                               class="px-3.5 py-2 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition shadow-sm flex items-center">
                                Chi tiết
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-3xl p-12 text-center shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/40 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎲</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-gray-900 dark:text-gray-100">Chưa tìm thấy bài quay thưởng nào!</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
                        Hiện chưa có món đồ quay thưởng nào khớp với bộ lọc của bạn. Hãy thử thay đổi tìm kiếm hoặc đăng bài quay thưởng đầu tiên nhé!
                    </p>
                    @auth
                        <a href="{{ route('item.create') }}" wire:navigate class="inline-flex items-center px-5 py-2.5 rounded-xl bg-purple-600 text-white font-bold text-xs mt-5 hover:bg-purple-700 transition shadow-md">
                            + Đăng bài quay thưởng mới
                        </a>
                    @endauth
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div>
            {{ $items->links() }}
        </div>
    </div>
</div>
