@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'w-full
                    border border-[#d9d2c8] rounded-sm
                    bg-white text-[#0d1b2a]
                    px-3.5 py-2.5 text-sm
                    placeholder-[#a0aec0]
                    focus:border-[#c9a84c] focus:ring focus:ring-[#c9a84c]/20 focus:outline-none
                    disabled:bg-[#f7f3ed] disabled:cursor-not-allowed
                    transition duration-150 ease-in-out'
    ]) }}
>