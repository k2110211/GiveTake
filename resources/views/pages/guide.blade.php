<x-app-layout>
    <div class="py-10 sm:py-16" x-data="{ activeTab: 'giver', activeFaq: null }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
                <a href="/" wire:navigate class="hover:text-teal-600 dark:hover:text-teal-400 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Hướng dẫn sử dụng</span>
            </nav>

            <!-- Hero Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 dark:bg-teal-950/70 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 mb-4">
                    📖 Cẩm nang Cho & Nhận
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-4">
                    Hướng dẫn sử dụng nền tảng Cho & Nhận
                </h1>
                <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                    Mọi thứ bạn cần biết để bắt đầu cho đi, nhận lại và xây dựng uy tín trong cộng đồng sẻ chia văn minh.
                </p>

                <!-- Navigation Tabs -->
                <div class="inline-flex p-1.5 rounded-2xl bg-gray-100 dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 mt-8 gap-1 shadow-inner">
                    <button type="button"
                            @click="activeTab = 'giver'"
                            :class="activeTab === 'giver' ? 'bg-white dark:bg-gray-700 text-teal-600 dark:text-teal-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium'"
                            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 cursor-pointer flex items-center gap-2">
                        <span>🎁</span>
                        <span>Người Tặng đồ</span>
                    </button>
                    <button type="button"
                            @click="activeTab = 'receiver'"
                            :class="activeTab === 'receiver' ? 'bg-white dark:bg-gray-700 text-teal-600 dark:text-teal-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium'"
                            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 cursor-pointer flex items-center gap-2">
                        <span>📥</span>
                        <span>Người Nhận đồ</span>
                    </button>
                    <button type="button"
                            @click="activeTab = 'raffle'"
                            :class="activeTab === 'raffle' ? 'bg-white dark:bg-gray-700 text-teal-600 dark:text-teal-400 shadow-sm font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium'"
                            class="px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 cursor-pointer flex items-center gap-2">
                        <span>🎰</span>
                        <span>Quay số may mắn</span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: Giver Flow -->
            <div x-show="activeTab === 'giver'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 mb-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-teal-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-teal-500/30">
                            1
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Đăng tin tặng đồ</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Chụp từ 1-5 tấm ảnh rõ nét, ghi mô tả tình trạng đồ dùng (mới, đã qua sử dụng), chọn địa điểm (Tỉnh/Quận) và hình thức (Tặng miễn phí, Đổi đồ hoặc Quay thưởng).
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-teal-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-teal-500/30">
                            2
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Chờ duyệt tin</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Tin đăng sẽ được đưa vào trạng thái <span class="font-semibold text-amber-600 dark:text-amber-400">Chờ duyệt</span>. Đội ngũ Admin kiểm duyệt nhanh chóng để bảo vệ cộng đồng khỏi hàng cấm và spam.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-teal-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-teal-500/30">
                            3
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Chọn người nhận & Chat</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Đọc lời nhắn từ những người xin đồ trên Dashboard. Bạn có toàn quyền chọn người phù hợp nhất và bấm <strong class="text-teal-600">"Chấp nhận"</strong> để mở phòng chat thống nhất cách thức nhận đồ (đến lấy trực tiếp hoặc bàn giao cho shipper/đơn vị vận chuyển do người nhận tự đặt).
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-emerald-500/30">
                            4
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Bàn giao & Nhận +15 Karma</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Sau khi trao đồ (trực tiếp hoặc đưa cho shipper của người nhận), bấm nút <span class="font-semibold text-emerald-600">"Đã bàn giao"</span> trong phòng chat. Bạn nhận ngay <strong>+15 điểm Karma</strong> và nhận đánh giá sao từ người nhận!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Receiver Flow -->
            <div x-show="activeTab === 'receiver'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 mb-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-emerald-500/30">
                            1
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Tìm món đồ cần thiết</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Truy cập trang Tìm kiếm, lọc theo Danh mục hoặc chọn Tỉnh/Thành phố gần bạn để thuận tiện cho việc di chuyển đến nhận đồ hoặc thuê vận chuyển cước phí thấp.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-emerald-500/30">
                            2
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Gửi lời nhắn chân thành</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Bấm <span class="font-semibold text-teal-600">"Xin món đồ này"</span> và gửi một lời nhắn lịch sự, nêu rõ hoàn cảnh hoặc phương án nhận đồ (qua lấy trực tiếp hoặc tự đặt shipper) để chủ đồ thêm tin tưởng.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-emerald-500/30">
                            3
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Thống nhất hình thức nhận đồ</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Khi chủ đồ đồng ý, hai bên nhắn tin trong phòng chat để thống nhất: <strong>Đến lấy trực tiếp</strong> tại điểm hẹn công cộng, hoặc <strong>Người nhận tự thuê đơn vị vận chuyển / Shipper</strong> (Ahamove, Grab, Be,...) đến lấy đồ và tự thanh toán cước phí.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs relative overflow-hidden group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black flex items-center justify-center text-base mb-4 shadow-sm shadow-emerald-500/30">
                            4
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Nhận đồ & Đánh giá (+10 Karma)</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Khi nhận được đồ nguyên vẹn (nhận trực tiếp hoặc qua shipper), bấm <span class="font-semibold text-emerald-600">"Đã nhận được đồ"</span> và để lại đánh giá sao cảm ơn chủ đồ. Bạn sẽ được cộng <strong>+10 điểm Karma</strong>!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Raffle Flow -->
            <div x-show="activeTab === 'raffle'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 mb-20">
                <div class="p-8 rounded-3xl bg-gradient-to-r from-purple-500/10 via-pink-500/5 to-transparent dark:from-purple-950/30 dark:to-gray-800 border border-purple-200/60 dark:border-purple-900/40">
                    <div class="max-w-3xl">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <span>🎰</span>
                            <span>Cơ chế Vòng quay may mắn (Raffle) hoạt động thế nào?</span>
                        </h3>
                        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            <p>
                                Vòng quay may mắn dành cho những món đồ giá trị hoặc có quá nhiều người cùng muốn nhận. Thay vì chủ đồ phải khó xử lựa chọn, hệ thống sẽ quay thưởng hoàn toàn ngẫu nhiên và công bằng.
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-400">
                                <li><strong>Điều kiện tham gia:</strong> Mỗi món đồ quay thưởng có thể đặt mức yêu cầu <span class="text-purple-600 dark:text-purple-400 font-bold">"Cần n Karma"</span> (VD: Cần 30 Karma). Bạn phải có số điểm Karma bằng hoặc lớn hơn mức này để lấy vé.</li>
                                <li><strong>Nhận vé hoàn toàn miễn phí:</strong> Bạn không bị trừ điểm Karma khi tham gia, điểm Karma chỉ đóng vai trò là điều kiện uy tín chứng minh bạn là thành viên tích cực.</li>
                                <li><strong>Quay số minh bạch:</strong> Khi hết hạn hoặc đủ vé, chủ đồ bấm quay thưởng. Thuật toán ngẫu nhiên chọn ra người trúng giải và lập tức tạo phòng chat riêng để thống nhất giao nhận (trực tiếp hoặc gửi qua shipper).</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Safety Guidelines -->
            <div class="mb-20">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2 block">
                        An toàn & Văn hóa
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Nguyên tắc an toàn khi giao nhận đồ
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs">
                        <span class="text-3xl mb-3 block">📍</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Hẹn ở nơi công cộng</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Nếu gặp mặt trực tiếp: Ưu tiên nơi đông người như sảnh chung cư, quán cà phê, cổng trường học hoặc trung tâm thương mại vào ban ngày.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs">
                        <span class="text-3xl mb-3 block">🛵</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Thuê vận chuyển / Shipper</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Nếu thuê bên vận chuyển: Người nhận chủ động đặt xe và tự thanh toán cước phí cho tài xế; đồng thời báo trước biển số/thời gian cho chủ đồ.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs">
                        <span class="text-3xl mb-3 block">🚫</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Không chuyển tiền trước</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Mọi món đồ tặng trên Cho & Nhận là <strong>100% miễn phí</strong>. Tuyệt đối cảnh giác nếu có ai yêu cầu chuyển khoản đặt cọc tiền ship hoặc chi phí vô lý.
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xs">
                        <span class="text-3xl mb-3 block">⏰</span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-2">Đúng giờ & Tôn trọng</h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Đến đúng giờ hẹn (hoặc theo dõi sát lộ trình shipper). Nếu có trục trặc đột xuất, hãy nhắn tin báo trước cho đối phương để không phải chờ đợi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mb-16">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-2 block">
                        Giải đáp thắc mắc
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Câu hỏi thường gặp (FAQ)
                    </h2>
                </div>

                <div class="max-w-3xl mx-auto space-y-3">
                    <!-- Q1 -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/70 dark:border-gray-700 overflow-hidden transition">
                        <button type="button" 
                                @click="activeFaq = (activeFaq === 1 ? null : 1)"
                                class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Tham gia Cho & Nhận có mất khoản phí nào không?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="activeFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeFaq === 1" x-cloak x-transition class="px-6 pb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700/60 pt-3">
                            Hoàn toàn không. Cho & Nhận là nền tảng phi lợi nhuận phục vụ cộng đồng. Việc đăng tin, nhận đồ, nhắn tin hay tham gia quay thưởng may mắn đều hoàn toàn miễn phí.
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/70 dark:border-gray-700 overflow-hidden transition">
                        <button type="button" 
                                @click="activeFaq = (activeFaq === 2 ? null : 2)"
                                class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Tôi có thể thuê shipper hoặc dịch vụ vận chuyển để đến lấy đồ thay vì đến trực tiếp không?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="activeFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeFaq === 2" x-cloak x-transition class="px-6 pb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700/60 pt-3">
                            <strong>Hoàn toàn được!</strong> Nếu bạn ở xa hoặc không tiện di chuyển, người nhận có thể chủ động đặt dịch vụ vận chuyển (GrabExpress, Ahamove, BeDelivery, v.v...) đến địa điểm của chủ đồ để lấy hàng. Lưu ý:
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Người nhận cần nhắn tin hỏi và thống nhất trước với chủ đồ về thời gian shipper tới lấy.</li>
                                <li>Cung cấp số điện thoại hoặc thông tin đơn hàng cho chủ đồ nhận diện đúng tài xế.</li>
                                <li>Người nhận tự thanh toán 100% chi phí vận chuyển trực tiếp cho shipper/ứng dụng, không yêu cầu chủ đồ trả phí.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/70 dark:border-gray-700 overflow-hidden transition">
                        <button type="button" 
                                @click="activeFaq = (activeFaq === 3 ? null : 3)"
                                class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Làm thế nào để kiếm thêm điểm Karma?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="activeFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeFaq === 3" x-cloak x-transition class="px-6 pb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700/60 pt-3">
                            Bạn được tặng sẵn 10 Karma khi đăng ký tài khoản. Bạn có thể gia tăng Karma bằng cách:
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li><strong>+15 Karma</strong> mỗi lần đăng tặng đồ và bàn giao thành công.</li>
                                <li><strong>+10 Karma</strong> mỗi lần viết đánh giá sau khi hoàn tất giao dịch.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/70 dark:border-gray-700 overflow-hidden transition">
                        <button type="button" 
                                @click="activeFaq = (activeFaq === 4 ? null : 4)"
                                class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Tại sao tin đăng của tôi lại bị từ chối?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="activeFaq === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeFaq === 4" x-cloak x-transition class="px-6 pb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700/60 pt-3">
                            Tin đăng có thể bị từ chối nếu: ảnh bị mờ hoặc vi phạm bản quyền; thuộc danh mục hàng hóa cấm (thuốc kê đơn, thực phẩm hỏng, chất nguy hiểm); hoặc chứa nội dung quảng cáo thương mại. Lý do từ chối cụ thể sẽ hiển thị trên Dashboard của bạn.
                        </div>
                    </div>

                    <!-- Q5 -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/70 dark:border-gray-700 overflow-hidden transition">
                        <button type="button" 
                                @click="activeFaq = (activeFaq === 5 ? null : 5)"
                                class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 cursor-pointer">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Nếu đối phương bùng kèo không tới thì xử lý thế nào?</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="activeFaq === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeFaq === 5" x-cloak x-transition class="px-6 pb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700/60 pt-3">
                            Nếu đối phương không tới hoặc đổi ý, chủ đồ chỉ cần bấm nút <strong>"Hủy giao dịch"</strong> trong phòng chat. Món đồ sẽ lập tức mở lại trạng thái "Có sẵn" để bạn chọn người nhận khác mà không bị ảnh hưởng đến điểm uy tín.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
