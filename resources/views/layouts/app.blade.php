<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nebula Store') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
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
<body class="font-body antialiased bg-dark-bg text-gray-200">
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen flex">
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 z-40 lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'" x-transition:enter="transition-transform ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#0b1120] border-r border-white/5 flex flex-col transition-all duration-300">
            <!-- Logo -->
            <div class="h-16 flex items-center px-4 border-b border-white/5">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center">
                        <span class="text-white font-heading font-bold text-sm">N</span>
                    </div>
                    <span :class="sidebarCollapsed ? 'lg:hidden' : ''" class="font-heading font-bold text-lg text-white neon-text">Nebula Store</span>
                </a>
            </div>

            <!-- Menu -->
            <nav class="flex-1 py-4 overflow-y-auto" :class="{ 'lg:overflow-visible sidebar-collapsed': sidebarCollapsed }">
                <x-sidebar-item href="{{ route('store.index') }}" :icon="`<span class='material-icons'>storefront</span>`" :active="request()->routeIs('store.*')">Store</x-sidebar-item>

                @auth
                    @if(auth()->user()->isPlayer())
                        <x-sidebar-item href="{{ route('library.index') }}" :icon="`<span class='material-icons'>menu_book</span>`" :active="request()->routeIs('library.*')">Library</x-sidebar-item>
                    @endif

                    @if(auth()->user()->isDeveloper())
                        @if(auth()->user()->is_verified)
                            <x-sidebar-item href="{{ route('developer.games.index') }}" :icon="`<span class='material-icons'>videogame_asset</span>`" :active="request()->routeIs('developer.games.*')">My Games</x-sidebar-item>

                            <x-sidebar-item href="{{ route('developer.reports.index') }}" :icon="`<span class='material-icons'>bar_chart</span>`" :active="request()->routeIs('developer.reports.*')">Reports</x-sidebar-item>
                        @else
                            <x-sidebar-item href="{{ route('developer.dashboard') }}" :icon="`<span class='material-icons'>warning</span>`" :active="request()->routeIs('developer.dashboard')">Verification Status</x-sidebar-item>
                        @endif
                    @endif

                    @if(auth()->user()->isAdmin())
                        <x-sidebar-item href="{{ route('admin.dashboard') }}" :icon="`<span class='material-icons'>admin_panel_settings</span>`" :active="request()->routeIs('admin.dashboard')">Admin Panel</x-sidebar-item>

                        <x-sidebar-item href="{{ route('admin.users.index') }}" :icon="`<span class='material-icons'>group</span>`" :active="request()->routeIs('admin.users.*')">User Management</x-sidebar-item>
                    @else
                        <x-sidebar-item href="{{ route('wallet.index') }}" :icon="`<span class='material-icons'>account_balance_wallet</span>`" :active="request()->routeIs('wallet.*')">Wallet</x-sidebar-item>
                    @endif

                    <div class="mt-4 pt-4 border-t border-white/5">
                        <x-sidebar-item href="{{ route('profile.edit') }}" :icon="`<span class='material-icons'>person</span>`" :active="request()->routeIs('profile.*')">Profile</x-sidebar-item>
                    </div>
                @else
                    <x-sidebar-item href="{{ route('login') }}" :icon="`<span class='material-icons'>login</span>`" :active="request()->routeIs('login')">Login</x-sidebar-item>

                    <x-sidebar-item href="{{ route('register') }}" :icon="`<span class='material-icons'>person_add</span>`" :active="request()->routeIs('register')">Register</x-sidebar-item>
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
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar -->
            <header class="h-16 bg-[#0b1120]/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30">
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
            <main class="flex-1 p-4 lg:p-6 overflow-auto">
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
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
