<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Neboostla') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .sidebar-collapsed-mini { width: 5rem !important; min-width: 5rem !important; overflow: visible !important; }
        .mobile-sidebar-hidden { transform: translateX(-100%); }
        .desktop-sidebar-visible { transform: translateX(0) !important; }
        @media (min-width: 1024px) {
            .mobile-sidebar-hidden { transform: translateX(0); }
            .desktop-sidebar-static { position: static !important; }
            html.sidebar-is-collapsed aside { width: 5rem !important; min-width: 5rem !important; overflow: visible !important; }
            html.sidebar-is-collapsed aside .sidebar-item span { display: none !important; }
            html.sidebar-is-collapsed aside .sidebar-item-logout span:not(.material-icons) { display: none !important; }
            html.sidebar-is-collapsed aside .neon-text { display: none !important; }
        }
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
    </style>
</head>
<body class="font-body antialiased bg-dark-bg text-gray-200 scrollbar-hide">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" 
         x-init="$watch('sidebarCollapsed', val => { 
             localStorage.setItem('sidebarCollapsed', val);
             if (val) document.documentElement.classList.add('sidebar-is-collapsed');
             else document.documentElement.classList.remove('sidebar-is-collapsed');
         })" class="min-h-screen flex relative" style="z-index: 1;">
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 z-40 lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="{ 'desktop-sidebar-visible': sidebarOpen, 'sidebar-collapsed-mini': sidebarCollapsed }" class="fixed desktop-sidebar-static top-0 flex-shrink-0 inset-y-0 left-0 z-50 w-64 h-screen bg-[#0b1120] border-r border-white/5 flex flex-col transition-all duration-300 mobile-sidebar-hidden">
            <!-- Logo -->
            <div class="h-16 flex items-center px-4 border-b border-white/5 overflow-hidden">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                    <div :class="sidebarCollapsed ? 'lg:w-0 lg:opacity-0' : 'lg:w-32 lg:opacity-100'" class="transition-all duration-300 overflow-hidden flex items-center">
                        <span class="font-heading font-bold text-lg text-white neon-text whitespace-nowrap">Neboostla</span>
                    </div>
                </a>
            </div>

            <!-- Menu -->
            <nav class="flex-1 py-4 overflow-y-auto scrollbar-hide" :class="{ 'lg:overflow-visible sidebar-collapsed': sidebarCollapsed }">
                <x-sidebar-item href="{{ route('store.index') }}" icon="<i class='material-icons'>storefront</i>" :active="request()->routeIs('store.*')">Store</x-sidebar-item>

                @auth
                    @if(auth()->user()->isPlayer())
                        <x-sidebar-item href="{{ route('library.index') }}" icon="<i class='material-icons'>menu_book</i>" :active="request()->routeIs('library.*')">Library</x-sidebar-item>
                        <x-sidebar-item href="{{ route('cart.index') }}" icon="<i class='material-icons'>shopping_cart</i>" :active="request()->routeIs('cart.*')">Cart</x-sidebar-item>
                    @endif

                    @if(auth()->user()->isDeveloper())
                        @if(auth()->user()->is_verified)
                            <x-sidebar-item href="{{ route('developer.games.index') }}" icon="<i class='material-icons'>videogame_asset</i>" :active="request()->routeIs('developer.games.*')">My Games</x-sidebar-item>

                            <x-sidebar-item href="{{ route('developer.reports.index') }}" icon="<i class='material-icons'>bar_chart</i>" :active="request()->routeIs('developer.reports.*')">Reports</x-sidebar-item>
                        @else
                            <x-sidebar-item href="{{ route('developer.dashboard') }}" icon="<i class='material-icons'>warning</i>" :active="request()->routeIs('developer.dashboard')">Verification Status</x-sidebar-item>
                        @endif
                    @endif

                    @if(auth()->user()->isAdmin())
                        <x-sidebar-item href="{{ route('admin.dashboard') }}" icon="<i class='material-icons'>admin_panel_settings</i>" :active="request()->routeIs('admin.dashboard')">Admin Panel</x-sidebar-item>
                        <x-sidebar-item href="{{ route('admin.orders') }}" icon="<i class='material-icons'>shopping_bag</i>" :active="request()->routeIs('admin.orders')">Orders</x-sidebar-item>
                        <x-sidebar-item href="{{ route('admin.users.index') }}" icon="<i class='material-icons'>group</i>" :active="request()->routeIs('admin.users.*')">User Management</x-sidebar-item>
                    @else
                        <x-sidebar-item href="{{ route('wallet.index') }}" icon="<i class='material-icons'>account_balance_wallet</i>" :active="request()->routeIs('wallet.*')">Wallet</x-sidebar-item>
                    @endif

                    <div class="mt-4 pt-4 border-t border-white/5">
                        <x-sidebar-item href="{{ route('profile.edit') }}" icon="<i class='material-icons'>person</i>" :active="request()->routeIs('profile.*')">Profile</x-sidebar-item>
                    </div>
                @else
                    <x-sidebar-item href="{{ route('login') }}" icon="<i class='material-icons'>login</i>" :active="request()->routeIs('login')">Login</x-sidebar-item>

                    <x-sidebar-item href="{{ route('register') }}" icon="<i class='material-icons'>person_add</i>" :active="request()->routeIs('register')">Register</x-sidebar-item>
                @endauth
            </nav>

            <!-- Logout (bottom of sidebar) -->
            @auth
                <div class="border-t border-white/5 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-item-logout group relative w-full flex items-center gap-3 pl-4 pr-4 py-2.5 text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200">
                            <span class="material-icons flex-shrink-0">logout</span>
                            <span :class="{ 'lg:hidden': sidebarCollapsed }" class="text-sm font-medium whitespace-nowrap">Logout</span>
                            <div x-show="sidebarCollapsed" x-cloak class="hidden lg:block absolute left-full ml-2 px-3 py-1.5 rounded-lg bg-[#0b1120] border border-white/10 text-sm text-white whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                Logout
                            </div>
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Top Bar -->
            <header class="h-16 flex-shrink-0 bg-[#0b1120]/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-4 lg:px-6 z-30">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-white/5 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2 rounded-lg hover:bg-white/5 text-gray-400">
                    <svg x-show="!sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    <svg x-show="sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                </button>

                <div class="flex items-center gap-4 ml-auto">
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg glass-card">
                                <svg class="w-4 h-4 text-neon-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium text-neon-cyan">Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center">
                                <span class="text-white font-heading font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto scrollbar-hide flex flex-col">
                <div class="flex-1 p-4 lg:p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>

                @if(isset($footer))
                    {{ $footer }}
                @endif
            </main>
        </div>
    </div>

    <!-- Global Background Music Player -->
    <div class="fixed bottom-6 right-6 z-50">
        <audio id="bgMusic" loop>
            <source src="{{ asset('musics/the_mountain-space-discovery-179468.mp3') }}" type="audio/mpeg">
        </audio>
        <button id="bgMusicToggle" class="w-12 h-12 rounded-full bg-[#0b1120]/80 backdrop-blur-xl border border-white/10 flex items-center justify-center text-gray-400 hover:text-neon-cyan hover:border-neon-cyan transition-all duration-300 shadow-lg shadow-black/50 group">
            <span class="material-icons" id="bgMusicIcon">volume_off</span>
            
            <!-- Tooltip -->
            <span class="absolute -top-10 right-0 bg-[#0b1120] border border-white/10 text-xs text-white px-3 py-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                Background Music
            </span>
        </button>
    </div>

    <script src="{{ asset('js/space-bg.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.2/lib/anime.min.js"></script>
    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bgMusic = document.getElementById('bgMusic');
            const bgMusicToggle = document.getElementById('bgMusicToggle');
            const bgMusicIcon = document.getElementById('bgMusicIcon');
            let isMusicIntentionallyPaused = true;
            
            bgMusic.volume = 0.3; // Set background volume to 30% so it's not too loud
            
            const musicPref = localStorage.getItem('bgMusicEnabled');
            const savedTime = localStorage.getItem('bgMusicTime');
            
            // Restore playback time from previous page
            if (savedTime) {
                bgMusic.currentTime = parseFloat(savedTime);
            }
            
            if (musicPref === 'true') {
                bgMusic.play().then(() => {
                    isMusicIntentionallyPaused = false;
                    bgMusicIcon.textContent = 'volume_up';
                    bgMusicToggle.classList.add('text-neon-cyan', 'border-neon-cyan');
                }).catch(e => {
                    console.log('Autoplay prevented by browser', e);
                    isMusicIntentionallyPaused = true;
                });
            }

            bgMusicToggle.addEventListener('click', () => {
                if (bgMusic.paused) {
                    bgMusic.play();
                    isMusicIntentionallyPaused = false;
                    bgMusicIcon.textContent = 'volume_up';
                    bgMusicToggle.classList.add('text-neon-cyan', 'border-neon-cyan');
                    localStorage.setItem('bgMusicEnabled', 'true');
                } else {
                    bgMusic.pause();
                    isMusicIntentionallyPaused = true;
                    bgMusicIcon.textContent = 'volume_off';
                    bgMusicToggle.classList.remove('text-neon-cyan', 'border-neon-cyan');
                    localStorage.setItem('bgMusicEnabled', 'false');
                }
            });

            // Listen to all video events globally using capture phase
            document.addEventListener('play', (e) => {
                if (e.target.tagName === 'VIDEO') {
                    if (!bgMusic.paused) {
                        bgMusic.pause();
                        bgMusicIcon.textContent = 'volume_off';
                    }
                }
            }, true);

            document.addEventListener('pause', (e) => {
                if (e.target.tagName === 'VIDEO') {
                    // Only resume if user had music enabled previously
                    if (!isMusicIntentionallyPaused) {
                        bgMusic.play();
                        bgMusicIcon.textContent = 'volume_up';
                    }
                }
            }, true);

            document.addEventListener('ended', (e) => {
                if (e.target.tagName === 'VIDEO') {
                    if (!isMusicIntentionallyPaused) {
                        bgMusic.play();
                        bgMusicIcon.textContent = 'volume_up';
                    }
                }
            }, true);

            // Save playback position before navigating to a new page
            window.addEventListener('beforeunload', () => {
                localStorage.setItem('bgMusicTime', bgMusic.currentTime);
            });
            
            // Save periodically as a fallback
            setInterval(() => {
                if (!bgMusic.paused) {
                    localStorage.setItem('bgMusicTime', bgMusic.currentTime);
                }
            }, 1000);
        });
    </script>
    @stack('scripts')
</body>
</html>
