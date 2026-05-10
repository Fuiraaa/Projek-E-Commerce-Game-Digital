<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function pay($order_number)
    {
        $order = Order::with('items.game')->where('order_number', $order_number)->firstOrFail();

        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        return view('player.order.pay', compact('order'));
    }

    public function processPayment(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        $method = $request->input('payment_method');

        if ($method === 'wallet') {
            if (auth()->user()->wallet_balance < $order->total_price) {
                return back()->with('error', 'Insufficient wallet balance.');
            }

            DB::transaction(function () use ($order) {
                auth()->user()->decrement('wallet_balance', $order->total_price);
                $order->update([
                    'payment_method' => 'wallet',
                    'payment_status' => 'paid',
                    'approval_status' => 'pending', // Menunggu persetujuan Admin
                ]);

                // Clear from cart
                \App\Models\Cart::where('user_id', auth()->id())
                    ->whereIn('game_id', $order->items->pluck('game_id'))
                    ->delete();
            });

            return redirect()->route('library.index')->with('success', 'Order created with Wallet. Waiting for admin approval.');
        } elseif ($method === 'midtrans') {
            $order->update(['payment_method' => 'midtrans']);

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            if (!$order->snap_token) {
                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_number,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            }

            return view('player.order.midtrans', compact('order'));
        }

        return back()->with('error', 'Invalid payment method.');
    }

    public function midtrans($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending' || !$order->snap_token) {
            abort(403);
        }

        return view('player.order.midtrans', compact('order'));
    }

    public function cancel($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($order->user_id !== auth()->id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        if ($order->snap_token) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            
            try {
                \Midtrans\Transaction::cancel($order->order_number);
            } catch (\Exception $e) {
                // If cancellation fails (e.g. not found on Midtrans side yet), we just proceed locally.
            }
        }

        $order->update([
            'payment_status' => 'cancelled',
            'approval_status' => 'cancelled',
        ]);

        return redirect()->route('cart.index')->with('success', 'Ongoing payment cancelled successfully.');
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        try {
            $status = \Midtrans\Transaction::status($order->order_number);
            if ($status->transaction_status == 'capture' || $status->transaction_status == 'settlement') {
                if ($order->payment_status == 'pending') {
                    $order->update(['payment_status' => 'paid', 'approval_status' => 'pending']);
                    
                    \App\Models\Cart::where('user_id', $order->user_id)
                        ->whereIn('game_id', $order->items->pluck('game_id'))
                        ->delete();
                }
            }
        } catch (\Exception $e) {
            // fallback if status fetch fails but frontend says success
            if ($order->payment_status == 'pending') {
                $order->update(['payment_status' => 'paid', 'approval_status' => 'pending']);
                
                \App\Models\Cart::where('user_id', $order->user_id)
                    ->whereIn('game_id', $order->items->pluck('game_id'))
                    ->delete();
            }
        }

        return redirect()->route('library.index')->with('success', 'Payment successful! Waiting for admin approval.');
    }

    public function webhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $order = Order::where('order_number', $request->order_id)->first();
            if ($order && $order->payment_status == 'pending') {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $order->update(['payment_status' => 'paid', 'approval_status' => 'pending']);
                    
                    \App\Models\Cart::where('user_id', $order->user_id)
                        ->whereIn('game_id', $order->items->pluck('game_id'))
                        ->delete();
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                    $order->update(['payment_status' => 'failed']);
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }
}
