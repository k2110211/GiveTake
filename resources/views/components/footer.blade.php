<footer class="bg-gray-900 text-gray-400 mt-auto">
    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            <!-- Brand -->
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="/" class="flex items-center space-x-2 mb-4 group">
                    <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:shadow-teal-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-tight">GiveTake</span>
                </a>
                <p class="text-sm text-gray-500 leading-relaxed mb-4 max-w-xs">
                    Nền tảng trao đổi & tặng đồ dùng cũ cho cộng đồng. Cho đi là nhận lại, cùng xây dựng lối sống bền vững.
                </p>
                <div class="flex space-x-3">
                    <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-teal-600 flex items-center justify-center transition-colors duration-200" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-teal-600 flex items-center justify-center transition-colors duration-200" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-teal-600 flex items-center justify-center transition-colors duration-200" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Liên kết nhanh</h4>
                <ul class="space-y-2.5">
                    <li><a href="/" class="text-sm hover:text-teal-400 transition-colors">Trang chủ</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-sm hover:text-teal-400 transition-colors">Bảng quản trị</a></li>
                        <li><a href="{{ route('item.create') }}" class="text-sm hover:text-teal-400 transition-colors">Đăng tin</a></li>
                    @else
                        <li><a href="{{ route('register') }}" class="text-sm hover:text-teal-400 transition-colors">Đăng ký</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm hover:text-teal-400 transition-colors">Đăng nhập</a></li>
                    @endauth
                </ul>
            </div>

            <!-- About -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Về GiveTake</h4>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm hover:text-teal-400 transition-colors">Giới thiệu</a></li>
                    <li><a href="#" class="text-sm hover:text-teal-400 transition-colors">Hướng dẫn sử dụng</a></li>
                    <li><a href="#" class="text-sm hover:text-teal-400 transition-colors">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-sm hover:text-teal-400 transition-colors">Điều khoản dịch vụ</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">Liên hệ</h4>
                <ul class="space-y-2.5">
                    <li class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-teal-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        contact@givetake.vn
                    </li>
                    <li class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-teal-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        TP. Hồ Chí Minh, Việt Nam
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-xs text-gray-600">
                &copy; {{ date('Y') }} GiveTake. Tất cả quyền được bảo lưu.
            </p>
            <p class="text-xs text-gray-600">
                Made with <span class="text-rose-500">♥</span> for the community
            </p>
        </div>
    </div>
</footer>
