<style>
    .af-footer {
        background-color: #070f19;
        border-top: 3px solid var(--gold, #c9a84c);
        color: rgba(255,255,255,.4);
        font-family: 'DM Sans', sans-serif;
        font-size: .82rem;
        padding: 2rem 0;
    }
    .af-footer-brand {
        font-family: 'Playfair Display', serif;
        color: rgba(255,255,255,.75);
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .02em;
    }
    .af-footer-brand span { color: var(--gold, #c9a84c); }
    .af-footer a {
        color: rgba(255,255,255,.4);
        text-decoration: none;
        transition: color .2s;
        white-space: nowrap;
    }
    .af-footer a:hover { color: var(--gold, #c9a84c); }
    .af-footer-divider {
        color: rgba(255,255,255,.12);
        margin: 0 .5rem;
    }
</style>

<footer class="af-footer">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">

            {{-- Brand + copyright --}}
            <div>
                <div class="af-footer-brand mb-1">
                    <i class="bi bi-archive me-1" style="color:var(--gold,#c9a84c);"></i>
                    Notre <span>Archive</span>
                </div>
                <div style="font-size:.75rem;">
                    &copy; {{ date('Y') }} Tous droits réservés.
                </div>
            </div>

            {{-- Links --}}
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-1" style="row-gap:.4rem;">
                <a href="{{ route('apropos') }}">À propos</a>
                <span class="af-footer-divider">·</span>
                <a href="{{ route('contact') }}">Contact</a>
                <span class="af-footer-divider">·</span>
                <a href="#">Mentions légales</a>
                <span class="af-footer-divider">·</span>
                <a href="mailto:josueawougno@gmail.com?subject=Demande d'information&body=Bonjour,">
                    <i class="bi bi-envelope me-1"></i>Email admin
                </a>
                <span class="af-footer-divider">·</span>
                <a href="tel:+22893947171">
                    <i class="bi bi-telephone me-1"></i>+228 93 94 71 71
                </a>
            </div>

        </div>
    </div>
</footer>
