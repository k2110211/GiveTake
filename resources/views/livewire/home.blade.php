<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" wire:init="loadItems">
    <!-- Hero Banner Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 text-white p-8 sm:p-12 shadow-xl">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -left-20 w-80 h-80 bg-cyan-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 max-w-2xl">
                <span class="bg-white/20 backdrop-blur-md text-[10px] sm:text-xs font-semibold tracking-widest uppercase px-3 py-1 rounded-full text-white mb-4 inline-block">
                    Cộng Đồng Chia Sẻ Đồ Cũ
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                    Give & Take — Cho Đi Là Nhận Lại
                </h1>
                <p class="text-sm sm:text-base text-emerald-100 mb-6 leading-relaxed">
                    Nền tảng giúp bạn trao tặng những món đồ không dùng tới hoặc trao đổi lấy những gì bạn đang cần. Hãy cùng nhau xây dựng lối sống xanh và sẻ chia!
                </p>
                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="/dashboard" class="bg-white text-teal-800 font-bold px-6 py-3 rounded-xl shadow-md hover:bg-emerald-50 hover:shadow-lg transition-all duration-200 text-sm">
                            Đăng tin chia sẻ ngay
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-teal-800 font-bold px-6 py-3 rounded-xl shadow-md hover:bg-emerald-50 hover:shadow-lg transition-all duration-200 text-sm">
                            Tham gia cộng đồng
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Animated Stats in Hero -->
            <div class="relative z-10 mt-8 grid grid-cols-3 gap-4 max-w-lg"
                 x-data="{ shown: false }"
                 x-intersect.once="shown = true">
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-2xl sm:text-3xl font-extrabold counter-value"
                         x-data="{ count: 0, target: {{ $totalItems ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-[10px] sm:text-xs text-emerald-200 font-medium mt-0.5">Món đồ</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-2xl sm:text-3xl font-extrabold counter-value"
                         x-data="{ count: 0, target: {{ $totalUsers ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-[10px] sm:text-xs text-emerald-200 font-medium mt-0.5">Thành viên</div>
                </div>
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-3">
                    <div class="text-2xl sm:text-3xl font-extrabold counter-value"
                         x-data="{ count: 0, target: {{ $totalCompleted ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-[10px] sm:text-xs text-emerald-200 font-medium mt-0.5">Đã trao đổi</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Search, Filters & Listing -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 h-fit sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center uppercase tracking-wide">
                            <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Bộ lọc tìm kiếm
                        </h3>
                        <button wire:click="resetFilters" class="text-xs text-teal-600 dark:text-teal-400 hover:underline font-medium">
                            Xoá tất cả
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Danh mục</label>
                            <select wire:model.live="categoryId" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 transition-colors">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type Filter (Give / Exchange) -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Hình thức</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button wire:click="$set('type', '')" class="py-2 text-[10px] font-bold rounded-xl transition-all duration-200 {{ $type === '' ? 'bg-teal-500 text-white shadow-sm scale-[1.02]' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                    Tất cả
                                </button>
                                <button wire:click="$set('type', 'give')" class="py-2 text-[10px] font-bold rounded-xl transition-all duration-200 {{ $type === 'give' ? 'bg-emerald-500 text-white shadow-sm scale-[1.02]' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                    Tặng
                                </button>
                                <button wire:click="$set('type', 'exchange')" class="py-2 text-[10px] font-bold rounded-xl transition-all duration-200 {{ $type === 'exchange' ? 'bg-orange-500 text-white shadow-sm scale-[1.02]' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                    Đổi đồ
                                </button>
                            </div>
                        </div>

                        <!-- City Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Tỉnh / Thành phố</label>
                            <select wire:model.live="city" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 transition-colors">
                                <option value="">Tất cả Tỉnh/Thành</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- District Filter -->
                        @if($city)
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Quận / Huyện</label>
                                <select wire:model.live="district" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 transition-colors">
                                    <option value="">Tất cả Quận/Huyện</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Grid Section -->
            <div class="lg:col-span-3">
                <!-- Search Bar -->
                <div class="relative mb-8 shadow-sm rounded-2xl overflow-hidden">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm kiếm đồ dùng (ví dụ: áo khoác, sách giáo khoa, đàn guitar...)..." class="block w-full pl-11 pr-4 py-4 border-0 dark:border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-teal-500 rounded-2xl text-sm border-gray-100 dark:border-gray-700">

                    <!-- Loading indicator -->
                    <div wire:loading.delay wire:target="search, categoryId, type, city, district" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <svg class="animate-spin h-5 w-5 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Skeleton Loading State -->
                @if(!$readyToLoad)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @for($i = 0; $i < 6; $i++)
                            <div class="skeleton-card">
                                <div class="skeleton pt-[60%]"></div>
                                <div class="p-5 space-y-3">
                                    <div class="skeleton h-5 w-3/4"></div>
                                    <div class="skeleton h-3 w-full"></div>
                                    <div class="skeleton h-3 w-2/3"></div>
                                    <div class="flex justify-between items-center pt-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="skeleton w-8 h-8 rounded-full"></div>
                                            <div class="skeleton h-3 w-16"></div>
                                        </div>
                                        <div class="skeleton h-8 w-24 rounded-xl"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    <!-- Items Listing -->
                    <div class="relative">
                        <!-- Tiny Loading Indicator Overlay during queries -->
                        <div wire:loading class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-[1px] z-10 flex items-center justify-center rounded-3xl">
                            <svg class="animate-spin h-8 w-8 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                    @if($items->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 reveal">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Không tìm thấy món đồ nào</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Thử đổi từ khoá tìm kiếm khác hoặc xoá bộ lọc để xem thêm các bài đăng khác nhé.</p>
                            <button wire:click="resetFilters" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-xl bg-teal-500 text-white shadow-sm hover:bg-teal-600 transition-colors">
                                Xoá bộ lọc
                            </button>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach($items as $index => $item)
                                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-300 reveal"
                                     style="transition-delay: {{ $index * 50 }}ms">

                                    <!-- Card Image -->
                                    <div class="relative pt-[60%] overflow-hidden bg-gray-100 dark:bg-gray-900">
                                        @if(!empty($item->images) && isset($item->images[0]))
                                            <img src="{{ $item->images[0] }}" alt="{{ $item->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" loading="lazy">
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 text-gray-400">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif

                                        <!-- Badge Type -->
                                        <div class="absolute top-3 left-3">
                                            @if($item->type === 'give')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 backdrop-blur-sm text-white shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                                    Tặng đồ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-500/90 backdrop-blur-sm text-white shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                    Trao đổi
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white px-2 py-0.5 rounded-lg text-[9px] font-semibold uppercase tracking-wider">
                                            {{ $item->category->name }}
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-5 flex-1 flex flex-col">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base mb-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-1">
                                                {{ $item->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed">
                                                {{ $item->description }}
                                            </p>

                                            @if($item->type === 'exchange' && $item->exchange_wish)
                                                <div class="mb-4 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/30 p-2.5 rounded-xl">
                                                    <span class="text-[9px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider block mb-0.5">Nhu cầu đổi:</span>
                                                    <p class="text-xs text-orange-800 dark:text-orange-300 line-clamp-1 italic">
                                                        "{{ $item->exchange_wish }}"
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Location Info -->
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-4 border-t border-gray-50 dark:border-gray-700/50 pt-3">
                                            <svg class="w-4 h-4 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate">{{ $item->district?->name }}, {{ $item->city?->name }}</span>
                                        </div>

                                        <!-- User/Owner Section & Button -->
                                        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700/50 pt-3">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center font-bold text-white text-xs shadow-sm">
                                                    {{ substr($item->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 block max-w-[80px] truncate">{{ $item->user->name }}</span>
                                                    <span class="text-[9px] text-gray-400 flex items-center">
                                                        <svg class="w-3 h-3 mr-0.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.538-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                        {{ number_format($item->user->trust_score, 1) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="/items/{{ $item->id }}" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 shadow-sm hover:shadow-md transition-all duration-200" wire:navigate>
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-8">
                            {{ $items->links() }}
                        </div>
                    @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
