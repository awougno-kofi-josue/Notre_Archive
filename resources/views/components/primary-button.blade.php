<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center gap-1.5 px-5 py-2.5
                bg-[#c9a84c] text-[#0d1b2a]
                border border-transparent rounded-sm
                font-semibold text-xs uppercase tracking-widest
                hover:bg-[#b8973f]
                focus:outline-none focus:ring-2 focus:ring-[#c9a84c] focus:ring-offset-2
                active:bg-[#a8872f]
                disabled:opacity-40 disabled:cursor-not-allowed
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>