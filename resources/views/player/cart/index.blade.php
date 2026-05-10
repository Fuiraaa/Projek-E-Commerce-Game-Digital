<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-white mb-1">Your Cart</h1>
        <p class="text-gray-400 mb-6">Your selected games ready for checkout.</p>

        @if(isset($pendingOrder))
            <div class="mb-6 p-6 glass-card rounded-2xl relative overflow-hidden flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="relative flex gap-4 items-center">
                    <div class="w-12 h-12 rounded-xl bg-neon-cyan/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-neon-cyan text-2xl">hourglass_empty</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-white text-lg">Ongoing Payment</h3>
                        <p class="text-sm text-gray-400 mt-0.5">You have an ongoing payment for order <span class="text-white font-mono text-xs bg-white/10 px-1.5 py-0.5 rounded">{{ $pendingOrder->order_number }}</span>.</p>
                    </div>
                </div>
                <div class="relative flex flex-row items-stretch gap-3 w-full sm:w-auto">
                    <form action="{{ route('order.cancel', $pendingOrder->order_number) }}" method="POST" class="flex-1 sm:flex-none flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full whitespace-nowrap flex items-center justify-center px-6 py-2.5 bg-transparent border border-red-500 text-red-400 hover:bg-red-500/10 rounded-xl transition-all font-medium text-sm">Cancel</button>
                    </form>
                    <a href="{{ $pendingOrder->snap_token ? route('order.midtrans', $pendingOrder->order_number) : route('order.pay', $pendingOrder->order_number) }}" class="flex-1 sm:flex-none whitespace-nowrap flex items-center justify-center px-6 py-2.5 btn-primary text-white hover:scale-105 duration-300 rounded-xl transition-all font-bold text-sm shadow-[0_0_15px_rgba(0,240,255,0.3)]">
                        Resume Payment
                    </a>
                </div>
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="glass-card p-8 text-center rounded-2xl">
                <span class="material-icons text-6xl text-gray-500 mb-4 opacity-50">shopping_cart</span>
                <p class="text-gray-400 text-lg mb-6">Your cart is empty.</p>
                <a href="{{ route('store.index') }}" class="btn-primary px-6 py-2 rounded-xl text-white font-medium inline-block">
                    Browse Store
                </a>
            </div>
        @else
            <div class="glass-card p-6 rounded-2xl mb-6 flex flex-col gap-4">
                @foreach($carts as $cart)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5 relative">
                        <div class="w-full sm:w-48 aspect-video sm:aspect-[16/9] rounded-lg overflow-hidden bg-black flex-shrink-0">
                            @if($cart->game->cover_image)
                                <img src="{{ asset('storage/' . $cart->game->cover_image) }}" alt="{{ $cart->game->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                                    <span class="material-icons text-white/20 text-3xl">sports_esports</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-heading font-bold text-lg text-white"><a href="{{ route('store.show', $cart->game->slug) }}" class="hover:text-neon-cyan transition-colors">{{ $cart->game->title }}</a></h3>
                            <p class="text-gray-400 text-sm mt-1">Rp {{ number_format($cart->game->price, 0, ',', '.') }}</p>
                        </div>
                        <form action="{{ route('cart.remove', $cart) }}" method="POST" class="absolute top-4 right-4 sm:static">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 p-2 rounded-lg hover:bg-red-500/10 transition-colors" title="Remove from cart">
                                <span class="material-icons">delete</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="glass-card p-6 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Total Price</p>
                    <p class="text-2xl font-bold text-neon-violet">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    @if(isset($pendingOrder))
                        <button type="button" disabled class="px-8 py-3 rounded-xl bg-gray-500/20 text-gray-400 font-medium cursor-not-allowed flex items-center gap-2">
                            <span class="material-icons">payment</span>
                            Pending Payment
                        </button>
                    @else
                        <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-white font-medium hover:scale-105 transition-transform duration-300 flex items-center gap-2" {{ auth()->user()->wallet_balance < $total ? 'disabled' : '' }}>
                            <span class="material-icons">payment</span>
                            Checkout
                        </button>
                    @endif
                </form>
            </div>
            
            @if(auth()->user()->wallet_balance < $total)
                <div class="mt-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm">error_outline</span>
                        Insufficient wallet balance.
                    </div>
                    <a href="{{ route('wallet.index') }}" class="px-3 py-1 bg-white/5 hover:bg-white/10 rounded border border-white/10 text-white transition-colors">Top up wallet</a>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
