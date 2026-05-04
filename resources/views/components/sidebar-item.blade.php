@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}" {{ $attributes->merge(['class' => "sidebar-item group relative flex items-center gap-3 pl-4 pr-4 py-2.5 text-gray-400 hover:text-white " . ($active ? 'active text-white' : '')]) }}>
    <div class="flex-shrink-0">
        {!! $icon !!}
    </div>
    <span :class="{ 'lg:hidden': sidebarCollapsed }" class="text-sm font-medium whitespace-nowrap">{{ $slot }}</span>
    <div x-show="sidebarCollapsed" x-cloak class="hidden lg:block absolute left-full ml-2 px-3 py-1.5 rounded-lg bg-[#0b1120] border border-white/10 text-sm text-white whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
        {{ $slot }}
    </div>
</a>
