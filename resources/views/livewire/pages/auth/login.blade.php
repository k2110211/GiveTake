<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        if ($user && $user->is_admin) {
            $this->redirect(route('admin.dashboard'), navigate: true);
            return;
        }

        $this->redirectIntended(default: route('home'), navigate: true);
    }
}; ?>

<div>
    {{-- Heading --}}
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Đăng nhập</h2>
        <p class="text-sm text-gray-500 mt-1">Chào mừng bạn trở lại! 👋</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-teal-50 border border-teal-200 text-sm text-teal-700 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Email
            </label>
            <input wire:model="form.email" id="email" type="email" name="email"
                   required autofocus autocomplete="username"
                   placeholder="email@example.com"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('form.email')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                    Mật khẩu
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-xs text-teal-600 hover:text-teal-700 font-medium hover:underline">
                        Quên mật khẩu?
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password"
                   required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('form.password')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-gray-300 text-teal-600
                          focus:ring-teal-500 focus:ring-offset-0">
            <label for="remember" class="ml-2 text-sm text-gray-600 select-none">
                Ghi nhớ đăng nhập
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 px-6 rounded-xl text-sm font-bold text-white
                       bg-gradient-to-r from-emerald-500 to-teal-600
                       hover:from-emerald-600 hover:to-teal-700
                       shadow-md hover:shadow-lg
                       transition-all duration-200 active:scale-[0.98]">
            Đăng nhập
        </button>
    </form>

    {{-- Register link --}}
    <p class="mt-6 text-center text-sm text-gray-500">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" wire:navigate
           class="text-teal-600 font-semibold hover:text-teal-700 hover:underline ml-1">
            Đăng ký ngay
        </a>
    </p>
</div>
