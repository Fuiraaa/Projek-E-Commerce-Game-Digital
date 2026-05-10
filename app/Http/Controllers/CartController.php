<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Game;
use App\Models\Library;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $carts = Cart::with('game')->where('user_id', auth()->id())->get();
        $total = $carts->sum(fn ($cart) => $cart->game->price);

        $pendingOrder = \App\Models\Order::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        return view('player.cart.index', compact('carts', 'total', 'pendingOrder'));
    }

    public function add(Request $request, Game $game): RedirectResponse
    {
        if ($game->status !== 'active') {
            return redirect()->back()->with('error', 'Game is not available for purchase.');
        }

        if (Library::where('user_id', auth()->id())->where('game_id', $game->id)->exists()) {
            return redirect()->back()->with('error', 'You already own this game.');
        }

        if (Cart::where('user_id', auth()->id())->where('game_id', $game->id)->exists()) {
            return redirect()->back()->with('error', 'Game is already in your cart.');
        }

        Cart::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
        ]);

        return redirect()->back()->with('success', 'Game added to cart.');
    }

    public function remove(Cart $cart): RedirectResponse
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(): RedirectResponse
    {
        $user = auth()->user();
        $carts = Cart::with('game')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $pendingOrder = \App\Models\Order::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->first();

        if ($pendingOrder) {
            return redirect()->route('cart.index')->with('error', 'You have a pending order. Please complete or cancel it first.');
        }

        $totalPrice = $carts->sum(fn ($cart) => $cart->game->price);

        $order = \App\Models\Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'payment_status' => 'pending',
            'approval_status' => 'pending',
        ]);

        foreach ($carts as $cart) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'game_id' => $cart->game_id,
                'price' => $cart->game->price,
            ]);
        }

        return redirect()->route('order.pay', $order->order_number);
    }
}
