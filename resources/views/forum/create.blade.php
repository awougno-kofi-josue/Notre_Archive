<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau sujet</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Communaute</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Creer un sujet de discussion
                </h1>
            </div>
            <a href="{{ route('forum.index') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Retour forum
            </a>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="af-card">
                <form method="POST" action="{{ route('forum.store') }}">
                    @csrf
                    <div style="display:grid;gap:1rem;">
                        <div>
                            <label class="af-form-label" for="titre">Titre du sujet</label>
                            <input type="text" id="titre" name="titre" class="af-input" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="af-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="af-form-label" for="contenu">Contenu</label>
                            <textarea id="contenu" name="contenu" class="af-input" style="min-height:180px;" required>{{ old('contenu') }}</textarea>
                            @error('contenu')
                                <div class="af-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div style="margin-top:1rem;">
                        <button type="submit" class="af-btn af-btn-navy">
                            <i class="bi bi-send"></i> Publier le sujet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
