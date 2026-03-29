<x-app-layout>
    <x-slot name="header">
        @section('title', ' - Tableau de bord Admin')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord Admin
        </h2>
    </x-slot>

    @once
    <style>
        :root { --navy:#0d1b2a; --gold:#c9a84c; --cream:#f7f3ed; --slate:#4a5568; }
        .af-hero-bar {
            background-color:var(--navy);
            border-bottom:3px solid var(--gold);
            position:relative; overflow:hidden;
            padding:2.5rem 0;
        }
        .af-hero-bar::before {
            content:''; position:absolute; inset:0;
            background-image:repeating-linear-gradient(45deg,rgba(201,168,76,.05) 0,rgba(201,168,76,.05) 1px,transparent 0,transparent 50%);
            background-size:28px 28px; pointer-events:none;
        }
        .af-label { font-size:.72rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:var(--gold); }

        /* stat cards */
        .stat-card {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:4px;
            padding:2rem 1.5rem;
            display:flex; flex-direction:column; align-items:center;
            text-align:center; text-decoration:none;
            transition:box-shadow .25s, border-color .25s, transform .25s;
            height:100%;
        }
        .stat-card:hover {
            box-shadow:0 8px 28px rgba(13,27,42,.1);
            border-color:var(--gold);
            transform:translateY(-3px);
        }
        .stat-icon {
            width:52px; height:52px;
            background-color:var(--navy);
            border-radius:4px;
            display:inline-flex; align-items:center; justify-content:center;
            font-size:1.4rem; color:var(--gold);
            margin-bottom:1rem;
        }
        .stat-label {
            font-family:'Playfair Display',serif;
            font-size:1rem; font-weight:700;
            color:var(--navy); margin-bottom:.5rem;
        }
        .stat-count {
            font-family:'Playfair Display',serif;
            font-size:2.4rem; font-weight:900;
            color:var(--navy); line-height:1;
            margin-bottom:.75rem;
        }
        .stat-action {
            font-size:.72rem; font-weight:600;
            letter-spacing:.1em; text-transform:uppercase;
            color:var(--gold);
            display:inline-flex; align-items:center; gap:.3rem;
        }
        .stat-card:hover .stat-action i { transform:translateX(3px); }
        .stat-action i { transition:transform .2s; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    {{-- ── HERO BAR ── --}}
    <div class="af-hero-bar">
        <div class="container position-relative" style="font-family:'DM Sans',sans-serif;">
            <div class="af-label mb-1">Administration</div>
            <h1 style="font-family:'Playfair Display',serif; font-size:clamp(1.6rem,3vw,2.2rem);
                        font-weight:900; color:#fff; margin:0; line-height:1.1;">
                Tableau de bord <span style="color:var(--gold);">Admin</span>
            </h1>
            <p style="color:rgba(255,255,255,.45); font-size:.88rem; margin-top:.5rem; margin-bottom:0;">
                Vue d'ensemble de la plateforme
            </p>
        </div>
    </div>

    {{-- ── STATS GRID ── --}}
    <div style="background-color:var(--cream); padding:4rem 0; font-family:'DM Sans',sans-serif;">
        <div class="container">

            <div class="af-label mb-1">Statistiques</div>
            <h2 style="font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700;
                        color:var(--navy); margin-bottom:2rem;">Gérer la plateforme</h2>

            <div class="row g-4">

                {{-- Documents --}}
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('documents.index') }}" class="stat-card">
                        <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <div class="stat-label">Documents</div>
                        <div class="stat-count">{{ $documentsCount }}</div>
                        <div class="stat-action">Gérer <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>

                {{-- Parcours --}}
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('admin.parcours.index') }}" class="stat-card">
                        <div class="stat-icon"><i class="bi bi-diagram-3"></i></div>
                        <div class="stat-label">Parcours</div>
                        <div class="stat-count">{{ $parcoursCount }}</div>
                        <div class="stat-action">Gérer <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>

                {{-- Années --}}
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('admin.niveaux.index') }}" class="stat-card">
                        <div class="stat-icon"><i class="bi bi-calendar3"></i></div>
                        <div class="stat-label">Années</div>
                        <div class="stat-count">{{ $niveauxCount }}</div>
                        <div class="stat-action">Gérer <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>

                {{-- Utilisateurs --}}
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('admin.users.index') }}" class="stat-card">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div class="stat-label">Utilisateurs</div>
                        <div class="stat-count">{{ $usersCount }}</div>
                        <div class="stat-action">Gérer <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>

                {{-- Messages --}}
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('admin.messages.index') }}" class="stat-card">
                        <div class="stat-icon"><i class="bi bi-chat-left-dots"></i></div>
                        <div class="stat-label">Messages</div>
                        <div class="stat-count">{{ $messagesCount }}</div>
                        <div class="stat-action">Gérer <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>

                {{-- Total général (décoratif) --}}
                <div class="col-sm-6 col-lg-4">
                    <div class="stat-card" style="border-left:4px solid var(--gold); cursor:default;">
                        <div class="stat-icon" style="background:rgba(201,168,76,.15);">
                            <i class="bi bi-bar-chart-line" style="color:var(--gold);"></i>
                        </div>
                        <div class="stat-label">Total ressources</div>
                        <div class="stat-count" style="color:var(--gold);">
                            {{ $documentsCount + $parcoursCount + $niveauxCount }}
                        </div>
                        <div style="font-size:.72rem; color:var(--slate); letter-spacing:.06em; text-transform:uppercase;">
                            Éléments indexés
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>