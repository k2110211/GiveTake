<div>
    {{-- Flash success message --}}
    @if(session()->has('review_success'))
        <div class="mb-4 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-800 dark:text-emerald-300 flex items-start shadow-sm">
            <svg class="w-5 h-5 mr-3 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-bold">Đã ghi nhận đánh giá!</p>
                <p class="text-xs mt-0.5">{{ session('review_success') }}</p>
            </div>
        </div>
    @endif
 
    {{-- Review CTA --}}
    @if($hasReviewed)
        <div class="flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30">
            <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="text-xs font-semibold text-amber-700 dark:text-amber-400">Bạn đã đánh giá giao dịch này rồi. Cảm ơn!</span>
        </div>
    @else
        <button wire:click="openModal"
                class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-2xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-white font-bold text-sm shadow-sm transition-all">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span>Đánh giá {{ $reviewee->name }} (+10 Karma)</span>
        </button>
    @endif
 
    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="$set('showModal', false)"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
 
            {{-- Modal Card --}}
            <div class="relative bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 z-10">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Đánh giá giao dịch
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Đánh giá trải nghiệm với <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $reviewee->name }}</span>
                        </p>
                    </div>
                    <button wire:click="$set('showModal', false)"
                            class="p-1.5 rounded-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
 
                {{-- Star Rating Selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                        Mức độ hài lòng <span class="text-rose-500">*</span>
                    </label>
 
                    <div class="flex items-center justify-center space-x-2" x-data="{ hover: 0 }">
                        @for($star = 1; $star <= 5; $star++)
                            <button type="button"
                                    wire:click="setRating({{ $star }})"
                                    x-on:mouseenter="hover = {{ $star }}"
                                    x-on:mouseleave="hover = 0"
                                    class="focus:outline-none transition-transform hover:scale-125">
                                <svg class="w-9 h-9 transition-colors"
                                     :class="(hover >= {{ $star }} || (hover === 0 && {{ $rating }} >= {{ $star }})) ? 'text-amber-400 fill-current' : 'text-gray-200 dark:text-gray-700 fill-current'"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
 
                    <p class="text-center text-xs font-semibold mt-2 text-amber-600 dark:text-amber-400">
                        @if($rating === 1) 😞 Rất không hài lòng
                        @elseif($rating === 2) 😐 Không hài lòng
                        @elseif($rating === 3) 🙂 Bình thường
                        @elseif($rating === 4) 😊 Hài lòng
                        @elseif($rating === 5) 🤩 Rất hài lòng!
                        @else Chọn số sao để đánh giá
                        @endif
                    </p>
 
                    @error('rating')
                        <p class="text-xs text-rose-600 mt-1 text-center">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Comment --}}
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        Nhận xét (tuỳ chọn)
                    </label>
                    <textarea wire:model="comment"
                              rows="3"
                              placeholder="Chia sẻ trải nghiệm của bạn: người tặng có thân thiện không? Món đồ có đúng mô tả không?..."
                              class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-amber-400 focus:ring focus:ring-amber-200 dark:text-gray-300 placeholder-gray-400">
                    </textarea>
                    @error('comment')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Karma info --}}
                <div class="mb-6 p-3 bg-teal-50 dark:bg-teal-950/20 border border-teal-100 dark:border-teal-900/30 rounded-2xl flex items-center space-x-3">
                    <div class="w-8 h-8 bg-teal-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">+10</span>
                    </div>
                    <p class="text-xs text-teal-700 dark:text-teal-400">
                        Bạn sẽ nhận <strong>+10 điểm Karma</strong> khi hoàn tất đánh giá này. Karma giúp bạn được ưu tiên hơn khi xin đồ!
                    </p>
                </div>
 
                {{-- Submit Button --}}
                <div class="flex justify-end gap-3">
                    <button type="button"
                            wire:click="$set('showModal', false)"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                        Để sau
                    </button>
                    <button type="button"
                            wire:click="submitReview"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition shadow-sm flex items-center">
                        <span wire:loading wire:target="submitReview"
                              class="inline-block animate-spin w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full"></span>
                        Gửi đánh giá
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
