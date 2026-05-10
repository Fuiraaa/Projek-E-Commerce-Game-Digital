<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{
        allGames: {{ Js::from($allGames->map($toCard)) }},
        trendingGames: {{ Js::from($trendingGames->map($toCard)) }},
        newArrivals: {{ Js::from($newArrivals->map($toCard)) }},
        popularGames: {{ Js::from($popularGames) }},
        filter: 'all',
        search: '',
        get filteredGames() {
            let games;
            switch (this.filter) {
                case 'trending': games = this.trendingGames; break;
                case 'new': games = this.newArrivals; break;
                default: games = this.allGames; break;
            }
            if (this.search.length === 0) return games;
            const q = this.search.toLowerCase();
            return games.filter(g =>
                g.title.toLowerCase().includes(q) ||
                g.description.toLowerCase().includes(q) ||
                g.developer.toLowerCase().includes(q)
            );
        },
        page: 1,
        perPage: 8,
        get paginatedGames() {
            return this.filteredGames.slice((this.page - 1) * this.perPage, this.page * this.perPage);
        },
        get totalPages() { return Math.ceil(this.filteredGames.length / this.perPage); },
        nextPage() { if (this.page < this.totalPages) { this.page++; window.scrollTo({ top: 300, behavior: 'smooth' }); } },
        prevPage() { if (this.page > 1) { this.page--; window.scrollTo({ top: 300, behavior: 'smooth' }); } },
        init() {
            this.$watch('filter', () => this.page = 1);
            this.$watch('search', () => this.page = 1);
        }
    }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white neon-text">Game Store</h1>
                <p class="text-gray-400 mt-1">Browse and discover amazing games</p>
            </div>
        </div>

        <!-- Popular Games Carousel -->
        @if(count($popularGames) > 0)
            <div
                x-data="{
                    games: {{ Js::from($popularGames) }},
                    current: 0,
                    interval: null,
                    hovered: false,
                }"
                x-init="interval = setInterval(() => { if (!hovered) current = (current + 1) % games.length }, 3000)"
                @mouseenter="hovered = true"
                @mouseleave="hovered = false"
                class="mb-8 rounded-xl border bg-[#0b1120]/50 backdrop-blur-xl relative overflow-hidden transition-all duration-300"
                :style="hovered ? 'border-color: rgba(114,220,255,0.3); box-shadow: 0 0 20px rgba(114,220,255,0.1);' : 'border-color: rgba(255,255,255,0.1);'"
            >
                <div class="relative overflow-hidden" style="height: 280px;">
                    <template x-for="(game, index) in games" :key="game.id">
                        <div
                            :style="current === index ? 'opacity: 1; z-index: 5;' : 'opacity: 0; z-index: 0; pointer-events: none;'"
                            class="absolute inset-0 transition-opacity duration-500 ease-in-out"
                        >
                            <div class="flex flex-row h-full">
                            <div style="width: 40%; position: relative; overflow: hidden;">
                                <div style="width: 100%; height: 100%; overflow: hidden;">
                                    <template x-if="game.cover">
                                        <img :src="game.cover" :alt="game.title" :style="hovered ? 'transform: scale(1.05);' : 'transform: scale(1);'" class="w-full h-full object-cover transition-transform duration-500">
                                    </template>
                                    <template x-if="!game.cover">
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(139,92,246,0.2)); display: flex; align-items: center; justify-content: center;">
                                            <span class="material-icons" style="color: rgba(255,255,255,0.2); font-size: 64px;">videogame_asset</span>
                                        </div>
                                    </template>
                                </div>
                                <div style="position: absolute; top: 12px; left: 12px; padding: 4px 12px; border-radius: 9999px; background: rgba(139,92,246,0.9); color: white; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 4px; z-index: 10;">
                                    <span class="material-icons" style="font-size: 16px;">trending_up</span>
                                    <span x-text="game.purchases + ' sold'"></span>
                                </div>
                            </div>
                            <div style="width: 60%; padding: 24px; display: flex; flex-direction: column; justify-content: center;">
                                <h2 class="font-heading text-2xl font-bold text-white mb-2" x-text="game.title"></h2>
                                <p class="text-sm text-neon-cyan mb-3">by <span x-text="game.developer"></span></p>
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2" x-text="game.description"></p>
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <span class="text-2xl font-bold text-neon-violet">Rp <span x-text="game.price"></span></span>
                                    <a :href="'/store/' + game.slug" class="px-6 py-2.5 rounded-lg btn-primary text-white text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button @click="current = (current - 1 + games.length) % games.length" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); padding: 8px; border-radius: 9999px; background: rgba(0,0,0,0.5); color: white; z-index: 20;" class="hover:bg-black/70 transition-colors">
                    <span class="material-icons">chevron_left</span>
                </button>
                <button @click="current = (current + 1) % games.length" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); padding: 8px; border-radius: 9999px; background: rgba(0,0,0,0.5); color: white; z-index: 20;" class="hover:bg-black/70 transition-colors">
                    <span class="material-icons">chevron_right</span>
                </button>

                <div style="position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 20;">
                    <template x-for="(game, index) in games" :key="index">
                        <button
                            @click="current = index"
                            :style="current === index ? 'background: #72DCFF; width: 24px; height: 10px;' : 'background: rgba(255,255,255,0.3); width: 10px; height: 10px;'"
                            style="border-radius: 9999px; transition: all 0.3s ease;"
                        ></button>
                    </template>
                </div>
            </div>
        @endif

        <!-- Filters & Search -->
        <div class="glass-card p-4 mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex gap-2 flex-wrap">
                <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-neon-blue/20 text-neon-cyan border border-neon-blue/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    All Games
                </button>
                <button @click="filter = 'trending'" :class="filter === 'trending' ? 'bg-neon-blue/20 text-neon-cyan border border-neon-blue/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    Trending Now
                </button>
                <button @click="filter = 'new'" :class="filter === 'new' ? 'bg-neon-blue/20 text-neon-cyan border border-neon-blue/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    New Arrivals
                </button>
            </div>

            <div class="flex-1 flex gap-2 sm:justify-end">
                <input type="text" x-model="search" placeholder="Search games..." class="flex-1 sm:w-64 px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm placeholder-gray-500 focus:border-neon-blue outline-none" style="background-color: rgba(30,41,59,0.5);">
                <button x-show="search.length > 0" @click="search = ''" class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Games Grid -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-xl font-bold text-white">
                <template x-if="filter === 'trending'">
                    <span class="inline-flex items-center gap-2">
                        <span class="material-icons text-neon-cyan text-lg">trending_up</span>
                        Trending Now
                    </span>
                </template>
                <template x-if="filter === 'new'">
                    <span class="inline-flex items-center gap-2">
                        <span class="material-icons text-green-400 text-lg">new_releases</span>
                        New Arrivals
                    </span>
                </template>
                <template x-if="filter === 'all'">
                    All Games
                </template>
            </h2>
            <template x-if="search.length > 0">
                <span class="text-sm text-gray-400">Results for "<span class="text-neon-cyan" x-text="search"></span>"</span>
            </template>
        </div>

        <template x-if="filteredGames.length > 0">
            <div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="game in paginatedGames" :key="game.id">
                        <div class="glass-card glass-card-hover overflow-hidden group transition-all duration-300">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <template x-if="game.cover_image">
                                    <img :src="game.cover_image" :alt="game.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </template>
                                <template x-if="!game.cover_image">
                                    <div class="w-full h-full bg-gradient-to-br from-neon-blue/20 to-neon-violet/20 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </template>
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-neon-violet/80 text-white backdrop-blur-sm" x-text="'Rp ' + game.price"></span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-heading font-semibold text-white text-lg truncate" x-text="game.title"></h3>
                                <p class="text-gray-400 text-sm mt-1 line-clamp-2" x-text="game.description"></p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-gray-500" x-text="'by ' + game.developer"></span>
                                    <a :href="'/store/' + game.slug" class="px-4 py-2 rounded-lg btn-primary text-white text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Pagination Buttons -->
                <div class="mt-8 flex items-center justify-between border-t border-white/5 pt-6" x-show="totalPages > 1">
                    <p class="text-sm text-gray-400">
                        Showing <span x-text="(page - 1) * perPage + 1"></span> to <span x-text="Math.min(page * perPage, filteredGames.length)"></span> of <span x-text="filteredGames.length"></span>
                    </p>
                    <div class="flex items-center gap-2">
                        <button @click="prevPage" :disabled="page === 1" :class="page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-2 rounded-xl bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                            <span class="material-icons">chevron_left</span>
                        </button>
                        <span class="text-sm font-bold text-white px-4"><span x-text="page"></span> / <span x-text="totalPages"></span></span>
                        <button @click="nextPage" :disabled="page === totalPages" :class="page === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neon-blue/20'" class="p-2 rounded-xl bg-white/5 text-neon-cyan border border-neon-blue/30 transition-all flex items-center justify-center">
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="filteredGames.length === 0">
            <div class="glass-card p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <p class="mt-4 text-gray-500">
                    <template x-if="search.length > 0">No games found matching your search.</template>
                    <template x-if="search.length === 0">No games available in this category.</template>
                </p>
            </div>
        </template>
    </div>
    <x-slot name="footer">
        <x-footer />
    </x-slot>
</x-app-layout>
