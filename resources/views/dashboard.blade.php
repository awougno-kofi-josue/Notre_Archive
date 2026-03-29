<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    @once
    <style>
        :root {
            --navy:  #0d1b2a;
            --gold:  #c9a84c;
            --cream: #f7f3ed;
            --slate: #4a5568;
        }
        .af-section-label {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: .5rem;
        }
        .dash-card {
            border: 1px solid #e8e2da;
            border-radius: 4px;
            background: #fff;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: box-shadow .25s, border-color .25s, transform .25s;
            text-decoration: none;
        }
        .dash-card:hover {
            box-shadow: 0 8px 28px rgba(13,27,42,.09);
            border-color: var(--gold);
            transform: translateY(-3px);
        }
        .dash-icon {
            width: 48px; height: 48px;
            border-radius: 4px;
            background-color: var(--navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: var(--gold);
            margin-bottom: 1.25rem;
            flex-shrink: 0;
        }
        .dash-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: .5rem;
        }
        .dash-card-text {
            font-size: .88rem;
            color: var(--slate);
            line-height: 1.65;
            flex-grow: 1;
        }
        .dash-card-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: 1.25rem;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--gold);
            text-decoration: none;
        }
        .dash-card-link i { transition: transform .2s; }
        .dash-card:hover .dash-card-link i { transform: translateX(4px); }

        .stat-pill {
            background: rgba(201,168,76,.1);
            border: 1px solid rgba(201,168,76,.3);
            border-radius: 2px;
            padding: .2rem .6rem;
            font-size: .7rem;
            font-weight: 600;
            color: var(--gold);
            letter-spacing: .06em;
            text-transform: uppercase;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    {{-- ── WELCOME BANNER ── --}}
    <div style="
        background-color: var(--navy);
        border-bottom: 3px solid var(--gold);
        position: relative;
        overflow: hidden;
        padding: 3rem 0;
    ">
        <div style="
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(
                45deg,
                rgba(201,168,76,.05) 0, rgba(201,168,76,.05) 1px,
                transparent 0, transparent 50%
            );
            background-size: 28px 28px;
            pointer-events: none;
        "></div>
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3"
             style="font-family: 'DM Sans', sans-serif;">
            <div>
                <span class="af-section-label">Espace personnel</span>
                <h1 style="
                    font-family: 'Playfair Display', serif;
                    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
                    font-weight: 900;
                    color: #fff;
                    line-height: 1.15;
                    margin: 0;
                ">
                    Bonjour, <span style="color: var(--gold);">{{ Auth::user()->name }}</span> 👋
                </h1>
                <p style="color: rgba(255,255,255,.5); font-size: .9rem; margin-top: .5rem; margin-bottom: 0;">
                    Que souhaitez-vous faire aujourd'hui ?
                </p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: .72rem; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.35); margin-bottom: .3rem;">
                    Compte actif
                </div>
                <div style="
                    background: rgba(201,168,76,.15);
                    border: 1px solid rgba(201,168,76,.4);
                    border-radius: 3px;
                    padding: .3rem .9rem;
                    color: var(--gold);
                    font-size: .8rem;
                    font-weight: 600;
                    letter-spacing: .06em;
                ">
                    <i class="bi bi-patch-check-fill me-1"></i> Membre vérifié
                </div>
            </div>
        </div>
    </div>

    {{-- ── DASHBOARD CARDS ── --}}
    <div style="background-color: var(--cream); padding: 4rem 0; font-family: 'DM Sans', sans-serif;">
        <div class="container">

            <span class="af-section-label">Actions rapides</span>
            <h2 style="
                font-family: 'Playfair Display', serif;
                font-size: clamp(1.4rem, 2.5vw, 1.9rem);
                font-weight: 700;
                color: var(--navy);
                margin-bottom: 2rem;
            ">Que voulez-vous faire ?</h2>

            <div class="row g-4">

                {{-- Voir les documents --}}
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="dash-card-title">Voir les documents</div>
                            <span class="stat-pill">500+</span>
                        </div>
                        <p class="dash-card-text">
                            Consultez tous les documents auxquels vous avez accès — classés par parcours et année.
                        </p>
                        <a href="{{ route('documents.index') }}" class="dash-card-link">
                            Accéder <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Ajouter un document --}}
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="dash-card-title">Ajouter un document</div>
                        <p class="dash-card-text">
                            Contribuez à l'archive en déposant vos cours, épreuves ou autres ressources pédagogiques.
                        </p>
                        <a href="{{ route('documents.create') }}" class="dash-card-link">
                            Déposer <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Mon profil --}}
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="dash-card-title">Mon profil</div>
                        <p class="dash-card-text">
                            Modifiez vos informations personnelles, votre mot de passe et vos préférences de compte.
                        </p>
                        <a href="{{ route('profile.edit') }}" class="dash-card-link">
                            Modifier <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- ── QUICK INFO STRIP ── --}}
            <div class="row g-3 mt-2">
                @foreach ([
                    ['bi-clock-history',   'Dernière connexion',  'Aujourd\'hui'],
                    ['bi-file-earmark-check', 'Documents consultés', '—'],
                    ['bi-upload',          'Documents déposés',   '—'],
                ] as [$icon, $label, $value])
                <div class="col-md-4">
                    <div style="
                        background: #fff;
                        border: 1px solid #e8e2da;
                        border-radius: 4px;
                        padding: 1.1rem 1.5rem;
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                    ">
                        <i class="bi {{ $icon }}" style="font-size: 1.4rem; color: var(--gold);"></i>
                        <div>
                            <div style="font-size: .72rem; letter-spacing: .08em; text-transform: uppercase; color: var(--slate);">{{ $label }}</div>
                            <div style="font-weight: 600; color: var(--navy); font-size: .95rem; margin-top: .1rem;">{{ $value }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>