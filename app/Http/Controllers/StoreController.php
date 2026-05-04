<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request): View
    {
        $allGames = Game::where('status', 'active')
            ->with('developer')
            ->latest()
            ->get();

        $trendingGames = Game::select('games.*', DB::raw("(SELECT COUNT(*) FROM transactions WHERE transactions.game_id = games.id AND transactions.status = 'success' AND transactions.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as weekly_purchases"))
            ->where('games.status', 'active')
            ->having('weekly_purchases', '>', 0)
            ->orderByDesc('weekly_purchases')
            ->take(8)
            ->with('developer')
            ->get();

        $newArrivals = Game::select('games.*')
            ->where('status', 'active')
            ->latest('updated_at')
            ->take(8)
            ->with('developer')
            ->get();

        $popularGames = Game::select('games.*', DB::raw("(SELECT COUNT(*) FROM transactions WHERE transactions.game_id = games.id AND transactions.status = 'success') as purchase_count"))
            ->where('games.status', 'active')
            ->orderByDesc('purchase_count')
            ->take(5)
            ->with('developer')
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'slug' => $g->slug,
                    'title' => $g->title,
                    'description' => Str::limit($g->description, 120),
                    'price' => number_format($g->price, 0, ',', '.'),
                    'developer' => $g->developer?->name ?? 'Unknown',
                    'cover' => $g->cover_image ? asset('storage/' . $g->cover_image) : null,
                    'purchases' => (int) $g->purchase_count,
                ];
            })->values()->toArray();

        $toCard = fn($g) => [
            'id' => $g->id,
            'slug' => $g->slug,
            'title' => $g->title,
            'description' => Str::limit($g->description, 80),
            'price' => number_format($g->price, 0, ',', '.'),
            'developer' => $g->developer?->name ?? 'Unknown',
            'cover_image' => $g->cover_image ? asset('storage/' . $g->cover_image) : null,
        ];

        return view('store.index', compact('allGames', 'popularGames', 'trendingGames', 'newArrivals', 'toCard'));
    }

    public function show(Game $game): View
    {
        if ($game->status !== 'active') {
            abort(404);
        }

        $alreadyOwned = auth()->check()
            ? \App\Models\Library::where('user_id', auth()->id())->where('game_id', $game->id)->exists()
            : false;

        return view('store.show', compact('game', 'alreadyOwned'));
    }
}
