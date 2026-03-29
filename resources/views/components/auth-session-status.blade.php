@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
        'class' => 'flex items-center gap-2
                    px-4 py-3 mb-4
                    bg-[#c9a84c]/10 border border-[#c9a84c]/35 border-l-4 border-l-[#c9a84c]
                    rounded-sm text-sm text-[#0d1b2a] font-medium'
    ]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c9a84c] flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.28 5.78-3.5 3.5a.75.75 0 0 1-1.06 0l-1.5-1.5a.75.75 0 1 1 1.06-1.06l.97.97 2.97-2.97a.75.75 0 0 1 1.06 1.06z"/>
        </svg>
        {{ $status }}
    </div>
@endif