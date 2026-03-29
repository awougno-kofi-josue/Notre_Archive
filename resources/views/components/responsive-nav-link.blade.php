@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2.5
       border-l-4 border-[#c9a84c]
       text-sm font-semibold text-[#c9a84c]
       bg-[#c9a84c]/10
       focus:outline-none transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2.5
       border-l-4 border-transparent
       text-sm font-medium text-white/70
       hover:text-white hover:bg-white/5 hover:border-white/30
       focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>