@extends('errors.layout')

@section('title', '500 - Lỗi máy chủ tạm thời')

@section('content')
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200/80 dark:border-rose-800/60 mb-6 text-rose-600 dark:text-rose-400">
        <span class="text-4xl">⚡</span>
    </div>

    <h1 class="text-6xl font-black tracking-tight text-rose-600 dark:text-rose-400 mb-2">
        500
    </h1>

    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-3">
        Sự cố kết nối máy chủ
    </h2>

    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 leading-relaxed mb-8 max-w-md mx-auto">
        Hệ thống đang được bảo trì hoặc gặp sự cố xử lý dữ liệu tạm thời. Đội ngũ kỹ thuật đã được thông báo và đang khắc phục.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3">
        <button onclick="window.location.reload()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white text-sm font-bold shadow-md shadow-teal-500/20 hover:shadow-lg transition-all duration-200 flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Tải lại trang</span>
        </button>
        <a href="/" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:text-teal-600 dark:hover:text-teal-400 text-sm font-bold border border-gray-200 dark:border-gray-700 hover:border-teal-400 dark:hover:border-teal-500 shadow-xs transition-all duration-200">
            Về trang chủ
        </a>
    </div>
@endsection
