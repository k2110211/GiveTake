<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Bảng quản trị của tôi
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý các tin đăng chia sẻ và các yêu cầu giao dịch của bạn.
                </p>
            </div>
            
            <a href="{{ route('item.create') }}" class="inline-flex items-center px-5 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 shadow-md transition-all h-fit w-fit" wire:navigate>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Đăng tin mới
            </a>
        </div>
 
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-800 dark:text-emerald-300 flex items-start shadow-sm">
                <svg class="w-5 h-5 mr-3 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-bold text-sm">Thành công!</h4>
                    <p class="text-xs mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif
 
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Stat item 1 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block">Điểm Karma</span>
                <div class="flex items-baseline space-x-1.5 mt-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-amber-500">{{ $stats['karma'] }}</span>
                    <span class="text-xs text-gray-500">karma</span>
                </div>
            </div>
            
            <!-- Stat item 2 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block">Tin đã đăng</span>
                <div class="flex items-baseline space-x-1.5 mt-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100">{{ $stats['posted_count'] }}</span>
                    <span class="text-xs text-gray-500">món đồ</span>
                </div>
            </div>
 
            <!-- Stat item 3 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block">Đã trao đổi xong</span>
                <div class="flex items-baseline space-x-1.5 mt-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $stats['successful_count'] }}</span>
                    <span class="text-xs text-gray-500">hoàn thành</span>
                </div>
            </div>
 
            <!-- Stat item 4 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between relative">
                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider block">Yêu cầu nhận đồ mới</span>
                <div class="flex items-baseline space-x-1.5 mt-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-teal-600 dark:text-teal-400">{{ $stats['pending_received_count'] }}</span>
                    <span class="text-xs text-gray-500">đang chờ duyệt</span>
                </div>
                @if($stats['pending_received_count'] > 0)
                    <span class="absolute top-5 right-5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                    </span>
                @endif
            </div>
        </div>
 
        <!-- Tabbed Dashboard Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Tabs Header -->
            <div class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/10 px-6 sm:px-8 py-4 flex items-center space-x-6">
                <button wire:click="$set('activeTab', 'my-items')" class="text-sm font-bold pb-2 border-b-2 transition-all {{ $activeTab === 'my-items' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-400 dark:text-gray-500 hover:text-gray-600' }}">
                    Tin đăng của tôi ({{ $myItems->count() }})
                </button>
                <button wire:click="$set('activeTab', 'sent-requests')" class="text-sm font-bold pb-2 border-b-2 transition-all {{ $activeTab === 'sent-requests' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-gray-400 dark:text-gray-500 hover:text-gray-600' }}">
                    Yêu cầu đã gửi ({{ $stats['sent_requests_count'] }})
                </button>
            </div>
 
            <!-- Tabs Body -->
            <div class="p-6 sm:p-8">
                @if($activeTab === 'my-items')
                    <!-- Tab 1: My Items -->
                    @if($myItems->isEmpty())
                        <div class="text-center py-16 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-2xl">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Bạn chưa đăng tin món đồ nào</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Hãy chia sẻ những món đồ bạn không còn sử dụng để tích lũy thêm điểm Karma tốt lành nhé.</p>
                            <a href="{{ route('item.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-xl bg-teal-500 text-white shadow-sm hover:bg-teal-600">
                                Đăng tin đầu tiên
                            </a>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach($myItems as $item)
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                                    <!-- Item Header Card -->
                                    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-800">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative flex-shrink-0">
                                                @if($item->thumbnail)
                                                    <img src="{{ $item->thumbnail }}" alt="Item thumbnail" class="absolute inset-0 w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-teal-600">
                                                    <a href="{{ route('item.detail', ['id' => $item->id]) }}" wire:navigate>{{ $item->title }}</a>
                                                </h4>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">{{ $item->category->name }}</span>
                                                    <span class="text-gray-300 dark:text-gray-700">•</span>
                                                    <span class="text-xs {{ $item->type_id == 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-orange-600 dark:text-orange-400' }} font-bold">
                                                        {{ $item->type->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
 
                                        <!-- Status Badge -->
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->status->color ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">{{ $item->status->name }}</span>
                                            @if((int)$item->item_status_id === 5)
                                                <p class="text-[11px] text-amber-600 dark:text-amber-400 mt-1 flex items-center justify-end gap-1 font-medium">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Chờ BQT duyệt
                                                </p>
                                            @elseif((int)$item->item_status_id === 6)
                                                <p class="text-[11px] text-rose-600 dark:text-rose-400 mt-1 flex items-center justify-end gap-1 font-medium">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Đã bị từ chối
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if((int)$item->item_status_id === 5)
                                        <div class="p-4 bg-amber-50/50 dark:bg-amber-950/20 border-t border-amber-100 dark:border-amber-900/30 text-xs text-amber-800 dark:text-amber-300 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Món đồ của bạn đã được gửi và đang chờ Ban Quản Trị phê duyệt trước khi hiển thị công khai.</span>
                                        </div>
                                    @elseif((int)$item->item_status_id === 6)
                                        <div class="p-4 bg-rose-50/50 dark:bg-rose-950/20 border-t border-rose-100 dark:border-rose-900/30 text-xs text-rose-800 dark:text-rose-300 flex items-start gap-2">
                                            <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <div>
                                                <span class="font-bold">Món đồ đã bị từ chối duyệt.</span>
                                                <p class="mt-0.5 text-rose-700 dark:text-rose-400">Lý do: {{ $item->rejection_reason ?: 'Nội dung hoặc hình ảnh không phù hợp quy định đăng tin.' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Received Requests Sub-section -->
                                        <div class="p-5 bg-white dark:bg-gray-800/40">
                                            <h5 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">
                                                Yêu cầu nhận đồ ({{ $item->requests->count() }})
                                            </h5>
                                            
                                            @if($item->requests->isEmpty())
                                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">Chưa có ai gửi yêu cầu nhận món đồ này.</p>
                                            @else
                                            <div class="space-y-4">
                                                @foreach($item->requests as $req)
                                                    <div class="p-4 rounded-xl border {{ $req->request_status_id == 2 ? 'border-emerald-200 bg-emerald-50/5 dark:border-emerald-900/30' : 'border-gray-100 dark:border-gray-800 bg-gray-50/20 dark:bg-gray-900/10' }} flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                                        <div class="flex items-start space-x-3 flex-1">
                                                            <div class="w-9 h-9 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center font-bold text-teal-800 dark:text-teal-300 text-xs flex-shrink-0">
                                                                {{ substr($req->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100">{{ $req->user->name }}</span>
                                                                    <span class="text-[10px] font-semibold text-yellow-500 flex items-center">
                                                                        ★ {{ number_format($req->user->trust_score, 1) }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 whitespace-pre-line leading-normal bg-white dark:bg-gray-900/80 p-3 rounded-lg border border-gray-100 dark:border-gray-800 italic">
                                                                    "{{ $req->message }}"
                                                                </p>
                                                                <span class="text-[10px] text-gray-400 mt-1 block">Gửi yêu cầu lúc {{ $req->created_at->format('H:i d/m/Y') }}</span>
                                                            </div>
                                                        </div>
 
                                                        <!-- Actions or Status badges -->
                                                        <div class="flex items-center gap-2 flex-shrink-0 self-end md:self-center">
                                                            @if($req->request_status_id == 1)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/30 dark:text-yellow-400">
                                                                    {{ $req->status->name }}
                                                                </span>
                                                                @if($req->chatRoom)
                                                                    <a href="{{ route('chat.room', ['roomId' => $req->chatRoom->id]) }}" wire:navigate class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-teal-500 hover:bg-teal-600 transition shadow-sm flex items-center">
                                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                                        </svg>
                                                                        Trò chuyện & Duyệt
                                                                    </a>
                                                                @endif
                                                                <button wire:click="rejectRequest({{ $req->id }})" wire:confirm="Bạn có chắc chắn muốn từ chối yêu cầu này?" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                                                    Từ chối
                                                                </button>
                                                            @elseif($req->request_status_id == 2)
                                                                <div class="flex items-center gap-2">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">{{ $req->status->name }}</span>
                                                                    @if($req->chatRoom)
                                                                        <a href="{{ route('chat.room', ['roomId' => $req->chatRoom->id]) }}" wire:navigate class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-blue-500 hover:bg-blue-600 transition shadow-sm flex items-center">
                                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                                            </svg>
                                                                            Trò chuyện
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="text-xs text-gray-400 dark:text-gray-500 italic">{{ $req->status->name }}</span>
                                                                @if($req->chatRoom)
                                                                    <a href="{{ route('chat.room', ['roomId' => $req->chatRoom->id]) }}" wire:navigate class="px-2 py-1 rounded-lg text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-700 flex items-center">
                                                                        Xem chat
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <!-- Tab 2: Requests I've Sent -->
                    @if($sentRequests->isEmpty())
                        <div class="text-center py-16 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-2xl">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Bạn chưa gửi yêu cầu xin đồ nào</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Hãy dạo quanh trang chủ và gửi lời nhắn lịch sự nếu bạn tìm thấy món đồ hữu ích nhé.</p>
                            <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-xl bg-teal-500 text-white shadow-sm hover:bg-teal-600" wire:navigate>
                                Khám phá trang chủ
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($sentRequests as $req)
                                <div class="p-5 rounded-2xl border border-gray-150 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                    <!-- Item Summary -->
                                    <div class="flex items-center space-x-4">
                                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 relative flex-shrink-0">
                                            @if($req->item->thumbnail)
                                                <img src="{{ $req->item->thumbnail }}" alt="Item thumbnail" class="absolute inset-0 w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-teal-600">
                                                <a href="{{ route('item.detail', ['id' => $req->item->id]) }}" wire:navigate>{{ $req->item->title }}</a>
                                            </h4>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Người đăng: {{ $req->item->user->name }}</span>
                                                <span class="text-gray-300 dark:text-gray-700">•</span>
                                                <span class="text-xs {{ $req->item->type_id == 1 ? 'text-emerald-600' : 'text-orange-600' }} font-bold">
                                                    {{ $req->item->type->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- Request Details -->
                                    <div class="flex-1 md:px-6">
                                        <div class="text-xs text-gray-500 mb-1">Lời nhắn của bạn:</div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 p-3 rounded-lg border border-gray-100 dark:border-gray-800 italic">
                                            "{{ $req->message }}"
                                        </p>
                                        <span class="text-[9px] text-gray-400 mt-1 block">Gửi lúc {{ $req->created_at->format('H:i d/m/Y') }}</span>
                                    </div>

                                    <!-- Status & Actions -->
                                    <div class="flex items-center gap-3 flex-shrink-0 self-end md:self-center">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $req->status->color ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700' }}">{{ $req->status->name }}</span>
                                        </div>

                                        @if($req->chatRoom)
                                            <a href="{{ route('chat.room', ['roomId' => $req->chatRoom->id]) }}" wire:navigate class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-blue-500 hover:bg-blue-600 transition shadow-sm flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                Trò chuyện
                                            </a>
                                        @endif

                                        @if($req->request_status_id == 1)
                                            <button wire:click="cancelRequest({{ $req->id }})" wire:confirm="Bạn có chắc chắn muốn hủy yêu cầu này?" class="text-xs text-rose-600 hover:text-rose-800 hover:underline font-semibold">
                                                Hủy yêu cầu
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
 
    </div>
</div>
