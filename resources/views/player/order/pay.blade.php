<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-white mb-1">Checkout Order</h1>
        <p class="text-gray-400 mb-6">Select a payment method to complete your order.</p>

        <div class="glass-card p-6 rounded-2xl mb-6">
            <h2 class="text-xl font-bold text-white mb-4">Order Summary (#{{ $order->order_number }})</h2>
            
            <div class="space-y-3 mb-6">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-gray-300">
                        <span>{{ $item->game->title }}</span>
                        <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="border-t border-white/10 pt-3 flex justify-between items-center text-white font-bold text-lg">
                    <span class="mt-6">Total</span>
                    <span class="text-neon-violet mt-6">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <h3 class="text-lg font-bold text-white mb-4">Select Payment Method</h3>
            
            <form action="{{ route('order.process', $order->order_number) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Wallet Option -->
                <label class="flex items-center gap-4 p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-colors mb-3">
                    <div class="flex-1 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-neon-cyan">account_balance_wallet</span>
                            <div>
                                <p class="font-bold text-white">Neboostla Wallet</p>
                                <p class="text-sm text-gray-400">Balance: Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="wallet" class="w-5 h-5 text-neon-cyan bg-gray-800 border-gray-600 focus:ring-neon-cyan focus:ring-2" required>
                </label>

                <!-- Midtrans Option -->
                <label class="flex items-center gap-4 p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                    <div class="flex-1 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-green-400">payments</span>
                            <div>
                                <p class="font-bold text-white">Other Methods (Midtrans)</p>
                                <p class="text-sm text-gray-400">GoPay, Bank Transfer, QRIS, dll.</p>
                            </div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="midtrans" class="w-5 h-5 text-neon-cyan bg-gray-800 border-gray-600 focus:ring-neon-cyan focus:ring-2" required>
                </label>

                <div class="pt-6">
                    <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-medium hover:scale-105 transition-transform duration-300">
                        Continue to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
