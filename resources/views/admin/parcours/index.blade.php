<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des parcours</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Gestion des <span style="color:var(--gold);">parcours</span>
                </h1>
            </div>
            <a href="{{ route('admin.parcours.create') }}" class="af-btn af-btn-primary af-hero-action"
               style="background:#fff;color:#0d1b2a;border:2px solid #c9a84c;box-shadow:0 6px 18px rgba(0,0,0,.18);">
                <i class="bi bi-plus-lg"></i> Ajouter un parcours
            </a>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="af-card">

                @if(session('success'))
                <div class="af-alert-success">
                    <i class="bi bi-check-circle-fill" style="color:var(--gold);"></i>
                    {{ session('success') }}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom du parcours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parcours as $i => $parcour)
                            <tr>
                                <td style="color:var(--slate);font-size:.78rem;">{{ $i + 1 }}</td>
                                <td style="font-weight:600;">
                                    <i class="bi bi-diagram-3 me-2" style="color:var(--gold);"></i>
                                    {{ $parcour->nom }}
                                </td>
                                <td>
                                    <div style="display:flex;gap:.5rem;">
                                        <a href="{{ route('admin.parcours.edit', $parcour) }}" class="af-btn af-btn-navy af-btn-sm">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.parcours.destroy', $parcour) }}"
                                              onsubmit="return confirm('Supprimer ce parcours ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="af-btn af-btn-danger af-btn-sm">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="af-table-empty">
                                    <i class="bi bi-diagram-3"></i>
                                    Aucun parcours pour le moment.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
