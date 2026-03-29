<a {{ $attributes->merge([
    'class' => 'block w-full px-4 py-2.5 text-left text-sm
                text-[#0d1b2a] font-medium
                hover:bg-[#f7f3ed] hover:text-[#c9a84c]
                focus:outline-none focus:bg-[#f7f3ed]
                border-l-2 border-transparent hover:border-[#c9a84c]
                transition duration-150 ease-in-out'
]) }}>
    {{ $slot }}
</a>