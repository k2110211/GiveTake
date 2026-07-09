<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Tổng quan hệ thống</h1>
        <p class="text-sm text-gray-500 mt-0.5">Dữ liệu thống kê thời gian thực</p>
    </div>
 
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'Người dùng', 'value' => $stats['total_users'], 'sub' => $stats['banned_users'] . ' bị khóa', 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Món đồ', 'value' => $stats['total_items'], 'sub' => $stats['available_items'] . ' đang chia sẻ', 'color' => 'teal', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['label' => 'Yêu cầu', 'value' => $stats['total_requests'], 'sub' => $stats['pending_requests'] . ' đang chờ', 'color' => 'amber', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Đánh giá', 'value' => $stats['total_reviews'], 'sub' => '⭐ ' . $stats['avg_rating'] . ' trung bình', 'color' => 'rose', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            ];
            $colorMap = [
                'blue'  => ['bg' => 'bg-blue-50',  'icon' => 'bg-blue-100 text-blue-600',  'val' => 'text-blue-700'],
                'teal'  => ['bg' => 'bg-teal-50',  'icon' => 'bg-teal-100 text-teal-600',  'val' => 'text-teal-700'],
                'amber' => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-100 text-amber-600','val' => 'text-amber-700'],
                'rose'  => ['bg' => 'bg-rose-50',  'icon' => 'bg-rose-100 text-rose-600',  'val' => 'text-rose-700'],
            ];
        @endphp
 
        @foreach($cards as $card)
            @php $c = $colorMap[$card['color']]; @endphp
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-start space-x-4">
                <div class="p-2.5 rounded-xl {{ $c['icon'] }} flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p>
                    <p class="text-2xl font-extrabold {{ $c['val'] }}">{{ $card['value'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $card['sub'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
 
    <!-- Item Status Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
        @foreach([['available','Đang chia sẻ','teal'], ['reserved','Đang giữ','amber'], ['completed','Hoàn thành','green']] as [$status, $label, $color])
            @php
                $count = $stats[$status . '_items'];
                $pct = $stats['total_items'] > 0 ? round(($count / $stats['total_items']) * 100) : 0;
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">{{ $pct }}% tổng số món</p>
            </div>
        @endforeach
    </div>
 
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Items -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-800">Món đồ gần đây</h3>
                <a href="{{ route('admin.items') }}" class="text-xs text-teal-600 hover:underline">Xem tất cả →</a>
            </div>
            <ul class="divide-y divide-gray-50">
                @forelse($recentItems as $item)
                    <li class="px-5 py-3 flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item->title }}</p>
                            <p class="text-xs text-gray-400">{{ $item->user->name }} · {{ $item->city }}</p>
                        </div>
                        <span class="ml-3 text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $item->status === 'available' ? 'bg-teal-100 text-teal-700' : ($item->status === 'reserved' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                            {{ $item->status }}
                        </span>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-gray-400">Chưa có dữ liệu</li>
                @endforelse
            </ul>
        </div>
 
        <!-- Recent Requests -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-800">Yêu cầu gần đây</h3>
                <a href="{{ route('admin.transactions') }}" class="text-xs text-teal-600 hover:underline">Xem tất cả →</a>
            </div>
            <ul class="divide-y divide-gray-50">
                @forelse($recentRequests as $req)
                    <li class="px-5 py-3 flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $req->item->title ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">từ {{ $req->user->name }}</p>
                        </div>
                        <span class="ml-3 text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($req->status === 'approved' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-700') }}">
                            {{ $req->status }}
                        </span>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-gray-400">Chưa có dữ liệu</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
