<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeveloperDashboardController extends Controller
{
    public function index(): View
    {
        return view('developer.dashboard');
    }

    public function requestVerification(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->is_verified) {
            return redirect()->route('developer.games.index');
        }

        $user->update([
            'is_rejected' => false,
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Verification re-request submitted. Please wait for admin review.');
    }
}
