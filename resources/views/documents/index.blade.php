<x-app-layout>
    <x-slot name="header">
        @section('title', ' - Liste des documents')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Liste des documents
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
            padding: 2.5rem 0;
        }
        .af-hero-bar::before {
            content:'';
            position:absolute;
            inset:0;
            background-image: repeating-linear-gradient(45deg,rgba(201,168,76,.05) 0,rgba(201,168,76,.05) 1px,transparent 0,transparent 50%);
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

        .af-filter-bar { background:#fff; border-bottom:1px solid #e8e2da; padding:1rem 0; }
        .af-select {
            font-family:'DM Sans',sans-serif;
            background-color:#fff;
            border:1px solid #d9d2c8;
            border-radius:3px;
            padding:.5rem .9rem;
            font-size:.875rem;
            color:var(--navy);
            outline:none;
            transition:border-color .2s;
        }
        .af-select:focus { border-color:var(--gold); }
        .af-btn-add {
            background-color:var(--gold);
            color:var(--navy);
            border:none;
            border-radius:3px;
            padding:.5rem 1.2rem;
            font-size:.8rem;
            font-weight:700;
            letter-spacing:.06em;
            text-transform:uppercase;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:.4rem;
            transition:background .2s;
        }
        .af-btn-add:hover { background-color:#b8973f; color:var(--navy); }

        .doc-card {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:4px;
            padding:1.75rem;
            display:flex;
            flex-direction:column;
            height:100%;
            transition:box-shadow .25s, border-color .25s, transform .25s;
        }
        .doc-card:hover {
            box-shadow:0 8px 28px rgba(13,27,42,.09);
            border-color:var(--gold);
            transform:translateY(-3px);
        }
        .doc-card-icon {
            width:40px;
            height:40px;
            background-color:var(--navy);
            border-radius:3px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:1.1rem;
            color:var(--gold);
            margin-bottom:1rem;
            flex-shrink:0;
        }
        .doc-title {
            font-family:'Playfair Display',serif;
            font-size:1.05rem;
            font-weight:700;
            color:var(--navy);
            margin-bottom:.6rem;
        }
        .doc-meta {
            font-size:.8rem;
            color:var(--slate);
            display:flex;
            align-items:center;
            gap:.35rem;
            margin-bottom:.3rem;
        }
        .doc-desc {
            font-size:.85rem;
            color:var(--slate);
            line-height:1.6;
            flex-grow:1;
            margin-top:.5rem;
            display:-webkit-box;
            -webkit-line-clamp:3;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }
        .doc-actions {
            margin-top:1.25rem;
            display:flex;
            gap:.6rem;
            flex-wrap:wrap;
        }
        .doc-actions form { flex:1; }
        .doc-btn {
            width:100%;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:.4rem;
            padding:.55rem .5rem;
            border-radius:3px;
            font-size:.78rem;
            font-weight:600;
            letter-spacing:.05em;
            text-transform:uppercase;
            text-decoration:none;
            transition:background .2s;
        }
        .doc-btn-open  { background:var(--navy); color:#fff; }
        .doc-btn-open:hover { background:#1b3a6b; color:#fff; }
        .doc-btn-dl    { background:var(--gold); color:var(--navy); }
        .doc-btn-dl:hover { background:#b8973f; color:var(--navy); }
        .doc-btn-delete { background:#c0392b; color:#fff; border:none; cursor:pointer; }
        .doc-btn-delete:hover { background:#a93226; color:#fff; }

        .af-flash {
            background:#fff;
            border:1px solid rgba(201,168,76,.35);
            border-left:4px solid var(--gold);
            border-radius:3px;
            color:var(--navy);
            padding:.9rem 1rem;
            margin-bottom:1rem;
            font-size:.9rem;
        }
        .af-empty {
            text-align:center;
            padding:4rem 1rem;
            color:var(--slate);
            font-size:.95rem;
        }
        .af-empty i {
            font-size:3rem;
            color:#d9d2c8;
            display:block;
            margin-bottom:1rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3"
             style="font-family:'DM Sans',sans-serif;">
            <div>
                <div class="af-label mb-1">Bibliotheque</div>
                <h1 style="font-family:'Playfair Display',serif; font-size:clamp(1.6rem,3vw,2.2rem); font-weight:900; color:#fff; margin:0; line-height:1.1;">
                    Liste des <span style="color:var(--gold);">documents</span>
                </h1>
            </div>
            @auth
                <a href="{{ route('documents.create') }}" class="af-btn-add">
                    <i class="bi bi-plus-lg"></i> Ajouter un document
                </a>
            @else
                <a href="{{ route('login') }}" class="af-btn-add">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </a>
            @endauth
        </div>
    </div>

    <div class="af-filter-bar">
        <div class="container">
            <form id="documents-filter-form" method="GET" action="{{ route('documents.index') }}"
                  style="display:flex; flex-wrap:wrap; gap:.75rem; align-items:center; font-family:'DM Sans',sans-serif;">
                @if($lockedParcours)
                    <input type="hidden" name="parcours_id" value="{{ $lockedParcours->id }}">
                    <div class="af-select" style="background:#f8f5ef;">
                        Parcours: {{ $lockedParcours->nom }}
                    </div>
                @else
                    <select name="parcours_id" class="af-select">
                        <option value="">Tous les parcours</option>
                        @foreach($parcoursList as $parcours)
                            <option value="{{ $parcours->id }}" {{ request('parcours_id') == $parcours->id ? 'selected' : '' }}>
                                {{ $parcours->nom }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <select name="annee_id" class="af-select">
                    <option value="">Toutes les annees</option>
                    @foreach($anneesList as $annee)
                        <option value="{{ $annee->id }}" {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                            {{ $annee->nom }} ({{ $annee->parcours->nom ?? '' }})
                        </option>
                    @endforeach
                </select>

                @if($canFilterByUser)
                    <select name="user_id" class="af-select">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($usersList as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                @if((!$lockedParcours) && (request('parcours_id') || request('annee_id') || request('user_id')))
                <a href="{{ route('documents.index') }}"
                   style="font-size:.78rem; color:var(--slate); text-decoration:none; letter-spacing:.04em;">
                    <i class="bi bi-x-circle me-1"></i>Reinitialiser
                </a>
                @endif
            </form>
        </div>
    </div>

    <div style="background-color:var(--cream); padding:3rem 0; font-family:'DM Sans',sans-serif;">
        <div class="container">
            @if(session('success'))
                <div class="af-flash">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="af-flash" style="border-color:rgba(192,57,43,.35); border-left-color:#c0392b;">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                </div>
            @endif

            <div class="row g-4">
                @forelse($documents as $doc)
                <div class="col-md-6 col-lg-4">
                    <div class="doc-card">
                        <div class="doc-card-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="doc-title">{{ $doc->titre }}</div>
                        <div class="doc-meta">
                            <i class="bi bi-diagram-3" style="color:var(--gold);"></i>
                            {{ $doc->parcours?->nom ?? 'N/A' }}
                        </div>
                        <div class="doc-meta">
                            <i class="bi bi-calendar3" style="color:var(--gold);"></i>
                            {{ $doc->niveau?->nom ?? 'N/A' }}
                        </div>
                        <div class="doc-meta">
                            <i class="bi bi-person" style="color:var(--gold);"></i>
                            {{ $doc->user?->name ?? 'Utilisateur inconnu' }}
                        </div>
                        <p class="doc-desc">{{ $doc->description }}</p>
                        <div class="doc-actions">
                            @auth
                                <a href="{{ route('documents.view', $doc->id) }}" target="_blank" class="doc-btn doc-btn-open">
                                    <i class="bi bi-eye"></i> Ouvrir
                                </a>
                                <a href="{{ route('documents.download', $doc->id) }}" class="doc-btn doc-btn-dl">
                                    <i class="bi bi-download"></i> Telecharger
                                </a>
                            @else
                                <a href="{{ route('documents.view', $doc->id) }}" class="doc-btn doc-btn-open">
                                    <i class="bi bi-lock"></i> Connexion requise
                                </a>
                                <a href="{{ route('documents.download', $doc->id) }}" class="doc-btn doc-btn-dl">
                                    <i class="bi bi-download"></i> Telecharger
                                </a>
                            @endauth
                            @can('delete', $doc)
                                <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="doc-btn doc-btn-delete">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="af-empty">
                        <i class="bi bi-folder2-open"></i>
                        Aucun document trouve pour ces criteres.
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $documents->links() }}
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('documents-filter-form');
            if (!form) {
                return;
            }

            form.querySelectorAll('select').forEach((select) => {
                select.addEventListener('change', function () {
                    form.submit();
                });
            });
        })();
    </script>
</x-app-layout>
