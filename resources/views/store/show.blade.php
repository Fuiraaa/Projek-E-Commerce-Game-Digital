<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="glass-card overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Cover Image -->
                <div class="aspect-square md:aspect-auto">
                    @if($game->cover_image)
                        <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="p-6 lg:p-8 flex flex-col">
                    <h1 class="font-heading text-3xl font-bold text-white">{{ $game->title }}</h1>
                    <p class="text-gray-400 mt-2">by <span class="text-neon-cyan">{{ $game->developer->name }}</span></p>

                    <div class="mt-6 flex items-center gap-4">
                        <span class="text-3xl font-bold text-neon-violet">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                        @if($game->game_file)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 flex items-center gap-1">
                                <span class="material-icons text-sm">download</span>
                                Downloadable
                            </span>
                        @endif
                    </div>

                    <div class="mt-6 flex-1">
                        <h3 class="font-heading text-lg font-semibold text-white mb-2">Description</h3>
                        <p class="text-gray-400 leading-relaxed">{{ $game->description }}</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/5">
                        @auth
                            @if(auth()->user()->isPlayer())
                                @if($alreadyOwned)
                                    <button disabled class="w-full py-3 rounded-xl bg-green-500/20 text-green-400 font-medium cursor-not-allowed">
                                        Already in Library
                                    </button>
                                @else
                                    <form action="{{ route('checkout', $game->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-medium">
                                            Buy Now
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-gray-500 text-sm text-center">Login as a player to purchase this game.</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full block text-center btn-primary py-3 rounded-xl text-white font-medium">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
