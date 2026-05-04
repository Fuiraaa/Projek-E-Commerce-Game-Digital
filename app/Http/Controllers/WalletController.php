<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        return view('wallet.index');
    }

    public function topup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:10000000'],
        ]);

        auth()->user()->increment('wallet_balance', $validated['amount']);

        return redirect()->back()->with('success', 'Wallet topped up successfully.');
    }
}
