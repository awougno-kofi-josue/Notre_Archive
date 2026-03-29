<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notre Archive</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0d1b2a;
            --indigo: #1b3a6b;
            --gold:   #c9a84c;
            --cream:  #f7f3ed;
            --slate:  #4a5568;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .navbar-custom {
            background-color: var(--navy);
            border-bottom: 3px solid var(--gold);
            padding: 1rem 0;
        }
        .navbar-toggler {
            border: 1px solid rgba(255,255,255,.35) !important;
        }
        .navbar-toggler:focus {
            box-shadow: none !important;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .navbar-brand-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff !important;
            letter-spacing: .02em;
        }
        .navbar-brand-text span { color: var(--gold); }
        .nav-link-custom {
            color: #ffffff !important;
            font-size: .875rem;
            font-weight: 500;
            letter-spacing: .04em;
            text-transform: uppercase;
            transition: color .2s, background-color .2s, border-color .2s;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 4px;
            padding: .35rem .7rem !important;
            background: rgba(0,0,0,.15);
        }
        .nav-link-custom:hover {
            color: var(--navy) !important;
            background: #ffffff;
            border-color: #ffffff;
        }
        .btn-nav-logout {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .btn-nav-primary {
            background-color: var(--gold);
            color: var(--navy) !important;
            border-radius: 2px;
            padding: .35rem 1rem;
            font-weight: 600;
            font-size: .8rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            transition: background .2s;
        }
        .btn-nav-primary:hover { background-color: #b8973f; }

        /* ── HERO ── */
        .hero {
            position: relative;
            overflow: hidden;
            background-color: var(--navy);
            padding: 6rem 0 5rem;
        }
        /* subtle diagonal grid texture */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    rgba(201,168,76,.06) 0,
                    rgba(201,168,76,.06) 1px,
                    transparent 0,
                    transparent 50%
                );
            background-size: 28px 28px;
            pointer-events: none;
        }
        /* gold accent bar */
        .hero::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 5px; height: 100%;
            background: linear-gradient(to bottom, var(--gold), transparent);
        }

        .hero-eyebrow {
            display: inline-block;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1px solid rgba(201,168,76,.4);
            padding: .3rem .9rem;
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            font-weight: 900;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .hero-title em {
            font-style: normal;
            color: var(--gold);
        }
        .hero-subtitle {
            font-size: 1.05rem;
            font-weight: 300;
            color: rgba(255,255,255,.65);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }
        .btn-hero-primary {
            background-color: var(--gold);
            color: var(--navy);
            font-weight: 700;
            font-size: .9rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            border: none;
            border-radius: 2px;
            padding: .85rem 2rem;
            text-decoration: none;
            transition: background .2s, transform .2s;
            display: inline-block;
        }
        .btn-hero-primary:hover {
            background-color: #b8973f;
            transform: translateY(-1px);
            color: var(--navy);
        }
        .btn-hero-secondary {
            background: transparent;
            color: rgba(255,255,255,.75);
            font-size: .9rem;
            letter-spacing: .04em;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 2px;
            padding: .85rem 1.6rem;
            text-decoration: none;
            transition: border-color .2s, color .2s;
            display: inline-block;
        }
        .btn-hero-secondary:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        /* hero stats */
        .hero-stats {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
        }
        .stat-label {
            font-size: .78rem;
            color: rgba(255,255,255,.5);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-top: .3rem;
        }

        /* ── FEATURES ── */
        .section-features {
            padding: 5rem 0;
            background-color: #fff;
        }
        .section-label {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: .6rem;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
        }

        .feature-card {
            border: 1px solid #e8e2da;
            border-radius: 4px;
            padding: 2rem;
            height: 100%;
            transition: box-shadow .25s, transform .25s, border-color .25s;
            background: var(--cream);
        }
        .feature-card:hover {
            box-shadow: 0 8px 28px rgba(13,27,42,.08);
            transform: translateY(-3px);
            border-color: var(--gold);
        }
        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 4px;
            background-color: var(--navy);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.3rem;
            color: var(--gold);
            flex-shrink: 0;
        }
        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: .5rem;
        }
        .feature-text {
            font-size: .9rem;
            color: var(--slate);
            line-height: 1.65;
            margin: 0;
        }

        /* ── CTA BAND ── */
        .cta-band {
            background-color: var(--navy);
            padding: 4rem 0;
            border-top: 3px solid var(--gold);
        }
        .cta-band h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: .5rem;
        }
        .cta-band p {
            color: rgba(255,255,255,.55);
            font-size: .95rem;
            margin: 0;
        }

        /* ── FOOTER ── */
        footer {
            background-color: #070f19;
            color: rgba(255,255,255,.4);
            font-size: .82rem;
            padding: 1.75rem 0;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        footer a { color: var(--gold); text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        @media (max-width: 991.98px) {
            .hero { padding: 4rem 0 3.2rem; }
            .hero-subtitle { max-width: 100%; }
            .hero-stats { margin-top: 2rem; }
            .navbar-nav { margin-top: .8rem; gap: .5rem; }
        }

        @media (max-width: 767.98px) {
            .navbar-brand-text { font-size: 1.05rem; }
            .hero { padding: 3rem 0 2.4rem; }
            .hero-title { font-size: clamp(1.8rem, 8vw, 2.4rem); }
            .hero-subtitle { font-size: .95rem; margin-bottom: 1.5rem; }
            .btn-hero-primary, .btn-hero-secondary {
                width: 100%;
                text-align: center;
            }
            .hero-stats .col-auto {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .section-features, .cta-band { padding: 3rem 0; }
            .feature-card { padding: 1.35rem; }
        }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand navbar-brand-text" href="#">
            <i class="bi bi-archive me-2" style="color: var(--gold);"></i>Notre <span>Archive</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                @auth
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn-nav-logout nav-link-custom nav-link">
                                <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-nav-primary" href="{{ route('register') }}">S'inscrire</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="hero-eyebrow">Plateforme académique</span>
                <h1 class="hero-title">
                    Tous vos <em>documents</em><br>en un seul endroit
                </h1>
                <p class="hero-subtitle">
                    Accédez aux épreuves, cours et ressources pédagogiques
                    classés par parcours et par année — rapidement, sans friction.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('documents.index') }}" class="btn-hero-primary">
                        <i class="bi bi-folder2-open me-2"></i>Parcourir les documents
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn-hero-secondary">
                        Créer un compte <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @endguest
                </div>

                <div class="hero-stats row g-4 mt-1">
                    <div class="col-auto">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Documents</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Parcours</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Années couvertes</div>
                    </div>
                </div>
            </div>

            {{-- Decorative panel (desktop only) --}}
            <div class="col-lg-5 d-none d-lg-flex justify-content-end">
                <div style="
                    width: 340px; height: 380px;
                    border: 1px solid rgba(201,168,76,.3);
                    border-radius: 4px;
                    position: relative;
                    overflow: hidden;
                    background: rgba(27,58,107,.4);
                    backdrop-filter: blur(4px);
                    padding: 2rem;
                    display: flex; flex-direction: column; gap: 1rem;
                ">
                    @foreach ([
                        ['Mathématiques L2','Analyse & Algèbre','bi-file-earmark-text'],
                        ['Informatique L3','Algorithmes avancés','bi-file-earmark-code'],
                        ['Physique L1','Mécanique classique','bi-file-earmark-richtext'],
                        ['Droit L2','Droit civil','bi-file-earmark-text'],
                    ] as $doc)
                    <div style="
                        background: rgba(255,255,255,.06);
                        border: 1px solid rgba(255,255,255,.08);
                        border-radius: 3px;
                        padding: .75rem 1rem;
                        display: flex; align-items: center; gap: .85rem;
                    ">
                        <i class="bi {{ $doc[2] }}" style="font-size:1.4rem; color: var(--gold); flex-shrink:0;"></i>
                        <div>
                            <div style="font-size:.72rem; color:rgba(255,255,255,.4); letter-spacing:.08em; text-transform:uppercase;">{{ $doc[0] }}</div>
                            <div style="font-size:.88rem; color:#fff; font-weight:500; margin-top:.1rem;">{{ $doc[1] }}</div>
                        </div>
                        <i class="bi bi-download ms-auto" style="color:rgba(255,255,255,.3); font-size:.9rem;"></i>
                    </div>
                    @endforeach
                    <div style="
                        margin-top: auto;
                        font-size: .72rem;
                        color: rgba(255,255,255,.3);
                        letter-spacing: .06em;
                        text-transform: uppercase;
                        text-align: center;
                    ">Aperçu — documents récents</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURES ── --}}
<section class="section-features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Pourquoi utiliser l'archive ?</span>
            <h2 class="section-title">Conçu pour les étudiants</h2>
        </div>
        <div class="row g-4">
            @foreach ([
                ['bi-search','Recherche rapide','Trouvez n\'importe quelle épreuve par matière, parcours ou année en quelques secondes.'],
                ['bi-folder-symlink','Classement structuré','Documents organisés par filière et niveau pour une navigation sans effort.'],
                ['bi-cloud-download','Téléchargement libre','Téléchargez les ressources directement depuis votre navigateur, sans compte requis.'],
                ['bi-shield-lock','Accès sécurisé','Les dépôts de documents sont réservés aux membres vérifiés de la faculté.'],
            ] as $f)
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi {{ $f[0] }}"></i>
                    </div>
                    <div class="feature-title">{{ $f[1] }}</div>
                    <p class="feature-text">{{ $f[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA BAND ── --}}
<section class="cta-band">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <h2>Prêt à explorer la bibliothèque ?</h2>
                <p>Des centaines d'épreuves et de cours vous attendent.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('documents.index') }}" class="btn-hero-primary me-3">
                    Voir les documents
                </a>
                @guest
                <a href="{{ route('register') }}" class="btn-hero-secondary mt-2 mt-lg-0 d-inline-block">
                    S'inscrire gratuitement
                </a>
                @endguest
            </div>
        </div>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer>
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <span>&copy; {{ date('Y') }} Notre Archive — Tous droits réservés.</span>
        <span>
            <a href="#">Mentions légales</a> &nbsp;·&nbsp;
            <a href="#">Contact</a>
        </span>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
