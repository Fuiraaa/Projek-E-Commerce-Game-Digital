<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Left Column: Gallery & Trailer (2/3) -->
            <div class="md:col-span-2 flex flex-col gap-4">
                @php
                    $photos = is_string($game->gallery_photos) ? json_decode($game->gallery_photos, true) : $game->gallery_photos;
                    if (!$photos && is_string($game->gallery_images)) {
                        $photos = json_decode($game->gallery_images, true) ?: [];
                    } elseif (!$photos) {
                        $photos = $game->gallery_images ?: [];
                    }
                    if (!is_array($photos)) $photos = [];
                    
                    $hasMedia = $game->trailer_video || count($photos) > 0;
                @endphp

                @if(!$hasMedia)
                    <!-- State when no trailer and no gallery -->
                    <div class="aspect-video bg-black/40 rounded-xl overflow-hidden flex flex-col items-center justify-center shadow-lg border border-white/5 text-gray-500">
                        <span class="material-icons text-6xl mb-4 opacity-30">videocam_off</span>
                        <p class="text-xl font-medium text-gray-400">No Gameplay Media</p>
                        <p class="text-sm mt-2 text-gray-500">Trailer and gallery photos are not available.</p>
                    </div>
                @else
                    <!-- Main Preview -->
                    <div class="aspect-video bg-black rounded-xl overflow-hidden relative flex items-center justify-center shadow-lg border border-white/10">
                        <video id="mainVideo" controls class="w-full h-full object-contain hidden" src=""></video>
                        <img id="mainImage" class="w-full h-full object-contain hidden" src="" alt="{{ $game->title }}">
                    </div>

                    <!-- Thumbnails List -->
                    <!-- Thumbnails List -->
                    <div style="position: relative; margin-top: 16px;">
                        <button id="scrollLeftBtn" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); padding: 8px; border-radius: 9999px; background: rgba(0,0,0,0.5); color: white; z-index: 20;" class="hover:bg-black/70 transition-colors flex items-center justify-center">
                            <span class="material-icons">chevron_left</span>
                        </button>
                        
                        <div id="thumbnailsContainer" class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide scroll-smooth">
                            <!-- Trailer Thumbnail -->
                            @if($game->trailer_video)
                            <div class="w-32 h-20 sm:w-40 sm:h-24 flex-shrink-0 cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-neon-cyan transition-all thumbnail-item" data-type="video" data-src="{{ asset('storage/' . $game->trailer_video) }}">
                                <div class="w-full h-full relative bg-gray-800 flex items-center justify-center group">
                                    @if($game->cover_image)
                                        <img src="{{ asset('storage/' . $game->cover_image) }}" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-30 transition-opacity" alt="Trailer Background">
                                    @endif
                                    <span class="material-icons text-white group-hover:text-neon-cyan text-4xl transition-colors relative z-10">play_circle_outline</span>
                                </div>
                            </div>
                            @endif

                            <!-- Gallery Photos -->
                            @foreach($photos as $index => $photo)
                            <div class="w-32 h-20 sm:w-40 sm:h-24 flex-shrink-0 cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-neon-cyan transition-all thumbnail-item" data-type="image" data-src="{{ asset('storage/' . $photo) }}">
                                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover" alt="Gallery {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>

                        <button id="scrollRightBtn" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); padding: 8px; border-radius: 9999px; background: rgba(0,0,0,0.5); color: white; z-index: 20;" class="hover:bg-black/70 transition-colors flex items-center justify-center">
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Right Column: Game Info (1/3) -->
            <div class="md:col-span-1 flex flex-col gap-4 lg:gap-6">
                <!-- Cover Image (Steam-like Capsule) -->
                <div class="rounded-lg overflow-hidden shadow-lg border border-white/10 w-full aspect-[16/9]">
                    @if($game->cover_image)
                        <img src="{{ asset('storage/' . $game->cover_image) }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Details Card -->
                <div class="glass-card p-6 flex flex-col flex-1">
                    <h1 class="font-heading text-3xl font-bold text-white">{{ $game->title }}</h1>
                    <p class="text-gray-400 mt-2">by <span class="text-neon-cyan">{{ $game->developer->name }}</span></p>

                    <div class="mt-4 flex flex-row items-center gap-2 sm:gap-3">
                        <span class="text-2xl xl:text-3xl font-bold text-neon-violet truncate">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                        @if($game->game_file)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 flex items-center gap-1 shrink-0 whitespace-nowrap">
                                <span class="material-icons text-sm">download</span>
                                Downloadable
                            </span>
                        @endif
                    </div>

                    <div class="mt-6 flex-1">
                        <h3 class="font-heading text-lg font-semibold text-white mb-2">Description</h3>
                        <p class="text-gray-400 text-sm leading-relaxed whitespace-pre-line">{{ $game->description }}</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/5">
                        @auth
                            @if(auth()->user()->isPlayer())
                                @if(isset($alreadyOwned) && $alreadyOwned)
                                    <button disabled class="w-full py-3 rounded-xl bg-green-500/20 text-green-400 font-medium cursor-not-allowed">
                                        Already in Library
                                    </button>
                                @else
                                    <form action="{{ route('checkout', $game->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full btn-primary py-3 rounded-xl text-white font-medium hover:scale-105 transition-transform duration-300">
                                            Buy Now
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-gray-500 text-sm text-center">Login as a player to purchase this game.</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full block text-center btn-primary py-3 rounded-xl text-white font-medium hover:scale-105 transition-transform duration-300">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <script>
        const mainImage = document.getElementById('mainImage');
        const mainVideo = document.getElementById('mainVideo');
        const thumbnails = document.querySelectorAll('.thumbnail-item');

        if (thumbnails.length > 0 && mainImage && mainVideo) {
            setMedia(thumbnails[0]);
        } else if (mainImage) {
            @if(isset($game->cover_image) && $game->cover_image)
            mainImage.src = "{{ asset('storage/' . $game->cover_image) }}";
            mainImage.classList.remove('hidden');
            @endif
        }

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                if (mainImage && mainVideo) {
                    setMedia(thumb);
                }
            });
        });

        function setMedia(thumb) {
            if (!mainImage || !mainVideo) return;
            
            thumbnails.forEach(t => {
                t.classList.remove('border-neon-cyan');
                t.classList.add('border-transparent');
            });
            thumb.classList.add('border-neon-cyan');
            thumb.classList.remove('border-transparent');

            const type = thumb.dataset.type;
            const src = thumb.dataset.src;

            if (type === 'video') {
                mainImage.classList.add('hidden');
                mainVideo.classList.remove('hidden');
                if (mainVideo.src !== new URL(src, window.location.href).href) {
                    mainVideo.src = src;
                }
                mainVideo.play().catch(e => console.log("Auto-play prevented", e));
            } else {
                mainVideo.pause();
                mainVideo.classList.add('hidden');
                mainImage.classList.remove('hidden');
                mainImage.src = src;
            }
        }

        // Slider functionality
        const thumbnailsContainer = document.getElementById('thumbnailsContainer');
        const scrollLeftBtn = document.getElementById('scrollLeftBtn');
        const scrollRightBtn = document.getElementById('scrollRightBtn');

        if (scrollLeftBtn && scrollRightBtn && thumbnailsContainer) {
            const scrollAmount = 200;
            
            scrollLeftBtn.addEventListener('click', () => {
                thumbnailsContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            
            scrollRightBtn.addEventListener('click', () => {
                thumbnailsContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
    </script>
    @endpush
</x-app-layout>
