<x-app-layout>
    <div class="max-w-7xl mx-auto">
        @php
            $successMsg = request('success') ?: session('success');
        @endphp
        @if($successMsg)
            <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $successMsg }}
            </div>
            @php
                session()->forget('success');
            @endphp
        @endif

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white neon-text">My Games</h1>
                <p class="text-gray-400 mt-1">Manage your game catalog</p>
            </div>
            <a href="{{ route('developer.games.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-white font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Game
            </a>
        </div>

        @if($games->count() > 0)
            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Game</th>
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Price</th>
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">File</th>
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Status</th>
                                <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                                                @if($game->cover_image)
                                                    <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20"></div>
                                                @endif
                                            </div>
                                            <span class="font-medium text-white">{{ $game->title }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-neon-cyan">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4">
                                        @if($game->game_file)
                                            <span class="flex items-center gap-1 text-sm text-green-400">
                                                <span class="material-icons text-base">check_circle</span>
                                                {{ Str::slug($game->title) }}.zip
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">No file</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($game->status === 'active')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Active</span>
                                        @elseif($game->status === 'pending')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">Pending</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('developer.games.edit', $game) }}" class="p-2 rounded-lg hover:bg-blue-500/20 text-blue-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('developer.games.destroy', $game) }}" method="POST" onsubmit="return confirm('Delete this game?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 text-red-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="glass-card p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                <p class="mt-4 text-gray-500">No games yet. Add your first game!</p>
            </div>
        @endif
    </div>
</x-app-layout>
