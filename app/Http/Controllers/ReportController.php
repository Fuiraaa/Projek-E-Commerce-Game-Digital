<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $developerId = auth()->id();
        $filter = $request->query('filter', 'month');
        $now = now();

        $gameStats = Game::where('developer_id', $developerId)
            ->withCount(['transactions as total_sales' => function ($query) use ($filter, $now) {
                $query->where('status', 'success');
                $this->applyDateFilter($query, $filter, $now);
            }])
            ->withSum(['transactions' => function ($query) use ($filter, $now) {
                $query->where('status', 'success');
                $this->applyDateFilter($query, $filter, $now);
            }], 'total_price')
            ->latest()
            ->get()
            ->map(function ($game) {
                $game->revenue = $game->transactions_sum_total_price ?? 0;
                $game->net_revenue = $game->revenue * 0.95;
                return $game;
            });

        $totalSales = $gameStats->sum('total_sales');
        $totalRevenue = $gameStats->sum('net_revenue');

        $chartData = $this->getChartData($developerId, $filter);

        return view('developer.reports.index', compact('gameStats', 'totalSales', 'totalRevenue', 'chartData', 'filter'));
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

    private function getChartData($developerId, $filter): array
    {
        $now = now();

        if ($filter === 'day') {
            $transactions = Transaction::whereHas('game', function ($q) use ($developerId) {
                    $q->where('developer_id', $developerId);
                })
                ->select(
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
                $revenue[] = $found ? (float) $found->revenue * 0.95 : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        } elseif ($filter === 'year') {
            $transactions = Transaction::whereHas('game', function ($q) use ($developerId) {
                    $q->where('developer_id', $developerId);
                })
                ->select(
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
                $revenue[] = $found ? (float) $found->revenue * 0.95 : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        } else {
            $transactions = Transaction::whereHas('game', function ($q) use ($developerId) {
                    $q->where('developer_id', $developerId);
                })
                ->select(
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

            $daysInMonth = $now->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $found = $transactions->firstWhere('day', $d);
                $revenue[] = $found ? (float) $found->revenue * 0.95 : 0;
                $sales[] = $found ? (int) $found->count : 0;
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'sales' => $sales,
        ];
    }

    public function exportPdf()
    {
        $developerId = auth()->id();
        $developer = auth()->user();

        $gameStats = Game::where('developer_id', $developerId)
            ->withCount(['transactions as total_sales' => function ($query) {
                $query->where('status', 'success');
            }])
            ->withSum(['transactions' => function ($query) {
                $query->where('status', 'success');
            }], 'total_price')
            ->latest()
            ->get()
            ->map(function ($game) {
                $game->revenue = $game->transactions_sum_total_price ?? 0;
                $game->net_revenue = $game->revenue * 0.95;
                return $game;
            });

        $totalSales = $gameStats->sum('total_sales');
        $totalRevenue = $gameStats->sum('net_revenue');

        $pdf = Pdf::loadView('developer.reports.pdf', compact('developer', 'gameStats', 'totalSales', 'totalRevenue'));

        return $pdf->download('sales-report-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $developerId = auth()->id();

        $gameStats = Game::where('developer_id', $developerId)
            ->withCount(['transactions as total_sales' => function ($query) {
                $query->where('status', 'success');
            }])
            ->withSum(['transactions' => function ($query) {
                $query->where('status', 'success');
            }], 'total_price')
            ->latest()
            ->get()
            ->map(function ($game) {
                $game->revenue = $game->transactions_sum_total_price ?? 0;
                $game->net_revenue = $game->revenue * 0.95;
                return $game;
            });

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales-report-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($gameStats) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Game Title', 'Total Sales', 'Gross Revenue', 'Net Revenue (95%)', 'Status']);

            foreach ($gameStats as $game) {
                fputcsv($file, [
                    $game->title,
                    $game->total_sales,
                    'Rp ' . number_format($game->revenue, 0, ',', '.'),
                    'Rp ' . number_format($game->net_revenue, 0, ',', '.'),
                    ucfirst($game->status),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
