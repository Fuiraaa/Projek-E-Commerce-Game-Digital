<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="font-heading text-3xl font-bold text-white neon-text">My Library</h1>
            <p class="text-gray-400 mt-1">Your purchased games and license keys</p>
        </div>

        <div x-data="{ playMethod: 'launcher' }" class="glass-card p-4 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <h3 class="font-heading font-semibold text-neon-cyan flex items-center gap-2">
                    <span class="material-icons">info</span> How to Play
                </h3>
                <div class="flex gap-2">
                    <button @click="playMethod = 'launcher'" :class="playMethod === 'launcher' ? 'bg-neon-violet/20 text-neon-violet border-neon-violet/30' : 'bg-white/5 text-gray-400 border-transparent hover:bg-white/10'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all border">
                        <span class="material-icons text-xs align-middle">rocket_launch</span>
                        Launcher
                    </button>
                    <button @click="playMethod = 'manual'" :class="playMethod === 'manual' ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-white/5 text-gray-400 border-transparent hover:bg-white/10'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all border">
                        <span class="material-icons text-xs align-middle">folder_zip</span>
                        Manual
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <div x-show="playMethod === 'launcher'" x-transition class="p-3 rounded-lg bg-white/5 border border-neon-violet/20">
                    <p class="text-xs font-semibold text-neon-violet mb-2 flex items-center gap-1">
                        <span class="material-icons text-sm">rocket_launch</span>
                        Using Neboostla Launcher (Recommended)
                    </p>
                    <ol class="space-y-1.5 text-sm text-gray-300">
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-neon-violet/20 text-neon-violet flex items-center justify-center text-xs font-bold">1</span>
                            <span>Click <strong class="text-white">Download</strong> to get the game .zip file.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-neon-violet/20 text-neon-violet flex items-center justify-center text-xs font-bold">2</span>
                            <span><strong class="text-white">Download & Install</strong> the Neboostla Launcher (click Play Now).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-neon-violet/20 text-neon-violet flex items-center justify-center text-xs font-bold">3</span>
                            <span>Click <strong class="text-white">Play Now</strong> to launch the game directly.</span>
                        </li>
                    </ol>
                </div>

                <div x-show="playMethod === 'manual'" x-transition class="p-3 rounded-lg bg-white/5 border border-white/10">
                    <p class="text-xs font-semibold text-gray-400 mb-2 flex items-center gap-1">
                        <span class="material-icons text-sm">folder_zip</span>
                        Manual (Extract & Play)
                    </p>
                    <ol class="space-y-1.5 text-sm text-gray-300">
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-500/20 text-gray-400 flex items-center justify-center text-xs font-bold">1</span>
                            <span><strong class="text-white">Extract</strong> the downloaded .zip file to any folder.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-500/20 text-gray-400 flex items-center justify-center text-xs font-bold">2</span>
                            <span>Open the folder and find the <code class="px-1 py-0.5 rounded bg-white/10 text-neon-cyan text-xs">.exe</code> file.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-500/20 text-gray-400 flex items-center justify-center text-xs font-bold">3</span>
                            <span><strong class="text-white">Double-click</strong> the .exe file to start playing.</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        @if($libraries->count() > 0)
            <div class="space-y-4">
                @foreach($libraries as $library)
                    <div class="glass-card glass-card-hover p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 transition-all duration-300">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            @if($library->game->cover_image)
                                <img src="{{ asset('storage/' . $library->game->cover_image) }}" alt="{{ $library->game->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-heading font-semibold text-white text-lg">{{ $library->game->title }}</h3>
                            <p class="text-gray-500 text-sm">Purchased: {{ $library->purchased_at->format('M d, Y') }}</p>
                        </div>

                        <div class="flex flex-col items-start sm:items-end gap-2 w-full sm:w-auto">
                            <div class="px-3 py-1.5 rounded-lg bg-neon-blue/10 border border-neon-blue/20">
                                <span class="text-xs text-gray-500">License Key:</span>
                                <code class="text-sm text-neon-cyan font-mono ml-2">{{ $library->license_key }}</code>
                            </div>
                            <div class="flex gap-2 mt-1">
                                @if($library->game->game_file)
                                    @if(in_array($library->game_id, $downloadedGameIds))
                                        <button onclick="showLauncherModal('{{ $library->game->title }}')" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-neon-violet/20 text-neon-violet hover:bg-neon-violet/30 transition-colors text-sm font-medium">
                                            <span class="material-icons text-base">play_arrow</span>
                                            Play Now
                                        </button>
                                    @else
                                        <a href="{{ route('game.download', $library->game) }}" onclick="handleDownload(this)" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-neon-blue/20 text-neon-cyan hover:bg-neon-blue/30 transition-colors text-sm font-medium">
                                            <span class="material-icons text-base">download</span>
                                            Download
                                        </a>
                                    @endif
                                @else
                                    <span class="px-4 py-2 rounded-lg bg-gray-500/10 text-gray-500 text-sm">No game file available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass-card p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <p class="mt-4 text-gray-500">Your library is empty. Start shopping!</p>
                <a href="{{ route('store.index') }}" class="mt-4 inline-block btn-primary px-6 py-2 rounded-xl text-white font-medium">Browse Games</a>
            </div>
        @endif
    </div>

    <!-- Neboostla Launcher Modal -->
    <div id="launcherModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="glass-card w-full max-w-md mx-4 p-6 relative" onclick="event.stopPropagation()">
            <button onclick="closeLauncherModal()" class="absolute top-4 right-4 p-1 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                <span class="material-icons">close</span>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center">
                    <span class="material-icons text-white text-2xl">rocket_launch</span>
                </div>
                <div>
                    <h3 class="font-heading text-xl font-bold text-white">Neboostla Launcher</h3>
                    <p class="text-xs text-gray-400">Required to play games</p>
                </div>
            </div>

            <p class="text-sm text-gray-300 mb-4">
                To play <strong class="text-neon-cyan" id="launcherGameTitle"></strong>, you need to install the Neboostla Launcher.
            </p>

            <div class="space-y-3 mb-6">
                <div class="flex items-start gap-3 p-3 rounded-lg bg-white/5">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center text-xs font-bold">
                        <span class="material-icons text-sm">download</span>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-white">Download the Launcher</p>
                        <p class="text-xs text-gray-400">Get the Neboostla Launcher installer</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg bg-white/5">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold">
                        <span class="material-icons text-sm">install_desktop</span>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-white">Install on Your PC</p>
                        <p class="text-xs text-gray-400">Run the installer and follow setup</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg bg-white/5">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-neon-violet/20 text-neon-violet flex items-center justify-center text-xs font-bold">
                        <span class="material-icons text-sm">play_arrow</span>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-white">Launch & Play</p>
                        <p class="text-xs text-gray-400">Open games directly from your library</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('launcher.download') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-neon-blue to-neon-violet text-white font-medium hover:opacity-90 transition-opacity text-sm">
                    <span class="material-icons text-base">download</span>
                    Download Launcher
                </a>
                <button onclick="closeLauncherModal()" class="px-4 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm">
                    Later
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function handleDownload(el) {
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.5';
            el.querySelector('span:last-child').textContent = 'Downloading...';
            setTimeout(() => {
                window.location.reload();
            }, 5000);
        }

        function showLauncherModal(title) {
            document.getElementById('launcherGameTitle').textContent = title;
            document.getElementById('launcherModal').classList.remove('hidden');
        }

        function closeLauncherModal() {
            document.getElementById('launcherModal').classList.add('hidden');
        }

        document.getElementById('launcherModal').addEventListener('click', function(e) {
            if (e.target === this) closeLauncherModal();
        });
    </script>
    @endpush
</x-app-layout>
