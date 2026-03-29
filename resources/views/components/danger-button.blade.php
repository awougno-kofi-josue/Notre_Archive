<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center gap-1.5 px-5 py-2.5
                bg-[#c0392b] text-white
                border border-transparent rounded-sm
                font-semibold text-xs uppercase tracking-widest
                hover:bg-[#a93226]
                focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                active:bg-[#922b21]
                disabled:opacity-40 disabled:cursor-not-allowed
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>