<x-app-layout>
    <div class="py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
                <a href="/" wire:navigate class="hover:text-teal-600 dark:hover:text-teal-400 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Điều khoản dịch vụ</span>
            </nav>

            <!-- Header -->
            <div class="border-b border-gray-200 dark:border-gray-800 pb-8 mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 dark:bg-teal-950/70 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 mb-3">
                    📜 Quy chuẩn cộng đồng
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-3">
                    Điều khoản sử dụng dịch vụ
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Cập nhật lần cuối: Ngày 09 tháng 09 năm 2026 • Vui lòng đọc kỹ trước khi tham gia nền tảng
                </p>
            </div>

            <!-- Content -->
            <div class="space-y-10 text-gray-600 dark:text-gray-300 text-sm sm:text-base leading-relaxed">
                
                <!-- Section 1 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">1</span>
                        <span>Nguyên tắc hoạt động phi thương mại</span>
                    </h2>
                    <p class="mb-3">
                        Cho & Nhận là nền tảng hoạt động vì cộng đồng nhằm kết nối người cho và người nhận đồ dùng cũ. Khi tham gia Cho & Nhận, bạn đồng ý tuân thủ nguyên tắc:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-sm">
                        <li><strong>Không mua bán trục lợi:</strong> Mọi tin đăng cho tặng phải là miễn phí 100%. Nghiêm cấm hành vi lợi dụng nền tảng để bán hàng, ép người nhận trả phí, hoặc xin đồ về để kinh doanh lại.</li>
                        <li><strong>Trao đổi công bằng:</strong> Đối với hình thức Trao đổi đồ, hai bên tự nguyện thỏa thuận việc hoán đổi đồ dùng tương xứng mà không phát sinh ép buộc hay gian lận.</li>
                    </ul>
                </section>

                <!-- Section 2: Prohibited Items -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 flex items-center justify-center text-xs font-bold">2</span>
                        <span>Danh mục hàng hóa NGHIÊM CẤM trao tặng</span>
                    </h2>
                    <p class="mb-3">
                        Để đảm bảo an toàn tính mạng, sức khỏe và tuân thủ pháp luật, các vật phẩm sau đây <strong>tuyệt đối không được phép</strong> đăng tải trên Cho & Nhận:
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/30 text-xs text-rose-900 dark:text-rose-300">
                            <strong>🚫 Vũ khí & Chất nguy hiểm:</strong> Súng, đao, kiếm, pháo nổ, hóa chất độc hại, chất dễ cháy nổ.
                        </div>
                        <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/30 text-xs text-rose-900 dark:text-rose-300">
                            <strong>🚫 Dược phẩm & Thuốc:</strong> Thuốc điều trị bệnh theo đơn, vắc-xin, chất kích thích, thuốc lá điện tử.
                        </div>
                        <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/30 text-xs text-rose-900 dark:text-rose-300">
                            <strong>🚫 Động vật hoang dã:</strong> Các loài thú cưng cấm nuôi nhốt, sản phẩm từ ngà voi, sừng tê giác, da động vật quý hiếm.
                        </div>
                        <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/30 text-xs text-rose-900 dark:text-rose-300">
                            <strong>🚫 Thực phẩm hết hạn:</strong> Đồ ăn thức uống đã quá hạn sử dụng, thực phẩm biến chất có nguy cơ ngộ độc.
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        * Mọi hành vi cố tình đăng tải các sản phẩm trong danh mục cấm sẽ bị xóa bài ngay lập tức và khóa tài khoản vĩnh viễn không cần báo trước.
                    </p>
                </section>

                <!-- Section 3 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">3</span>
                        <span>Quy trình kiểm duyệt & Biện pháp xử lý</span>
                    </h2>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-sm">
                        <li><strong>Kiểm duyệt tiền kỳ:</strong> Tất cả tin đăng bắt buộc phải trải qua bước kiểm duyệt của Quản trị viên trước khi hiển thị công khai trên hệ thống nhằm bảo vệ quyền lợi người dùng.</li>
                        <li><strong>Xử lý gian lận:</strong> Hành vi spam tin, đăng tin giả mạo, quấy rối bằng ngôn từ thô tục trong phòng chat, hoặc không đến điểm hẹn nhiều lần sẽ bị trừ điểm uy tín, trừ điểm Karma hoặc khóa tài khoản (`is_banned`).</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">4</span>
                        <span>Miễn trừ trách nhiệm về chất lượng hàng hóa</span>
                    </h2>
                    <div class="p-4 rounded-2xl bg-amber-50/80 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 text-sm">
                        <p class="font-semibold text-amber-900 dark:text-amber-200 mb-1">
                            ⚠️ Lưu ý về đồ đã qua sử dụng:
                        </p>
                        <p class="text-amber-800 dark:text-amber-300/90 leading-relaxed text-xs sm:text-sm">
                            Hầu hết đồ dùng trên Cho & Nhận là đồ cũ đã qua sử dụng. Người nhận có quyền và nghĩa vụ kiểm tra kỹ tình trạng đồ trước khi mang về. Cho & Nhận đóng vai trò là nền tảng cầu nối thông tin và <strong>không chịu trách nhiệm pháp lý</strong> về chất lượng, độ bền, xuất xứ hay bảo hành của các món đồ do người dùng tự nguyện trao tặng nhau.
                        </p>
                    </div>
                </section>

                <!-- Section 5 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">5</span>
                        <span>Quy định giao nhận & Thuê đơn vị vận chuyển (Shipper)</span>
                    </h2>
                    <p class="mb-3">
                        Để tạo điều kiện thuận lợi nhất cho cộng đồng, Cho & Nhận hỗ trợ linh hoạt 2 hình thức giao nhận đồ:
                    </p>
                    <ul class="list-disc list-inside space-y-2 pl-2 text-sm leading-relaxed mb-3">
                        <li><strong>Gặp mặt trực tiếp:</strong> Hai bên hẹn gặp tại địa điểm công cộng an toàn (quán cafe, sảnh chung cư, cổng cơ quan, công viên) vào khung giờ ban ngày.</li>
                        <li><strong>Thuê shipper / Vận chuyển bên thứ ba:</strong> Người nhận có thể chủ động đặt dịch vụ giao hàng (Ahamove, GrabExpress, BeDelivery, v.v.) qua lấy đồ, với điều kiện đã trao đổi và được người tặng đồng ý về thời gian bàn giao.</li>
                    </ul>
                    <div class="p-4 rounded-2xl bg-teal-50/70 dark:bg-teal-950/30 border border-teal-200/60 dark:border-teal-900/40 text-xs sm:text-sm">
                        <p class="font-semibold text-teal-900 dark:text-teal-200 mb-1">
                            🛵 Trách nhiệm chi phí & rủi ro vận chuyển:
                        </p>
                        <p class="text-teal-800 dark:text-teal-300/90 leading-relaxed">
                            Toàn bộ cước phí thuê shipper do <strong>người nhận tự thanh toán</strong> 100% cho tài xế hoặc qua ứng dụng giao hàng. Nền tảng Cho & Nhận hoàn toàn phi lợi nhuận và không tham gia thu cước. Người nhận tự chịu trách nhiệm về rủi ro va đập, bể vỡ hoặc thất lạc trong quá trình shipper vận chuyển.
                        </p>
                    </div>
                </section>

                <!-- Section 6 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">6</span>
                        <span>Giải quyết tranh chấp & Khiếu nại</span>
                    </h2>
                    <p>
                        Nếu phát hiện thành viên có dấu hiệu lừa đảo, không trung thực hoặc vi phạm văn hóa cộng đồng, người dùng có thể gửi khiếu nại về ban quản trị qua địa chỉ <a href="mailto:support@chonhan.vn" class="text-teal-600 dark:text-teal-400 font-bold underline">support@chonhan.vn</a>. Chúng tôi sẽ xem xét lịch sử phòng chat, đánh giá mức độ vi phạm và đưa ra biện pháp xử lý thích đáng.
                    </p>
                </section>

            </div>

        </div>
    </div>
</x-app-layout>
