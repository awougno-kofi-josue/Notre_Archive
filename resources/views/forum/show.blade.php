<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Discussion</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Forum</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    {{ $thread->titre }}
                </h1>
            </div>
            <a href="{{ route('forum.index') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Retour forum
            </a>
        </div>
    </div>

    <div class="af-body">
        <div class="container d-flex flex-column gap-4">
            @if(session('success'))
                <div class="af-alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="af-card">
                <div style="font-size:.82rem;color:var(--slate);margin-bottom:.5rem;">
                    Par {{ $thread->user?->name ?? '-' }} • {{ $thread->created_at?->format('d/m/Y H:i') }}
                </div>
                <div style="color:var(--navy);line-height:1.7;white-space:pre-wrap;">{{ $thread->contenu }}</div>
            </div>

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--navy);margin:0 0 1rem 0;">
                    Reponses
                </h2>

                <div style="display:grid;gap:.75rem;">
                    @forelse($thread->replies as $reply)
                        <div id="reply-{{ $reply->id }}" style="border:1px solid #e8e2da;border-radius:6px;padding:.8rem .9rem;background:#fffdfa;">
                            <div style="font-size:.8rem;color:var(--slate);margin-bottom:.35rem;">
                                {{ $reply->user?->name ?? '-' }} • {{ $reply->created_at?->format('d/m/Y H:i') }}
                            </div>
                            <div style="color:var(--navy);line-height:1.6;white-space:pre-wrap;">{{ $reply->contenu }}</div>
                        </div>
                    @empty
                        <div class="af-table-empty" style="padding:1.5rem;">
                            <i class="bi bi-chat"></i>
                            Aucune reponse pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--navy);margin:0 0 1rem 0;">
                    Ajouter une reponse
                </h2>
                @auth
                    <form method="POST" action="{{ route('forum.replies.store', $thread) }}">
                        @csrf
                        <textarea name="contenu" class="af-input" style="min-height:120px;" required>{{ old('contenu') }}</textarea>
                        @error('contenu')
                            <div class="af-error">{{ $message }}</div>
                        @enderror
                        <div style="margin-top:.8rem;">
                            <button type="submit" class="af-btn af-btn-navy">
                                <i class="bi bi-send"></i> Repondre
                            </button>
                        </div>
                    </form>
                @else
                    <div style="color:var(--slate);font-size:.9rem;">
                        Connectez-vous pour participer a la discussion.
                        <a href="{{ route('login') }}" style="color:var(--navy);font-weight:700;">Se connecter</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
