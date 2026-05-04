<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameDownload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GameController extends Controller
{
    private function requireVerified(): ?RedirectResponse
    {
        if (!auth()->user()->is_verified) {
            return redirect()->route('developer.dashboard')->with('error', 'Your account is pending admin verification.');
        }
        return null;
    }

    public function index(): View
    {
        $games = auth()->user()->games()->latest()->get();
        return view('developer.games.index', compact('games'));
    }

    public function create(): View|RedirectResponse
    {
        if ($response = $this->requireVerified()) {
            return $response;
        }
        return view('developer.games.create');
    }

    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($response = $this->requireVerified()) {
            return $response;
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'game_file' => ['nullable', 'file', 'mimes:zip', 'max:204800'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;

        while (Game::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['developer_id'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('game_file')) {
            $validated['game_file'] = $request->file('game_file')->store('games', 'public');
        }

        Game::create($validated);

        if ($request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'redirect' => route('developer.games.index'),
                'success' => 'Game submitted for review.',
            ]);
        }

        return redirect()->route('developer.games.index')->with('success', 'Game submitted for review.');
    }

    public function edit(Game $game): View|RedirectResponse
    {
        if ($response = $this->requireVerified()) {
            return $response;
        }
        $this->authorize('update', $game);
        return view('developer.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($response = $this->requireVerified()) {
            return $response;
        }
        $this->authorize('update', $game);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'game_file' => ['nullable', 'file', 'mimes:zip', 'max:204800'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($game->cover_image) {
                Storage::disk('public')->delete($game->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('game_file')) {
            if ($game->game_file) {
                Storage::disk('public')->delete($game->game_file);
            }
            $validated['game_file'] = $request->file('game_file')->store('games', 'public');
        }

        if ($validated['title'] !== $game->title) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;

            while (Game::where('slug', $slug)->where('id', '!=', $game->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
        }

        $game->update($validated);

        if ($request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'redirect' => route('developer.games.index'),
                'success' => 'Game updated successfully.',
            ]);
        }

        return redirect()->route('developer.games.index')->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game): RedirectResponse
    {
        if ($response = $this->requireVerified()) {
            return $response;
        }
        $this->authorize('delete', $game);

        if ($game->cover_image) {
            Storage::disk('public')->delete($game->cover_image);
        }

        if ($game->game_file) {
            Storage::disk('public')->delete($game->game_file);
        }

        $game->delete();

        return redirect()->route('developer.games.index')->with('success', 'Game deleted successfully.');
    }
}
