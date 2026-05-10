<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-2xl glass-card p-8 lg:p-12 mb-8">
            <div class="absolute inset-0 bg-gradient-to-r from-neon-blue/10 to-neon-violet/10"></div>
            <div class="relative z-10">
                <h1 class="font-heading text-4xl lg:text-5xl font-bold text-white neon-text">Welcome to Neboostla</h1>
                <p class="mt-4 text-lg text-gray-400 max-w-2xl">Discover and purchase the best digital games. Build your ultimate game library with exclusive titles from indie developers.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('store.index') }}" class="btn-primary px-6 py-3 rounded-xl text-white font-medium">
                        Browse Games
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all">
                            Get Started
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Featured Games -->
        <div class="mb-8">
            <h2 class="font-heading text-2xl font-bold text-white mb-6">Featured Games</h2>
            @if($games->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($games as $game)
                        <x-game-card :game="$game" />
                    @endforeach
                </div>
            @else
                <div class="glass-card p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="mt-4 text-gray-500">No games available yet. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>
    <x-slot name="footer">
        <x-footer />
    </x-slot>
</x-app-layout>
