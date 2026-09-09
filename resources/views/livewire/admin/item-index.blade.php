<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Quản lý & Kiểm duyệt món đồ</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kiểm duyệt bài đăng mới, quản lý trạng thái và xóa bài vi phạm</p>
        </div>
        @if($countPending > 0)
            <div class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-sm animate-pulse">
                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                Đang có {{ $countPending }} bài viết chờ duyệt
            </div>
        @endif
    </div>
 
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-teal-50 border border-teal-200 rounded-2xl text-sm text-teal-800 flex items-center shadow-sm">
            <svg class="w-5 h-5 text-teal-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Bar -->
    <div class="flex flex-wrap gap-2 mb-5">
        <button wire:click="setTab('pending')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Chờ duyệt
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                {{ $countPending }}
            </span>
        </button>

        <button wire:click="setTab('available')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'available' ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Đang hiển thị / Có sẵn
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab === 'available' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $countAvailable }}
            </span>
        </button>

        <button wire:click="setTab('rejected')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Đã từ chối
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab === 'rejected' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $countRejected }}
            </span>
        </button>

        <button wire:click="setTab('all')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $activeTab === 'all' ? 'bg-gray-800 text-white shadow-md shadow-gray-800/20' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            Tất cả
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                {{ $countAll }}
            </span>
        </button>
    </div>
 
    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-48">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm theo tiêu đề món đồ hoặc tên người đăng..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border-gray-200 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
        </div>

        @if($activeTab === 'all')
            <select wire:model.live="filterStatus" class="rounded-xl border-gray-200 text-sm focus:border-teal-500">
                <option value="">Tất cả trạng thái</option>
                <option value="5">Chờ duyệt</option>
                <option value="1">Có sẵn</option>
                <option value="3">Đang trao đổi</option>
                <option value="4">Hoàn thành</option>
                <option value="6">Từ chối</option>
                <option value="2">Đã đóng</option>
            </select>
        @endif
    </div>
 
    <!-- Items Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Món đồ</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Người đăng</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Danh mục / Kiểu</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Thao tác duyệt / Xử lý</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50/60 transition-colors {{ $item->item_status_id == 5 ? 'bg-amber-50/20' : '' }}">
                            <!-- Item info + thumbnail -->
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-14 h-14 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-200 cursor-pointer"
                                         wire:click="previewItem({{ $item->id }})">
                                        @if($item->thumbnail)
                                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">No img</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <button wire:click="previewItem({{ $item->id }})" class="text-left font-semibold text-gray-900 hover:text-teal-600 transition-colors line-clamp-1 block">
                                            {{ $item->title }}
                                        </button>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                                            <span>📍 {{ $item->city->name ?? '-' }}</span>
                                            <span>•</span>
                                            <span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($item->item_status_id == 6 && $item->rejection_reason)
                                            <p class="text-[11px] text-rose-600 mt-1 line-clamp-1">
                                                Lý do: {{ $item->rejection_reason }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Uploader -->
                            <td class="px-4 py-4 hidden md:table-cell">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-800 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ substr($item->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-xs">{{ $item->user->name ?? '—' }}</p>
                                        <span class="text-[10px] text-gray-400">Karma: {{ $item->user->karma_points ?? 0 }}đ</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category & Type -->
                            <td class="px-4 py-4 hidden lg:table-cell">
                                <span class="text-xs text-gray-600 font-medium block">{{ $item->category->name ?? '—' }}</span>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold {{ $item->type_id == 1 ? 'bg-emerald-50 text-emerald-700' : ($item->type_id == 2 ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700') }}">
                                    {{ $item->type->name ?? 'Cho đi' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->status->color ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $item->status->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- If Pending: Show Quick Approve & Reject Buttons -->
                                    @if($item->item_status_id == 5)
                                        <button wire:click="approveItem({{ $item->id }})"
                                                title="Duyệt bài ngay"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Duyệt
                                        </button>
                                        <button wire:click="openRejectModal({{ $item->id }})"
                                                title="Từ chối duyệt bài"
                                                class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Từ chối
                                        </button>
                                    @elseif($item->item_status_id == 6)
                                        <button wire:click="approveItem({{ $item->id }})"
                                                title="Duyệt lại"
                                                class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition-all">
                                            Duyệt lại
                                        </button>
                                    @endif

                                    <!-- Quick Preview button -->
                                    <button wire:click="previewItem({{ $item->id }})"
                                            title="Xem chi tiết nội dung"
                                            class="p-1.5 text-gray-500 hover:text-teal-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Force status dropdown (for other cases) -->
                                    <select wire:change="forceStatus({{ $item->id }}, $event.target.value)"
                                            class="text-[11px] rounded-lg border-gray-200 py-1 px-2 focus:border-teal-500 text-gray-600">
                                        <option value="5" {{ $item->item_status_id == 5 ? 'selected' : '' }}>Chờ duyệt</option>
                                        <option value="1" {{ $item->item_status_id == 1 ? 'selected' : '' }}>Có sẵn</option>
                                        <option value="3" {{ $item->item_status_id == 3 ? 'selected' : '' }}>Đang trao đổi</option>
                                        <option value="4" {{ $item->item_status_id == 4 ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="6" {{ $item->item_status_id == 6 ? 'selected' : '' }}>Từ chối</option>
                                        <option value="2" {{ $item->item_status_id == 2 ? 'selected' : '' }}>Đã đóng</option>
                                    </select>

                                    <!-- Delete button -->
                                    <button wire:click="confirmDelete({{ $item->id }})"
                                            title="Xóa vĩnh viễn"
                                            class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16 text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Không có món đồ nào trong danh mục này
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $items->links() }}</div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1: PREVIEW ITEM MODAL                              -->
    <!-- ======================================================== -->
    @if($previewItem)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto"
             wire:click.self="closePreview">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-100 my-8">
                <!-- Modal Header -->
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $previewItem->status->color ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $previewItem->status->name }}
                        </span>
                        <h3 class="text-base font-bold text-gray-900 mt-1">{{ $previewItem->title }}</h3>
                    </div>
                    <button wire:click="closePreview" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-[75vh] overflow-y-auto space-y-6">
                    <!-- Images Gallery -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hình ảnh sản phẩm</h4>
                        <div class="grid grid-cols-3 gap-3">
                            @if($previewItem->thumbnail)
                                <div class="col-span-3 sm:col-span-2 aspect-[4/3] rounded-2xl overflow-hidden bg-gray-100 border border-gray-200">
                                    <img src="{{ $previewItem->thumbnail }}" alt="{{ $previewItem->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="col-span-3 sm:col-span-1 flex flex-row sm:flex-col gap-3">
                                @if(!empty($previewItem->images))
                                    @foreach($previewItem->images as $img)
                                        <div class="flex-1 aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                                            <img src="{{ $img }}" alt="Image" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mô tả chi tiết</h4>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ $previewItem->description }}
                        </div>
                    </div>

                    <!-- Meta Details Grid -->
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-gray-400 block mb-1">Hình thức:</span>
                            <span class="font-bold text-gray-800 text-sm">{{ $previewItem->type->name ?? 'Cho đi' }}</span>
                            @if($previewItem->type_id == 2 && $previewItem->exchange_wish)
                                <p class="text-gray-600 mt-1 italic">Ước muốn: "{{ $previewItem->exchange_wish }}"</p>
                            @endif
                            @if($previewItem->type_id == 3)
                                <p class="text-purple-600 font-semibold mt-1">Yêu cầu tối thiểu: {{ $previewItem->min_karma }} Karma</p>
                            @endif
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-gray-400 block mb-1">Khu vực bàn giao:</span>
                            <span class="font-bold text-gray-800 text-sm">{{ $previewItem->district->name ?? '-' }}, {{ $previewItem->city->name ?? '-' }}</span>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-gray-400 block mb-1">Người đăng:</span>
                            <span class="font-bold text-gray-800">{{ $previewItem->user->name ?? '-' }}</span>
                            <span class="text-gray-400 block">{{ $previewItem->user->email ?? '-' }}</span>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-gray-400 block mb-1">Thời gian gửi:</span>
                            <span class="font-medium text-gray-700">{{ $previewItem->created_at->format('H:i d/m/Y') }}</span>
                        </div>
                    </div>

                    @if($previewItem->rejection_reason)
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
                            <span class="font-bold block mb-1">Lý do đã từ chối trước đó:</span>
                            {{ $previewItem->rejection_reason }}
                        </div>
                    @endif
                </div>

                <!-- Modal Footer with Actions -->
                <div class="p-5 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
                    <button wire:click="closePreview" class="px-4 py-2 bg-white text-gray-700 text-xs font-bold rounded-xl border border-gray-200 hover:bg-gray-100">
                        Đóng
                    </button>
                    <div class="flex items-center gap-2">
                        @if($previewItem->item_status_id == 5)
                            <button wire:click="approveItem({{ $previewItem->id }})" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Phê duyệt món đồ này
                            </button>
                            <button wire:click="openRejectModal({{ $previewItem->id }})" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Từ chối duyệt
                            </button>
                        @elseif($previewItem->item_status_id == 6)
                            <button wire:click="approveItem({{ $previewItem->id }})" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Duyệt lại món đồ này
                            </button>
                        @elseif($previewItem->item_status_id == 1)
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Đã duyệt (Đang hiển thị công khai)
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL 2: REJECT REASON MODAL                             -->
    <!-- ======================================================== -->
    @if($rejectItemId)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-gray-100">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <h3 class="text-base font-bold text-gray-900 mb-1">Từ chối duyệt bài đăng</h3>
                <p class="text-xs text-gray-500 mb-4">Chọn lý do nhanh hoặc nhập lý do chi tiết để thông báo cho người đăng bài.</p>

                <!-- Quick reason chips -->
                <div class="space-y-1.5 mb-4">
                    <label class="text-xs font-bold text-gray-600 block">Lý do nhanh:</label>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach([
                            'Hình ảnh không rõ ràng / mờ',
                            'Nội dung vi phạm quy chuẩn cộng đồng',
                            'Món đồ thuộc danh mục cấm tặng đổi',
                            'Thông tin mô tả quá sơ sài',
                            'Địa chỉ hoặc hình thức không hợp lệ'
                        ] as $quickReason)
                            <button type="button"
                                    wire:click="selectQuickReason('{{ $quickReason }}')"
                                    class="text-[11px] px-2.5 py-1 rounded-lg border text-left transition-all {{ $rejectionReason === $quickReason ? 'bg-rose-50 text-rose-800 border-rose-300 font-bold' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                {{ $quickReason }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Custom text area -->
                <div class="mb-5">
                    <label class="text-xs font-bold text-gray-600 block mb-1">Nội dung phản hồi chi tiết:</label>
                    <textarea wire:model="rejectionReason" rows="3" placeholder="Nhập lý do từ chối gửi tới người đăng..."
                              class="w-full text-xs rounded-xl border-gray-200 focus:border-rose-500 focus:ring focus:ring-rose-100 p-3"></textarea>
                    @error('rejectionReason')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button wire:click="confirmReject" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs rounded-xl font-bold transition-all shadow-sm">
                        Xác nhận từ chối
                    </button>
                    <button wire:click="$set('rejectItemId', null)" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-xl font-bold transition-all">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- MODAL 3: DELETE CONFIRM MODAL                            -->
    <!-- ======================================================== -->
    @if($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm border border-gray-100">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Xóa vĩnh viễn món đồ?</h3>
                <p class="text-xs text-gray-500 mb-5 leading-relaxed">Hành động này không thể hoàn tác. Tất cả yêu cầu và phòng chat liên quan cũng sẽ bị xóa vĩnh viễn.</p>
                <div class="flex gap-3">
                    <button wire:click="deleteItem" class="flex-1 py-2.5 bg-red-600 text-white text-xs rounded-xl font-bold hover:bg-red-700 transition-all">Xóa</button>
                    <button wire:click="$set('confirmDeleteId', null)" class="flex-1 py-2.5 bg-gray-100 text-gray-700 text-xs rounded-xl font-bold hover:bg-gray-200 transition-all">Hủy</button>
                </div>
            </div>
        </div>
    @endif
</div>
