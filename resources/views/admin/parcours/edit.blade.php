<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier un parcours</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative">
            <div class="af-label mb-1">Administration · Parcours</div>
            <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                Modifier <span style="color:var(--gold);">{{ $parcour->nom }}</span>
            </h1>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div style="font-size:.78rem;color:var(--slate);margin-bottom:1.25rem;">
                        <a href="{{ route('admin.parcours.index') }}" style="color:var(--gold);text-decoration:none;">
                            <i class="bi bi-arrow-left me-1"></i>Retour à la liste
                        </a>
                    </div>

                    <div class="af-card">
                        <div class="af-label mb-1">Formulaire</div>
                        <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--navy);margin-bottom:1.75rem;">
                            Édition du parcours
                        </h2>

                        <form method="POST" action="{{ route('admin.parcours.update', $parcour) }}">
                            @csrf @method('PUT')

                            <div class="mb-5">
                                <label for="nom" class="af-form-label">Nom du parcours</label>
                                <input type="text" id="nom" name="nom"
                                       value="{{ old('nom', $parcour->nom) }}"
                                       class="af-input" required>
                                @error('nom')
                                <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display:flex;gap:.75rem;">
                                <button type="submit" class="af-btn af-btn-primary">
                                    <i class="bi bi-check-lg"></i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.parcours.index') }}" class="af-btn af-btn-ghost">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
