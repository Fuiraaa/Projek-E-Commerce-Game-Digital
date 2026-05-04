<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Library;
use App\Models\GameDownload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GameDownloadController extends Controller
{
    public function download(Game $game): BinaryFileResponse|RedirectResponse
    {
        $library = Library::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->first();

        if (!$library) {
            return redirect()->route('store.show', $game)->with('error', 'You must purchase this game first.');
        }

        if (!$game->game_file) {
            return redirect()->route('library.index')->with('error', 'Game file is not available.');
        }

        GameDownload::updateOrCreate(
            ['user_id' => auth()->id(), 'game_id' => $game->id],
            ['downloaded' => true]
        );

        $path = Storage::disk('public')->path($game->game_file);
        $filename = Str::slug($game->title) . '.zip';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
