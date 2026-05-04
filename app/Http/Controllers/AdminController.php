<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'month');
        $now = now();

        $pendingDevelopers = User::where('role', 'developer')->where('is_verified', false)->where('is_rejected', false)->latest()->get();
        $rejectedDevelopers = User::where('role', 'developer')->where('is_rejected', true)->latest()->get();
        $pendingGames = Game::where('status', 'pending')->with('developer')->latest()->get();
        $rejectedGames = Game::where('status', 'rejected')->with('developer')->latest()->get();

        $transactionQuery = Transaction::with(['user', 'game'])->where('status', 'success');
        $this->applyDateFilter($transactionQuery, $filter, $now);
        $recentTransactions = $transactionQuery->latest()->take(15)->get();

        $revenueQuery = Transaction::where('status', 'success');
        $this->applyDateFilter($revenueQuery, $filter, $now);

        $stats = [
            'total_developers' => User::where('role', 'developer')->count(),
            'total_players' => User::where('role', 'player')->count(),
            'total_games' => Game::where('status', 'active')->count(),
            'total_revenue' => (clone $revenueQuery)->sum('total_price'),
            'total_transactions' => (clone $revenueQuery)->count(),
        ];

        $chartData = $this->getChartData($filter);

        return view('admin.dashboard', compact('pendingDevelopers', 'rejectedDevelopers', 'pendingGames', 'rejectedGames', 'recentTransactions', 'stats', 'chartData', 'filter'));
    }

    private function applyDateFilter($query, $filter, $now): void
    {
        switch ($filter) {
            case 'day':
                $query->whereDate('created_at', $now->format('Y-m-d'));
                break;
            case 'year':
                $query->whereYear('created_at', $now->year);
                break;
            case 'month':
            default:
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
        }
    }

    private function getChartData($filter): array
    {
        $now = now();

        if ($filter === 'day') {
            $transactions = Transaction::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('status', 'success')
                ->whereDate('created_at', $now->format('Y-m-d'))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            $labels = [];
            $revenue = [];
            $sales = [];

            for ($h = 0; $h < 24; $h++) {
                $labels[] = sprintf('%02d:00', $h);
                $found = $transactions->firstWhere('hour', $h);
                $revenue[] = $found ? (float) $found->revenue : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        } elseif ($filter === 'year') {
            $transactions = Transaction::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('status', 'success')
                ->whereYear('created_at', $now->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = [];
            $revenue = [];
            $sales = [];

            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            for ($m = 1; $m <= 12; $m++) {
                $labels[] = $monthNames[$m - 1];
                $found = $transactions->firstWhere('month', $m);
                $revenue[] = $found ? (float) $found->revenue : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        } else {
            $daysInMonth = $now->daysInMonth;
            $transactions = Transaction::select(
                    DB::raw('DAY(created_at) as day'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('status', 'success')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $labels = [];
            $revenue = [];
            $sales = [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $found = $transactions->firstWhere('day', $d);
                $revenue[] = $found ? (float) $found->revenue : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'sales' => $sales,
        ];
    }

    public function verifyDeveloper(User $user): RedirectResponse
    {
        if ($user->role !== 'developer') {
            abort(403);
        }

        $user->update(['is_verified' => true, 'is_rejected' => false, 'rejection_reason' => null]);

        return redirect()->back()->with('success', 'Developer verified successfully.');
    }

    public function rejectDeveloper(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'developer') {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'is_verified' => false,
            'is_rejected' => true,
            'rejection_reason' => $validated['rejection_reason'] ?? 'Verification request denied.',
        ]);

        return redirect()->back()->with('success', 'Developer rejected. Account remains active.');
    }

    public function approveGame(Game $game): RedirectResponse
    {
        $game->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Game approved successfully.');
    }

    public function rejectGame(Game $game): RedirectResponse
    {
        $game->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Game rejected.');
    }
}
