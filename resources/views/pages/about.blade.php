<x-app-layout>
    <div class="py-10 sm:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
                <a href="/" wire:navigate class="hover:text-teal-600 dark:hover:text-teal-400 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Giới thiệu</span>
            </nav>

            <!-- Hero Section -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-500/10 via-emerald-500/5 to-transparent dark:from-teal-950/30 dark:via-emerald-950/20 dark:to-gray-900 border border-teal-200/60 dark:border-teal-900/40 p-8 sm:p-14 mb-16 shadow-xs">
                <div class="max-w-3xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 dark:bg-teal-950/70 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 mb-5">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Về Cho & Nhận
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight leading-tight mb-6">
                        Trao yêu thương, Nhận nụ cười — Cùng tạo dựng lối sống bền vững
                    </h1>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed font-normal mb-8">
                        Cho & Nhận ra đời từ niềm tin giản dị: <strong class="text-teal-600 dark:text-teal-400">Một món đồ bạn không còn dùng đến có thể là niềm vui to lớn của một người khác.</strong> Chúng tôi xây dựng một cầu nối nhân văn, an toàn và thông minh để mọi người trao tặng, đổi đồ cũ và cùng nhau giảm thiểu rác thải tiêu dùng.
                    </p>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('search') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white text-sm font-bold shadow-md shadow-teal-500/20 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <span>Khám phá món đồ</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('item.create') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:text-teal-600 dark:hover:text-teal-400 text-sm font-bold border border-gray-200 dark:border-gray-700 hover:border-teal-400 dark:hover:border-teal-500 shadow-xs transition-all duration-200">
                            Đăng tặng đồ ngay
                        </a>
                    </div>
                </div>
            </div>

            <!-- Story & Vision -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-2 block">
                        Câu chuyện của chúng tôi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-4">
                        Biến đồ dư thừa thành giá trị sẻ chia ý nghĩa
                    </h2>
                    <div class="space-y-4 text-sm sm:text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                        <p>
                            Mỗi năm, hàng triệu món đồ gia dụng, sách vở, quần áo, thiết bị công nghệ vẫn còn dùng rất tốt nhưng bị lãng quên trong kho hoặc bị vứt bỏ lãng phí. Trong khi đó, nhiều sinh viên, gia đình trẻ hay những hoàn cảnh khó khăn lại đang rất cần những vật dụng ấy cho cuộc sống sinh hoạt hằng ngày.
                        </p>
                        <p>
                            Cho & Nhận tạo ra không gian kết nối trực tiếp giữa hai bên: không vì mục đích thương mại, không đặt nặng giá trị vật chất, mà đề cao sự tử tế, minh bạch và tinh thần trách nhiệm với môi trường sống.
                        </p>
                        <p>
                            Bằng cách ứng dụng cơ chế điểm thưởng **Karma** và **Điểm tín nhiệm (Trust Score)**, chúng tôi tạo nên một môi trường văn minh, nơi người cho đi được ghi nhận xứng đáng và người nhận trân trọng giá trị được trao.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-teal-100 dark:border-teal-900/30 shadow-xs">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-2xl mb-4 shadow-inner">
                            🌍
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Kinh tế tuần hoàn</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Kéo dài vòng đời vật dụng, trực tiếp cắt giảm dấu chân carbon và rác thải đô thị.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-emerald-100 dark:border-emerald-900/30 shadow-xs">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mb-4 shadow-inner">
                            🤝
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Gắn kết cộng đồng</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Kết nối những người hàng xóm cùng khu vực, trao tặng bằng nụ cười thân thiện.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-amber-100 dark:border-amber-900/30 shadow-xs">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-4 shadow-inner">
                            ⭐
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Lòng tin & Uy tín</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Đánh giá sao 1-5 và điểm Karma minh bạch, nói không với hành vi trục lợi.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white dark:bg-gray-800 border border-indigo-100 dark:border-indigo-900/30 shadow-xs">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl mb-4 shadow-inner">
                            🛡️
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-1">Kiểm duyệt an toàn</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Tin đăng được đội ngũ kiểm duyệt kỹ lưỡng trước khi hiển thị công khai.</p>
                    </div>
                </div>
            </div>

            <!-- Core Values -->
            <div class="mb-20">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-2 block">
                        Giá trị cốt lõi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Kim chỉ nam trong mọi hoạt động của Cho & Nhận
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm hover:shadow-md transition">
                        <span class="text-3xl mb-4 block">🌱</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Bền vững & Tiết kiệm</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Chúng tôi tin rằng tái sử dụng là hành động thiết thực nhất để bảo vệ hành tinh. Không mua sắm dư thừa, ưu tiên trao tặng đồ còn tốt.
                        </p>
                    </div>

                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm hover:shadow-md transition">
                        <span class="text-3xl mb-4 block">✨</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Tử tế & Chân thành</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Mỗi lời nhắn, mỗi buổi gặp mặt trao đồ là dịp để lan tỏa sự ấm áp. Tôn trọng thời gian, giữ lời hứa và trò chuyện lịch thiệp là nét đẹp văn hóa tại Cho & Nhận.
                        </p>
                    </div>

                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm hover:shadow-md transition">
                        <span class="text-3xl mb-4 block">🎯</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Công bằng & Đúng người</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Với tính năng Vòng quay may mắn (Raffle) và xét duyệt yêu cầu minh bạch, đồ dùng sẽ đến được tay những người xứng đáng và cần thiết nhất.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Impact Numbers -->
            <div class="rounded-3xl bg-gradient-to-r from-gray-900 to-gray-800 text-white p-8 sm:p-12 mb-20 shadow-xl">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-gray-700/60">
                    <div class="pt-4 md:pt-0">
                        <p class="text-3xl sm:text-4xl font-extrabold text-emerald-400 mb-1">10,000+</p>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Món đồ trao tặng</p>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <p class="text-3xl sm:text-4xl font-extrabold text-teal-400 mb-1">25,000+</p>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Điểm Karma tích lũy</p>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <p class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1">8,500+</p>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Thành viên tích cực</p>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <p class="text-3xl sm:text-4xl font-extrabold text-sky-400 mb-1">63</p>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Tỉnh thành kết nối</p>
                    </div>
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="text-center bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-8 sm:p-12 shadow-xs">
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 mb-3">
                    Bạn đã sẵn sàng cùng Cho & Nhận sẻ chia?
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl mx-auto mb-6">
                    Tham gia cộng đồng ngay hôm nay. Bắt đầu bằng việc kiểm tra lại tủ đồ của bạn và mang lại nụ cười cho một ai đó!
                </p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('register') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-sm transition">
                        Đăng ký tài khoản miễn phí
                    </a>
                    <a href="{{ route('guide') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-sm transition">
                        Xem hướng dẫn sử dụng
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
