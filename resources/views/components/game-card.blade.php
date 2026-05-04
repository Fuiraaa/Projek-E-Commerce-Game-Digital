@props(['game'])

<div class="glass-card glass-card-hover overflow-hidden group transition-all duration-300">
    <div class="relative aspect-[4/3] overflow-hidden">
        @if($game->cover_image)
            <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        @endif
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-neon-violet/80 text-white backdrop-blur-sm">
                Rp {{ number_format($game->price, 0, ',', '.') }}
            </span>
        </div>
    </div>
    <div class="p-4">
        <h3 class="font-heading font-semibold text-white text-lg truncate">{{ $game->title }}</h3>
        <p class="text-gray-400 text-sm mt-1 line-clamp-2">{{ Str::limit($game->description, 80) }}</p>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-500">by {{ $game->developer->name ?? 'Developer' }}</span>
            <a href="{{ route('store.show', $game->slug) }}" class="px-4 py-2 rounded-lg btn-primary text-white text-sm font-medium">
                View Details
            </a>
        </div>
    </div>
</div>
