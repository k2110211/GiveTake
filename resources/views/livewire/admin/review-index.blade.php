<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Kiểm duyệt đánh giá</h1>
        <p class="text-sm text-gray-500 mt-0.5">Xem và xóa đánh giá vi phạm</p>
    </div>
 
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700">{{ session('success') }}</div>
    @endif
 
    <!-- Delete confirm -->
    @if($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-base font-bold text-gray-900 mb-2">Xóa đánh giá?</h3>
                <p class="text-sm text-gray-500 mb-5">Đánh giá này sẽ bị xóa vĩnh viễn và Trust Score sẽ không tự cập nhật lại.</p>
                <div class="flex gap-3">
                    <button wire:click="deleteReview" class="flex-1 py-2 bg-red-500 text-white text-sm rounded-xl font-medium hover:bg-red-600">Xóa</button>
                    <button wire:click="$set('confirmDeleteId', null)" class="flex-1 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl font-medium">Hủy</button>
                </div>
            </div>
        </div>
    @endif
 
    <!-- Rating stats -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-gray-800 mb-3">Phân phối đánh giá</h3>
        <div class="flex items-end gap-2 h-16">
            @for($star = 5; $star >= 1; $star--)
                @php $count = $ratingStats[$star] ?? 0; $total = $reviews->total(); $pct = $total > 0 ? ($count / $total) * 100 : 0; @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs font-bold text-gray-700">{{ $count }}</span>
                    <div class="w-full bg-gray-100 rounded-t-sm flex flex-col-reverse" style="height: 40px">
                        <div class="bg-amber-400 rounded-t-sm transition-all w-full" style="height: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $star }}★</span>
                </div>
            @endfor
        </div>
    </div>
 
    <!-- Filter -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <select wire:model.live="filterRating" class="rounded-xl border-gray-200 text-sm focus:border-teal-500">
            <option value="">Tất cả sao</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} sao</option>
            @endfor
        </select>
    </div>
 
    <!-- Reviews table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Người đánh giá</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Được đánh giá</th>
                    <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sao</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Nhận xét</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Xóa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $review->reviewer->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $review->created_at->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $review->reviewee->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center text-amber-500 font-bold">{{ $review->rating }}★</td>
                        <td class="px-4 py-3 text-gray-500 hidden lg:table-cell">{{ $review->comment ? Str::limit($review->comment, 60) : '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <button wire:click="confirmDelete({{ $review->id }})" class="text-xs text-rose-600 hover:underline">Xóa</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">Không có đánh giá nào</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
    </div>
</div>
