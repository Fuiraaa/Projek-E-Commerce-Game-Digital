<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-white">Pending Orders</h1>
                <p class="text-gray-400 mt-1">Approve paid orders to deliver games to players.</p>
            </div>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10 text-xs tracking-wider text-gray-400 font-medium">
                            <th class="py-3 px-4 text-left">Order Number</th>
                            <th class="py-3 px-4 text-left">Player</th>
                            <th class="py-3 px-4 text-left">Game</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                            <th class="py-3 px-4 text-left">Payment Method</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 font-mono text-neon-cyan">{{ $order->order_number }}</td>
                                <td class="p-4 text-white font-medium">{{ $order->user->name }}</td>
                                <td class="p-4 text-gray-300">
                                    <ul class="list-disc list-inside">
                                        @foreach($order->items as $item)
                                            <li>{{ $item->game->title }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="p-4 text-right text-neon-violet font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="p-4 text-gray-400 capitalize">{{ $order->payment_method }}</td>
                                <td class="p-4 text-center">
                                    @if($order->approval_status === 'pending')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">Pending</span>
                                    @elseif($order->approval_status === 'approved')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Approved</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($order->approval_status === 'pending')
                                            <form action="{{ route('admin.approve-order', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-lg hover:bg-green-500/20 text-green-400 transition-colors" title="Approve">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 text-red-400 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500">
                                    <span class="material-icons text-4xl mb-2 opacity-50">check_circle</span>
                                    <p>No pending orders at the moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
