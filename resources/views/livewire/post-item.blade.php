<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                Đăng tin chia sẻ món đồ mới
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                Hãy cung cấp thông tin chi tiết về món đồ của bạn để cộng đồng dễ dàng tiếp cận và chia sẻ nhé.
            </p>
        </div>
 
        <!-- Form Container -->
        <form wire:submit.prevent="save" class="space-y-8 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10">
            
            <!-- SECTION 1: BASIC INFO -->
            <div class="space-y-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center">
                    <span class="w-2 h-5 bg-teal-500 rounded-full mr-2.5"></span>
                    Thông tin cơ bản
                </h3>
 
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Tiêu đề bài đăng <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" wire:model="title" placeholder="Ví dụ: Áo khoác gió Uniqlo size L còn mới" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 placeholder-gray-400">
                    @error('title') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
 
                <!-- Category -->
                <div>
                    <label for="categoryId" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Danh mục món đồ <span class="text-rose-500">*</span></label>
                    <select id="categoryId" wire:model="categoryId" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
 
                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Mô tả chi tiết <span class="text-rose-500">*</span></label>
                    <textarea id="description" wire:model="description" rows="5" placeholder="Hãy mô tả rõ tình trạng sử dụng, kích cỡ, màu sắc, xuất xứ hoặc các lưu ý khác để người nhận dễ hình dung..." class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 placeholder-gray-400"></textarea>
                    @error('description') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
 
            <!-- SECTION 2: IMAGES -->
            <div class="space-y-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center">
                    <span class="w-2 h-5 bg-teal-500 rounded-full mr-2.5"></span>
                    Hình ảnh sản phẩm <span class="text-rose-500 ml-1">*</span>
                </h3>
 
                <div class="space-y-4">
                    <!-- Dropzone -->
                    <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-10 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 hover:border-teal-400 transition-colors cursor-pointer group">
                        <input type="file" id="images" wire:model="images" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <svg class="w-10 h-10 text-gray-400 group-hover:text-teal-500 transition-colors mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Nhấp để tải ảnh lên</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Hỗ trợ tối đa 5 ảnh, định dạng JPG, PNG, WEBP (tối đa 2MB mỗi ảnh)</p>
                    </div>
 
                    <!-- Livewire Upload Loading -->
                    <div wire:loading wire:target="images" class="w-full text-center py-2 text-xs text-teal-600 dark:text-teal-400 font-semibold">
                        <span class="inline-block animate-bounce mr-1">⚡</span> Đang tải ảnh lên hệ thống, vui lòng chờ...
                    </div>
 
                    @error('images') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    @error('images.*') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
 
                    <!-- Image Previews -->
                    @if (!empty($images))
                        <div class="grid grid-cols-5 gap-4 pt-4 border-t border-gray-50 dark:border-gray-700/50">
                            @foreach ($images as $key => $image)
                                @if (method_exists($image, 'temporaryUrl'))
                                    <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm">
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                                        <button type="button" wire:click="$set('images.{{ $key }}', null)" class="absolute top-1 right-1 bg-black/60 backdrop-blur-sm hover:bg-rose-600 text-white rounded-full p-1 shadow-sm transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
 
            <!-- SECTION 3: DEAL INFO -->
            <div class="space-y-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center">
                    <span class="w-2 h-5 bg-teal-500 rounded-full mr-2.5"></span>
                    Hình thức sẻ chia
                </h3>
 
                <!-- Type Selection -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Hình thức đăng bài <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer focus:outline-none transition {{ $type === 'give' ? 'border-teal-500 bg-teal-50/10 ring-2 ring-teal-200' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                            <input type="radio" name="type" value="give" wire:model.live="type" class="sr-only">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $type === 'give' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                Tặng miễn phí
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1 block">Cho đi không nhận lại điểm Karma tương ứng</span>
                        </label>
 
                        <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer focus:outline-none transition {{ $type === 'exchange' ? 'border-teal-500 bg-teal-50/10 ring-2 ring-teal-200' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                            <input type="radio" name="type" value="exchange" wire:model.live="type" class="sr-only">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $type === 'exchange' ? 'bg-orange-500' : 'bg-gray-300' }}"></span>
                                Trao đổi đồ dùng
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1 block">Đề xuất trao đổi lấy một món đồ dùng khác</span>
                        </label>
                    </div>
                </div>
 
                <!-- Exchange Wish -->
                @if($type === 'exchange')
                    <div class="bg-orange-50/10 border border-orange-100 dark:border-orange-900/30 p-5 rounded-2xl animate-fadeIn">
                        <label for="exchangeWish" class="block text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider mb-2">Mong muốn nhận lại <span class="text-rose-500">*</span></label>
                        <textarea id="exchangeWish" wire:model="exchangeWish" rows="3" placeholder="Ví dụ: Mong muốn đổi lấy balo thể thao hoặc vợt bóng bàn còn dùng tốt..." class="w-full rounded-xl border-orange-200 dark:border-orange-900/40 bg-white dark:bg-gray-900 text-sm focus:border-orange-500 focus:ring focus:ring-orange-200 dark:text-gray-300 placeholder-gray-400"></textarea>
                        @error('exchangeWish') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
 
            <!-- SECTION 4: LOCATION -->
            <div class="space-y-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center">
                    <span class="w-2 h-5 bg-teal-500 rounded-full mr-2.5"></span>
                    Khu vực giao dịch
                </h3>
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- City Dropdown -->
                    <div>
                        <label for="city" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Tỉnh / Thành phố <span class="text-rose-500">*</span></label>
                        <select id="city" wire:model.live="city" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300">
                            <option value="">-- Chọn Tỉnh / Thành --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('city') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
 
                    <!-- District Dropdown -->
                    <div>
                        <label for="district" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Quận / Huyện <span class="text-rose-500">*</span></label>
                        <select id="district" wire:model="district" @if(!$city) disabled @endif class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:border-teal-500 focus:ring focus:ring-teal-200 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">-- Chọn Quận / Huyện --</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('district') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
 
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 border-t border-gray-100 dark:border-gray-700/50 pt-8 mt-8">
                <a href="/" class="px-6 py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors" wire:navigate>
                    Hủy bỏ
                </a>
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 transition-all shadow-md flex items-center justify-center">
                    <span wire:loading wire:target="save" class="inline-block animate-spin w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full"></span>
                    Đăng tin bài
                </button>
            </div>
 
        </form>
    </div>
</div>
