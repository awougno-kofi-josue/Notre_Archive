<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des années</h2>
    </x-slot>

    @include('admin._admin_styles') {{-- ou @once avec le bloc CSS --}}

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Gestion des <span style="color:var(--gold);">années</span>
                </h1>
            </div>
            <a href="{{ route('admin.niveaux.create') }}" class="af-btn af-btn-primary af-hero-action"
               style="background:#fff;color:#0d1b2a;border:2px solid #c9a84c;box-shadow:0 6px 18px rgba(0,0,0,.18);">
                <i class="bi bi-plus-lg"></i> Ajouter une année
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
                                <th>Année</th>
                                <th>Parcours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($niveaux as $i => $niveau)
                            <tr>
                                <td style="color:var(--slate);font-size:.78rem;">{{ $i + 1 }}</td>
                                <td style="font-weight:600;">{{ $niveau->nom }}</td>
                                <td>
                                    @if($niveau->parcours)
                                    <span style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--slate);">
                                        <i class="bi bi-diagram-3" style="color:var(--gold);"></i>
                                        {{ $niveau->parcours->nom }}
                                    </span>
                                    @else
                                    <span style="color:#c0c0c0;font-size:.82rem;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:.5rem;">
                                        <a href="{{ route('admin.niveaux.edit', $niveau) }}" class="af-btn af-btn-navy af-btn-sm">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.niveaux.destroy', $niveau) }}"
                                              onsubmit="return confirm('Supprimer cette année ?')">
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
                                <td colspan="4" class="af-table-empty">
                                    <i class="bi bi-calendar3"></i>
                                    Aucune année pour le moment.
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
