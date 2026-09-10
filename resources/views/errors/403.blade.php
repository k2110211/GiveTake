@extends('errors.layout')

@section('title', '403 - Quyền truy cập bị từ chối')

@section('content')
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200/80 dark:border-amber-800/60 mb-6 text-amber-600 dark:text-amber-400">
        <span class="text-4xl">🛡️</span>
    </div>

    <h1 class="text-6xl font-black tracking-tight text-amber-600 dark:text-amber-400 mb-2">
        403
    </h1>

    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight mb-3">
        Khu vực giới hạn truy cập
    </h2>

    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 leading-relaxed mb-8 max-w-md mx-auto">
        Tài khoản của bạn không có đủ thẩm quyền để xem nội dung này, hoặc phiên truy cập đã hết hạn.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-3">
        <a href="/" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white text-sm font-bold shadow-md shadow-teal-500/20 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Về trang chủ</span>
        </a>
        <a href="{{ route('login') }}" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:text-teal-600 dark:hover:text-teal-400 text-sm font-bold border border-gray-200 dark:border-gray-700 hover:border-teal-400 dark:hover:border-teal-500 shadow-xs transition-all duration-200">
            Đăng nhập lại
        </a>
    </div>
@endsection
