<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="font-heading text-3xl font-bold text-white neon-text">Profile</h1>
            <p class="text-gray-400 mt-1">Manage your account settings</p>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="glass-card p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="glass-card p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
