<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Hero Banner Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-600 text-white p-8 sm:p-12 shadow-xl">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10 max-w-2xl">
                <span class="bg-white/20 backdrop-blur-md text-[10px] sm:text-xs font-semibold tracking-widest uppercase px-3 py-1 rounded-full text-white mb-4 inline-block">
                    Cộng Đồng Chia Sẻ Đồ Cũ
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                    Give & Take - Cho Đi Là Nhận Lại
                </h1>
                <p class="text-sm sm:text-base text-emerald-100 mb-6">
                    Nền tảng giúp bạn trao tặng những món đồ không dùng tới hoặc trao đổi lấy những gì bạn đang cần. Hãy cùng nhau xây dựng lối sống xanh và sẻ chia!
                </p>
                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="/dashboard" class="bg-white text-teal-800 font-bold px-6 py-3 rounded-xl shadow-md hover:bg-emerald-50 transition duration-150 ease-in-out text-sm">
                            Đăng tin chia sẻ ngay
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-teal-800 font-bold px-6 py-3 rounded-xl shadow-md hover:bg-emerald-50 transition duration-150 ease-in-out text-sm">
                            Tham gia cộng đồng
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
 
    <!-- Main Content: Search, Filters & Listing -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 h-fit sticky top-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center uppercase tracking-wide">
                        <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Bộ lọc tìm kiếm
                    </h3>
                    <button wire:click="resetFilters" class="text-xs text-teal-600 dark:text-teal-400 hover:underline">
                        Xoá tất cả
                    </button>
                </div>
 
                <div class="space-y-6">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Danh mục</label>
                        <select wire:model.live="categoryId" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300">
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
                            <button wire:click="$set('type', '')" class="py-2 text-[10px] font-bold rounded-xl transition duration-150 {{ $type === '' ? 'bg-teal-500 text-white shadow-sm' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                Tất cả
                            </button>
                            <button wire:click="$set('type', 'give')" class="py-2 text-[10px] font-bold rounded-xl transition duration-150 {{ $type === 'give' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                Tặng
                            </button>
                            <button wire:click="$set('type', 'exchange')" class="py-2 text-[10px] font-bold rounded-xl transition duration-150 {{ $type === 'exchange' ? 'bg-orange-500 text-white shadow-sm' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                Đổi đồ
                            </button>
                        </div>
                    </div>
 
                    <!-- City Filter -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Tỉnh / Thành phố</label>
                        <select wire:model.live="city" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300">
                            <option value="">Tất cả Tỉnh/Thành</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <!-- District Filter -->
                    @if($city)
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Quận / Huyện</label>
                            <select wire:model.live="district" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300">
                                <option value="">Tất cả Quận/Huyện</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
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
                </div>
 
                <!-- Items Listing -->
                @if($items->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Không tìm thấy món đồ nào</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Thử đổi từ khoá tìm kiếm khác hoặc xoá bộ lọc để xem thêm các bài đăng khác nhé.</p>
                        <button wire:click="resetFilters" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-xl bg-teal-500 text-white shadow-sm hover:bg-teal-600">
                            Xoá bộ lọc
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($items as $item)
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300">
                                
                                <!-- Card Image -->
                                <div class="relative pt-[60%] overflow-hidden bg-gray-100 dark:bg-gray-900">
                                    @if(!empty($item->images) && isset($item->images[0]))
                                        <img src="{{ $item->images[0] }}" alt="{{ $item->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                                            Không có ảnh
                                        </div>
                                    @endif
 
                                    <!-- Badge Type -->
                                    <div class="absolute top-3 left-3">
                                        @if($item->type === 'give')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm">
                                                Tặng đồ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-500 text-white shadow-sm">
                                                Trao đổi
                                            </span>
                                        @endif
                                    </div>
 
                                    <!-- Category Badge -->
                                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white px-2 py-0.5 rounded text-[9px] font-semibold uppercase tracking-wider">
                                        {{ $item->category->name }}
                                    </div>
                                </div>
 
                                <!-- Card Body -->
                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base mb-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-1">
                                            {{ $item->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
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
                                        <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $item->district }}, {{ $item->city }}</span>
                                    </div>
 
                                    <!-- User/Owner Section & Button -->
                                    <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700/50 pt-3">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center font-bold text-teal-800 dark:text-teal-200 text-xs">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 block max-w-[80px] truncate">{{ $item->user->name }}</span>
                                                <span class="text-[9px] text-gray-400 flex items-center">
                                                    ★ {{ number_format($item->user->trust_score, 1) }} ({{ $item->user->karma_points }}đ)
                                                </span>
                                            </div>
                                        </div>
 
                                        <a href="/items/{{ $item->id }}" class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 shadow-sm transition-all" wire:navigate>
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
 
        </div>
    </div>
</div>
