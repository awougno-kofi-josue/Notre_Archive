<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            À propos
        </h2>
    </x-slot>

    {{-- ── STYLES PARTAGÉS ── --}}
    @once
    <style>
        :root {
            --navy:   #0d1b2a;
            --indigo: #1b3a6b;
            --gold:   #c9a84c;
            --cream:  #f7f3ed;
            --slate:  #4a5568;
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
        .af-section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
        }
        .af-card {
            border: 1px solid #e8e2da;
            border-radius: 4px;
            background: #fff;
            padding: 2rem;
            height: 100%;
            transition: box-shadow .25s, border-color .25s;
        }
        .af-card:hover {
            box-shadow: 0 6px 24px rgba(13,27,42,.07);
            border-color: var(--gold);
        }
        .af-icon-box {
            width: 44px; height: 44px;
            border-radius: 4px;
            background-color: var(--navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }
        .af-badge {
            display: inline-block;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1px solid rgba(201,168,76,.4);
            padding: .25rem .7rem;
            border-radius: 2px;
            background: rgba(201,168,76,.06);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    {{-- ── HERO BANNER ── --}}
    <div style="
        background-color: var(--navy);
        border-bottom: 3px solid var(--gold);
        position: relative;
        overflow: hidden;
        padding: 3.5rem 0;
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
        <div class="container position-relative">
            <span class="af-section-label">Plateforme académique</span>
            <h1 style="
                font-family: 'Playfair Display', serif;
                font-size: clamp(2rem, 4vw, 3rem);
                font-weight: 900;
                color: #fff;
                line-height: 1.15;
                margin-bottom: .75rem;
            ">À propos de l'<span style="color: var(--gold);">Archive</span></h1>
            <p style="color: rgba(255,255,255,.55); font-size: 1rem; max-width: 500px; margin: 0;">
                Tout ce que vous devez savoir sur notre mission et nos fonctionnalités.
            </p>
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div style="background-color: var(--cream); padding: 4rem 0; font-family: 'DM Sans', sans-serif;">
        <div class="container">

            {{-- Intro card --}}
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="af-card" style="border-left: 4px solid var(--gold);">
                        <span class="af-badge mb-3 d-inline-block">Notre mission</span>
                        <p style="font-size: 1.05rem; color: var(--slate); line-height: 1.75; margin: 0;">
                            Bienvenue sur <strong style="color: var(--navy);">Archive Faculté</strong>.
                            Ce site permet de gérer les documents et les parcours des étudiants de manière
                            simple et efficace. Notre objectif est de faciliter l'accès aux ressources
                            pédagogiques et d'améliorer l'organisation académique pour tous.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Feature cards --}}
            <span class="af-section-label">Ce que vous pouvez faire</span>
            <h2 class="af-section-title mb-4">Fonctionnalités principales</h2>

            <div class="row g-4">
                @foreach ([
                    ['bi-cloud-download', 'Documents', 'Ajoutez, consultez et téléchargez des documents classés par parcours et par année.'],
                    ['bi-envelope-paper', 'Contact admin', 'Contactez l\'administrateur directement via notre formulaire de contact intégré.'],
                    ['bi-book-half', 'Ressources pédagogiques', 'Accédez à une bibliothèque de cours et d\'épreuves enrichie par toute la communauté.'],
                    ['bi-diagram-3', 'Organisation académique', 'Un système de classement structuré pour retrouver n\'importe quel document en quelques clics.'],
                ] as [$icon, $title, $text])
                <div class="col-sm-6 col-lg-3">
                    <div class="af-card text-center">
                        <div class="af-icon-box mx-auto">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <h3 style="
                            font-family: 'Playfair Display', serif;
                            font-size: 1.05rem;
                            font-weight: 700;
                            color: var(--navy);
                            margin-bottom: .5rem;
                        ">{{ $title }}</h3>
                        <p style="font-size: .88rem; color: var(--slate); line-height: 1.65; margin: 0;">{{ $text }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div style="
                margin-top: 3.5rem;
                background-color: var(--navy);
                border-radius: 4px;
                border-top: 3px solid var(--gold);
                padding: 2.5rem 2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 1.5rem;
            ">
                <div>
                    <div style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #fff;">
                        Prêt à explorer ?
                    </div>
                    <div style="color: rgba(255,255,255,.5); font-size: .9rem; margin-top: .3rem;">
                        Des centaines de documents vous attendent.
                    </div>
                </div>
                <a href="{{ route('documents.index') }}" style="
                    background-color: var(--gold);
                    color: var(--navy);
                    font-weight: 700;
                    font-size: .85rem;
                    letter-spacing: .06em;
                    text-transform: uppercase;
                    border-radius: 2px;
                    padding: .8rem 1.8rem;
                    text-decoration: none;
                    transition: background .2s;
                    white-space: nowrap;
                " onmouseover="this.style.background='#b8973f'" onmouseout="this.style.background='var(--gold)'">
                    <i class="bi bi-folder2-open me-2"></i>Voir les documents
                </a>
            </div>

        </div>
    </div>
</x-app-layout>