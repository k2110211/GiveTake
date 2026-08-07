<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900"
     wire:poll.3000ms="refresh"
     x-data="{
        scrollToBottom() {
            this.$refs.stream.scrollTop = this.$refs.stream.scrollHeight;
        }
     }"
     x-init="
        scrollToBottom();
        $watch('$store.newMessageCount', () => $nextTick(() => scrollToBottom()));
     "
     x-on:message-sent.window="$nextTick(() => scrollToBottom())"
     x-on:message-received.window="$nextTick(() => scrollToBottom())">
 
    <!-- Chat Header -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 shadow-sm px-4 sm:px-6 py-4 flex-shrink-0 sticky top-0 z-10">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <!-- Back + Context -->
            <div class="flex items-center space-x-4 min-w-0">
                <a href="{{ route('dashboard') }}" wire:navigate class="p-2 rounded-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
 
                <!-- Item thumbnail -->
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0 relative border border-gray-100 dark:border-gray-800">
                    @if(!empty($room->itemRequest->item->images) && isset($room->itemRequest->item->images[0]))
                        <img src="{{ $room->itemRequest->item->images[0] }}" alt="Item" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
 
                <!-- Names -->
                <div class="min-w-0">
                    <h1 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">
                        {{ $room->itemRequest->item->title }}
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                        <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span>Trò chuyện với <span class="font-bold text-teal-600 dark:text-teal-400">{{ $otherParticipant->name }}</span></span>
                    </p>
                </div>
            </div>
 
            <!-- Item detail link -->
            <a href="{{ route('item.detail', ['id' => $room->itemRequest->item->id]) }}" wire:navigate class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-xl text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/40 hover:bg-teal-100 dark:hover:bg-teal-950/70 border border-teal-200/50 dark:border-teal-900/30 transition-all">
                Xem món đồ
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
 
    <!-- Message Stream -->
    <div class="flex-1 overflow-y-auto scrollbar-thin" x-ref="stream">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 flex flex-col space-y-4">
 
            <!-- Context Banner: Original Request Message -->
            <div class="bg-white dark:bg-gray-800 border border-teal-100 dark:border-teal-900/30 rounded-2xl p-4 shadow-sm">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-400 to-emerald-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                        {{ substr($room->itemRequest->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xs font-bold text-teal-700 dark:text-teal-400">Lời nhắn</span>
                            <span class="text-[10px] text-gray-400">Ngày {{ $room->itemRequest->created_at->format('d-m-Y') }}</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 italic leading-relaxed">
                            "{{ $room->itemRequest->message }}"
                        </p>
                    </div>
                </div>
            </div>
 
            <!-- Messages -->
            @forelse($room->messages->sortBy('created_at') as $msg)
                @php $isMine = $msg->user_id === auth()->id(); @endphp
 
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end space-x-2 max-w-[85%] {{ $isMine ? 'flex-row-reverse space-x-reverse' : '' }}">
                        <!-- Avatar -->
                        <div class="w-8 h-8 rounded-xl {{ $isMine ? 'bg-gradient-to-br from-teal-400 to-emerald-600' : 'bg-gray-200 dark:bg-gray-700' }} flex items-center justify-center text-xs font-bold {{ $isMine ? 'text-white' : 'text-gray-700 dark:text-gray-300' }} flex-shrink-0 mb-1 shadow-sm">
                            {{ substr($msg->user->name, 0, 1) }}
                        </div>
 
                        <!-- Bubble -->
                        <div>
                            <div class="px-4 py-2.5 rounded-2xl {{ $isMine
                                ? 'bg-gradient-to-br from-teal-500 to-emerald-600 text-white rounded-br-none shadow-sm'
                                : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200/50 dark:border-gray-700/50 rounded-bl-none shadow-sm' }}">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">{{ $msg->message }}</p>
                            </div>
                            <div class="flex items-center space-x-1 mt-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <span class="text-[9px] text-gray-400 dark:text-gray-500">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                                @if($isMine)
                                    <span class="text-[9px] {{ $msg->is_read ? 'text-teal-400' : 'text-gray-400' }} font-bold">
                                        {{ $msg->is_read ? '✓✓ Đã xem' : '✓ Đã gửi' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">Hãy bắt đầu cuộc trò chuyện!</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Gửi tin nhắn đầu tiên để thảo luận cách giao nhận món đồ.</p>
                </div>
            @endforelse
 
        </div>
    </div>
 
    <!-- Message Input Footer -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-t border-gray-200/50 dark:border-gray-700/50 shadow-sm px-4 sm:px-6 py-4 flex-shrink-0">
        <div class="max-w-3xl mx-auto">
            <form wire:submit.prevent="sendMessage" class="flex items-end space-x-3">
                <div class="flex-1">
                    <textarea
                        wire:model="newMessage"
                        rows="1"
                        placeholder="Nhập tin nhắn..."
                        x-data
                        x-on:keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
                        class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring focus:ring-teal-200 dark:focus:ring-teal-900/30 resize-none py-3.5 px-4 leading-relaxed transition-all"
                        style="max-height: 120px;"
                        x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'">
                    </textarea>
                    @error('newMessage') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
 
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-70 cursor-wait"
                    class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white flex items-center justify-center transition-all shadow-md hover:shadow-lg">
                    <span wire:loading wire:target="sendMessage">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="sendMessage">
                        <svg class="w-5 h-5 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </span>
                </button>
            </form>
 
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2.5 text-center">
                Nhấn <kbd class="px-1.5 py-0.5 rounded text-[10px] font-mono bg-gray-100 dark:bg-gray-800">Enter</kbd> để gửi,
                <kbd class="px-1.5 py-0.5 rounded text-[10px] font-mono bg-gray-100 dark:bg-gray-800">Shift+Enter</kbd> để xuống dòng
            </p>
        </div>
    </div>
 
    {{-- Review Section --}}
    @if($room->itemRequest->request_status_id == 2 || $room->itemRequest->item->item_status_id == 4)
    <div class="bg-amber-50/50 dark:bg-amber-950/10 border-t border-dashed border-amber-200/50 dark:border-amber-900/20 px-4 sm:px-6 py-4 flex-shrink-0">
        <div class="max-w-3xl mx-auto">
            <p class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider mb-3">
                Đánh giá giao dịch
            </p>
            @livewire('submit-review', ['itemRequestId' => $room->itemRequest->id], key('review-' . $room->itemRequest->id))
        </div>
    </div>
    @endif
 
</div>
