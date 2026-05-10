<x-app-layout>
    <div x-data="{ rejectModal: false, rejectUserId: null }" class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white neon-text">Admin Dashboard</h1>
                <p class="text-gray-400 mt-1">Manage developers, games, and transactions</p>
            </div>
            <div>
                <select id="adminFilter" onchange="window.location.href='?filter='+this.value" class="px-4 py-2.5 rounded-lg glass-card bg-white/5 border border-white/10 text-white text-sm outline-none focus:border-neon-blue cursor-pointer [&>option]:bg-dark-card [&>option]:text-white" style="background-color: #1e293b; color: #e2e8f0;">
                    <option value="day" {{ $filter === 'day' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">Today</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">This Month</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">This Year</option>
                </select>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Developers</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_developers'] }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Players</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_players'] }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-neon-violet/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-neon-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Active Games</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_games'] }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-neon-cyan/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-neon-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Revenue</p>
                        <p class="text-xl font-bold text-neon-cyan">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">Revenue Over Time</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="glass-card p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">Sales Over Time</h3>
                <canvas id="salesChart" height="200"></canvas>
            </div>
        </div>

        <!-- Developers Section -->
        <h2 class="font-heading text-lg font-semibold text-gray-400 mb-3">Developers</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pending Developers -->
            <div class="glass-card p-6" x-data="{
                items: {{ Js::from($pendingDevelopers) }},
                page: 1,
                perPage: 3,
                get paginatedItems() { return this.items.slice((this.page - 1) * this.perPage, this.page * this.perPage); },
                get totalPages() { return Math.ceil(this.items.length / this.perPage); },
                nextPage() { if (this.page < this.totalPages) this.page++; },
                prevPage() { if (this.page > 1) this.page--; }
            }">
                <h2 class="font-heading text-xl font-bold text-white mb-4">Pending Developers</h2>
                <template x-if="items.length > 0">
                    <div>
                        <div class="space-y-3">
                            <template x-for="dev in paginatedItems" :key="dev.id">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                    <div>
                                        <p class="font-medium text-white" x-text="dev.name"></p>
                                        <p class="text-sm text-gray-400" x-text="dev.email"></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form :action="'{{ url('admin/verify-developer') }}/' + dev.id" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 text-sm">Verify</button>
                                        </form>
                                        <button @click="rejectModal = true; rejectUserId = dev.id" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 text-sm">Reject</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-4" x-show="totalPages > 1">
                            <p class="text-xs text-gray-400">
                                Showing <span x-text="(page - 1) * perPage + 1"></span> to <span x-text="Math.min(page * perPage, items.length)"></span> of <span x-text="items.length"></span>
                            </p>
                            <div class="flex items-center gap-2">
                                <button @click="prevPage" :disabled="page === 1" :class="page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-1.5 rounded-lg bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                                    <span class="material-icons text-sm">chevron_left</span>
                                </button>
                                <span class="text-xs font-bold text-white px-2"><span x-text="page"></span> / <span x-text="totalPages"></span></span>
                                <button @click="nextPage" :disabled="page === totalPages" :class="page === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-1.5 rounded-lg bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                                    <span class="material-icons text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="items.length === 0">
                    <p class="text-gray-500 text-center py-4">No pending developers.</p>
                </template>
            </div>

            <!-- Rejected Developers -->
            <div class="glass-card p-6">
                <h2 class="font-heading text-xl font-bold text-white mb-4">Rejected Developers</h2>
                @if($rejectedDevelopers->count() > 0)
                    <div class="space-y-3">
                        @foreach($rejectedDevelopers as $dev)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                <div>
                                    <p class="font-medium text-white">{{ $dev->name }}</p>
                                    <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $dev->rejection_reason }}</p>
                                </div>
                                <form action="{{ route('admin.verify-developer', $dev) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 text-sm">Re-verify</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No rejected developers.</p>
                @endif
            </div>
        </div>

        <!-- Games Section -->
        <h2 class="font-heading text-lg font-semibold text-gray-400 mb-3">Games</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pending Games -->
            <div class="glass-card p-6" x-data="{
                items: {{ Js::from($pendingGames) }},
                page: 1,
                perPage: 3,
                get paginatedItems() { return this.items.slice((this.page - 1) * this.perPage, this.page * this.perPage); },
                get totalPages() { return Math.ceil(this.items.length / this.perPage); },
                nextPage() { if (this.page < this.totalPages) this.page++; },
                prevPage() { if (this.page > 1) this.page--; }
            }">
                <h2 class="font-heading text-xl font-bold text-white mb-4">Pending Games</h2>
                <template x-if="items.length > 0">
                    <div>
                        <div class="space-y-3">
                            <template x-for="game in paginatedItems" :key="game.id">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                    <div>
                                        <p class="font-medium text-white" x-text="game.title"></p>
                                        <p class="text-sm text-gray-400">by <span x-text="game.developer ? game.developer.name : 'Unknown'"></span></p>
                                        <template x-if="game.game_file">
                                            <span class="text-xs text-green-400 flex items-center gap-0.5 mt-0.5">
                                                <span class="material-icons text-xs">folder_zip</span> File attached
                                            </span>
                                        </template>
                                    </div>
                                    <div class="flex gap-2">
                                        <form :action="'{{ url('admin/approve-game') }}/' + game.id" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 text-sm">Approve</button>
                                        </form>
                                        <form :action="'{{ url('admin/reject-game') }}/' + game.id" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 text-sm">Reject</button>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-4" x-show="totalPages > 1">
                            <p class="text-xs text-gray-400">
                                Showing <span x-text="(page - 1) * perPage + 1"></span> to <span x-text="Math.min(page * perPage, items.length)"></span> of <span x-text="items.length"></span>
                            </p>
                            <div class="flex items-center gap-2">
                                <button @click="prevPage" :disabled="page === 1" :class="page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-1.5 rounded-lg bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                                    <span class="material-icons text-sm">chevron_left</span>
                                </button>
                                <span class="text-xs font-bold text-white px-2"><span x-text="page"></span> / <span x-text="totalPages"></span></span>
                                <button @click="nextPage" :disabled="page === totalPages" :class="page === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-1.5 rounded-lg bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                                    <span class="material-icons text-sm">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="items.length === 0">
                    <p class="text-gray-500 text-center py-4">No pending games.</p>
                </template>
            </div>

            <!-- Rejected Games -->
            <div class="glass-card p-6">
                <h2 class="font-heading text-xl font-bold text-white mb-4">Rejected Games</h2>
                @if($rejectedGames->count() > 0)
                    <div class="space-y-3">
                        @foreach($rejectedGames as $game)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                <div>
                                    <p class="font-medium text-white">{{ $game->title }}</p>
                                    <p class="text-sm text-gray-400">by {{ $game->developer->name }}</p>
                                </div>
                                <form action="{{ route('admin.approve-game', $game) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 text-sm">Re-approve</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No rejected games.</p>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="glass-card p-6 mt-6">
            <h2 class="font-heading text-xl font-bold text-white mb-4">Recent Transactions</h2>
            @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="text-left py-3 px-3 text-sm font-medium text-gray-400">Player</th>
                                <th class="text-left py-3 px-3 text-sm font-medium text-gray-400">Game</th>
                                <th class="text-right py-3 px-3 text-sm font-medium text-gray-400">Amount</th>
                                <th class="text-center py-3 px-3 text-sm font-medium text-gray-400">Status</th>
                                <th class="text-right py-3 px-3 text-sm font-medium text-gray-400">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-3 text-white">{{ $tx->user->name }}</td>
                                    <td class="py-3 px-3 text-gray-300">{{ $tx->game->title }}</td>
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
                <p class="text-gray-500 text-center py-4">No transactions for this period.</p>
            @endif
        </div>

        <!-- Reject Modal -->
        <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" style="display: none;">
            <div class="glass-card w-full max-w-md mx-4 p-6" @click.stop>
                <h3 class="font-heading text-xl font-bold text-white mb-4">Reject Developer</h3>
                <form :action="`/admin/reject-developer/${rejectUserId}`" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Rejection Reason (optional)</label>
                        <textarea name="rejection_reason" rows="3" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors resize-none" placeholder="Enter reason for rejection..."></textarea>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="rejectModal = false" class="px-4 py-2 rounded-lg border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);
        const filterType = @json($filter);

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: chartData.revenue,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { size: 10 } },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        ticks: {
                            color: '#64748b',
                            callback: (val) => 'Rp ' + (val / 1000).toFixed(0) + 'k'
                        },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });

        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Sales Count',
                    data: chartData.sales,
                    backgroundColor: 'rgba(139, 92, 246, 0.6)',
                    borderColor: '#8b5cf6',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: { color: '#64748b', font: { size: 10 } },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { color: '#64748b', beginAtZero: true },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
