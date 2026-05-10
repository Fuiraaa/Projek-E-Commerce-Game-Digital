<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Library;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function checkout(Game $game): RedirectResponse
    {
        if ($game->status !== 'active') {
            return redirect()->back()->with('error', 'Game is not available for purchase.');
        }

        if (Library::where('user_id', auth()->id())->where('game_id', $game->id)->exists()) {
            return redirect()->back()->with('error', 'You already own this game.');
        }

        $pendingOrder = \App\Models\Order::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->first();

        if ($pendingOrder) {
            return redirect()->route('cart.index')->with('error', 'You have a pending order. Please complete or cancel it first.');
        }

        // Instead of immediate purchase, create an Order
        $order = \App\Models\Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'total_price' => $game->price,
            'payment_status' => 'pending',
            'approval_status' => 'pending',
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'game_id' => $game->id,
            'price' => $game->price,
        ]);

        return redirect()->route('order.pay', $order->order_number);
    }
}
