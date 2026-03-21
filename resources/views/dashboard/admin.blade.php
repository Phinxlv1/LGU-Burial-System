<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        :root {
            --navy:       #0f1e3d;
            --navy-mid:   #1a2f5e;
            --navy-light: #243459;
            --accent:     #3b82f6;
            --accent-bg:  #eff6ff;
            --red:        #ef4444;
            --amber:      #f59e0b;
            --green:      #10b981;
            --surface:    #ffffff;
            --surface-2:  #f8fafc;
            --border:     #e2e8f0;
            --border-2:   #f1f5f9;
            --text-1:     #0f172a;
            --text-2:     #475569;
            --text-3:     #94a3b8;
            --mono:       'DM Mono', monospace;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--text-1);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-left { display: flex; flex-direction: column; gap: 1px; }
        .topbar-title { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .topbar-date  { font-size: 11px; color: var(--text-3); font-weight: 400; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        .role-pill {
            font-family: var(--mono);
            font-size: 10px;
            font-weight: 500;
            color: var(--accent);
            background: var(--accent-bg);
            border: 1px solid #bfdbfe;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .45rem 1rem;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s, transform .12s;
            text-decoration: none;
        }
        .btn-new:hover { background: var(--navy-light); transform: translateY(-1px); }

        /* ── CONTENT ── */
        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1rem; }
        /* ── HERO ── */
        .hero {
            background: var(--navy);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -40px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(59,130,246,.12) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 25%;
            width: 280px; height: 180px;
            background: radial-gradient(ellipse, rgba(29,78,216,.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-text h2 {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -.025em;
        }
        .hero-text p {
            font-size: 12px;
            color: rgba(255,255,255,.4);
            margin-top: .3rem;
            font-weight: 300;
            font-family: var(--mono);
            letter-spacing: .02em;
        }
        .hero-stats {
            display: flex;
            align-items: center;
            gap: 0;
            flex-shrink: 0;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .hero-stat {
            padding: .9rem 1.5rem;
            text-align: center;
        }
        .hero-stat + .hero-stat {
            border-left: 1px solid rgba(255,255,255,.1);
        }
        .hero-stat-val {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.03em;
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 10px;
            color: rgba(255,255,255,.35);
            margin-top: 4px;
            font-family: var(--mono);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* ── STAT CARDS ── */
        .stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            transition: box-shadow .2s, transform .2s;
            cursor: default;
        }
        .stat-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,.07);
            transform: translateY(-2px);
        }
        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon.blue  { background: #eff6ff; }
        .stat-icon.amber { background: #fffbeb; }
        .stat-icon.red   { background: #fef2f2; }
        .stat-icon.green { background: #f0fdf4; }

        .stat-pill {
            font-family: var(--mono);
            font-size: 9px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .stat-pill.ok  { background: #f0fdf4; color: #15803d; }
        .stat-pill.bad { background: #fef2f2; color: #dc2626; }
        .stat-pill.neu { background: var(--surface-2); color: var(--text-3); }

        .stat-value {
            font-size: 34px;
            font-weight: 700;
            letter-spacing: -.04em;
            line-height: 1;
        }
        .stat-value.blue  { color: var(--navy); }
        .stat-value.amber { color: var(--amber); }
        .stat-value.red   { color: var(--red); }
        .stat-value.green { color: var(--green); }

        .stat-label { font-size: 12px; color: var(--text-2); font-weight: 400; }
        .stat-sub   { font-size: 11px; color: var(--text-3); font-family: var(--mono); }

        /* ── TWO-COL ── */
        .two-col {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1rem;
    align-items: start;
    margin-top: 100rem;
}

        /* ── PANEL ── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .panel-head {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid var(--border-2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-title { font-size: 13px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .panel-sub   { font-size: 11px; color: var(--text-3); font-family: var(--mono); margin-top: 2px; }

        .link-arrow {
            font-size: 12px;
            font-weight: 500;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
            transition: gap .15s;
        }
        .link-arrow:hover { gap: 6px; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; }
        th {
            font-size: 10px;
            font-weight: 500;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: .07em;
            padding: .5rem 1rem;
            text-align: left;
            background: var(--surface-2);
            font-family: var(--mono);
            white-space: nowrap;
        }
        td {
            font-size: 13px;
            color: var(--text-2);
            padding: .7rem 1rem;
            border-top: 1px solid var(--border-2);
            vertical-align: middle;
        }
        tbody tr:hover td { background: #f8fafc; }
        tr.row-expired td { background: #fff5f5; border-top-color: #fecaca; }
        tr.row-expired:hover td { background: #fef2f2; }
        tr.row-expired td:first-child { border-left: 3px solid var(--red); }

        .permit-mono {
            font-family: var(--mono);
            font-size: 11px;
            font-weight: 500;
            color: var(--navy);
            letter-spacing: .02em;
        }
        .deceased-name { font-weight: 500; color: var(--text-1); }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            font-family: var(--mono);
            letter-spacing: .03em;
            white-space: nowrap;
        }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-yellow .badge-dot { background: #ca8a04; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-green  .badge-dot { background: #16a34a; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-blue   .badge-dot { background: #3b82f6; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-red    .badge-dot { background: #ef4444; }
        .badge-orange { background: #fff7ed; color: #9a3412; }
        .badge-orange .badge-dot { background: #f97316; }

        /* ── BUTTONS ── */
        .btn-xs {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 6px;
            border: 1px solid var(--border);
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-2);
            background: var(--surface);
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }
        .btn-xs:hover { border-color: var(--navy); color: var(--navy); background: #f8fafc; }
        .btn-xs.danger { border-color: #fca5a5; color: #dc2626; background: #fff5f5; }
        .btn-xs.danger:hover { background: #fee2e2; border-color: var(--red); }

        /* ── PAGINATION ── */
        .pager {
            display: flex; align-items: center; justify-content: space-between;
            padding: .65rem 1.25rem;
            border-top: 1px solid var(--border-2);
        }
        .pager-info { font-size: 11px; color: var(--text-3); font-family: var(--mono); }
        .pager-btns { display: flex; align-items: center; gap: 2px; }
        .pager-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 26px; height: 26px; padding: 0 6px;
            border-radius: 5px; border: 1px solid var(--border);
            font-family: var(--mono); font-size: 11px; color: var(--text-2);
            text-decoration: none; background: var(--surface); cursor: pointer;
            transition: all .15s; line-height: 1;
        }
        .pager-btn:hover { border-color: var(--navy); color: var(--navy); }
        .pager-btn.active { background: var(--navy); color: #fff; border-color: var(--navy); }
        .pager-btn.disabled { color: var(--border); cursor: not-allowed; pointer-events: none; }

        /* ── SIDE STACK ── */
        .side-stack { display: flex; flex-direction: column; gap: 1rem; }

        /* Chart */
        .chart-body { padding: 1rem 1.25rem 1.25rem; }

        /* Alerts */
        .alert-list { display: flex; flex-direction: column; }
        .alert-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1.25rem;
            border-top: 1px solid var(--border-2);
            text-decoration: none;
            color: inherit;
            transition: background .15s;
        }
        .alert-row:first-child { border-top: none; }
        .alert-row:hover { background: var(--surface-2); }
        .alert-indicator {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .alert-indicator.red    { background: var(--red);   box-shadow: 0 0 0 3px #fee2e2; }
        .alert-indicator.amber  { background: var(--amber); box-shadow: 0 0 0 3px #fef3c7; }
        .alert-info { flex: 1; min-width: 0; }
        .alert-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .alert-meta {
            font-size: 10px;
            color: var(--text-3);
            font-family: var(--mono);
            margin-top: 1px;
        }

        /* Progress bars */
        .breakdown-body { padding: .75rem 1.25rem 1.1rem; display: flex; flex-direction: column; gap: .65rem; }
        .prog-row {}
        .prog-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .prog-name  { color: var(--text-2); font-weight: 500; }
        .prog-count { color: var(--text-3); font-family: var(--mono); font-size: 10px; }
        .prog-track {
            height: 4px;
            background: var(--border-2);
            border-radius: 10px;
            overflow: hidden;
        }
        .prog-fill {
            height: 100%;
            border-radius: 10px;
            transition: width .7s cubic-bezier(.4,0,.2,1);
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,30,61,.55);
            z-index: 100; align-items: center; justify-content: center;
            padding: 1rem; overflow-y: auto;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--surface);
            border-radius: 14px;
            width: 100%; max-width: 560px;
            box-shadow: 0 24px 64px rgba(0,0,0,.18);
            overflow: hidden;
            animation: modalIn .2s cubic-bezier(.34,1.3,.64,1);
            margin: auto;
        }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-12px) scale(.97); } to { opacity: 1; transform: none; } }
        .modal-header {
            padding: 1.1rem 1.25rem;
            background: var(--navy);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header h3 { font-size: 15px; font-weight: 600; color: #fff; letter-spacing: -.01em; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.5); padding: 4px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; max-height: 72vh; overflow-y: auto; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label { font-size: 10px; font-weight: 600; color: var(--text-2); text-transform: uppercase; letter-spacing: .07em; font-family: var(--mono); }
        .form-control { font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text-1); padding: .5rem .75rem; border: 1px solid var(--border); border-radius: 8px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: var(--surface); }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px #3b82f615; }
        .form-divider { font-size: 10px; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: .1em; padding: .5rem 0 .25rem; border-bottom: 1px solid var(--border-2); margin-top: .25rem; font-family: var(--mono); }
        .fee-grid { display: flex; flex-direction: column; gap: .35rem; }
        .fee-row { display: flex; align-items: center; gap: 10px; padding: .5rem .75rem; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: all .15s; }
        .fee-row:hover { border-color: var(--accent); background: var(--accent-bg); }
        .fee-row input[type=radio] { accent-color: var(--navy); width: 14px; height: 14px; cursor: pointer; flex-shrink: 0; }
        .fee-row label { font-size: 13px; font-weight: 500; color: var(--text-1); cursor: pointer; flex: 1; }
        .fee-amount { font-size: 12px; font-weight: 700; color: var(--navy); font-family: var(--mono); }
        .fee-section-label { font-size: 10px; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: .07em; padding: .5rem .1rem .2rem; font-family: var(--mono); }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid var(--border-2); display: flex; justify-content: flex-end; gap: .6rem; background: var(--surface-2); }
        .btn-cancel { padding: .5rem 1rem; border-radius: 8px; border: 1px solid var(--border); font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--text-2); background: var(--surface); cursor: pointer; transition: all .15s; }
        .btn-cancel:hover { border-color: var(--navy); color: var(--navy); }
        .btn-submit { display: inline-flex; align-items: center; gap: 6px; padding: .5rem 1.1rem; border-radius: 8px; border: none; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600; color: #fff; background: var(--navy); cursor: pointer; transition: background .15s; }
        .btn-submit:hover { background: var(--navy-light); }

        /* ── TOAST ── */
        .toast { position: fixed; top: 1.1rem; right: 1.1rem; z-index: 9999; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,.12); width: 300px; overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .4s cubic-bezier(.34,1.4,.64,1); pointer-events: none; }
        .toast.show { transform: translateX(0); pointer-events: auto; }
        .toast-body { display: flex; align-items: center; gap: .75rem; padding: .9rem 1rem; }
        .toast-icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #dcfce7; }
        .toast-title { font-size: 13px; font-weight: 600; color: var(--text-1); }
        .toast-msg   { font-size: 12px; color: var(--text-2); margin-top: 1px; }
        .toast-bar   { height: 3px; background: var(--green); transform-origin: left; animation: drainToast 5s linear forwards; }
        @keyframes drainToast { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* ── ANIMATIONS ── */
        .fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp .4s cubic-bezier(.4,0,.2,1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: none; } }
        .d1 { animation-delay: .04s; }
        .d2 { animation-delay: .08s; }
        .d3 { animation-delay: .12s; }
        .d4 { animation-delay: .16s; }
        .d5 { animation-delay: .20s; }
        .d6 { animation-delay: .24s; }
        .d7 { animation-delay: .28s; }
        .d8 { animation-delay: .32s; }

        .empty-row td { text-align: center; color: var(--text-3); padding: 2.5rem; font-size: 13px; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">Dashboard</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right">
            <span class="role-pill">Admin</span>
            <button class="btn-new" onclick="document.getElementById('permitModal').classList.add('open')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </button>
        </div>
    </div>

    <div class="content">

        <!-- HERO BANNER -->
        <div class="hero fade-up">
            <div class="hero-text">
                <h2>{{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }}, {{ auth()->user()->name }}</h2>
                <p>MCR · MUNICIPALITY OF CARMEN · DAVAO DEL NORTE · {{ strtoupper(now()->format('F Y')) }}</p>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-val">{{ $stats['this_month'] }}</div>
                    <div class="hero-stat-label">This Month</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val">{{ $stats['total'] }}</div>
                    <div class="hero-stat-label">All Time</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val" style="color:#fbbf24">{{ ($stats['expired'] ?? 0) + $stats['expiring'] }}</div>
                    <div class="hero-stat-label">Need Action</div>
                </div>
            </div>
        </div>

       <div class="stat-grid">
            <div class="stat-card fade-up d1">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="stat-pill neu">ALL TIME</span>
                </div>
                <div class="stat-value blue">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-sub">{{ $stats['released'] }} released</div>
            </div>
 
            
 
            <div class="stat-card fade-up d3">
                <div class="stat-top">
                    <div class="stat-icon red">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <span class="stat-pill {{ ($stats['expired'] ?? 0) > 0 ? 'bad' : 'ok' }}">{{ ($stats['expired'] ?? 0) > 0 ? 'URGENT' : 'NONE' }}</span>
                </div>
                <div class="stat-value red">{{ $stats['expired'] ?? 0 }}</div>
                <div class="stat-label">Expired</div>
                <div class="stat-sub">Renewal required</div>
            </div>
 
            <div class="stat-card fade-up d4">
                <div class="stat-top">
                    <div class="stat-icon amber">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="stat-pill {{ $stats['expiring'] > 0 ? 'bad' : 'ok' }}">{{ $stats['expiring'] > 0 ? 'SOON' : 'CLEAR' }}</span>
                </div>
                <div class="stat-value amber">{{ $stats['expiring'] }}</div>
                <div class="stat-label">Expiring Soon</div>
                <div class="stat-sub">Within 30 days</div>
            </div>
        </div>
        

        <!-- TWO COLUMN -->
        <div class="two-col" style="align-items: start; margin-top: -.5rem;">
            <!-- LEFT: PERMITS TABLE -->
            <div class="panel fade-up d5">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Recent Permit Applications</div>
                        <div class="panel-sub">{{ $recentPermits->total() }} total &nbsp;·&nbsp; showing latest {{ $recentPermits->count() }}</div>
                    </div>
                    <a href="{{ route('permits.index') }}" class="link-arrow">
                        View all
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Deceased</th>
                            <th>Type</th>
                            <th>Issued</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPermits as $permit)
                        @php
                            $expiring = $permit->status === 'released'
                                && $permit->expiry_date
                                && $permit->expiry_date->isFuture()
                                && $permit->expiry_date->diffInDays(now()) <= 30;
                        @endphp
                        <tr class="{{ $permit->status === 'expired' ? 'row-expired' : '' }}">
                            <td><span class="permit-mono">{{ $permit->permit_number }}</span></td>
                            <td><span class="deceased-name">{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</span></td>
                            <td style="font-size:12px;color:var(--text-3)">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                            <td style="font-size:11px;color:var(--text-3);font-family:var(--mono)">{{ $permit->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($permit->status === 'expired')
                                    <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                                @elseif($expiring)
                                    <span class="badge badge-orange"><span class="badge-dot"></span>Expiring</span>
                                @elseif($permit->status === 'released')
                                    <span class="badge badge-blue"><span class="badge-dot"></span>Released</span>
                                @elseif($permit->status === 'approved')
                                    <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                                @else
                                    <span class="badge badge-yellow"><span class="badge-dot"></span>Pending</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:4px;align-items:center">
                                    <a href="{{ route('permits.show', $permit) }}" class="btn-xs">View</a>
                                    @if($permit->status === 'expired')
                                    <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:inline" onsubmit="return confirm('Renew this permit?')">
                                        @csrf
                                        <button type="submit" class="btn-xs danger">Renew</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="6">No permits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if($recentPermits->hasPages())
                <div class="pager">
                    <span class="pager-info">{{ $recentPermits->firstItem() }}–{{ $recentPermits->lastItem() }} of {{ $recentPermits->total() }}</span>
                    <div class="pager-btns">
                        @if($recentPermits->onFirstPage())
                            <span class="pager-btn disabled">‹</span>
                        @else
                            <a href="{{ $recentPermits->previousPageUrl() }}" class="pager-btn">‹</a>
                        @endif
                        @php $cur=$recentPermits->currentPage();$last=$recentPermits->lastPage();$pages=[];for($p=1;$p<=$last;$p++)if($p==1||$p==$last||abs($p-$cur)<=1)$pages[]=$p;sort($pages); @endphp
                        @php $prev=null; @endphp
                        @foreach($pages as $page)
                            @if($prev!==null&&$page-$prev>1)<span class="pager-btn disabled" style="border:none">…</span>@endif
                            @if($page==$cur)<span class="pager-btn active">{{$page}}</span>@else<a href="{{ $recentPermits->url($page) }}" class="pager-btn">{{$page}}</a>@endif
                            @php $prev=$page; @endphp
                        @endforeach
                        @if($recentPermits->hasMorePages())<a href="{{ $recentPermits->nextPageUrl() }}" class="pager-btn">›</a>@else<span class="pager-btn disabled">›</span>@endif
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT COLUMN -->
            <div class="side-stack">

                <!-- Monthly Chart -->
                <div class="panel fade-up d6">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Monthly Permits</div>
                            <div class="panel-sub">{{ now()->year }} — permits issued per month</div>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="monthlyChart" height="150"></canvas>
                    </div>
                </div>

                <!-- Needs Attention -->
                @php
                    $needsAction = \App\Models\BurialPermit::with('deceased')
                        ->whereIn('status', ['expired', 'pending'])
                        ->latest()->limit(5)->get();
                @endphp
                <div class="panel fade-up d7">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Needs Attention</div>
                            <div class="panel-sub">Expired &amp; awaiting permits</div>
                        </div>
                        <a href="{{ route('permits.index', ['sort' => 'status', 'direction' => 'desc']) }}" class="link-arrow">All →</a>
                    </div>
                    <div class="alert-list">
                        @forelse($needsAction as $item)
                        <a href="{{ route('permits.show', $item) }}" class="alert-row">
                            <div class="alert-indicator {{ $item->status === 'expired' ? 'red' : 'amber' }}"></div>
                            <div class="alert-info">
                                <div class="alert-name">{{ optional($item->deceased)->last_name }}, {{ optional($item->deceased)->first_name }}</div>
                                <div class="alert-meta">{{ $item->permit_number }} · {{ $item->created_at->format('M d, Y') }}</div>
                            </div>
                            @if($item->status === 'expired')
                                <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                            @else
                                <span class="badge badge-yellow"><span class="badge-dot"></span>Pending</span>
                            @endif
                        </a>
                        @empty
                        <div style="padding:1.5rem;text-align:center;font-size:12px;color:var(--text-3)">
                            ✓ &nbsp;All clear — nothing needs attention
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Permit Type Breakdown -->
                @php
                    $feeBreakdown = \App\Models\BurialPermit::selectRaw('permit_type, COUNT(*) as total')
                        ->groupBy('permit_type')->pluck('total','permit_type');
                    $totalP = $feeBreakdown->sum() ?: 1;
                    $feeLabels = ['cemented'=>'Cemented','niche_1st'=>'1st Floor','niche_2nd'=>'2nd Floor','niche_3rd'=>'3rd Floor','niche_4th'=>'4th Floor','bone_niches'=>'Bone Niches'];
                    $feeColors = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444'];
                @endphp
                <div class="panel fade-up d8">
                    <div class="panel-head">
                        <div>
                            <div class="panel-title">Permit Type Breakdown</div>
                            <div class="panel-sub">Distribution across burial categories</div>
                        </div>
                    </div>
                    <div class="breakdown-body">
                        @foreach($feeLabels as $key => $label)
                        @php $cnt=$feeBreakdown[$key]??0; $pct=round(($cnt/$totalP)*100); $col=$feeColors[$loop->index%count($feeColors)]; @endphp
                        <div class="prog-row">
                            <div class="prog-labels">
                                <span class="prog-name">{{ $label }}</span>
                                <span class="prog-count">{{ $cnt }} &nbsp;<span style="color:var(--border)">|</span>&nbsp; {{ $pct }}%</span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $col }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div><!-- /side-stack -->
        </div><!-- /two-col -->

    </div><!-- /content -->
</div><!-- /main -->


<!-- TOAST -->
@if(session('success'))
<div class="toast" id="successToast">
    <div class="toast-body">
        <div class="toast-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <div class="toast-title">Success</div>
            <div class="toast-msg">{{ session('success') }}</div>
        </div>
    </div>
    <div class="toast-bar"></div>
</div>
@endif


<!-- NEW PERMIT MODAL -->
<div class="modal-overlay" id="permitModal" onclick="if(event.target===this)closeModal()">
    <div class="modal">
        <div class="modal-header">
            <h3>New Burial Permit</h3>
            <button class="modal-close" onclick="closeModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('permits.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Requestor's Name <span style="color:var(--red)">*</span></label>
                    <input type="text" name="requestor_name" class="form-control" placeholder="Full name of requestor" required>
                </div>
                <div class="form-divider">Deceased Information</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color:var(--red)">*</span></label>
                        <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color:var(--red)">*</span></label>
                        <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" placeholder="Filipino">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select…</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Date of Death <span style="color:var(--red)">*</span></label>
                        <input type="date" name="date_of_death" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kind of Burial</label>
                        <select name="kind_of_burial" class="form-control">
                            <option value="">Select…</option>
                            <option value="Ground">Ground</option>
                            <option value="Niche">Niche</option>
                            <option value="Cremation">Cremation</option>
                        </select>
                    </div>
                </div>
                <div class="form-divider">Burial Permit Fees</div>
                <div class="fee-grid">
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="cemented" id="fee_cemented">
                        <label for="fee_cemented">Cemented</label>
                        <span class="fee-amount">₱1,000.00</span>
                    </div>
                    <div class="fee-section-label">Niches (New)</div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_1st" id="fee_1st">
                        <label for="fee_1st">1st Floor</label>
                        <span class="fee-amount">₱8,000.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_2nd" id="fee_2nd">
                        <label for="fee_2nd">2nd Floor</label>
                        <span class="fee-amount">₱6,600.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_3rd" id="fee_3rd">
                        <label for="fee_3rd">3rd Floor</label>
                        <span class="fee-amount">₱5,700.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_4th" id="fee_4th">
                        <label for="fee_4th">4th Floor</label>
                        <span class="fee-amount">₱5,300.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="bone_niches" id="fee_bone">
                        <label for="fee_bone">Bone Niches</label>
                        <span class="fee-amount">₱5,000.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Create Permit
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function closeModal() { document.getElementById('permitModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// Open modal from hash
if (window.location.hash === '#new') {
    document.getElementById('permitModal').classList.add('open');
    history.replaceState(null, '', window.location.pathname);
}

// Toast
(function(){
    const t = document.getElementById('successToast');
    if (!t) return;
    requestAnimationFrame(() => setTimeout(() => t.classList.add('show'), 60));
    setTimeout(() => t.classList.remove('show'), 5300);
})();

// Monthly chart
const monthlyData = @json($stats['monthly']);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const maxVal = Math.max(...monthlyData, 1);

new Chart(document.getElementById('monthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            backgroundColor: monthlyData.map(v => v === maxVal && v > 0 ? '#0f1e3d' : '#e2e8f0'),
            hoverBackgroundColor: '#3b82f6',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f1e3d',
                titleFont: { family: 'DM Mono', size: 10 },
                bodyFont:  { family: 'DM Mono', size: 12 },
                padding: 10,
                displayColors: false,
                callbacks: {
                    title: items => months[items[0].dataIndex],
                    label: item => ` ${item.raw} permit${item.raw !== 1 ? 's' : ''}`,
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { family: 'DM Mono', size: 10 }, color: '#94a3b8', maxTicksLimit: 5 },
                grid: { color: '#f1f5f9' },
                border: { display: false }
            },
            x: {
                ticks: { font: { family: 'DM Mono', size: 10 }, color: '#94a3b8' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>

</body>
</html>