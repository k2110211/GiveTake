<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Quản lý món đồ</h1>
        <p class="text-sm text-gray-500 mt-0.5">Xem, xóa và thay đổi trạng thái món đồ</p>
    </div>
 
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700">{{ session('success') }}</div>
    @endif
 
    <!-- Delete confirm modal -->
    @if($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-base font-bold text-gray-900 mb-2">Xóa món đồ?</h3>
                <p class="text-sm text-gray-500 mb-5">Hành động này không thể hoàn tác. Tất cả yêu cầu và chat liên quan cũng sẽ bị xóa.</p>
                <div class="flex gap-3">
                    <button wire:click="deleteItem" class="flex-1 py-2 bg-red-500 text-white text-sm rounded-xl font-medium hover:bg-red-600">Xóa</button>
                    <button wire:click="$set('confirmDeleteId', null)" class="flex-1 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl font-medium hover:bg-gray-200">Hủy</button>
                </div>
            </div>
        </div>
    @endif
 
    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm theo tiêu đề..."
               class="flex-1 min-w-48 rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
        <select wire:model.live="filterStatus" class="rounded-xl border-gray-200 text-sm focus:border-teal-500">
            <option value="">Tất cả trạng thái</option>
            <option value="available">Available</option>
            <option value="reserved">Reserved</option>
            <option value="completed">Completed</option>
        </select>
    </div>
 
    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Món đồ</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Chủ sở hữu</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Danh mục</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ Str::limit($item->title, 45) }}</p>
                            <p class="text-xs text-gray-400">{{ $item->city }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $item->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 hidden lg:table-cell">{{ $item->category->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <select wire:change="forceStatus({{ $item->id }}, $event.target.value)"
                                    class="text-xs rounded-lg border-gray-200 py-1 focus:border-teal-500">
                                <option value="available" {{ $item->status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="reserved" {{ $item->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="completed" {{ $item->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <button wire:click="confirmDelete({{ $item->id }})"
                                    class="text-xs text-rose-600 hover:underline">Xóa</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">Không có món đồ nào</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $items->links() }}</div>
    </div>
</div>
