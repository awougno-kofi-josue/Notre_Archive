<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Espace personnel</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Mes notifications
                </h1>
            </div>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="af-btn af-btn-primary af-hero-action">
                    <i class="bi bi-check2-all"></i> Tout marquer lu
                </button>
            </form>
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
                @if($notifications->isEmpty())
                    <div class="af-table-empty">
                        <i class="bi bi-bell"></i>
                        Aucune notification pour le moment.
                    </div>
                @else
                    <div style="display:grid;gap:.75rem;">
                        @foreach($notifications as $notification)
                            <div style="
                                border:1px solid #e8e2da;
                                border-left:4px solid {{ $notification->is_read ? '#d9d2c8' : '#c9a84c' }};
                                border-radius:6px;
                                padding:.85rem .95rem;
                                background:{{ $notification->is_read ? '#fff' : '#fdf9f1' }};
                            ">
                                <div style="display:flex;justify-content:space-between;gap:1rem;align-items:flex-start;">
                                    <div>
                                        <div style="font-weight:800;color:var(--navy);">{{ $notification->titre }}</div>
                                        <div style="margin-top:.2rem;color:var(--slate);font-size:.9rem;line-height:1.5;">
                                            {{ $notification->message }}
                                        </div>
                                        <div style="margin-top:.4rem;font-size:.78rem;color:#718096;">
                                            {{ $notification->created_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;justify-content:flex-end;">
                                        @if($notification->link)
                                            <a href="{{ $notification->link }}" class="af-btn af-btn-sm af-btn-ghost">
                                                <i class="bi bi-box-arrow-up-right"></i> Ouvrir
                                            </a>
                                        @endif
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="af-btn af-btn-sm af-btn-navy">
                                                    <i class="bi bi-check2"></i> Marquer lu
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
