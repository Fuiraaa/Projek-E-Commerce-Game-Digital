<?php

namespace App\Http\Controllers;

use App\Models\GameDownload;
use App\Models\Library;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function index(): View
    {
        $libraries = Library::with('game')
            ->where('user_id', auth()->id())
            ->latest('purchased_at')
            ->get();

        $downloadedGameIds = GameDownload::where('user_id', auth()->id())
            ->where('downloaded', true)
            ->pluck('game_id')
            ->toArray();

        return view('player.library.index', compact('libraries', 'downloadedGameIds'));
    }
}
