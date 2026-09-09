<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900"
     wire:poll.3000ms="refresh"
     x-data="{
        scrollToBottom() {
            if (this.$refs.stream) {
                this.$refs.stream.scrollTop = this.$refs.stream.scrollHeight;
            }
        },
        confirmModalOpen: false,
        confirmType: '',
        confirmTitle: '',
        confirmDesc: '',
        confirmBtnText: '',
        confirmBtnClass: '',
        openConfirmModal(type, title, desc, btnText, btnClass) {
            this.confirmType = type;
            this.confirmTitle = title;
            this.confirmDesc = desc;
            this.confirmBtnText = btnText;
            this.confirmBtnClass = btnClass;
            this.confirmModalOpen = true;
        },
        executeConfirmedAction() {
            const action = this.confirmType;
            this.confirmModalOpen = false;
            if (action === 'confirmReceived') {
                $wire.confirmReceived();
            } else if (action === 'cancelTransaction') {
                $wire.cancelTransaction();
            } else if (action === 'rejectRequest') {
                $wire.rejectRequest();
            }
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

    <!-- Action / Status Banner -->
    @php
        $isOwner = auth()->id() === $room->itemRequest->item->user_id;
        $reqStatus = (int) $room->itemRequest->request_status_id;
        $itemStatus = (int) $room->itemRequest->item->item_status_id;
    @endphp

    <div class="border-b border-gray-200/60 dark:border-gray-700/60 bg-white/95 dark:bg-gray-800/95 backdrop-blur px-4 sm:px-6 py-3 flex-shrink-0 z-10 transition-all">
        <div class="max-w-3xl mx-auto">
            <!-- Flash notifications -->
            @if (session()->has('success'))
                <div class="mb-2 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-2 p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($isOwner)
                @if($reqStatus === 1 && $itemStatus <= 2)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gradient-to-r from-teal-50/80 to-emerald-50/80 dark:from-teal-950/30 dark:to-emerald-950/30 p-3.5 rounded-2xl border border-teal-200/60 dark:border-teal-900/40 shadow-xs">
                        <div class="flex items-start space-x-2.5 min-w-0">
                            <span class="text-xl">🤝</span>
                            <div>
                                <h4 class="text-xs font-bold text-teal-900 dark:text-teal-200">
                                    {{ $room->itemRequest->item->type_id == 2 ? 'Đề xuất trao đổi món đồ' : 'Yêu cầu xin món đồ' }}
                                </h4>
                                <p class="text-[11px] text-teal-700/80 dark:text-teal-300/80 mt-0.5">
                                    Sau khi trò chuyện và thấy phù hợp, hãy bấm chấp nhận để chuyển trạng thái món đồ sang Đang trao đổi.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                            <button 
                                wire:click="approveRequest" 
                                wire:loading.attr="disabled"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 transition shadow-sm hover:shadow flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $room->itemRequest->item->type_id == 2 ? 'Chấp nhận trao đổi' : 'Chấp nhận cho đồ' }}</span>
                            </button>
                            <button 
                                type="button"
                                @click="openConfirmModal('rejectRequest', 'Từ chối yêu cầu?', 'Bạn có chắc chắn muốn từ chối yêu cầu này từ {{ $otherParticipant->name }}?', 'Xác nhận từ chối', 'bg-rose-600 hover:bg-rose-700 text-white')"
                                wire:loading.attr="disabled"
                                class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition cursor-pointer">
                                Từ chối
                            </button>
                        </div>
                    </div>
                @elseif($reqStatus === 2)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-emerald-50 dark:bg-emerald-950/30 p-3.5 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/40 text-xs">
                        <div class="flex items-start space-x-2.5 min-w-0">
                            <span class="text-xl">🎉</span>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-900 dark:text-emerald-200">
                                    Đang giao dịch với {{ $otherParticipant->name }}
                                </h4>
                                <p class="text-[11px] text-emerald-700/80 dark:text-emerald-300/80 mt-0.5">
                                    Thống nhất cách nhận (gặp trực tiếp hoặc gửi shipper). Sau khi bàn giao đồ xong, hãy bấm xác nhận để hoàn tất và nhận đánh giá.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                            <button 
                                type="button"
                                @click="openConfirmModal('confirmReceived', 'Xác nhận đã bàn giao món đồ?', 'Bạn xác nhận đã bàn giao món đồ này thành công cho {{ $otherParticipant->name }} (trực tiếp hoặc qua đơn vị vận chuyển/shipper)? Món đồ sẽ chuyển sang hoàn tất giao dịch và bạn sẽ nhận +15 điểm Karma!', 'Đã bàn giao thành công', 'bg-emerald-600 hover:bg-emerald-700 text-white')"
                                wire:loading.attr="disabled"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Đã bàn giao</span>
                            </button>
                            <button 
                                type="button"
                                @click="openConfirmModal('cancelTransaction', 'Hủy giao dịch?', 'Bạn có chắc chắn muốn hủy giao kèo này? Món đồ sẽ quay về trạng thái Có sẵn để bạn chọn người nhận khác.', 'Đồng ý hủy giao dịch', 'bg-rose-600 hover:bg-rose-700 text-white')"
                                wire:loading.attr="disabled"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition cursor-pointer">
                                Hủy giao dịch
                            </button>
                        </div>
                    </div>
                @elseif($reqStatus === 5 || $itemStatus === 4)
                    <div class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-950/40 p-3.5 rounded-2xl border border-emerald-200/80 dark:border-emerald-800/60 text-xs">
                        <div class="flex items-center space-x-2 text-emerald-800 dark:text-emerald-300 font-semibold">
                            <span class="text-xl">🌟</span>
                            <span>Giao dịch hoàn tất thành công! Món đồ đã được trao tặng và tích lũy điểm Karma tốt lành.</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 flex-shrink-0">
                            Đã hoàn thành
                        </span>
                    </div>
                @elseif($reqStatus === 4)
                    <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-800 dark:text-amber-300 flex items-center space-x-2">
                        <span>⚠️</span>
                        <span>Giao dịch này đã được hủy bỏ. Món đồ đã mở lại trạng thái Có sẵn.</span>
                    </div>
                @elseif($reqStatus === 1 && $itemStatus === 3)
                    <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-900/40 text-xs text-amber-800 dark:text-amber-300 flex items-center space-x-2">
                        <span>⚠️</span>
                        <span>Món đồ này hiện đã được bạn chấp nhận trao đổi cho một người khác.</span>
                    </div>
                @elseif($reqStatus === 3)
                    <div class="p-3 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                        <span>ℹ️</span>
                        <span>Yêu cầu này đã bị từ chối.</span>
                    </div>
                @endif
            @else
                {{-- Requester Perspective --}}
                @if($reqStatus === 1)
                    <div class="flex items-center justify-between bg-teal-50/70 dark:bg-teal-950/30 p-3 rounded-2xl border border-teal-100 dark:border-teal-900/40 text-xs">
                        <div class="flex items-center space-x-2 text-teal-800 dark:text-teal-300">
                            <span>⏳</span>
                            <span>Yêu cầu của bạn đang chờ người tặng duyệt. Hãy trò chuyện lịch sự để thống nhất cách nhận đồ (lấy trực tiếp hoặc thuê bên vận chuyển/shipper) nhé!</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/50 dark:text-yellow-400">
                            Chờ duyệt
                        </span>
                    </div>
                @elseif($reqStatus === 2)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-emerald-50 dark:bg-emerald-950/30 p-3.5 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/40 text-xs">
                        <div class="flex items-start space-x-2.5 min-w-0">
                            <span class="text-xl">🎉</span>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-900 dark:text-emerald-200">
                                    Người tặng đã chấp nhận yêu cầu của bạn!
                                </h4>
                                <p class="text-[11px] text-emerald-700/80 dark:text-emerald-300/80 mt-0.5">
                                    Hãy thống nhất địa điểm hẹn lấy hoặc thời gian gọi shipper. Khi nhận được đồ, hãy bấm nút xác nhận bên cạnh.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                            <button 
                                type="button"
                                @click="openConfirmModal('confirmReceived', 'Xác nhận đã nhận được đồ?', 'Bạn xác nhận đã nhận được món đồ này nguyên vẹn từ {{ $otherParticipant->name }} (trực tiếp hoặc qua đơn vị vận chuyển/shipper)? Giao dịch sẽ hoàn tất và bạn có thể gửi đánh giá cho đối phương.', 'Đã nhận được đồ', 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white')"
                                wire:loading.attr="disabled"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Đã nhận được đồ</span>
                            </button>
                            <button 
                                type="button"
                                @click="openConfirmModal('cancelTransaction', 'Hủy nhận món đồ này?', 'Bạn có chắc chắn muốn hủy nhận món đồ này? Người tặng sẽ có thể chọn người nhận khác.', 'Đồng ý hủy nhận', 'bg-rose-600 hover:bg-rose-700 text-white')"
                                wire:loading.attr="disabled"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition cursor-pointer">
                                Hủy nhận
                            </button>
                        </div>
                    </div>
                @elseif($reqStatus === 5 || $itemStatus === 4)
                    <div class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-950/40 p-3.5 rounded-2xl border border-emerald-200/80 dark:border-emerald-800/60 text-xs">
                        <div class="flex items-center space-x-2 text-emerald-800 dark:text-emerald-300 font-semibold">
                            <span class="text-xl">🌟</span>
                            <span>Bạn đã nhận món đồ thành công! Hãy gửi lời cảm ơn và đánh giá người tặng bên dưới nhé.</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 flex-shrink-0">
                            Đã nhận đồ
                        </span>
                    </div>
                @elseif($reqStatus === 4)
                    <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 text-xs text-amber-800 dark:text-amber-300 flex items-center space-x-2">
                        <span>⚠️</span>
                        <span>Giao dịch này đã được hủy bỏ.</span>
                    </div>
                @elseif($reqStatus === 3)
                    <div class="p-3 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                        <span>ℹ️</span>
                        <span>Rất tiếc, yêu cầu này đã bị từ chối hoặc món đồ đã được trao cho người khác.</span>
                    </div>
                @endif
            @endif
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
    <div 
        x-data="{
            message: @entangle('newMessage').live,
            showEmojis: false,
            quickReplies: [
                '💬 Món đồ này còn không bạn?',
                '📍 Bạn tiện hẹn ở đâu và lúc mấy giờ?',
                '🛵 Mình xin phép đặt shipper qua lấy đồ được không bạn?',
                '📦 Mình có thể qua lấy đồ hôm nay không?',
                '🙏 Cảm ơn bạn rất nhiều!',
                '👍 Nhất trí, hẹn gặp bạn nhé!'
            ],
            quickEmojis: ['👋', '🙏', '📦', '🤝', '👍', '❤️', '😊', '🎉', '💐', '✨'],
            fillReply(text) {
                this.message = text;
                $wire.set('newMessage', text);
                this.$nextTick(() => {
                    this.adjustHeight();
                    this.$refs.chatInput.focus();
                });
            },
            insertEmoji(emoji) {
                this.message = (this.message || '') + emoji;
                $wire.set('newMessage', this.message);
                this.$nextTick(() => {
                    this.adjustHeight();
                    this.$refs.chatInput.focus();
                });
            },
            adjustHeight() {
                const el = this.$refs.chatInput;
                if (!el) return;
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 120) + 'px';
            },
            resetHeight() {
                this.$nextTick(() => {
                    const el = this.$refs.chatInput;
                    if (el) {
                        el.style.height = 'auto';
                    }
                });
            }
        }"
        @message-sent.window="resetHeight()"
        class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-t border-gray-200/60 dark:border-gray-700/60 shadow-lg px-3 sm:px-6 py-3 flex-shrink-0 z-10 transition-all">
        
        <div class="max-w-3xl mx-auto space-y-2.5">
            <!-- Quick Replies & Emoji Bar -->
            <div 
                x-data="{
                    canScrollLeft: false,
                    canScrollRight: true,
                    isExpanded: false,
                    checkScroll() {
                        const el = this.$refs.chipsContainer;
                        if (!el) return;
                        this.canScrollLeft = el.scrollLeft > 5;
                        this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 5);
                    },
                    scroll(direction) {
                        const el = this.$refs.chipsContainer;
                        if (!el) return;
                        el.scrollBy({ left: direction * 220, behavior: 'smooth' });
                        setTimeout(() => this.checkScroll(), 250);
                    },
                    handleWheel(e) {
                        const el = this.$refs.chipsContainer;
                        if (!el || this.isExpanded) return;
                        if (e.deltaY !== 0) {
                            e.preventDefault();
                            el.scrollLeft += e.deltaY;
                            this.checkScroll();
                        }
                    }
                }"
                x-init="$nextTick(() => checkScroll())"
                @resize.window="checkScroll()"
                class="relative">

                <div class="flex items-center gap-1.5">
                    <!-- Left Scroll Arrow (Desktop) -->
                    <button
                        x-show="!isExpanded && canScrollLeft"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button"
                        @click="scroll(-1)"
                        class="flex flex-shrink-0 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow-sm border border-gray-200 dark:border-gray-600 text-gray-500 hover:text-teal-600 items-center justify-center text-xs cursor-pointer transition-all hover:scale-110"
                        title="Cuộn sang trái">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <!-- Chips List (Scrollable or Wrapped when expanded) -->
                    <div
                        x-ref="chipsContainer"
                        @scroll.passive="checkScroll()"
                        @wheel="handleWheel($event)"
                        :class="isExpanded ? 'flex flex-wrap gap-1.5 py-1' : 'flex-1 min-w-0 flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1 scroll-smooth'"
                        class="text-xs">
                        <template x-for="(reply, idx) in quickReplies" :key="idx">
                            <button
                                type="button"
                                @click="fillReply(reply)"
                                class="whitespace-nowrap px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700/70 hover:bg-teal-50 dark:hover:bg-teal-950/40 text-gray-600 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 border border-gray-200/80 dark:border-gray-600/50 hover:border-teal-300 dark:hover:border-teal-700 transition text-[11px] font-medium shadow-2xs flex-shrink-0 cursor-pointer active:scale-95">
                                <span x-text="reply"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Right Scroll Arrow (Desktop) -->
                    <button
                        x-show="!isExpanded && canScrollRight"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button"
                        @click="scroll(1)"
                        class="flex flex-shrink-0 w-6 h-6 rounded-full bg-white dark:bg-gray-700 shadow-sm border border-gray-200 dark:border-gray-600 text-gray-500 hover:text-teal-600 items-center justify-center text-xs cursor-pointer transition-all hover:scale-110"
                        title="Cuộn sang phải">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Expand/Collapse Toggle Button -->
                    <button
                        type="button"
                        @click="isExpanded = !isExpanded; $nextTick(() => checkScroll())"
                        class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700/60 hover:bg-teal-50 dark:hover:bg-teal-950/40 text-gray-400 hover:text-teal-600 flex items-center justify-center text-[10px] transition cursor-pointer"
                        :title="isExpanded ? 'Thu gọn hàng tin nhắn' : 'Mở rộng xem tất cả tin mẫu'">
                        <svg class="w-3 h-3 transform transition-transform" :class="isExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Emoji toggle button -->
                    <div class="relative flex-shrink-0">
                        <button
                            type="button"
                            @click="showEmojis = !showEmojis"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition text-sm cursor-pointer"
                            title="Biểu tượng cảm xúc">
                            😊
                        </button>
                        
                        <!-- Quick Emojis Popover -->
                        <div 
                            x-show="showEmojis" 
                            @click.outside="showEmojis = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute bottom-9 right-0 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-2 flex items-center gap-1 z-30">
                            <template x-for="emo in quickEmojis" :key="emo">
                                <button
                                    type="button"
                                    @click="insertEmoji(emo); showEmojis = false"
                                    class="w-8 h-8 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center text-base transition cursor-pointer hover:scale-110 active:scale-95"
                                    x-text="emo">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form with perfectly aligned Textarea & Send Button -->
            <form wire:submit.prevent="sendMessage(); resetHeight()" class="flex items-end gap-2 sm:gap-3">
                <!-- Textarea Container -->
                <div class="relative flex-1">
                    <textarea
                        x-ref="chatInput"
                        wire:model="newMessage"
                        rows="1"
                        placeholder="Nhập tin nhắn..."
                        x-on:keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); resetHeight(); }"
                        x-on:input="adjustHeight()"
                        class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/80 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-teal-500/20 resize-none py-3 px-4 leading-6 transition-all min-h-[48px] flex items-center"
                        style="max-height: 120px;"></textarea>

                    <!-- Character warning if long -->
                    <div 
                        x-show="message && message.length > 1500" 
                        class="absolute right-3 bottom-1.5 text-[10px] font-mono text-gray-400 dark:text-gray-500 pointer-events-none">
                        <span x-text="message ? message.length : 0"></span>/2000
                    </div>
                </div>

                <!-- Send Button (Perfect 48px square aligned with 48px input) -->
                <button
                    type="submit"
                    :disabled="!message || !message.trim()"
                    wire:loading.attr="disabled"
                    :class="(!message || !message.trim()) ? 'opacity-40 grayscale cursor-not-allowed scale-95 shadow-none' : 'opacity-100 scale-100 hover:scale-105 shadow-md shadow-teal-500/25 cursor-pointer active:scale-95'"
                    class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white flex items-center justify-center transition-all duration-200 ease-out">
                    <span wire:loading wire:target="sendMessage">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="sendMessage" class="flex items-center justify-center">
                        <svg class="w-5 h-5 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3.4 20.4l17.45-7.48a1 1 0 000-1.84L3.4 3.6a.993.993 0 00-1.39.91L2 9.12c0 .5.37.93.87.99L17 12 2.87 13.88c-.5.07-.87.49-.87 1l.01 4.61c0 .71.73 1.2 1.39.91z"/>
                        </svg>
                    </span>
                </button>
            </form>

            @error('newMessage') 
                <span class="text-xs text-rose-600 dark:text-rose-400 block px-1">{{ $message }}</span> 
            @enderror

            <div class="flex items-center justify-between text-[10px] text-gray-400 dark:text-gray-500 px-1">
                <span>Nhấn <kbd class="px-1.5 py-0.5 rounded text-[9px] font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">Enter</kbd> gửi, <kbd class="px-1.5 py-0.5 rounded text-[9px] font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">Shift+Enter</kbd> xuống dòng</span>
                <span class="flex items-center gap-1">🔒 Lịch sự & Tôn trọng</span>
            </div>
        </div>
    </div>
 
    {{-- Review Section --}}
    @if($room->itemRequest->request_status_id == 2 || $room->itemRequest->request_status_id == 5 || $room->itemRequest->item->item_status_id == 4)
    <div class="bg-amber-50/50 dark:bg-amber-950/10 border-t border-dashed border-amber-200/50 dark:border-amber-900/20 px-4 sm:px-6 py-4 flex-shrink-0">
        <div class="max-w-3xl mx-auto">
            <p class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider mb-3">
                Đánh giá giao dịch
            </p>
            @livewire('submit-review', ['itemRequestId' => $room->itemRequest->id], key('review-' . $room->itemRequest->id))
        </div>
    </div>
    @endif
 
    <!-- Custom In-App Confirmation Modal (Eliminates blocked/suppressed browser confirm dialogs) -->
    <div x-show="confirmModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div x-show="confirmModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="confirmModalOpen = false"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

        {{-- Modal Dialog --}}
        <div x-show="confirmModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             @click.outside="confirmModalOpen = false"
             class="relative bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-gray-100 dark:border-gray-700 z-10 text-center">
            
            <!-- Icon -->
            <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl shadow-inner"
                 :class="confirmType === 'confirmReceived' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600'">
                <template x-if="confirmType === 'confirmReceived'">
                    <span>🎉</span>
                </template>
                <template x-if="confirmType !== 'confirmReceived'">
                    <span>⚠️</span>
                </template>
            </div>

            <!-- Title -->
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2" x-text="confirmTitle"></h3>

            <!-- Description -->
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6" x-text="confirmDesc"></p>

            <!-- Buttons -->
            <div class="flex items-center gap-2.5">
                <button type="button"
                        @click="confirmModalOpen = false"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition cursor-pointer">
                    Quay lại
                </button>
                <button type="button"
                        @click="executeConfirmedAction()"
                        :class="confirmBtnClass"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer flex items-center justify-center gap-1.5">
                    <span x-text="confirmBtnText"></span>
                </button>
            </div>
        </div>
    </div>

</div>
