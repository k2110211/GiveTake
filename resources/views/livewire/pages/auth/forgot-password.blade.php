<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    {{-- Heading --}}
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Quên mật khẩu?</h2>
        <p class="text-sm text-gray-500 mt-1 leading-relaxed">
            Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu.
        </p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-teal-50 border border-teal-200 text-sm text-teal-700 font-medium flex items-start gap-2">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Địa chỉ email
            </label>
            <input wire:model="email" id="email" type="email" name="email"
                   required autofocus
                   placeholder="email@example.com"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('email')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 px-6 rounded-xl text-sm font-bold text-white
                       bg-gradient-to-r from-emerald-500 to-teal-600
                       hover:from-emerald-600 hover:to-teal-700
                       shadow-md hover:shadow-lg
                       transition-all duration-200 active:scale-[0.98]">
            Gửi link đặt lại mật khẩu
        </button>
    </form>

    {{-- Back to login --}}
    <p class="mt-6 text-center text-sm text-gray-500">
        Nhớ ra rồi?
        <a href="{{ route('login') }}" wire:navigate
           class="text-teal-600 font-semibold hover:text-teal-700 hover:underline ml-1">
            Quay lại đăng nhập
        </a>
    </p>
</div>
