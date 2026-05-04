<x-app-layout>
    <div class="max-w-7xl mx-auto flex items-center justify-center min-h-[60vh]">
        <div class="glass-card p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-neon-blue to-neon-violet flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <h1 class="font-heading text-2xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-400 mt-2">You're logged into Nebula Store.</p>
        </div>
    </div>
</x-app-layout>
