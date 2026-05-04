<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-neon-blue focus:ring-neon-blue" name="remember">
                <span class="ms-2 text-sm text-gray-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-400 hover:text-white transition-colors" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-400 hover:text-white transition-colors" href="{{ route('register') }}">
                Don't have an account?
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-white font-medium">
                Log in
            </button>
        </div>
    </form>
</x-guest-layout>
