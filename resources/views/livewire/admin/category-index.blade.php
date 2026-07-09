<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Quản lý danh mục</h1>
        <p class="text-sm text-gray-500 mt-0.5">Thêm, sửa và xóa danh mục món đồ</p>
    </div>
 
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700">{{ session('success') }}</div>
    @endif
 
    <!-- Delete confirm -->
    @if($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-base font-bold text-gray-900 mb-2">Xóa danh mục?</h3>
                <p class="text-sm text-gray-500 mb-5">Các món đồ thuộc danh mục này sẽ không còn danh mục. Hành động không thể hoàn tác.</p>
                <div class="flex gap-3">
                    <button wire:click="deleteCategory" class="flex-1 py-2 bg-red-500 text-white text-sm rounded-xl font-medium hover:bg-red-600">Xóa</button>
                    <button wire:click="$set('confirmDeleteId', null)" class="flex-1 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl font-medium">Hủy</button>
                </div>
            </div>
        </div>
    @endif
 
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add form -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 h-fit">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Thêm danh mục mới</h3>
            <form wire:submit.prevent="createCategory" class="space-y-3">
                <div>
                    <input wire:model="name" type="text" placeholder="Tên danh mục..."
                           class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                    @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-teal-500 text-white text-sm rounded-xl font-medium hover:bg-teal-600 transition-colors">
                    + Thêm danh mục
                </button>
            </form>
        </div>
 
        <!-- Category list -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Danh sách danh mục ({{ $categories->count() }})</h3>
            </div>
            <ul class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                    <li class="px-5 py-3 flex items-center justify-between gap-3">
                        @if($editingId === $cat->id)
                            <form wire:submit.prevent="saveEdit" class="flex-1 flex gap-2">
                                <input wire:model="editName" type="text"
                                       class="flex-1 rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 py-1.5">
                                <button type="submit" class="px-3 py-1.5 bg-teal-500 text-white text-xs rounded-lg font-medium">Lưu</button>
                                <button type="button" wire:click="cancelEdit" class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-lg">Hủy</button>
                            </form>
                        @else
                            <div class="flex-1 flex items-center gap-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $cat->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $cat->items_count }} món đồ · slug: {{ $cat->slug }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button wire:click="startEdit({{ $cat->id }})" class="text-xs text-blue-600 hover:underline">Sửa</button>
                                <button wire:click="confirmDelete({{ $cat->id }})" class="text-xs text-rose-600 hover:underline">Xóa</button>
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-gray-400">Chưa có danh mục nào</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
