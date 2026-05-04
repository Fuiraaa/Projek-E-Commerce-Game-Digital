<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="font-heading text-3xl font-bold text-white neon-text">Wallet</h1>
            <p class="text-gray-400 mt-1">Manage your balance</p>
        </div>

        <!-- Balance Card -->
        <div class="glass-card p-8 mb-6 text-center">
            <p class="text-gray-400 mb-2">Current Balance</p>
            <p class="text-4xl font-bold text-neon-cyan">Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}</p>
        </div>

        <!-- Top Up Form -->
        <div class="glass-card p-6">
            <h2 class="font-heading text-xl font-bold text-white mb-4">Top Up Balance</h2>
            <form action="{{ route('wallet.topup') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Amount (Rp)</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="1000" max="10000000" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors" placeholder="Minimum Rp 1,000" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-medium">
                        Top Up Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
