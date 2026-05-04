<section class="space-y-4">
    <header>
        <h2 class="font-heading text-lg font-semibold text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors font-medium"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="glass-card p-6">
            <h2 class="font-heading text-lg font-semibold text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                @csrf
                @method('delete')

                <div>
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="{{ __('Password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/30 text-white hover:bg-red-500/40 transition-colors font-medium">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
