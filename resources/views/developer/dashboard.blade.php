<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="glass-card p-8 text-center">
            @if(auth()->user()->is_rejected)
                <!-- Rejected State -->
                <div class="w-20 h-20 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>

                <h1 class="font-heading text-2xl font-bold text-white">Verification Rejected</h1>
                <p class="text-gray-400 mt-3 max-w-md mx-auto">Your developer account verification was rejected. You can review the reason and request verification again.</p>

                @if(auth()->user()->rejection_reason)
                    <div class="mt-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-left">
                        <h3 class="font-heading text-sm font-semibold text-red-400 mb-2">Rejection Reason:</h3>
                        <p class="text-gray-300 text-sm">{{ auth()->user()->rejection_reason }}</p>
                    </div>
                @endif

                <form action="{{ route('developer.request-verification') }}" method="POST" class="mt-8">
                    @csrf
                    <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-white font-medium">
                        Request Verification Again
                    </button>
                </form>
            @else
                <!-- Pending State -->
                <div class="w-20 h-20 rounded-full bg-yellow-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>

                <h1 class="font-heading text-2xl font-bold text-white">Account Pending Verification</h1>
                <p class="text-gray-400 mt-3 max-w-md mx-auto">Your developer account is under review. An admin will verify your account shortly.</p>

                <div class="mt-8 p-4 rounded-lg bg-white/5 border border-white/10 text-left">
                    <h3 class="font-heading text-sm font-semibold text-gray-300 mb-2">What you can do now:</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Browse and purchase games as a player
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Top up your wallet balance
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                            Publish games (waiting for verification)
                        </li>
                    </ul>
                </div>
            @endif

            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Registered: {{ auth()->user()->created_at->format('M d, Y H:i') }}
            </div>

            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('store.index') }}" class="btn-primary px-6 py-3 rounded-xl text-white font-medium">
                    Browse Store
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
