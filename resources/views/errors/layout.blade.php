<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Cho & Nhận</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts: Be Vietnam Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen flex flex-col justify-between selection:bg-teal-500 selection:text-white">

    <!-- Simple Header -->
    <header class="py-6 px-4 sm:px-8 border-b border-gray-100 dark:border-gray-800 bg-white/70 dark:bg-gray-900/70 backdrop-blur-md sticky top-0 z-10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2.5 group">
                <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="text-gray-900 dark:text-white font-extrabold text-lg tracking-tight">
                    Cho<span class="text-teal-500 dark:text-teal-400"> & </span>Nhận
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="/" class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                    ← Về trang chủ
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-16">
        <div class="max-w-lg w-full text-center">
            @yield('content')
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="py-6 px-4 border-t border-gray-100 dark:border-gray-800 text-center text-xs text-gray-400 dark:text-gray-500">
        &copy; {{ date('Y') }} Cho & Nhận. Nền tảng chia sẻ đồ dùng cộng đồng phi lợi nhuận.
    </footer>

</body>
</html>
