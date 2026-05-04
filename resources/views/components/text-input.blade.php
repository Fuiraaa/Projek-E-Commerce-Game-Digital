@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-1 focus:ring-neon-blue outline-none transition-colors']) }}>
