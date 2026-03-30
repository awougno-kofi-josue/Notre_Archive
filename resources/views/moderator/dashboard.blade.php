<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Espace moderateur</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Moderation</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Parcours <span style="color:var(--gold);">{{ $parcours?->nom ?? 'Non defini' }}</span>
                </h1>
            </div>
            <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                <a href="{{ route('moderator.messages.index') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-megaphone"></i> Messages etudiants
                </a>
                <a href="{{ route('documents.create') }}" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-plus-lg"></i> Ajouter un document
                </a>
            </div>
        </div>
    </div>

    <div class="af-body">
        <div class="container d-flex flex-column gap-4">
            @if(session('success'))
                <div class="af-alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="af-alert-success" style="border-color:rgba(192,57,43,.35);border-left-color:#c0392b;background:rgba(192,57,43,.08);">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="af-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h2 style="font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--navy);margin:0;">
                        Derniers messages envoyes au parcours
                    </h2>
                    <a href="{{ route('moderator.messages.index') }}" class="af-btn af-btn-ghost af-btn-sm">
                        <i class="bi bi-box-arrow-up-right"></i> Ouvrir la messagerie
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Par</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMessages as $message)
                                <tr>
                                    <td style="font-weight:700;">{{ $message->titre }}</td>
                                    <td>{{ $message->sender?->name ?? '-' }}</td>
                                    <td style="color:var(--slate);">{{ $message->created_at?->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="af-table-empty">
                                        <i class="bi bi-megaphone"></i>
                                        Aucun message envoye pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="af-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h2 style="font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--navy);margin:0;">
                        Documents du parcours
                    </h2>
                    <a href="{{ route('documents.index') }}" class="af-btn af-btn-ghost af-btn-sm">
                        <i class="bi bi-box-arrow-up-right"></i> Voir tout
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Annee</th>
                                <th>Auteur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                                <tr>
                                    <td style="font-weight:700;">{{ $document->titre }}</td>
                                    <td>{{ $document->niveau?->nom ?? '-' }}</td>
                                    <td>{{ $document->user?->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('documents.view', $document->id) }}" target="_blank" class="af-btn af-btn-navy af-btn-sm">
                                                <i class="bi bi-eye"></i> Ouvrir
                                            </a>
                                            <a href="{{ route('documents.download', $document->id) }}" class="af-btn af-btn-primary af-btn-sm">
                                                <i class="bi bi-download"></i> Telecharger
                                            </a>
                                            @can('delete', $document)
                                                <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="af-btn af-btn-danger af-btn-sm">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="af-table-empty">
                                        <i class="bi bi-folder2-open"></i>
                                        Aucun document dans ce parcours.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $documents->links() }}
                </div>
            </div>

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--navy);margin:0 0 1rem 0;">
                    Utilisateurs du parcours
                </h2>

                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Documents</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td style="font-weight:700;">{{ $user->name }}</td>
                                    <td style="color:var(--slate);">{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_admin)
                                            <span class="af-badge-admin">Admin</span>
                                        @elseif($user->can_manage_documents)
                                            <span class="af-badge-online">Moderateur</span>
                                        @else
                                            <span class="af-badge-user">Utilisateur</span>
                                        @endif
                                    </td>
                                    <td style="font-weight:700;">{{ $user->documents_count }}</td>
                                    <td>
                                        @if($user->is_admin)
                                            <span style="font-size:.78rem;color:var(--slate);">Controle total</span>
                                        @else
                                            <form method="POST" action="{{ route('moderator.users.document-access.toggle', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="af-btn af-btn-sm {{ $user->can_manage_documents ? 'af-btn-warning' : 'af-btn-navy' }}">
                                                    <i class="bi {{ $user->can_manage_documents ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                                                    {{ $user->can_manage_documents ? 'Retirer acces' : 'Donner acces' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="af-table-empty">
                                        <i class="bi bi-people"></i>
                                        Aucun utilisateur trouve dans ce parcours.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
