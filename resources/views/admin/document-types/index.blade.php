<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Types de documents</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Types de documents
                </h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
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

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--navy);margin:0 0 1rem 0;">
                    Ajouter un type
                </h2>
                <form method="POST" action="{{ route('admin.document-types.store') }}" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:flex-start;">
                    @csrf
                    <div style="flex:1;min-width:220px;">
                        <input type="text" name="nom" class="af-input" placeholder="Ex: TD, Examen, Cours..." value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="af-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="af-btn af-btn-navy">
                        <i class="bi bi-plus-lg"></i> Ajouter
                    </button>
                </form>
            </div>

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--navy);margin:0 0 1rem 0;">
                    Liste des types
                </h2>
                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Documents</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documentTypes as $documentType)
                                <tr>
                                    <td style="font-weight:700;">{{ $documentType->nom }}</td>
                                    <td>{{ $documentType->documents_count }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.document-types.destroy', $documentType) }}" onsubmit="return confirm('Supprimer ce type ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="af-btn af-btn-sm af-btn-danger">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="af-table-empty">
                                        <i class="bi bi-tags"></i>
                                        Aucun type de document.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $documentTypes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
