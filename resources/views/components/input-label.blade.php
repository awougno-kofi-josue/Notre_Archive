@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block text-xs font-semibold uppercase tracking-[.07em] text-[#0d1b2a] mb-1.5'
]) }}>
    {{ $value ?? $slot }}
</label>