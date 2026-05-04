<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role', 'all');
        $search = $request->query('search', '');

        $query = User::whereIn('role', ['player', 'developer']);

        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'roleFilter', 'search'));
    }

    public function show(User $user): View
    {
        $user->loadCount('libraries');

        if ($user->role === 'player') {
            $stats = [
                'games_purchased' => $user->libraries_count,
                'current_balance' => $user->wallet_balance,
                'total_spent' => Transaction::where('user_id', $user->id)->where('status', 'success')->sum('total_price'),
            ];
            $recentTransactions = Transaction::with('game')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $totalGames = Game::where('developer_id', $user->id)->count();
            $activeGames = Game::where('developer_id', $user->id)->where('status', 'active')->count();
            $totalRevenue = Transaction::whereHas('game', function ($q) use ($user) {
                $q->where('developer_id', $user->id);
            })->where('status', 'success')->sum('total_price');

            $stats = [
                'total_games' => $totalGames,
                'active_games' => $activeGames,
                'total_revenue' => $totalRevenue * 0.95,
                'verified' => $user->is_verified,
            ];
            $recentTransactions = Transaction::whereHas('game', function ($q) use ($user) {
                $q->where('developer_id', $user->id);
            })->with(['user', 'game'])->latest()->take(5)->get();
        }

        return view('admin.users.show', compact('user', 'stats', 'recentTransactions'));
    }

    public function toggleSuspend(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot suspend admin accounts.');
        }

        $user->update(['is_suspended' => !$user->is_suspended]);

        $action = $user->is_suspended ? 'suspended' : 'unsuspended';
        return redirect()->back()->with('success', "User {$action} successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin accounts.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
