<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Forum general</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Communaute</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Forum du site
                </h1>
            </div>
            @auth
                <a href="{{ route('forum.create') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-plus-lg"></i> Nouveau sujet
                </a>
            @else
                <a href="{{ route('login') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter pour poster
                </a>
            @endauth
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            @if(session('success'))
                <div class="af-alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="af-card">
                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Sujet</th>
                                <th>Auteur</th>
                                <th>Reponses</th>
                                <th>Derniere activite</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($threads as $thread)
                                <tr>
                                    <td style="font-weight:700;">
                                        @if($thread->is_pinned)
                                            <span class="af-badge-admin">Epingle</span>
                                        @endif
                                        {{ $thread->titre }}
                                    </td>
                                    <td>{{ $thread->user?->name ?? '-' }}</td>
                                    <td>{{ $thread->replies_count }}</td>
                                    <td style="color:var(--slate);">{{ $thread->updated_at?->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('forum.show', $thread) }}" class="af-btn af-btn-sm af-btn-navy">
                                            <i class="bi bi-chat-left-text"></i> Ouvrir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="af-table-empty">
                                        <i class="bi bi-chat"></i>
                                        Aucun sujet pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $threads->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
