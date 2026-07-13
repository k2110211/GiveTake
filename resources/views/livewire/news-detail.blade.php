<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Navigation -->
        <nav class="flex mb-8 text-sm font-medium text-gray-500 dark:text-gray-400 justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors" wire:navigate>Trang chủ</a>
                <span class="mx-2">/</span>
                <span class="text-gray-400">Tin tức & Sự kiện</span>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-gray-100 truncate max-w-[200px]">{{ $news->title }}</span>
            </div>
            <a href="/" class="text-xs text-teal-600 dark:text-teal-400 hover:underline flex items-center font-bold" wire:navigate>
                &larr; Quay lại trang chủ
            </a>
        </nav>

        <!-- Main News Card -->
        <article class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
            <!-- Large Cover Image -->
            <div class="relative w-full aspect-[21/9] bg-gray-100 dark:bg-gray-900 overflow-hidden">
                <img src="{{ $news->image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80' }}" 
                     alt="{{ $news->title }}" 
                     class="w-full h-full object-cover">
                <!-- Overlay details -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6 sm:p-8">
                    <span class="bg-teal-500 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full text-white shadow-sm">
                        Tin Tức & Sự Kiện
                    </span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 sm:p-10">
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-6 mb-8 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold text-xs shadow-inner">
                            {{ substr($news->user->name, 0, 1) }}
                        </div>
                        <div class="ml-2.5">
                            <p class="font-bold text-gray-900 dark:text-gray-200">{{ $news->user->name }}</p>
                            <p class="text-[10px] text-gray-400">Người viết / Ban Quản Trị</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span>Đăng lúc: {{ $news->created_at->format('H:i d/m/Y') }}</span>
                        @if($news->updated_at != $news->created_at)
                            <span class="text-gray-300 dark:text-gray-700">•</span>
                            <span>Cập nhật: {{ $news->updated_at->format('H:i d/m/Y') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Main Title -->
                <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-6 leading-tight">
                    {{ $news->title }}
                </h1>

                <!-- Summary Callout -->
                <div class="p-5 rounded-2xl bg-teal-50/50 dark:bg-teal-950/10 border-l-4 border-teal-500 text-sm font-medium text-gray-700 dark:text-gray-300 mb-8 leading-relaxed italic">
                    "{{ $news->summary }}"
                </div>

                <!-- Rich Text Content body -->
                <div class="prose prose-teal max-w-none dark:prose-invert text-sm sm:text-base text-gray-700 dark:text-gray-300 space-y-6 leading-relaxed">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>
        </article>
    </div>
</div>
