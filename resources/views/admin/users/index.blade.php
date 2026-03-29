<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Utilisateurs</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Liste des <span style="color:var(--gold);">utilisateurs</span>
                </h1>
            </div>
            {{-- badge en ligne --}}
            <div style="display:flex;align-items:center;gap:.6rem;
                        background:rgba(39,174,96,.12);border:1px solid rgba(39,174,96,.3);
                        border-radius:30px;padding:.45rem 1.1rem;">
                <span class="af-online-dot"></span>
                <span style="font-size:.78rem;font-weight:600;color:#27ae60;letter-spacing:.06em;text-transform:uppercase;">
                    {{ $onlineUsersCount }} en ligne
                </span>
            </div>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="af-card">

                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Dernière activité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            @php
                                $isOnline = $user->last_seen_at && $user->last_seen_at->gte($onlineLimit);
                            @endphp
                            <tr>
                                {{-- Avatar + Nom --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem;">
                                        <div style="
                                            width:34px;height:34px;border-radius:50%;
                                            background:var(--navy);
                                            display:flex;align-items:center;justify-content:center;
                                            font-size:.8rem;font-weight:700;color:var(--gold);
                                            flex-shrink:0;
                                        ">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span style="font-weight:600;">{{ $user->name }}</span>
                                    </div>
                                </td>

                                <td style="color:var(--slate);font-size:.85rem;">{{ $user->email }}</td>

                                <td>
                                    @if($user->is_admin)
                                    <span class="af-badge-admin">Admin</span>
                                    @else
                                    <span class="af-badge-user">Utilisateur</span>
                                    @endif
                                </td>

                                <td>
                                    @if($isOnline)
                                    <span class="af-badge-online">
                                        <span class="af-online-dot" style="width:6px;height:6px;margin-right:.3rem;"></span>
                                        Connecté
                                    </span>
                                    @else
                                    <span class="af-badge-offline">Hors ligne</span>
                                    @endif
                                </td>

                                <td style="color:var(--slate);font-size:.82rem;">
                                    @if($user->last_seen_at)
                                    <i class="bi bi-clock me-1" style="color:var(--gold);"></i>
                                    {{ $user->last_seen_at->diffForHumans() }}
                                    @else
                                    <span style="color:#c0c0c0;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="af-table-empty">
                                    <i class="bi bi-people"></i>
                                    Aucun utilisateur trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
