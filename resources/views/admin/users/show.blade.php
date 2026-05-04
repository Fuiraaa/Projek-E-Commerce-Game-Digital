<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-2 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to User Management
            </a>
            <h1 class="font-heading text-3xl font-bold text-white neon-text">User Details</h1>
        </div>

        <!-- Profile Card -->
        <div class="glass-card p-6 mb-6">
            <div class="flex items-start gap-6">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-heading font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="font-heading text-xl font-bold text-white">{{ $user->name }}</h2>
                        @if($user->is_suspended)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Suspended</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Active</span>
                        @endif
                    </div>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">Registered: {{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('admin.users.toggle-suspend', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg {{ $user->is_suspended ? 'bg-green-500/20 text-green-400 hover:bg-green-500/30' : 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30' }} text-sm transition-colors">
                            {{ $user->is_suspended ? 'Unsuspend' : 'Suspend' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 text-sm transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @if($user->role === 'player')
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Games Purchased</p>
                    <p class="text-2xl font-bold text-neon-cyan mt-1">{{ $stats['games_purchased'] }}</p>
                </div>
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Current Balance</p>
                    <p class="text-2xl font-bold text-white mt-1">Rp {{ number_format($stats['current_balance'], 0, ',', '.') }}</p>
                </div>
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Total Spent</p>
                    <p class="text-2xl font-bold text-neon-violet mt-1">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                </div>
            @else
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Total Games</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $stats['total_games'] }}</p>
                </div>
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Active Games</p>
                    <p class="text-2xl font-bold text-neon-cyan mt-1">{{ $stats['active_games'] }}</p>
                </div>
                <div class="glass-card p-5">
                    <p class="text-sm text-gray-400">Total Revenue (95%)</p>
                    <p class="text-2xl font-bold text-neon-violet mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="glass-card p-6">
            <h3 class="font-heading text-lg font-semibold text-white mb-4">Recent Transactions</h3>
            @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="text-left py-3 px-3 text-sm font-medium text-gray-400">Game</th>
                                @if($user->role === 'developer')
                                    <th class="text-left py-3 px-3 text-sm font-medium text-gray-400">Player</th>
                                @endif
                                <th class="text-right py-3 px-3 text-sm font-medium text-gray-400">Amount</th>
                                <th class="text-center py-3 px-3 text-sm font-medium text-gray-400">Status</th>
                                <th class="text-right py-3 px-3 text-sm font-medium text-gray-400">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-3 text-white">{{ $tx->game->title ?? '-' }}</td>
                                    @if($user->role === 'developer')
                                        <td class="py-3 px-3 text-gray-300">{{ $tx->user->name ?? '-' }}</td>
                                    @endif
                                    <td class="py-3 px-3 text-right text-neon-cyan">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-3 text-center">
                                        @if($tx->status === 'success')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Success</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Failed</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-right text-gray-500 text-sm">{{ $tx->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No transactions yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
