{{--
    ── SHARED ADMIN STYLES ──
    Colle ce bloc @once dans chaque page admin, ou mieux :
    place-le dans ton layout x-app-layout directement.
--}}
@once
<style>
    :root { --navy:#0d1b2a; --gold:#c9a84c; --cream:#f7f3ed; --slate:#4a5568; }

    /* ── hero bar ── */
    .af-hero-bar {
        background-color:var(--navy); border-bottom:3px solid var(--gold);
        position:relative; overflow:hidden; padding:2.25rem 0;
    }
    .af-hero-bar::before {
        content:''; position:absolute; inset:0;
        background-image:repeating-linear-gradient(45deg,rgba(201,168,76,.05) 0,rgba(201,168,76,.05) 1px,transparent 0,transparent 50%);
        background-size:28px 28px; pointer-events:none; z-index:1;
    }
    .af-hero-bar .container { position:relative; z-index:2; }
    .af-label { font-size:.7rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:var(--gold); }

    /* ── page body ── */
    .af-body { background-color:var(--cream); padding:3rem 0; font-family:'DM Sans',sans-serif; }

    /* ── card ── */
    .af-card { background:#fff; border:1px solid #e8e2da; border-radius:4px; padding:2.25rem; }

    /* ── form fields ── */
    .af-form-label {
        display:block; font-size:.75rem; font-weight:600;
        letter-spacing:.07em; text-transform:uppercase;
        color:var(--navy); margin-bottom:.4rem;
    }
    .af-input, .af-select {
        width:100%; font-family:'DM Sans',sans-serif; font-size:.9rem;
        color:var(--navy); background:#fff;
        border:1px solid #d9d2c8; border-radius:3px;
        padding:.6rem .95rem; outline:none;
        transition:border-color .2s, box-shadow .2s;
    }
    .af-input:focus, .af-select:focus {
        border-color:var(--gold); box-shadow:0 0 0 3px rgba(201,168,76,.12);
    }
    .af-error { font-size:.78rem; color:#c0392b; margin-top:.3rem; }

    /* ── buttons ── */
    .af-btn {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.65rem 1.4rem; border-radius:3px;
        font-family:'DM Sans',sans-serif; font-size:.8rem;
        font-weight:700; letter-spacing:.06em; text-transform:uppercase;
        text-decoration:none; border:none; cursor:pointer;
        transition:background .2s, transform .15s;
    }
    .af-btn:hover { transform:translateY(-1px); }
    .af-btn-primary   { background:var(--gold);  color:var(--navy); border:1px solid #b8973f; }
    .af-btn-primary:hover { background:#b8973f; color:var(--navy); border-color:#9f8036; }
    .af-btn-navy      { background:var(--navy);  color:#fff; }
    .af-btn-navy:hover { background:#1b3a6b; color:#fff; }
    .af-btn-ghost {
        background:transparent; color:var(--slate);
        border:1px solid #d9d2c8;
    }
    .af-btn-ghost:hover { border-color:var(--navy); color:var(--navy); }
    .af-btn-sm { padding:.4rem .9rem; font-size:.72rem; }
    .af-btn-danger     { background:#c0392b; color:#fff; }
    .af-btn-danger:hover { background:#a93226; color:#fff; }
    .af-btn-warning    { background:#e67e22; color:#fff; }
    .af-btn-warning:hover { background:#ca6f1e; color:#fff; }

    /* ── alert ── */
    .af-alert-success {
        background:rgba(201,168,76,.08); border:1px solid rgba(201,168,76,.35);
        border-left:4px solid var(--gold); border-radius:3px;
        padding:.9rem 1.1rem; color:var(--navy);
        font-size:.88rem; margin-bottom:1.5rem;
        display:flex; align-items:center; gap:.6rem;
    }

    /* ── table ── */
    .af-table { width:100%; border-collapse:collapse; font-size:.875rem; }
    .af-table thead { background-color:var(--navy); }
    .af-table thead th {
        padding:.85rem 1rem; text-align:left;
        font-size:.7rem; font-weight:600; letter-spacing:.12em;
        text-transform:uppercase; color:rgba(255,255,255,.7);
        border:none;
    }
    .af-table tbody tr { border-bottom:1px solid #e8e2da; transition:background .15s; }
    .af-table tbody tr:hover { background-color:#fdf9f3; }
    .af-table tbody td { padding:.85rem 1rem; color:var(--navy); vertical-align:middle; }
    .af-table-empty { text-align:center; padding:3rem; color:var(--slate); font-size:.9rem; }
    .af-table-empty i { font-size:2rem; color:#d9d2c8; display:block; margin-bottom:.75rem; }

    /* ── status badge ── */
    .af-badge-online  { background:rgba(39,174,96,.12); color:#1e8449; border:1px solid rgba(39,174,96,.3); border-radius:20px; padding:.2rem .75rem; font-size:.72rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }
    .af-badge-offline { background:rgba(74,85,104,.1);  color:var(--slate); border:1px solid rgba(74,85,104,.2); border-radius:20px; padding:.2rem .75rem; font-size:.72rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }
    .af-badge-admin   { background:rgba(201,168,76,.12); color:#7d6026; border:1px solid rgba(201,168,76,.3); border-radius:20px; padding:.2rem .75rem; font-size:.72rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }
    .af-badge-user    { background:rgba(13,27,42,.07); color:var(--navy); border:1px solid rgba(13,27,42,.12); border-radius:20px; padding:.2rem .75rem; font-size:.72rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; }

    .af-online-dot { width:8px; height:8px; border-radius:50%; background:#27ae60; display:inline-block; margin-right:.4rem; box-shadow:0 0 0 2px rgba(39,174,96,.3); }

    .table-responsive { overflow-x:auto; -webkit-overflow-scrolling:touch; }

    /* High contrast for top action buttons in hero bars */
    .af-hero-action,
    .af-hero-action:visited {
        background:#ffffff !important;
        color:#0d1b2a !important;
        border:2px solid #c9a84c !important;
        box-shadow:0 6px 18px rgba(0,0,0,.18);
    }
    .af-hero-action:hover,
    .af-hero-action:focus {
        background:#c9a84c !important;
        color:#0d1b2a !important;
        border-color:#ffffff !important;
        text-decoration:none;
    }
    .af-hero-action i { color:inherit !important; }

    @media (max-width: 992px) {
        .af-body { padding:2rem 0; }
        .af-card { padding:1.4rem; }
    }

    @media (max-width: 768px) {
        .af-hero-bar { padding:1.35rem 0; }
        .af-body { padding:1.25rem 0; }
        .af-card { padding:1rem; border-radius:8px; }
        .af-btn { font-size:.72rem; padding:.55rem .95rem; }
        .af-table { min-width:680px; font-size:.82rem; }
        .af-hero-action {
            width:100%;
            justify-content:center;
        }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@endonce
