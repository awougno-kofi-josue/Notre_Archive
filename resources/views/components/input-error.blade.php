@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-0.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1 text-xs text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm.93 9.28-.022.015A.75.75 0 0 1 7.62 9.5v-3a.75.75 0 0 1 1.5 0v3a.75.75 0 0 1-.19.53zM8 5.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5z"/>
                </svg>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif