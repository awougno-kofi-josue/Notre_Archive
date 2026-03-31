<x-app-layout>
    @section('title', ' - Modifier un document')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier un document
        </h2>
    </x-slot>

    @once
    <style>
        :root { --navy:#0d1b2a; --gold:#c9a84c; --cream:#f7f3ed; --slate:#4a5568; }

        .af-hero-bar {
            background-color: var(--navy);
            border-bottom: 3px solid var(--gold);
            position: relative;
            overflow: hidden;
            padding: 2.25rem 0;
        }
        .af-hero-bar::before {
            content:'';
            position:absolute;
            inset:0;
            background-image: repeating-linear-gradient(45deg, rgba(201,168,76,.05) 0, rgba(201,168,76,.05) 1px, transparent 0, transparent 50%);
            background-size:28px 28px;
            pointer-events:none;
        }
        .af-label {
            font-size:.72rem;
            font-weight:600;
            letter-spacing:.15em;
            text-transform:uppercase;
            color:var(--gold);
        }
        .af-page {
            background-color: var(--cream);
            padding: 2.5rem 0 3.5rem;
            font-family: 'DM Sans', sans-serif;
        }
        .af-card {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:4px;
            padding:1.5rem;
            box-shadow:0 8px 28px rgba(13,27,42,.06);
        }
        .af-section-title {
            font-family:'Playfair Display', serif;
            font-size:1.1rem;
            font-weight:700;
            color:var(--navy);
            margin-bottom:1rem;
        }
        .af-row {
            display:grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap:1rem;
        }
        .af-field label {
            display:block;
            font-size:.82rem;
            font-weight:600;
            letter-spacing:.03em;
            text-transform:uppercase;
            color:var(--slate);
            margin-bottom:.4rem;
        }
        .af-input, .af-select, .af-textarea, .af-file {
            width:100%;
            border:1px solid #d9d2c8;
            border-radius:3px;
            padding:.62rem .75rem;
            font-size:.92rem;
            color:var(--navy);
            background:#fff;
            outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .af-textarea {
            min-height:110px;
            resize:vertical;
        }
        .af-input:focus, .af-select:focus, .af-textarea:focus, .af-file:focus {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,168,76,.18);
        }
        .af-help {
            font-size:.8rem;
            color:var(--slate);
            margin-top:.5rem;
        }
        .af-help a {
            color:#1b3a6b;
            font-weight:700;
            text-decoration:underline;
        }
        .af-error {
            font-size:.8rem;
            color:#b42318;
            margin-top:.4rem;
        }
        .af-errors-box {
            border:1px solid #f5c2c7;
            background:#fff5f5;
            color:#842029;
            border-radius:3px;
            padding:.85rem 1rem;
            font-size:.88rem;
            margin-bottom:1rem;
        }
        .af-actions {
            margin-top:1.35rem;
            display:flex;
            flex-wrap:wrap;
            gap:.75rem;
        }
        .af-btn-primary {
            background-color:var(--navy);
            color:#fff;
            border:none;
            border-radius:3px;
            padding:.65rem 1.2rem;
            font-size:.82rem;
            font-weight:700;
            letter-spacing:.06em;
            text-transform:uppercase;
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            transition:background .2s;
            text-decoration:none;
            cursor:pointer;
        }
        .af-btn-primary:hover { background-color:#1b3a6b; color:#fff; }
        .af-btn-secondary {
            background-color:var(--gold);
            color:var(--navy);
            border:none;
            border-radius:3px;
            padding:.65rem 1.2rem;
            font-size:.82rem;
            font-weight:700;
            letter-spacing:.06em;
            text-transform:uppercase;
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            transition:background .2s;
            text-decoration:none;
        }
        .af-btn-secondary:hover { background-color:#b8973f; color:var(--navy); }

        @media (max-width: 991.98px) {
            .af-row { grid-template-columns:1fr; }
        }
        @media (max-width: 575.98px) {
            .af-card { padding:1rem; }
            .af-actions { flex-direction:column; }
            .af-btn-primary, .af-btn-secondary { justify-content:center; width:100%; }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    <div class="af-hero-bar">
        <div class="container position-relative" style="font-family:'DM Sans',sans-serif;">
            <div class="af-label mb-1">Bibliotheque</div>
            <h1 style="font-family:'Playfair Display',serif; font-size:clamp(1.6rem,3vw,2.2rem); font-weight:900; color:#fff; margin:0; line-height:1.1;">
                Modifier un <span style="color:var(--gold);">document</span>
            </h1>
        </div>
    </div>

    <div class="af-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <div class="af-card">
                        <div class="af-section-title">
                            Informations du document
                        </div>

                        @if ($errors->any())
                            <div class="af-errors-box">
                                Merci de corriger les erreurs du formulaire.
                            </div>
                        @endif

                        <form id="document-edit-form" action="{{ route('documents.update', $document->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="af-row">
                                <div class="af-field">
                                    <label for="parcours">Parcours</label>
                                    <select name="parcours_id" id="parcours" class="af-select" disabled>
                                        @foreach($parcours as $p)
                                            <option
                                                value="{{ $p->id }}"
                                                @selected($document->parcours_id == $p->id)
                                            >
                                                {{ $p->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="af-help">Le parcours ne peut pas être modifié</div>
                                </div>

                                <div class="af-field">
                                    <label for="niveau">Annee</label>
                                    <select
                                        name="niveau_id"
                                        id="niveau"
                                        class="af-select"
                                        data-selected="{{ old('niveau_id', $document->niveau_id) }}"
                                        required
                                    >
                                        <option value="">Selectionner une annee</option>
                                        @foreach($annees as $a)
                                            <option
                                                value="{{ $a->id }}"
                                                data-parcours-id="{{ $a->parcours_id }}"
                                                @selected(old('niveau_id', $document->niveau_id) == $a->id)
                                            >
                                                {{ $a->nom }} ({{ $a->parcours ? $a->parcours->nom : 'Parcours non defini' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('niveau_id')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="af-row mt-3">
                                <div class="af-field">
                                    <label for="titre">Titre</label>
                                    <input type="text" name="titre" id="titre" class="af-input" value="{{ old('titre', $document->titre) }}" required>
                                    @error('titre')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="af-field">
                                    <label for="document_type_id">Type de document</label>
                                    <select name="document_type_id" id="document_type_id" class="af-select" required>
                                        <option value="">Selectionner un type</option>
                                        @foreach($documentTypes as $documentType)
                                            <option
                                                value="{{ $documentType->id }}"
                                                @selected(old('document_type_id', $document->document_type_id) == $documentType->id)
                                            >
                                                {{ $documentType->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_type_id')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="af-field mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="af-textarea" required>{{ old('description', $document->description) }}</textarea>
                                @error('description')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="af-actions">
                                <button type="submit" class="af-btn-primary">
                                    <i class="bi bi-check2-circle"></i> Enregistrer
                                </button>
                                <a href="{{ route('documents.index') }}" class="af-btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const niveauSelect = document.getElementById('niveau');
            const originalOptions = Array.from(niveauSelect.querySelectorAll('option[data-parcours-id]'))
                .map((option) => option.cloneNode(true));
            const oldNiveau = niveauSelect.dataset.selected || '';

            function renderNiveaux(useOldValue) {
                const previousValue = useOldValue ? oldNiveau : '';

                niveauSelect.innerHTML = '';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Selectionner une annee';
                niveauSelect.appendChild(placeholder);

                const matchedOptions = originalOptions.filter(
                    (option) => option.dataset.parcoursId === '{{ $document->parcours_id }}'
                );

                matchedOptions.forEach((option) => {
                    const clone = option.cloneNode(true);
                    if (previousValue && clone.value === previousValue) {
                        clone.selected = true;
                    }
                    niveauSelect.appendChild(clone);
                });

                niveauSelect.disabled = matchedOptions.length === 0;
            }

            renderNiveaux(true);
        })();
    </script>
</x-app-layout>
