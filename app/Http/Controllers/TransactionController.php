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

        $user = auth()->user();

        if ($user->wallet_balance < $game->price) {
            return redirect()->back()->with('error', 'Insufficient wallet balance. Please top up first.');
        }

        $platformFee = $game->price * 0.05;
        $developerEarnings = $game->price - $platformFee;

        DB::transaction(function () use ($game, $user, $developerEarnings) {
            $user->decrement('wallet_balance', $game->price);

            $game->developer->increment('wallet_balance', $developerEarnings);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'total_price' => $game->price,
                'status' => 'success',
            ]);

            Library::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'license_key' => Str::uuid(),
                'purchased_at' => now(),
            ]);
        });

        return redirect()->route('library.index')->with('success', 'Purchase successful! Game added to your library.');
    }
}
