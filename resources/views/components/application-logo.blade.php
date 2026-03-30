{{--
    Application Logo (fichier local)
    Source : /public/logo.png
--}}
<img
    src="{{ asset('logo.png') }}"
    alt="Logo Notre Archive"
    {{ $attributes->merge(['class' => 'object-contain']) }}
>
