<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header Page Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                    Khám phá kho chia sẻ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tìm kiếm những món đồ hữu ích được tặng hoặc trao đổi từ mọi người xung quanh bạn.
                </p>
            </div>
            @auth
                <a href="{{ route('item.create') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-teal-500 text-white font-bold hover:bg-teal-600 transition shadow-sm text-sm" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Đăng tin mới
                </a>
            @endauth
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
                                @foreach($typesList as $t)
                                    <button wire:click="$set('type', '{{ $t->id }}')" class="py-2 text-[10px] font-bold rounded-xl transition-all duration-200 {{ (string)$type === (string)$t->id ? 'bg-emerald-500 text-white shadow-sm scale-[1.02]' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100' }}">
                                        {{ $t->name }}
                                    </button>
                                @endforeach
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
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($items->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-12 text-center shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Không tìm thấy sản phẩm nào</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Hãy thử thay đổi từ khoá tìm kiếm hoặc đặt lại các bộ lọc xem sao nhé.</p>
                        <button wire:click="resetFilters" class="inline-flex items-center px-4 py-2 rounded-xl bg-teal-50 text-teal-700 dark:bg-teal-950/30 dark:text-teal-400 font-semibold hover:bg-teal-100 transition text-xs">
                            Đặt lại bộ lọc
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($items as $index => $item)
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                                 style="transition-delay: {{ $index * 50 }}ms">

                                <!-- Card Image -->
                                <div class="relative pt-[60%] overflow-hidden bg-gray-100 dark:bg-gray-900">
                                    @if($item->thumbnail)
                                        <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" loading="lazy">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 text-gray-400">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif

                                    <!-- Badge Type -->
                                    <div class="absolute top-3 left-3">
                                        @if($item->type_id == 1)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 backdrop-blur-sm text-white shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                                {{ $item->type->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-500/90 backdrop-blur-sm text-white shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                {{ $item->type->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">{{ $item->category->name }}</span>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 flex items-center font-semibold">
                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $item->district->name }}, {{ $item->city->name }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-teal-600 transition-colors line-clamp-1 mb-2">
                                            <a href="{{ route('item.detail', ['id' => $item->id]) }}" wire:navigate>{{ $item->title }}</a>
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed">
                                            {{ $item->description }}
                                        </p>
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="pt-4 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold text-xs shadow-inner">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-2">
                                                <p class="text-[10px] font-bold text-gray-800 dark:text-gray-200 line-clamp-1 leading-none">{{ $item->user->name }}</p>
                                                <span class="text-[8px] text-gray-400 font-semibold tracking-wider uppercase">Karma: {{ $item->user->karma_points }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('item.detail', ['id' => $item->id]) }}" wire:navigate class="inline-flex items-center text-xs font-bold text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300">
                                            Chi tiết
                                            <svg class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-4">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
