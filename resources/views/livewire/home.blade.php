<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Hero Slider Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        @if($newsList->isEmpty())
            <!-- Fallback Banner if no news -->
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 text-white p-8 sm:p-12 shadow-xl min-h-[300px] flex items-center">
                <div class="relative z-10 max-w-2xl">
                    <span class="bg-white/20 backdrop-blur-md text-xs font-semibold tracking-widest uppercase px-3 py-1 rounded-full text-white mb-4 inline-block">
                        Cộng Đồng Chia Sẻ Đồ Cũ
                    </span>
                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                        Give & Take — Cho Đi Là Nhận Lại
                    </h1>
                    <p class="text-sm sm:text-base text-emerald-100 leading-relaxed mb-6">
                        Nền tảng giúp bạn trao tặng những món đồ không dùng tới hoặc trao đổi lấy những gì bạn đang cần. Hãy cùng nhau xây dựng lối sống xanh và sẻ chia!
                    </p>
                    <a href="/search" class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-teal-800 font-bold shadow-md hover:bg-emerald-50 transition" wire:navigate>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @else
            <!-- Alpine News Slider -->
            <div class="relative rounded-3xl overflow-hidden shadow-xl bg-gray-950 text-white aspect-[21/9] sm:min-h-[400px] group"
                 x-data="{ 
                    activeSlide: 0, 
                    slidesCount: {{ $newsList->count() }},
                    autoPlay() {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                        }, 5000);
                    }
                 }"
                 x-init="autoPlay()">
                
                <!-- Slides -->
                @foreach($newsList as $index => $news)
                    <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                         x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-1500"
                         x-transition:enter-start="opacity-0 scale-102"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        <!-- Slide Image -->
                        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $news->image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80' }}')"></div>
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent sm:bg-gradient-to-r sm:from-gray-950 sm:via-gray-950/60 sm:to-transparent"></div>

                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 sm:top-0 sm:bottom-auto sm:h-full p-8 sm:p-16 flex flex-col justify-center max-w-2xl z-10">
                            <span class="bg-teal-500 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full text-white mb-3 w-fit">
                                Tin Tức & Sự Kiện
                            </span>
                            <h2 class="text-xl sm:text-4xl font-extrabold tracking-tight mb-3 line-clamp-2 leading-tight">
                                {{ $news->title }}
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-300 mb-6 line-clamp-2 leading-relaxed hidden sm:block">
                                {{ $news->summary }}
                            </p>
                            <div class="flex items-center gap-4">
                                <a href="{{ route('news.detail', ['id' => $news->id]) }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-teal-500 text-white font-bold hover:bg-teal-600 transition shadow-sm text-xs sm:text-sm" wire:navigate>
                                    Đọc tiếp
                                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Prev/Next Controls -->
                <button class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20"
                        @click="activeSlide = activeSlide === 0 ? slidesCount - 1 : activeSlide - 1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-20"
                        @click="activeSlide = (activeSlide + 1) % slidesCount">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Indicator Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
                    <template x-for="i in slidesCount" :key="i">
                        <button class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                :class="activeSlide === (i - 1) ? 'bg-teal-500 w-6' : 'bg-white/40 hover:bg-white/80'"
                                @click="activeSlide = i - 1"></button>
                    </template>
                </div>
            </div>
        @endif
    </div>

    <!-- Stats & Trust Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 text-white p-6 sm:p-8 shadow-xl">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto"
                 x-data="{ shown: false }"
                 x-intersect.once="shown = true">
                <div class="text-center p-4 rounded-2xl bg-white/10 backdrop-blur-sm">
                    <div class="text-2xl sm:text-3xl font-extrabold text-white"
                         x-data="{ count: 0, target: {{ $totalItems ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-xs text-emerald-100 font-semibold uppercase tracking-wider mt-2">Món đồ đăng tải</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/10 backdrop-blur-sm">
                    <div class="text-2xl sm:text-3xl font-extrabold text-white"
                         x-data="{ count: 0, target: {{ $totalUsers ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-xs text-emerald-100 font-semibold uppercase tracking-wider mt-2">Thành viên tích cực</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/10 backdrop-blur-sm">
                    <div class="text-2xl sm:text-3xl font-extrabold text-white"
                         x-data="{ count: 0, target: {{ $totalCompleted ?? 0 }} }"
                         x-init="$watch('shown', v => { if(v) { let start = performance.now(); const step = (now) => { let p = Math.min((now-start)/1500, 1); count = Math.floor((1-Math.pow(1-p,3))*target); if(p<1) requestAnimationFrame(step); }; requestAnimationFrame(step); } })"
                         x-text="count">0</div>
                    <div class="text-xs text-emerald-100 font-semibold uppercase tracking-wider mt-2">Món đồ đã tìm thấy chủ mới</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Grid Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center max-w-xl mx-auto mb-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                Tìm kiếm theo danh mục
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                Lựa chọn nhóm đồ dùng bạn đang quan tâm để khám phá nhanh hơn.
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="/search?categoryId={{ $category->id }}" 
                   wire:navigate
                   class="group relative rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 dark:bg-gray-800 shadow-sm hover:shadow-md transition duration-300 block">
                    
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-500 ease-out" 
                         style="background-image: url('{{ $category->image ?? 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=400&q=80' }}')"></div>
                    
                    <!-- Color Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-black/20 group-hover:from-teal-950/80 group-hover:via-teal-950/40 transition duration-300"></div>

                    <!-- Category Content -->
                    <div class="absolute inset-0 p-4 flex flex-col justify-end items-center z-10 text-center">
                        <span class="text-sm font-bold text-white tracking-wide uppercase drop-shadow-sm group-hover:text-teal-300 transition duration-300">
                            {{ $category->name }}
                        </span>
                        <span class="text-[9px] text-gray-300 uppercase tracking-widest font-semibold mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Xem sản phẩm &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
