<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Heading --}}
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tạo tài khoản</h2>
        <p class="text-sm text-gray-500 mt-1">Tham gia cộng đồng Give & Take miễn phí 🌿</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Họ và tên
            </label>
            <input wire:model="name" id="name" type="text" name="name"
                   required autofocus autocomplete="name"
                   placeholder="Nguyễn Văn A"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('name')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Email
            </label>
            <input wire:model="email" id="email" type="email" name="email"
                   required autocomplete="username"
                   placeholder="you@example.com"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('email')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Mật khẩu
            </label>
            <input wire:model="password" id="password" type="password" name="password"
                   required autocomplete="new-password"
                   placeholder="Tối thiểu 8 ký tự"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('password')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Xác nhận mật khẩu
            </label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password"
                   name="password_confirmation" required autocomplete="new-password"
                   placeholder="Nhập lại mật khẩu"
                   class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-3
                          focus:border-teal-500 focus:ring focus:ring-teal-200 focus:bg-white
                          transition-all placeholder-gray-300">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Terms note --}}
        <p class="text-xs text-gray-400 leading-relaxed">
            Bằng cách đăng ký, bạn đồng ý với
            <span class="text-teal-600 font-medium">điều khoản sử dụng</span>
            và <span class="text-teal-600 font-medium">chính sách bảo mật</span> của chúng tôi.
        </p>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3 px-6 rounded-xl text-sm font-bold text-white
                       bg-gradient-to-r from-emerald-500 to-teal-600
                       hover:from-emerald-600 hover:to-teal-700
                       shadow-md hover:shadow-lg
                       transition-all duration-200 active:scale-[0.98]">
            Tạo tài khoản
        </button>
    </form>

    {{-- Login link --}}
    <p class="mt-6 text-center text-sm text-gray-500">
        Đã có tài khoản?
        <a href="{{ route('login') }}" wire:navigate
           class="text-teal-600 font-semibold hover:text-teal-700 hover:underline ml-1">
            Đăng nhập
        </a>
    </p>
</div>
