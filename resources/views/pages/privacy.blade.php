<x-app-layout>
    <div class="py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
                <a href="/" wire:navigate class="hover:text-teal-600 dark:hover:text-teal-400 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Chính sách bảo mật</span>
            </nav>

            <!-- Header -->
            <div class="border-b border-gray-200 dark:border-gray-800 pb-8 mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 dark:bg-teal-950/70 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 mb-3">
                    🔒 Bảo mật & Quyền riêng tư
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-3">
                    Chính sách bảo mật thông tin
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Cập nhật lần cuối: Ngày 09 tháng 09 năm 2026 • Áp dụng cho toàn bộ người dùng Cho & Nhận
                </p>
            </div>

            <!-- Content -->
            <div class="space-y-10 text-gray-600 dark:text-gray-300 text-sm sm:text-base leading-relaxed">
                
                <!-- Section 1 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">1</span>
                        <span>Mục đích thu thập thông tin</span>
                    </h2>
                    <p class="mb-3">
                        Cho & Nhận chỉ thu thập các thông tin cần thiết tối thiểu nhằm phục vụ cho việc vận hành nền tảng chia sẻ và kết nối cộng đồng, bao gồm:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-sm">
                        <li><strong>Thông tin tài khoản:</strong> Họ và tên, địa chỉ email, mật khẩu mã hóa để đăng nhập và xác thực danh tính.</li>
                        <li><strong>Thông tin tin đăng:</strong> Tên món đồ, hình ảnh chụp, mô tả tình trạng và phân loại sản phẩm.</li>
                        <li><strong>Vị trí khu vực:</strong> Tỉnh/Thành phố và Quận/Huyện do người dùng chọn để thuận tiện tìm kiếm theo khoảng cách.</li>
                        <li><strong>Nội dung trao đổi:</strong> Tin nhắn giữa hai bên trong phòng chat riêng và đánh giá sao sau khi hoàn tất giao dịch.</li>
                    </ul>
                </section>

                <!-- Section 2 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">2</span>
                        <span>Nguyên tắc bảo vệ dữ liệu vị trí & địa chỉ nhận đồ</span>
                    </h2>
                    <div class="p-4 rounded-2xl bg-teal-50/70 dark:bg-teal-950/30 border border-teal-200/60 dark:border-teal-900/40 text-sm mb-3">
                        <p class="font-semibold text-teal-900 dark:text-teal-200 mb-1">
                            🛡️ Cam kết không công khai địa chỉ nhà riêng:
                        </p>
                        <p class="text-teal-800 dark:text-teal-300/90 leading-relaxed">
                            Cho & Nhận chỉ hiển thị công khai thông tin khu vực ở cấp <strong>Tỉnh/Thành phố và Quận/Huyện</strong> trên tin đăng. Chúng tôi tuyệt đối không công khai số nhà, ngõ ngách hay định vị GPS cá nhân lên giao diện công cộng. Địa chỉ gặp mặt cụ thể hoặc địa chỉ cung cấp cho tài xế/shipper đến nhận đồ do hai bên tự thống nhất và bảo mật trong phòng chat riêng.
                        </p>
                    </div>
                </section>

                <!-- Section 3 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">3</span>
                        <span>Bảo mật & Lưu trữ an toàn</span>
                    </h2>
                    <p class="mb-3">
                        Chúng tôi áp dụng các tiêu chuẩn kỹ thuật hiện đại để bảo vệ an toàn cho tài khoản của bạn:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-sm">
                        <li>Mật khẩu người dùng được băm một chiều an toàn bằng thuật toán mã hóa <strong>Bcrypt</strong> theo tiêu chuẩn ngành, không ai (kể cả quản trị viên hệ thống) có thể xem được mật khẩu dạng văn bản thô.</li>
                        <li>Toàn bộ phiên truyền tải dữ liệu được bảo vệ qua giao thức kết nối mã hóa SSL/TLS (HTTPS).</li>
                        <li>Phòng chat giao dịch được giới hạn quyền truy cập: Chỉ duy nhất chủ sở hữu món đồ và người gửi yêu cầu mới có quyền truy cập vào phòng chat đó.</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">4</span>
                        <span>Cam kết không chia sẻ cho bên thứ ba</span>
                    </h2>
                    <p>
                        Cho & Nhận cam kết <strong>không bán, không cho thuê, không chia sẻ</strong> bất kỳ thông tin cá nhân hay hành vi của người dùng cho bên thứ ba vì mục đích tiếp thị, quảng cáo hay trục lợi tài chính. Dữ liệu chỉ được cung cấp khi có yêu cầu bằng văn bản hợp pháp từ cơ quan thực thi pháp luật có thẩm quyền theo quy định của pháp luật Việt Nam.
                    </p>
                </section>

                <!-- Section 5 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">5</span>
                        <span>Quyền kiểm soát của bạn</span>
                    </h2>
                    <p class="mb-3">
                        Bạn luôn có toàn quyền kiểm soát dữ liệu cá nhân của mình trên nền tảng:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2 text-sm">
                        <li>Bạn có thể xem và cập nhật thông tin họ tên, email hoặc đổi mật khẩu bất kỳ lúc nào tại trang <a href="{{ route('profile') }}" wire:navigate class="text-teal-600 dark:text-teal-400 font-bold underline">Hồ sơ cá nhân</a>.</li>
                        <li>Bạn có quyền xóa tin đăng của mình khi không còn nhu cầu trao tặng.</li>
                        <li>Bạn có quyền yêu cầu vô hiệu hóa hoặc xóa vĩnh viễn tài khoản cùng toàn bộ lịch sử liên quan bằng cách gửi yêu cầu về hộp thư hỗ trợ.</li>
                    </ul>
                </section>

                <!-- Section 6 -->
                <section>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 dark:bg-teal-950 text-teal-700 dark:text-teal-300 flex items-center justify-center text-xs font-bold">6</span>
                        <span>Thông tin liên hệ bộ phận bảo mật</span>
                    </h2>
                    <p>
                        Nếu bạn có bất kỳ câu hỏi, thắc mắc hoặc phát hiện nghi vấn liên quan đến an toàn thông tin tại Cho & Nhận, xin vui lòng liên hệ ngay với chúng tôi:
                    </p>
                    <div class="mt-3 p-4 rounded-2xl bg-gray-100 dark:bg-gray-800 text-sm space-y-1">
                        <p>📧 Email bảo mật: <a href="mailto:privacy@chonhan.vn" class="text-teal-600 dark:text-teal-400 font-semibold">privacy@chonhan.vn</a></p>
                        <p>🏢 Địa chỉ: TP. Hồ Chí Minh, Việt Nam</p>
                    </div>
                </section>

            </div>

        </div>
    </div>
</x-app-layout>
