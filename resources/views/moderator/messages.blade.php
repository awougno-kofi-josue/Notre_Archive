<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messagerie parcours</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Moderation</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Messages aux etudiants du parcours
                </h1>
            </div>
            <a href="{{ route('moderator.dashboard') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Retour
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
                    Nouveau message ({{ $studentsCount }} destinataire{{ $studentsCount > 1 ? 's' : '' }})
                </h2>

                <form method="POST" action="{{ route('moderator.messages.store') }}">
                    @csrf
                    <div style="display:grid;gap:1rem;">
                        <div>
                            <label class="af-form-label" for="titre">Titre</label>
                            <input type="text" id="titre" name="titre" class="af-input" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="af-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="af-form-label" for="message">Message</label>
                            <textarea id="message" name="message" class="af-input" style="min-height:130px;" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="af-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div style="margin-top:1rem;">
                        <button type="submit" class="af-btn af-btn-navy">
                            <i class="bi bi-send"></i> Envoyer la notification
                        </button>
                    </div>
                </form>
            </div>

            <div class="af-card">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--navy);margin:0 0 1rem 0;">
                    Historique des messages envoyes
                </h2>
                <div class="table-responsive">
                    <table class="af-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                                <tr>
                                    <td style="font-weight:700;">{{ $message->titre }}</td>
                                    <td style="max-width:500px;white-space:normal;line-height:1.5;">{{ $message->message }}</td>
                                    <td style="color:var(--slate);">{{ $message->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="af-table-empty">
                                        <i class="bi bi-chat-square-text"></i>
                                        Aucun message envoye pour le moment.
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
