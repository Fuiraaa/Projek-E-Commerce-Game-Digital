<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary inline-flex items-center px-4 py-2 rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-neon-blue focus:ring-offset-2 focus:ring-offset-dark-bg transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
