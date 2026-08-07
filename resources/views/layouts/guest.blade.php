<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GiveTake') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased bg-gray-50">

<div class="min-h-screen flex">

    {{-- ── LEFT PANEL: Branding ─────────────────────────────────────────── --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] relative overflow-hidden
                bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600
                flex-col items-center justify-center p-12 text-white">

        {{-- Decorative blobs --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-cyan-400/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-300/10 rounded-full blur-3xl"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-md text-center">
            {{-- Logo mark --}}
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-sm shadow-xl mb-8">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>

            <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                Give & Take
            </h1>
            <p class="text-emerald-100 text-base xl:text-lg leading-relaxed mb-10">
                Cho đi những gì bạn không dùng,<br>
                nhận lại những gì bạn cần từ cộng đồng.
            </p>

            {{-- Feature bullets --}}
            <div class="space-y-4 text-left">
                @foreach([
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'text' => 'Kết nối cộng đồng yêu thích chia sẻ'],
                    ['icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'text' => 'Hệ thống Karma & Trust Score'],
                    ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'text' => 'Chat trực tiếp giữa người dùng'],
                ] as $feat)
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-2xl px-4 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-white/90">{{ $feat['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL: Form ────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white lg:px-12 xl:px-16">

        {{-- Mobile logo (only on small screens) --}}
        <div class="lg:hidden mb-8 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-extrabold text-gray-900">Give & Take</h2>
        </div>

        {{-- Back to home link --}}
        <div class="w-full max-w-md mb-6">
            <a href="{{ route('home') }}" wire:navigate
               class="inline-flex items-center text-xs text-gray-400 hover:text-teal-600 transition-colors">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Về trang chủ
            </a>
        </div>

        {{-- Slot (form content) --}}
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
</body>
</html>
