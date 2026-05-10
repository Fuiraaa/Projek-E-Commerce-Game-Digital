<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="glass-card p-8 rounded-2xl text-center">
            <span class="material-icons text-6xl text-neon-cyan mb-4 animate-pulse">payments</span>
            <h1 class="text-3xl font-heading font-bold text-white mb-2">Proceed with Payment</h1>
            <p class="text-gray-400 mb-6">Complete your payment of Rp {{ number_format($order->total_price, 0, ',', '.') }} securely with Midtrans.</p>
            
            <button id="pay-button" class="btn-primary px-8 py-3 rounded-xl text-white font-bold hover:scale-105 transition-transform duration-300">
                Pay Now
            </button>
        </div>
    </div>

    @push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $order->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('order.success', $order->order_number) }}";
                },
                onPending: function (result) {
                    alert("Waiting your payment!");
                },
                onError: function (result) {
                    alert("Payment failed!");
                },
                onClose: function () {
                    alert('You closed the popup without finishing the payment');
                }
            });
        };
    </script>
    @endpush
</x-app-layout>
