<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Tổng quan giao dịch</h1>
        <p class="text-sm text-gray-500 mt-0.5">Theo dõi tất cả yêu cầu và trạng thái giao dịch</p>
    </div>
 
    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm theo tên món đồ..."
               class="flex-1 min-w-48 rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
        <select wire:model.live="filterStatus" class="rounded-xl border-gray-200 text-sm focus:border-teal-500">
            <option value="">Tất cả</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>
 
    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Món đồ</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Chủ đồ</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Người xin</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Ngày tạo</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Chat Room</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ Str::limit($request->item->title ?? 'N/A', 40) }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $request->item->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $request->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $request->status->color ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $request->status->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs hidden lg:table-cell">{{ $request->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            @if($request->chatRoom)
                                <a href="{{ route('chat.room', $request->chatRoom->id) }}" target="_blank"
                                   class="text-xs text-teal-600 hover:underline">Xem chat →</a>
                            @else
                                <span class="text-xs text-gray-300">Chưa có</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">Không có giao dịch nào</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $requests->links() }}</div>
    </div>
</div>
