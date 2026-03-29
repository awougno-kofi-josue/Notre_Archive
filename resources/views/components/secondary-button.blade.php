<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center gap-1.5 px-5 py-2.5
                bg-transparent text-[#4a5568]
                border border-[#d9d2c8] rounded-sm
                font-semibold text-xs uppercase tracking-widest
                hover:border-[#0d1b2a] hover:text-[#0d1b2a]
                focus:outline-none focus:ring-2 focus:ring-[#c9a84c] focus:ring-offset-2
                disabled:opacity-40 disabled:cursor-not-allowed
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>