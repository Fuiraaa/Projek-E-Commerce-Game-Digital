<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white neon-text">User Management</h1>
                <p class="text-gray-400 mt-1">Manage players and developers</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="glass-card p-4 mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex gap-2 flex-wrap">
                <a href="?role=all&search={{ urlencode($search) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $roleFilter === 'all' ? 'bg-neon-blue/20 text-neon-cyan border border-neon-blue/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10' }}">
                    All Users
                </a>
                <a href="?role=player&search={{ urlencode($search) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $roleFilter === 'player' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10' }}">
                    Players
                </a>
                <a href="?role=developer&search={{ urlencode($search) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $roleFilter === 'developer' ? 'bg-neon-violet/20 text-neon-violet border border-neon-violet/30' : 'bg-white/5 text-gray-400 border border-transparent hover:bg-white/10' }}">
                    Developers
                </a>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 flex gap-2 sm:justify-end">
                <input type="hidden" name="role" value="{{ $roleFilter }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..." class="flex-1 sm:w-64 px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm placeholder-gray-500 focus:border-neon-blue outline-none" style="background-color: rgba(30,41,59,0.5);">
                <button type="submit" class="px-4 py-2 rounded-lg bg-neon-blue/20 text-neon-cyan text-sm hover:bg-neon-blue/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">User</th>
                            <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Role</th>
                            <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Status</th>
                            <th class="text-left py-4 px-4 text-sm font-medium text-gray-400">Registered</th>
                            <th class="text-right py-4 px-4 text-sm font-medium text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-heading font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($user->role === 'player')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Player</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-neon-violet/20 text-neon-violet">Developer</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($user->is_suspended)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Suspended</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Active</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-gray-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-lg hover:bg-blue-500/20 text-blue-400 transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.users.toggle-suspend', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg {{ $user->is_suspended ? 'hover:bg-green-500/20 text-green-400' : 'hover:bg-yellow-500/20 text-yellow-400' }} transition-colors" title="{{ $user->is_suspended ? 'Unsuspend' : 'Suspend' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->is_suspended ? 'M18.364 5.636a9 9 0 11-12.728 0M12 9v4m0 4h.01' : 'M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 text-red-400 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
