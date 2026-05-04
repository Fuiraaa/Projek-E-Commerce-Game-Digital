<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('Register as')" />
            <div class="grid grid-cols-2 gap-3 mt-2">
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="player" class="peer sr-only" checked>
                    <div class="p-4 rounded-lg border border-white/10 bg-white/5 peer-checked:border-neon-blue peer-checked:bg-neon-blue/10 transition-all text-center">
                        <svg class="w-8 h-8 mx-auto text-gray-400 peer-checked:text-neon-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="mt-2 text-sm font-medium text-gray-300 peer-checked:text-neon-blue">Player</p>
                        <p class="text-xs text-gray-500">Browse & Buy Games</p>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="developer" class="peer sr-only">
                    <div class="p-4 rounded-lg border border-white/10 bg-white/5 peer-checked:border-neon-violet peer-checked:bg-neon-violet/10 transition-all text-center">
                        <svg class="w-8 h-8 mx-auto text-gray-400 peer-checked:text-neon-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        <p class="mt-2 text-sm font-medium text-gray-300 peer-checked:text-neon-violet">Developer</p>
                        <p class="text-xs text-gray-500">Sell Your Games</p>
                    </div>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Developers require admin verification before publishing games.</p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-400 hover:text-white transition-colors" href="{{ route('login') }}">
                Already registered?
            </a>
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-white font-medium">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
