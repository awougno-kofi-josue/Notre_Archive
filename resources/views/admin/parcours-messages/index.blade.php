<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages de parcours</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Tous les messages moderateurs
                </h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            @if(session('success'))
                <div class="af-alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="af-card">
                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Parcours</th>
                                <th>Envoye par</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                                <tr>
                                    <td style="font-weight:700;">{{ $message->titre }}</td>
                                    <td>{{ $message->parcours?->nom ?? '-' }}</td>
                                    <td>{{ $message->sender?->name ?? '-' }}</td>
                                    <td style="max-width:460px;white-space:normal;line-height:1.5;">{{ $message->message }}</td>
                                    <td style="color:var(--slate);">{{ $message->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.parcours-messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
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
                                    <td colspan="6" class="af-table-empty">
                                        <i class="bi bi-chat-square-text"></i>
                                        Aucun message de parcours.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
