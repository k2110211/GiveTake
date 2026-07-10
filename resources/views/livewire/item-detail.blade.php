<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ activeImage: @js($item->thumbnail), showLightbox: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm font-medium text-gray-500 dark:text-gray-400">
            <a href="/" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors" wire:navigate>Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-gray-400">{{ $item->category->name }}</span>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-gray-100 truncate max-w-[200px]">{{ $item->title }}</span>
        </nav>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-800 dark:text-emerald-300 flex items-start shadow-sm"
                 x-init="window.showToast('{{ session('success') }}', 'success')">
                <svg class="w-5 h-5 mr-3 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-bold text-sm">Thành công!</h4>
                    <p class="text-xs mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-8 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-800 dark:text-rose-300 flex items-start shadow-sm"
                 x-init="window.showToast('{{ session('error') }}', 'error')">
                <svg class="w-5 h-5 mr-3 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="font-bold text-sm">Lỗi!</h4>
                    <p class="text-xs mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Product Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden reveal">
            
            <!-- Left Column: Gallery / Images -->
            <div class="lg:col-span-7 p-6 sm:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-700 flex flex-col">
                <div class="relative w-full rounded-2xl overflow-hidden bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center">
                    <div class="aspect-[4/3] w-full relative group cursor-zoom-in" @click="if(activeImage) showLightbox = true">
                        <template x-if="activeImage">
                            <img :src="activeImage" alt="{{ $item->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
                        </template>
                        <template x-if="!activeImage">
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                Không có hình ảnh sản phẩm
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="px-4 py-2 bg-black/60 backdrop-blur-sm text-white rounded-xl text-xs font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Click để phóng to
                            </span>
                        </div>
                    </div>
                </div>

                @php
                    $gallery = array_filter(array_merge([$item->thumbnail], $item->images ?? []));
                @endphp
                @if(count($gallery) > 1)
                    <div class="grid grid-cols-4 gap-4 mt-6">
                        @foreach($gallery as $img)
                            <div class="relative aspect-[4/3] rounded-xl overflow-hidden border transition-all duration-200"
                                 :class="activeImage === '{{ $img }}' ? 'border-teal-500 ring-2 ring-teal-200 dark:ring-teal-900/50' : 'border-gray-100 dark:border-gray-700 hover:border-gray-300 cursor-pointer'"
                                 @click="activeImage = '{{ $img }}'">
                                <img src="{{ $img }}" alt="Thumbnail" class="absolute inset-0 w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-3 flex items-center">
                        <span class="w-1.5 h-4 bg-teal-500 rounded-full mr-2"></span>
                        Mô tả sản phẩm
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line leading-relaxed">
                        {{ $item->description }}
                    </p>
                </div>
            </div>

            <!-- Right Column: Details & Owner -->
            <div class="lg:col-span-5 p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <!-- Category & Status Badges -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">
                            {{ $item->category->name }}
                        </span>
                        
                        <div>
                            @if($item->status === 'available')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                    Còn sẵn
                                </span>
                            @elseif($item->status === 'reserved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500 text-white shadow-sm">
                                    Đã hẹn tặng
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-500 text-white shadow-sm">
                                    Đã hoàn thành
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 tracking-tight leading-tight">
                        {{ $item->title }}
                    </h1>

                    <!-- Details Card -->
                    <div class="grid grid-cols-2 gap-4 mb-6 border-b border-gray-100 dark:border-gray-700/50 pb-6">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block mb-1">Hình thức</span>
                            @if($item->type === 'give')
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex items-center">
                                    Tặng miễn phí
                                </span>
                            @else
                                <span class="text-sm font-bold text-orange-600 dark:text-orange-400 flex items-center">
                                    Trao đổi đồ
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block mb-1">Khu vực</span>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 block truncate">
                                {{ $item->district?->name }}, {{ $item->city?->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Exchange Wish List -->
                    @if($item->type === 'exchange' && $item->exchange_wish)
                        <div class="mb-6 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/30 p-4 rounded-2xl">
                            <h4 class="text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                Mong muốn nhận lại:
                            </h4>
                            <p class="text-sm text-orange-900 dark:text-orange-300 italic font-medium">
                                "{{ $item->exchange_wish }}"
                            </p>
                        </div>
                    @endif

                    <!-- Giver Card -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 mb-8">
                        <h4 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Thông tin người đăng</h4>
                        <div class="flex items-center space-x-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center font-bold text-white text-lg shadow-md border border-white dark:border-gray-800">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <!-- Details -->
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $item->user->name }}</h5>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="text-xs font-semibold text-yellow-500 dark:text-yellow-400 flex items-center">
                                        <svg class="w-4 h-4 mr-0.5 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($item->user->trust_score, 1) }} Uy tín
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">|</span>
                                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400">
                                        {{ $item->user->karma_points }}đ Karma
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Bài viết đăng từ ngày {{ $item->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="mt-6">
                    @if($item->status !== 'available')
                        <button disabled class="w-full py-4 rounded-2xl text-sm font-bold text-gray-400 bg-gray-100 dark:bg-gray-700/50 dark:text-gray-500 cursor-not-allowed text-center">
                            Món đồ này đã được trao đổi xong
                        </button>
                    @elseif(auth()->check() && $item->user_id === auth()->id())
                        <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 p-4 rounded-2xl text-center text-sm font-medium text-blue-800 dark:text-blue-300">
                            Đây là món đồ bạn đăng tải. Bạn có thể quản lý tin đăng ở trang cá nhân.
                        </div>
                    @elseif($hasRequested)
                        <button disabled class="w-full py-4 rounded-2xl text-sm font-bold bg-teal-50 dark:bg-teal-950/20 border border-teal-200 dark:border-teal-900/30 text-teal-700 dark:text-teal-400 cursor-not-allowed text-center flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Đã gửi yêu cầu (Đang chờ duyệt)
                        </button>
                    @else
                        <button wire:click="openRequestModal" class="w-full py-4 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all text-center">
                            @if($item->type === 'give')
                                Nhận miễn phí món đồ này
                            @else
                                Gửi đề xuất trao đổi đồ
                            @endif
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Request Modal Overlay -->
    @if($showRequestModal)
        <div class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4 sm:p-6" x-data x-init="document.body.classList.add('overflow-hidden')" x-effect="if(!$wire.showRequestModal) document.body.classList.remove('overflow-hidden')">
            <!-- Backdrop -->
            <div wire:click="$set('showRequestModal', false)" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Box -->
            <div class="relative bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 z-10 transform transition-all">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Gửi lời nhắn đến người tặng
                    </h3>
                    <button wire:click="$set('showRequestModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitRequest">
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            @if($item->type === 'give')
                                Lời nhắn xin đồ
                            @else
                                Đề xuất trao đổi
                            @endif
                        </label>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-3 leading-normal">
                            @if($item->type === 'give')
                                Hãy giới thiệu ngắn gọn về bản thân và lý do bạn cần món đồ này để người tặng dễ dàng duyệt yêu cầu của bạn nhé.
                            @else
                                Hãy mô tả chi tiết món đồ bạn muốn trao đổi lại (tình trạng, hình thức) và cách bạn muốn thực hiện cuộc giao lưu này.
                            @endif
                        </p>
                        <textarea wire:model="message" rows="4" placeholder="Ví dụ: Chào anh/chị, em hiện là sinh viên nghèo mới nhập học, em đang rất cần bộ nồi chảo này để tự nấu ăn tiết kiệm chi phí..." class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 placeholder-gray-400"></textarea>
                        @error('message') 
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700/50 pt-5">
                        <button type="button" wire:click="$set('showRequestModal', false)" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-500 hover:bg-teal-600 transition-colors shadow-sm">
                            Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Image Lightbox Overlay -->
    <div x-show="showLightbox" class="lightbox-overlay" @click.self="showLightbox = false" x-cloak>
        <button class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors" @click="showLightbox = false">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="activeImage" alt="Zoomed view" class="lightbox-image" />
    </div>
</div>
