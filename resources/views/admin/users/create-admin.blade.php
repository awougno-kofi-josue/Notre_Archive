<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Creer un administrateur</h2>
    </x-slot>

    @include('admin._admin_styles')

    <div class="af-hero-bar">
        <div class="container position-relative d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="af-label mb-1">Administration</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#fff;margin:0;">
                    Nouveau <span style="color:var(--gold);">compte admin</span>
                </h1>
            </div>
            <a href="{{ route('admin.users.index') }}" class="af-btn af-btn-primary af-hero-action">
                <i class="bi bi-arrow-left"></i> Retour utilisateurs
            </a>
        </div>
    </div>

    <div class="af-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">
                    <div class="af-card">
                        @if ($errors->any())
                            <div class="af-alert-success" style="border-left-color:#c0392b;border-color:rgba(192,57,43,.35);background:rgba(192,57,43,.08);">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>Merci de corriger les erreurs du formulaire.</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.users.store-admin') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="af-form-label" for="name">Nom complet</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="af-input">
                                @error('name')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="af-form-label" for="email">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="af-input">
                                @error('email')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="af-form-label" for="password">Mot de passe</label>
                                <input id="password" type="password" name="password" required class="af-input">
                                @error('password')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="af-form-label" for="password_confirmation">Confirmation mot de passe</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="af-input">
                                @error('password_confirmation')
                                    <div class="af-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="af-btn af-btn-navy">
                                    <i class="bi bi-shield-lock"></i> Creer admin
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="af-btn af-btn-ghost">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
