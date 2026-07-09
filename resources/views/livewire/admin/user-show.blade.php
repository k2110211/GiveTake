<div>
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>
    </div>
 
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700">{{ session('success') }}</div>
    @endif
 
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Profile & Stats -->
        <div class="space-y-5">
            <!-- Profile card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400">Tham gia {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
 
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Điện thoại</span><span class="font-medium">{{ $user->phone ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Thành phố</span><span class="font-medium">{{ $user->city ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Quận/Huyện</span><span class="font-medium">{{ $user->district ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Trạng thái</span>
                        @if($user->is_admin) <span class="font-bold text-teal-600">Admin</span>
                        @elseif($user->is_banned) <span class="font-bold text-red-600">Bị khóa</span>
                        @else <span class="text-gray-600">Bình thường</span> @endif
                    </div>
                </div>
 
                @if(!$user->is_admin)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <button wire:click="toggleBan"
                                wire:confirm="{{ $user->is_banned ? 'Mở khóa tài khoản?' : 'Khóa tài khoản này?' }}"
                                class="w-full text-sm py-2 rounded-xl font-medium transition-colors
                                       {{ $user->is_banned ? 'bg-teal-50 text-teal-700 hover:bg-teal-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                            {{ $user->is_banned ? '🔓 Mở khóa tài khoản' : '🚫 Khóa tài khoản' }}
                        </button>
                    </div>
                @endif
            </div>
 
            <!-- Karma & Trust Score edit -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-800">Karma & Trust Score</h3>
                    @if(!$editingStats)
                        <button wire:click="$set('editingStats', true)" class="text-xs text-teal-600 hover:underline">Chỉnh sửa</button>
                    @endif
                </div>
 
                @if($editingStats)
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Karma Points</label>
                            <input type="number" wire:model="editKarma" min="0"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Trust Score (0-5)</label>
                            <input type="number" wire:model="editTrustScore" min="0" max="5" step="0.1"
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500">
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="saveStats" class="flex-1 py-1.5 bg-teal-500 text-white text-xs rounded-lg font-medium hover:bg-teal-600">Lưu</button>
                            <button wire:click="$set('editingStats', false)" class="flex-1 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-lg font-medium hover:bg-gray-200">Hủy</button>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-amber-50 rounded-xl p-3">
                            <p class="text-xl font-extrabold text-amber-600">{{ $user->karma_points }}</p>
                            <p class="text-xs text-gray-500">Karma</p>
                        </div>
                        <div class="bg-teal-50 rounded-xl p-3">
                            <p class="text-xl font-extrabold text-teal-600">{{ number_format($user->trust_score, 1) }}</p>
                            <p class="text-xs text-gray-500">Trust Score</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
 
        <!-- Right: Items & Reviews -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Items -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Món đồ đã đăng ({{ $user->items->count() }})</h3>
                </div>
                <ul class="divide-y divide-gray-50">
                    @forelse($user->items->take(5) as $item)
                        <li class="px-5 py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->title }}</p>
                                <p class="text-xs text-gray-400">{{ $item->category->name ?? '' }} · {{ $item->city }}</p>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $item->status === 'available' ? 'bg-teal-100 text-teal-700' : ($item->status === 'reserved' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                {{ $item->status }}
                            </span>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-gray-400">Chưa đăng món đồ nào</li>
                    @endforelse
                </ul>
            </div>
 
            <!-- Reviews received -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Đánh giá nhận được ({{ $user->receivedReviews->count() }})</h3>
                </div>
                <ul class="divide-y divide-gray-50">
                    @forelse($user->receivedReviews->take(5) as $review)
                        <li class="px-5 py-3">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-700">{{ $review->reviewer->name ?? '?' }}</p>
                                <span class="text-amber-500 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $review->comment }}</p>
                            @endif
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-gray-400">Chưa có đánh giá nào</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
