<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter une année</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative">
            <div class="af-label mb-1">Administration · Années</div>
            <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                Ajouter une <span style="color:var(--gold);">année</span>
            </h1>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    {{-- breadcrumb --}}
                    <div style="font-size:.78rem;color:var(--slate);margin-bottom:1.25rem;">
                        <a href="{{ route('admin.niveaux.index') }}" style="color:var(--gold);text-decoration:none;">
                            <i class="bi bi-arrow-left me-1"></i>Retour à la liste
                        </a>
                    </div>

                    <div class="af-card">
                        <div class="af-label mb-1">Formulaire</div>
                        <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--navy);margin-bottom:1.75rem;">
                            Nouvelle année
                        </h2>

                        <form method="POST" action="{{ route('admin.niveaux.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="nom" class="af-form-label">Nom de l'année</label>
                                <input type="text" id="nom" name="nom"
                                       value="{{ old('nom') }}"
                                       class="af-input" placeholder="ex : Licence 1, Master 2…" required>
                                @error('nom')
                                <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="parcours_id" class="af-form-label">Parcours</label>
                                <select id="parcours_id" name="parcours_id" class="af-select" required>
                                    <option value="">— Sélectionner un parcours —</option>
                                    @foreach($parcours as $parcour)
                                    <option value="{{ $parcour->id }}" @selected(old('parcours_id') == $parcour->id)>
                                        {{ $parcour->nom }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('parcours_id')
                                <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display:flex;gap:.75rem;">
                                <button type="submit" class="af-btn af-btn-primary">
                                    <i class="bi bi-check-lg"></i> Enregistrer
                                </button>
                                <a href="{{ route('admin.niveaux.index') }}" class="af-btn af-btn-ghost">
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
