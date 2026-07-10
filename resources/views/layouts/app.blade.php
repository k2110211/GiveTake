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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="GiveTake – Nền tảng trao đổi & tặng đồ dùng cũ cho cộng đồng. Cho đi là nhận lại!">

        <title>{{ config('app.name', 'GiveTake') }}</title>

        <!-- Fonts: Be Vietnam Pro -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-footer />
        </div>

        <!-- Toast Notification Container -->
        <div id="toast-container" class="toast-container"></div>

        <!-- Global JS: Toast System + Scroll Reveal -->
        <script>
            // ── Dark Mode Switcher System ──
            window.toggleTheme = function() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                // Dispatch custom event to notify Alpine components
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: !isDark } }));
            };

            // ── Toast Notification System ──
            window.showToast = function(message, type = 'success', duration = 4000) {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const icons = {
                    success: '<svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    error: '<svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    info: '<svg class="w-5 h-5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                };

                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    ${icons[type] || icons.info}
                    <div class="flex-1">
                        <p class="text-sm font-semibold">${message}</p>
                    </div>
                    <button onclick="this.closest('.toast').classList.add('toast-exit'); setTimeout(() => this.closest('.toast').remove(), 400)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0 ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                `;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 400);
                }, duration);
            };

            // Fire toasts from Livewire events
            document.addEventListener('livewire:navigated', () => {
                // Re-init scroll reveals after Livewire navigation
                initScrollReveal();
            });

            // Re-init scroll reveals after Livewire dynamic DOM updates
            document.addEventListener('livewire:update', () => {
                initScrollReveal();
            });

            // Periodic backup check to find any new unobserved reveal elements
            setInterval(() => {
                initScrollReveal();
            }, 1000);

            // ── Scroll Reveal System ──
            function initScrollReveal() {
                const reveals = document.querySelectorAll('.reveal:not(.revealed)');
                if (!reveals.length) return;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, idx) => {
                        if (entry.isIntersecting) {
                            setTimeout(() => {
                                entry.target.classList.add('revealed');
                            }, idx * 80);
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

                reveals.forEach(el => observer.observe(el));
            }

            // ── Animated Counter ──
            window.animateCounter = function(el, target, duration = 1500) {
                let start = 0;
                const startTime = performance.now();
                const step = (now) => {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                    const current = Math.floor(eased * target);
                    el.textContent = current.toLocaleString('vi-VN');
                    if (progress < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            // Init on DOM ready
            document.addEventListener('DOMContentLoaded', initScrollReveal);
        </script>

        @stack('scripts')
    </body>
</html>
