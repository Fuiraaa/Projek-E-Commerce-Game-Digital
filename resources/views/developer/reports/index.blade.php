<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white neon-text">Sales Reports</h1>
                <p class="text-gray-400 mt-1">View your game sales statistics</p>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <select id="reportFilter" onchange="window.location.href='?filter='+this.value" class="px-4 py-2.5 rounded-lg glass-card bg-white/5 border border-white/10 text-white text-sm outline-none focus:border-neon-blue cursor-pointer [&>option]:bg-dark-card [&>option]:text-white" style="background-color: #1e293b; color: #e2e8f0;">
                    <option value="day" {{ $filter === 'day' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">Today</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">This Month</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }} style="background-color: #1e293b; color: #e2e8f0;">This Year</option>
                </select>
                <a href="{{ route('developer.reports.pdf') }}" download class="px-4 py-2 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    PDF
                </a>
                <a href="{{ route('developer.reports.excel') }}" download class="px-4 py-2 rounded-xl bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Excel
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="glass-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-neon-blue/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-neon-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Sales</p>
                        <p class="text-2xl font-bold text-white">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-neon-violet/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-neon-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Net Revenue (95%)</p>
                        <p class="text-2xl font-bold text-neon-violet">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">Revenue Trend</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="glass-card p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">Sales Trend</h3>
                <canvas id="salesChart" height="200"></canvas>
            </div>
        </div>

        <!-- Games Table -->
        @if($gameStats->count() > 0)
            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Game Title</th>
                                <th class="text-center py-4 px-4 text-sm font-medium text-gray-400">Total Sales</th>
                                <th class="text-right py-4 px-4 text-sm font-medium text-gray-400">Gross Revenue</th>
                                <th class="text-right py-4 px-4 text-sm font-medium text-gray-400">Net Revenue</th>
                                <th class="text-center py-4 px-4 text-sm font-medium text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gameStats as $game)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4 font-medium text-white">{{ $game->title }}</td>
                                    <td class="py-4 px-4 text-center text-neon-cyan">{{ $game->total_sales }}</td>
                                    <td class="py-4 px-4 text-right text-gray-400">Rp {{ number_format($game->revenue, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-right text-neon-violet font-medium">Rp {{ number_format($game->net_revenue, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        @if($game->status === 'active')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Active</span>
                                        @elseif($game->status === 'pending')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">Pending</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="glass-card p-12 text-center">
                <p class="text-gray-500">No sales data for this period.</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Net Revenue (Rp)',
                    data: chartData.revenue,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8b5cf6',
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
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: '#3b82f6',
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
