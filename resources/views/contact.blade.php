<x-app-layout>
    <x-slot name="header">
        @section('title', ' - Contact')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contactez-nous
        </h2>
    </x-slot>
<x-ad/>
    @once
    <style>
        :root { --navy:#0d1b2a; --gold:#c9a84c; --cream:#f7f3ed; --slate:#4a5568; }
        .af-hero-bar {
            background-color:var(--navy);
            border-bottom:3px solid var(--gold);
            position:relative; overflow:hidden;
            padding:2.5rem 0;
        }
        .af-hero-bar::before {
            content:''; position:absolute; inset:0;
            background-image:repeating-linear-gradient(45deg,rgba(201,168,76,.05) 0,rgba(201,168,76,.05) 1px,transparent 0,transparent 50%);
            background-size:28px 28px; pointer-events:none;
        }
        .af-label { font-size:.72rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:var(--gold); }

        /* form */
        .af-form-card {
            background:#fff;
            border:1px solid #e8e2da;
            border-radius:4px;
            padding:2.5rem;
        }
        .af-form-label {
            display:block;
            font-size:.78rem; font-weight:600;
            letter-spacing:.07em; text-transform:uppercase;
            color:var(--navy); margin-bottom:.45rem;
        }
        .af-form-input, .af-form-textarea {
            width:100%;
            font-family:'DM Sans',sans-serif;
            font-size:.9rem;
            color:var(--navy);
            background:#fff;
            border:1px solid #d9d2c8;
            border-radius:3px;
            padding:.65rem 1rem;
            outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .af-form-input:focus, .af-form-textarea:focus {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,168,76,.12);
        }
        .af-form-textarea { resize:vertical; min-height:140px; }
        .af-btn-submit {
            background-color:var(--gold);
            color:var(--navy);
            border:none;
            border-radius:3px;
            padding:.8rem 2rem;
            font-family:'DM Sans',sans-serif;
            font-size:.85rem; font-weight:700;
            letter-spacing:.07em; text-transform:uppercase;
            cursor:pointer;
            transition:background .2s, transform .15s;
        }
        .af-btn-submit:hover { background-color:#b8973f; transform:translateY(-1px); }
        .af-alert-success {
            background:rgba(201,168,76,.1);
            border:1px solid rgba(201,168,76,.4);
            border-left:4px solid var(--gold);
            border-radius:3px;
            padding:1rem 1.25rem;
            color:var(--navy);
            font-size:.9rem;
            margin-bottom:1.5rem;
            display:flex; align-items:center; gap:.6rem;
        }

        /* info sidebar */
        .contact-info-item {
            display:flex; align-items:flex-start; gap:.9rem;
            padding:1.25rem;
            border:1px solid #e8e2da;
            border-radius:4px;
            background:#fff;
            margin-bottom:.75rem;
        }
        .contact-info-icon {
            width:36px; height:36px; flex-shrink:0;
            background:var(--navy); border-radius:3px;
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; color:var(--gold);
        }
        .contact-info-label { font-size:.7rem; letter-spacing:.09em; text-transform:uppercase; color:var(--slate); }
        .contact-info-val { font-size:.9rem; color:var(--navy); font-weight:500; margin-top:.1rem; }
        .contact-info-val a { color:var(--navy); text-decoration:none; }
        .contact-info-val a:hover { color:var(--gold); }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @endonce

    {{-- ── HERO BAR ── --}}
    <div class="af-hero-bar">
        <div class="container position-relative" style="font-family:'DM Sans',sans-serif;">
            <div class="af-label mb-1">Support</div>
            <h1 style="font-family:'Playfair Display',serif; font-size:clamp(1.6rem,3vw,2.2rem);
                        font-weight:900; color:#fff; margin:0; line-height:1.1;">
                Contactez-<span style="color:var(--gold);">nous</span>
            </h1>
            <p style="color:rgba(255,255,255,.45); font-size:.88rem; margin-top:.5rem; margin-bottom:0;">
                Une question ? Nous vous répondrons dans les plus brefs délais.
            </p>
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div style="background-color:var(--cream); padding:4rem 0; font-family:'DM Sans',sans-serif;">
        <div class="container">
            <div class="row g-5">

                {{-- FORM --}}
                <div class="col-lg-7">
                    <div class="af-form-card">
                        <div class="af-label mb-1">Formulaire</div>
                        <h2 style="font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:700;
                                    color:var(--navy); margin-bottom:1.75rem;">
                            Envoyez-nous un message
                        </h2>

                        @if(session('success'))
                        <div class="af-alert-success">
                            <i class="bi bi-check-circle-fill" style="color:var(--gold); font-size:1.1rem;"></i>
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="af-form-label">Nom complet</label>
                                <input type="text" id="name" name="name"
                                       class="af-form-input" placeholder="Jean Dupont" required
                                       value="{{ old('name') }}">
                                @error('name')
                                <div style="font-size:.78rem; color:#c0392b; margin-top:.3rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="af-form-label">Adresse e-mail</label>
                                <input type="email" id="email" name="email"
                                       class="af-form-input" placeholder="jean@exemple.com" required
                                       value="{{ old('email') }}">
                                @error('email')
                                <div style="font-size:.78rem; color:#c0392b; margin-top:.3rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="message" class="af-form-label">Message</label>
                                <textarea id="message" name="message"
                                          class="af-form-textarea"
                                          placeholder="Décrivez votre demande..." required>{{ old('message') }}</textarea>
                                @error('message')
                                <div style="font-size:.78rem; color:#c0392b; margin-top:.3rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="af-btn-submit">
                                <i class="bi bi-send me-2"></i>Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SIDEBAR INFO --}}
                <div class="col-lg-5">
                    <div class="af-label mb-1">Coordonnées</div>
                    <h2 style="font-family:'Playfair Display',serif; font-size:1.4rem; font-weight:700;
                                color:var(--navy); margin-bottom:1.5rem;">
                        Autres moyens de nous joindre
                    </h2>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="contact-info-label">Email</div>
                            <div class="contact-info-val">
                                <a href="mailto:josueawougno@gmail.com">josueawougno@gmail.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="contact-info-label">Téléphone</div>
                            <div class="contact-info-val">
                                <a href="tel:+22893947171">+228 93 94 71 71</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="contact-info-label">Disponibilité</div>
                            <div class="contact-info-val">Lun – Ven, 8h00 – 17h00</div>
                        </div>
                    </div>

                    {{-- Note card --}}
                    <div style="
                        background:var(--navy); border-radius:4px;
                        border-top:3px solid var(--gold);
                        padding:1.5rem; margin-top:1.5rem;
                    ">
                        <div style="font-family:'Playfair Display',serif; font-size:1rem; font-weight:700;
                                    color:#fff; margin-bottom:.5rem;">
                            <i class="bi bi-info-circle me-2" style="color:var(--gold);"></i>
                            Bon à savoir
                        </div>
                        <p style="font-size:.85rem; color:rgba(255,255,255,.55); line-height:1.65; margin:0;">
                            Pour toute demande urgente concernant un document ou un accès, précisez votre
                            parcours et votre niveau dans le message. Cela nous aidera à vous répondre plus vite.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>