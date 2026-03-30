<x-app-layout>
    @section('title', ' - Ajouter un document')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter un document
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
        .af-size-error {
            display:none;
            margin-top:.5rem;
            border:1px solid #f3a8a8;
            background:#fff1f1;
            color:#8a1c1c;
            border-radius:3px;
            padding:.7rem .8rem;
            font-size:.82rem;
            font-weight:700;
        }
        .af-size-error.is-visible {
            display:block;
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
                Ajouter un <span style="color:var(--gold);">document</span>
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

                        <form id="document-upload-form" action="{{ url('/documents') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="af-row">
                                <div class="af-field">
                                    <label for="parcours">Parcours</label>
                                    <select name="parcours_id" id="parcours" class="af-select" required>
                                        <option value="">Selectionner un parcours</option>
                                        @foreach($parcours as $p)
                                            <option value="{{ $p->id }}" @selected(old('parcours_id') == $p->id)>{{ $p->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('parcours_id')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="af-field">
                                    <label for="niveau">Annee</label>
                                    <select
                                        name="niveau_id"
                                        id="niveau"
                                        class="af-select"
                                        data-selected="{{ old('niveau_id') }}"
                                        required
                                    >
                                        <option value="">Selectionner une annee</option>
                                        @foreach($annees as $a)
                                            <option value="{{ $a->id }}" data-parcours-id="{{ $a->parcours_id }}">
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
                                    <input type="text" name="titre" id="titre" class="af-input" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="af-field">
                                    <label for="fichier">Fichier</label>
                                    <input type="file" class="af-file" id="fichier" name="fichier" accept=".pdf" required>
                                    <div id="fichier-size-error" class="af-size-error" role="alert" aria-live="assertive"></div>
                                    @error('fichier')
                                        <div class="af-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="af-field mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="af-textarea" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <p class="af-help">
                                Taille maximale: 5 Mo. Si le fichier depasse 5 Mo, compressez-le ici:
                                <a href="https://www.ilovepdf.com/fr/compresser_pdf" target="_blank" rel="noopener noreferrer">
                                    https://www.ilovepdf.com/fr/compresser_pdf
                                </a>
                            </p>

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
            const parcoursSelect = document.getElementById('parcours');
            const niveauSelect = document.getElementById('niveau');
            const form = document.getElementById('document-upload-form');
            const fileInput = document.getElementById('fichier');
            const fileSizeError = document.getElementById('fichier-size-error');
            const maxFileSizeBytes = 5 * 1024 * 1024;

            if (parcoursSelect && niveauSelect) {
                const defaultPlaceholder = 'Selectionner une annee';
                const emptyPlaceholder = 'Aucune annee pour ce parcours';
                const originalOptions = Array.from(niveauSelect.querySelectorAll('option[data-parcours-id]'))
                    .map((option) => option.cloneNode(true));
                const oldNiveau = niveauSelect.dataset.selected || '';

                function renderNiveaux(useOldValue) {
                    const selectedParcours = parcoursSelect.value;
                    const previousValue = useOldValue ? oldNiveau : '';

                    niveauSelect.innerHTML = '';

                    const placeholder = document.createElement('option');
                    placeholder.value = '';
                    placeholder.textContent = defaultPlaceholder;
                    niveauSelect.appendChild(placeholder);

                    if (!selectedParcours) {
                        niveauSelect.disabled = true;
                        return;
                    }

                    const matchedOptions = originalOptions.filter(
                        (option) => option.dataset.parcoursId === selectedParcours
                    );

                    if (!matchedOptions.length) {
                        placeholder.textContent = emptyPlaceholder;
                        niveauSelect.disabled = true;
                        return;
                    }

                    matchedOptions.forEach((option) => {
                        niveauSelect.appendChild(option.cloneNode(true));
                    });

                    niveauSelect.disabled = false;

                    if (previousValue && matchedOptions.some((option) => option.value === previousValue)) {
                        niveauSelect.value = previousValue;
                    }
                }

                parcoursSelect.addEventListener('change', function () {
                    renderNiveaux(false);
                });

                renderNiveaux(true);
            }

            function hideFileSizeError() {
                if (!fileSizeError) {
                    return;
                }

                fileSizeError.textContent = '';
                fileSizeError.classList.remove('is-visible');
            }

            function showFileSizeError() {
                if (!fileSizeError) {
                    return;
                }

                fileSizeError.textContent = 'Erreur: le fichier depasse 5 Mo. Compressez-le puis reessayez.';
                fileSizeError.classList.add('is-visible');
            }

            function isFileSizeValid() {
                if (!fileInput || !fileInput.files || !fileInput.files.length) {
                    hideFileSizeError();
                    return true;
                }

                const file = fileInput.files[0];
                if (file.size > maxFileSizeBytes) {
                    showFileSizeError();
                    return false;
                }

                hideFileSizeError();
                return true;
            }

            if (fileInput) {
                fileInput.addEventListener('change', isFileSizeValid);
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    if (!isFileSizeValid()) {
                        event.preventDefault();
                        fileInput.focus();
                    }
                });
            }
        })();
    </script>
</x-app-layout>
