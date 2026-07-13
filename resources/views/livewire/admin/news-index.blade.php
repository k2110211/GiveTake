<div>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Quản lý tin tức</h1>
            <p class="text-sm text-gray-500 mt-0.5">Đăng tải, cập nhật các chương trình quyên góp và cảnh báo an toàn</p>
        </div>
        @if(!$showForm)
            <button wire:click="openCreateForm" class="px-4 py-2.5 bg-teal-500 text-white text-sm rounded-xl font-medium hover:bg-teal-600 transition shadow-sm">
                + Viết bài mới
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-teal-50 border border-teal-200 rounded-xl text-sm text-teal-700 shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- Delete Confirm Modal -->
    @if($confirmDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-base font-bold text-gray-900 mb-2">Xóa tin tức?</h3>
                <p class="text-sm text-gray-500 mb-5">Bài viết này sẽ biến mất vĩnh viễn khỏi slide trang chủ. Bạn chắc chắn chứ?</p>
                <div class="flex gap-3">
                    <button wire:click="deleteNews" class="flex-1 py-2 bg-red-500 text-white text-sm rounded-xl font-medium hover:bg-red-600">Xóa</button>
                    <button wire:click="$set('confirmDeleteId', null)" class="flex-1 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl font-medium">Hủy</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Section (Create / Edit) -->
    @if($showForm)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
            <h3 class="text-base font-bold text-gray-800 mb-5">
                {{ $editingId ? 'Cập nhật bài viết' : 'Đăng bài viết mới' }}
            </h3>
            
            <form wire:submit.prevent="saveNews" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tiêu đề bài viết <span class="text-rose-500">*</span></label>
                            <input wire:model="title" type="text" placeholder="Nhập tiêu đề hấp dẫn..."
                                   class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            @error('title') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Summary -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tóm tắt ngắn <span class="text-rose-500">*</span></label>
                            <textarea wire:model="summary" rows="3" placeholder="Tóm tắt ngắn gọn hiển thị ngoài trang chủ (max 500 ký tự)..."
                                      class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200"></textarea>
                            @error('summary') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hình ảnh bài viết <span class="text-rose-500">*</span></label>
                            <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center bg-gray-50 hover:border-teal-400 transition-colors cursor-pointer group min-h-[140px]">
                                <input type="file" id="imageFile" wire:model="imageFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-teal-500 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs font-bold text-gray-700">Chọn ảnh đại diện bài đăng</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Chấp nhận JPG, PNG, WEBP (tối đa 2MB)</p>
                            </div>
                            <div wire:loading wire:target="imageFile" class="text-xs text-teal-600 font-semibold text-center mt-1">Đang tải ảnh lên...</div>
                            @error('imageFile') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror

                            <!-- Preview -->
                            <div class="flex gap-4 mt-3">
                                @if($imageFile)
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-semibold mb-1">Ảnh mới chọn:</p>
                                        <img src="{{ $imageFile->temporaryUrl() }}" class="w-24 h-16 object-cover rounded-lg border border-gray-200">
                                    </div>
                                @endif
                                @if($existingImage)
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-semibold mb-1">Ảnh hiện tại:</p>
                                        <img src="{{ $existingImage }}" class="w-24 h-16 object-cover rounded-lg border border-gray-200">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nội dung chi tiết <span class="text-rose-500">*</span></label>
                    <textarea wire:model="content" rows="8" placeholder="Viết nội dung bài đăng chi tiết tại đây..."
                              class="w-full rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200"></textarea>
                    @error('content') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Action buttons -->
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="cancelForm" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl font-medium">Hủy</button>
                    <button type="submit" class="px-6 py-2 bg-teal-500 text-white text-sm rounded-xl font-medium hover:bg-teal-600 transition">
                        {{ $editingId ? 'Cập nhật' : 'Đăng bài' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- News List Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Search bar inside table header -->
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3">
            <h3 class="text-sm font-bold text-gray-800">Danh sách bài đăng</h3>
            <div class="relative w-full sm:w-64">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm tin tức..."
                       class="w-full rounded-xl border-gray-200 text-xs pl-8 pr-3 py-2 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-teal-200">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Ảnh</th>
                        <th scope="col" class="px-6 py-3">Tiêu đề / Tóm tắt</th>
                        <th scope="col" class="px-6 py-3">Người đăng</th>
                        <th scope="col" class="px-6 py-3">Ngày tạo</th>
                        <th scope="col" class="px-6 py-3 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($newsItems as $news)
                        <tr class="bg-white hover:bg-gray-50/50">
                            <td class="px-6 py-4">
                                <img src="{{ $news->image ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=100&q=80' }}" 
                                     alt="Cover" 
                                     class="w-16 h-10 object-cover rounded-lg border border-gray-100 shadow-inner">
                            </td>
                            <td class="px-6 py-4 max-w-xs sm:max-w-md">
                                <p class="font-bold text-gray-900 line-clamp-1">{{ $news->title }}</p>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-0.5">{{ $news->summary }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-900">
                                {{ $news->user->name }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $news->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-xs">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="startEdit({{ $news->id }})" class="text-blue-600 hover:underline font-semibold">Sửa</button>
                                    <button wire:click="confirmDelete({{ $news->id }})" class="text-rose-600 hover:underline font-semibold">Xóa</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">Không tìm thấy bài viết nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($newsItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $newsItems->links() }}
            </div>
        @endif
    </div>
</div>
