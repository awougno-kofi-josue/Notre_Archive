<x-app-layout>
    <x-slot name="header">
        @section('title', ' - Dashboard Admin')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    @include('admin._admin_styles')

    @once
    <style>
        .ad-hero-meta {
            margin-top:.75rem;
            color:rgba(255,255,255,.65);
            font-size:.86rem;
            display:flex;
            flex-wrap:wrap;
            gap:1.1rem;
        }
        .ad-hero-meta strong {
            color:#fff;
            font-weight:700;
        }
        .ad-hero-actions {
            display:flex;
            flex-wrap:wrap;
            gap:.65rem;
        }
        .ad-kpi-grid {
            display:grid;
            grid-template-columns:repeat(4, minmax(0, 1fr));
            gap:1rem;
        }
        .ad-kpi-card {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:6px;
            padding:1.05rem 1.1rem;
            box-shadow:0 8px 24px rgba(13,27,42,.05);
        }
        .ad-kpi-label {
            font-size:.73rem;
            letter-spacing:.09em;
            text-transform:uppercase;
            color:var(--slate);
            margin-bottom:.45rem;
            font-weight:700;
        }
        .ad-kpi-value {
            font-family:'Playfair Display',serif;
            font-size:2rem;
            line-height:1;
            color:var(--navy);
            font-weight:900;
        }
        .ad-kpi-sub {
            margin-top:.45rem;
            color:#6a768a;
            font-size:.8rem;
        }
        .ad-stacked-card {
            background:linear-gradient(160deg, #0d1b2a 0%, #1a3557 100%);
            border:1px solid rgba(201,168,76,.3);
            border-radius:8px;
            padding:1.1rem;
            color:#eaf0f8;
            min-height:100%;
        }
        .ad-stacked-title {
            font-size:.78rem;
            text-transform:uppercase;
            letter-spacing:.1em;
            color:rgba(201,168,76,.9);
            margin-bottom:.75rem;
            font-weight:700;
        }
        .ad-role-grid {
            display:grid;
            grid-template-columns:repeat(3, minmax(0, 1fr));
            gap:.6rem;
        }
        .ad-role-pill {
            border:1px solid rgba(255,255,255,.2);
            border-radius:6px;
            padding:.6rem .55rem;
            background:rgba(255,255,255,.07);
        }
        .ad-role-pill strong {
            display:block;
            font-size:1.1rem;
            color:#fff;
            line-height:1.1;
            margin-bottom:.2rem;
            font-weight:800;
        }
        .ad-role-pill span {
            font-size:.72rem;
            color:rgba(255,255,255,.8);
            letter-spacing:.05em;
            text-transform:uppercase;
            font-weight:600;
        }
        .ad-actions-grid {
            display:grid;
            grid-template-columns:repeat(4, minmax(0, 1fr));
            gap:.8rem;
        }
        .ad-action {
            text-decoration:none;
            border:1px solid #e8e2da;
            border-radius:6px;
            background:#fff;
            color:var(--navy);
            padding:.9rem .95rem;
            display:flex;
            align-items:center;
            gap:.65rem;
            transition:all .2s;
        }
        .ad-action:hover {
            border-color:var(--gold);
            transform:translateY(-2px);
            box-shadow:0 6px 18px rgba(13,27,42,.07);
            color:var(--navy);
        }
        .ad-action i {
            width:32px;
            height:32px;
            border-radius:4px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:var(--navy);
            color:var(--gold);
            font-size:1rem;
            flex-shrink:0;
        }
        .ad-action strong {
            display:block;
            font-size:.82rem;
            line-height:1.15;
            font-weight:700;
        }
        .ad-action span {
            display:block;
            font-size:.72rem;
            color:#667083;
            margin-top:.2rem;
        }
        .ad-panels {
            display:grid;
            grid-template-columns:1.6fr 1.2fr 1.2fr;
            gap:1rem;
        }
        .ad-panel {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:8px;
            padding:1rem;
        }
        .ad-panel-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:.8rem;
            margin-bottom:.8rem;
        }
        .ad-panel-head h3 {
            margin:0;
            font-family:'Playfair Display',serif;
            color:var(--navy);
            font-size:1.2rem;
            font-weight:700;
        }
        .ad-mini-btn {
            text-decoration:none;
            font-size:.72rem;
            font-weight:700;
            letter-spacing:.07em;
            text-transform:uppercase;
            color:var(--gold);
        }
        .ad-list {
            margin:0;
            padding:0;
            list-style:none;
            display:flex;
            flex-direction:column;
            gap:.45rem;
        }
        .ad-list-item {
            border:1px solid #ebe5dc;
            border-radius:6px;
            padding:.62rem .72rem;
            background:#fffdfa;
        }
        .ad-list-title {
            color:var(--navy);
            font-weight:700;
            font-size:.84rem;
            line-height:1.3;
            margin-bottom:.22rem;
        }
        .ad-list-meta {
            color:#667083;
            font-size:.73rem;
            line-height:1.3;
            display:flex;
            flex-wrap:wrap;
            gap:.55rem;
        }
        .ad-list-meta i {
            color:var(--gold);
        }
        .ad-empty {
            padding:1rem;
            text-align:center;
            font-size:.86rem;
            color:#7b8697;
            border:1px dashed #ddd3c5;
            border-radius:6px;
            background:#fdfaf3;
        }
        @media (max-width: 1200px) {
            .ad-kpi-grid {
                grid-template-columns:repeat(3, minmax(0, 1fr));
            }
            .ad-actions-grid {
                grid-template-columns:repeat(2, minmax(0, 1fr));
            }
            .ad-panels {
                grid-template-columns:1fr;
            }
        }
        @media (max-width: 768px) {
            .ad-hero-actions {
                width:100%;
            }
            .ad-hero-actions .af-btn {
                width:100%;
                justify-content:center;
            }
            .ad-kpi-grid {
                grid-template-columns:repeat(2, minmax(0, 1fr));
            }
            .ad-role-grid {
                grid-template-columns:1fr;
            }
            .ad-actions-grid {
                grid-template-columns:1fr;
            }
        }
    </style>
    @endonce

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label">Administration generale</div>
                <h1 style="margin:.35rem 0 0 0;font-family:'Playfair Display',serif;font-size:clamp(1.6rem,3vw,2.35rem);font-weight:900;color:#fff;line-height:1.1;">
                    Dashboard du systeme
                </h1>
                <div class="ad-hero-meta">
                    <div><strong>{{ $onlineUsersCount }}</strong> utilisateurs en ligne</div>
                    <div><strong>{{ $documentsThisMonth }}</strong> documents ce mois</div>
                    <div><strong>{{ $messagesThisWeek }}</strong> messages cette semaine</div>
                </div>
            </div>
            <div class="ad-hero-actions">
                <a href="{{ route('documents.index') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-file-earmark-text"></i> Documents
                </a>
                <a href="{{ route('admin.users.create-admin') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-person-plus"></i> Creer admin
                </a>
            </div>
        </div>
    </div>

    <div class="af-body">
        <div class="container d-flex flex-column gap-4">
            <div class="ad-kpi-grid">
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Documents</div>
                    <div class="ad-kpi-value">{{ $documentsCount }}</div>
                    <div class="ad-kpi-sub">Total en bibliotheque</div>
                </div>
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Utilisateurs</div>
                    <div class="ad-kpi-value">{{ $usersCount }}</div>
                    <div class="ad-kpi-sub">Comptes inscrits</div>
                </div>
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Parcours</div>
                    <div class="ad-kpi-value">{{ $parcoursCount }}</div>
                    <div class="ad-kpi-sub">Filieres actives</div>
                </div>
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Annees</div>
                    <div class="ad-kpi-value">{{ $niveauxCount }}</div>
                    <div class="ad-kpi-sub">Niveaux catalogues</div>
                </div>
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Messages</div>
                    <div class="ad-kpi-value">{{ $messagesCount }}</div>
                    <div class="ad-kpi-sub">Messages recus</div>
                </div>
                <div class="ad-kpi-card">
                    <div class="ad-kpi-label">Utilisateurs en ligne</div>
                    <div class="ad-kpi-value">{{ $onlineUsersCount }}</div>
                    <div class="ad-kpi-sub">Dernieres 5 minutes</div>
                </div>
                <div class="ad-stacked-card" style="grid-column:span 2;">
                    <div class="ad-stacked-title">Repartition des roles</div>
                    <div class="ad-role-grid">
                        <div class="ad-role-pill">
                            <strong>{{ $adminsCount }}</strong>
                            <span>Admins</span>
                        </div>
                        <div class="ad-role-pill">
                            <strong>{{ $moderatorsCount }}</strong>
                            <span>Moderateurs</span>
                        </div>
                        <div class="ad-role-pill">
                            <strong>{{ $standardUsersCount }}</strong>
                            <span>Standards</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ad-actions-grid">
                <a href="{{ route('admin.parcours.index') }}" class="ad-action">
                    <i class="bi bi-diagram-3"></i>
                    <div>
                        <strong>Gerer les parcours</strong>
                        <span>Creation, edition, suppression</span>
                    </div>
                </a>
                <a href="{{ route('admin.niveaux.index') }}" class="ad-action">
                    <i class="bi bi-calendar3"></i>
                    <div>
                        <strong>Gerer les annees</strong>
                        <span>Organisation par niveau</span>
                    </div>
                </a>
                <a href="{{ route('admin.users.index') }}" class="ad-action">
                    <i class="bi bi-people"></i>
                    <div>
                        <strong>Gerer les utilisateurs</strong>
                        <span>Roles et acces moderateurs</span>
                    </div>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="ad-action">
                    <i class="bi bi-chat-left-dots"></i>
                    <div>
                        <strong>Voir les messages</strong>
                        <span>Demandes et feedback</span>
                    </div>
                </a>
            </div>

            <div class="ad-panels">
                <section class="ad-panel">
                    <div class="ad-panel-head">
                        <h3>Derniers documents</h3>
                        <a href="{{ route('documents.index') }}" class="ad-mini-btn">Tout voir</a>
                    </div>
                    @if($recentDocuments->isEmpty())
                        <div class="ad-empty">Aucun document pour le moment.</div>
                    @else
                        <ul class="ad-list">
                            @foreach($recentDocuments as $document)
                                <li class="ad-list-item">
                                    <div class="ad-list-title">{{ $document->titre }}</div>
                                    <div class="ad-list-meta">
                                        <span><i class="bi bi-diagram-3"></i> {{ $document->parcours?->nom ?? '-' }}</span>
                                        <span><i class="bi bi-calendar3"></i> {{ $document->niveau?->nom ?? '-' }}</span>
                                        <span><i class="bi bi-person"></i> {{ $document->user?->name ?? 'Utilisateur inconnu' }}</span>
                                        <span><i class="bi bi-clock"></i> {{ $document->created_at?->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="ad-panel">
                    <div class="ad-panel-head">
                        <h3>Derniers utilisateurs</h3>
                        <a href="{{ route('admin.users.index') }}" class="ad-mini-btn">Tout voir</a>
                    </div>
                    @if($recentUsers->isEmpty())
                        <div class="ad-empty">Aucun utilisateur pour le moment.</div>
                    @else
                        <ul class="ad-list">
                            @foreach($recentUsers as $user)
                                <li class="ad-list-item">
                                    <div class="ad-list-title">{{ $user->name }}</div>
                                    <div class="ad-list-meta">
                                        <span><i class="bi bi-envelope"></i> {{ $user->email }}</span>
                                        <span><i class="bi bi-diagram-3"></i> {{ $user->parcours?->nom ?? '-' }}</span>
                                        <span>
                                            <i class="bi bi-shield-lock"></i>
                                            @if($user->is_admin)
                                                Admin
                                            @elseif($user->can_manage_documents)
                                                Moderateur
                                            @else
                                                Standard
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="ad-panel">
                    <div class="ad-panel-head">
                        <h3>Derniers messages</h3>
                        <a href="{{ route('admin.messages.index') }}" class="ad-mini-btn">Tout voir</a>
                    </div>
                    @if($recentMessages->isEmpty())
                        <div class="ad-empty">Aucun message recu.</div>
                    @else
                        <ul class="ad-list">
                            @foreach($recentMessages as $message)
                                <li class="ad-list-item">
                                    <div class="ad-list-title">{{ $message->name }} - {{ $message->email }}</div>
                                    <div class="ad-list-meta">
                                        <span>{{ \Illuminate\Support\Str::limit($message->message, 70) }}</span>
                                    </div>
                                    <div class="ad-list-meta" style="margin-top:.3rem;">
                                        <span><i class="bi bi-clock"></i> {{ $message->created_at?->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
