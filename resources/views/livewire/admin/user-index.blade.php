<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Quản lý người dùng</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tìm kiếm, khóa, và nâng quyền tài khoản</p>
        </div>
    </div>
 
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700 font-medium">
            {{ session('success') }}
        </div>
    @endif
 
    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="Tìm theo tên hoặc email..."
               class="flex-1 min-w-48 rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
        <select wire:model.live="filterStatus" class="rounded-xl border-gray-200 text-sm focus:border-teal-500">
            <option value="">Tất cả</option>
            <option value="admin">Admin</option>
            <option value="banned">Đã khóa</option>
        </select>
    </div>
 
    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Người dùng</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Thành phố</th>
                    <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Karma</th>
                    <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Món đồ</th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">
                                {{ $user->name }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-500 hidden md:table-cell">{{ $user->city ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-amber-600">{{ $user->karma_points }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 hidden lg:table-cell">{{ $user->items_count }}</td>
                        <td class="px-4 py-3">
                            @if($user->is_admin)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-700">⚙ Admin</span>
                            @elseif($user->is_banned)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">🚫 Bị khóa</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Thường</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="text-xs text-blue-600 hover:underline">Chi tiết</a>
                                @if(!$user->is_admin)
                                    <button wire:click="toggleBan({{ $user->id }})"
                                            wire:confirm="{{ $user->is_banned ? 'Mở khóa tài khoản này?' : 'Khóa tài khoản này?' }}"
                                            class="text-xs {{ $user->is_banned ? 'text-teal-600' : 'text-rose-600' }} hover:underline">
                                        {{ $user->is_banned ? 'Mở khóa' : 'Khóa' }}
                                    </button>
                                    <button wire:click="promoteAdmin({{ $user->id }})"
                                            wire:confirm="Nâng quyền admin cho {{ $user->name }}?"
                                            class="text-xs text-gray-500 hover:text-gray-700 hover:underline">
                                        Nâng Admin
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">Không tìm thấy người dùng</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
