<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0d1117;
            color: #e6edf3;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* ── SUPER ADMIN SIDEBAR ── */
        .sa-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #0c0f1a;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 50;
            border-right: 1px solid rgba(255,255,255,.05);
        }
        .sa-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #e01a6e, #f59e0b);
        }
        .sa-brand {
            padding: 1.4rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sa-brand-top { display: flex; align-items: center; gap: 10px; margin-bottom: .4rem; }
        .sa-seal {
            width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
            flex-shrink: 0; border: 1.5px solid rgba(255,255,255,.15); filter: brightness(.9);
        }
        .sa-brand-text h1 { font-size: 12px; font-weight: 700; color: #fff; line-height: 1.3; }
        .sa-role-badge {
            display: inline-flex; align-items: center; gap: 4px;
            background: linear-gradient(90deg, rgba(99,102,241,.25), rgba(224,26,110,.2));
            border: 1px solid rgba(99,102,241,.4);
            border-radius: 20px; padding: 2px 8px;
            font-size: 9px; font-weight: 800; color: #a5b4fc;
            letter-spacing: .08em; text-transform: uppercase; margin-top: 5px;
        }
        .sa-nav { flex: 1; padding: .85rem 0; }
        .sa-nav-section {
            font-size: 8.5px; font-weight: 700; letter-spacing: .14em;
            text-transform: uppercase; color: rgba(255,255,255,.2);
            padding: .85rem 1.25rem .35rem;
        }
        .sa-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: .52rem 1rem; font-size: 12.5px; color: rgba(255,255,255,.5);
            text-decoration: none; border-radius: 7px; margin: 1px .65rem;
            transition: background .15s, color .15s; position: relative;
        }
        .sa-nav-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); }
        .sa-nav-item.active {
            background: rgba(99,102,241,.15); color: #a5b4fc; font-weight: 600;
        }
        .sa-nav-item.active::before {
            content: '';
            position: absolute; left: -0.65rem; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(180deg, #6366f1, #e01a6e);
            border-radius: 0 2px 2px 0;
        }
        .sa-nav-item svg { flex-shrink: 0; opacity: .6; }
        .sa-nav-item.active svg { opacity: 1; }
        .sa-divider { height: 1px; background: rgba(255,255,255,.05); margin: .5rem 1.25rem; }
        .sa-footer { padding: .9rem; border-top: 1px solid rgba(255,255,255,.06); }
        .sa-user-card {
            display: flex; align-items: center; gap: 9px;
            padding: .6rem .85rem;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 8px; margin-bottom: .6rem;
        }
        .sa-avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #6366f1, #e01a6e);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .sa-user-name { font-size: 12px; color: #fff; font-weight: 600; }
        .sa-user-role { font-size: 10px; color: #6366f1; font-weight: 600; margin-top: 1px; }
        .sa-logout {
            width: 100%; background: none;
            border: 1px solid rgba(255,255,255,.1); border-radius: 7px; padding: .45rem;
            font-family: inherit; font-size: 12px; color: rgba(255,255,255,.35);
            cursor: pointer; transition: all .15s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .sa-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .topbar {
            background: rgba(13,17,23,.95);
            border-bottom: 1px solid rgba(255,255,255,.07);
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(12px);
        }
        .topbar-left { display: flex; align-items: center; gap: .75rem; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #fff; }
        .topbar-date { font-size: 11px; color: rgba(255,255,255,.3); font-family: 'DM Mono', monospace; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }
        .sa-tag {
            background: linear-gradient(90deg, #6366f1, #e01a6e);
            color: #fff; font-size: 10px; font-weight: 800;
            padding: 4px 12px; border-radius: 20px;
            letter-spacing: .05em; text-transform: uppercase;
        }

        /* ── CONTENT ── */
        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1.5rem; }

        /* ── HERO BANNER ── */
        .hero-banner {
            background: linear-gradient(135deg, #1a1f35 0%, #0f1525 50%, #1a1022 100%);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
            position: relative; overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute; top: -30px; right: -30px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(99,102,241,.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-banner::after {
            content: '';
            position: absolute; bottom: -40px; left: 30%;
            width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(224,26,110,.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-text h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: .3rem; }
        .hero-text p { font-size: 13px; color: rgba(255,255,255,.4); }
        .hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; position: relative; z-index: 1; }
        .btn-export {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .55rem 1.1rem; border-radius: 8px;
            font-family: inherit; font-size: 12px; font-weight: 600;
            cursor: pointer; text-decoration: none; transition: all .15s;
            background: rgba(99,102,241,.2); color: #a5b4fc;
            border: 1px solid rgba(99,102,241,.4);
        }
        .btn-export:hover { background: rgba(99,102,241,.35); border-color: #6366f1; }
        .btn-export.primary {
            background: #6366f1; color: #fff; border-color: #6366f1;
        }
        .btn-export.primary:hover { background: #4f46e5; }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-card {
            background: #161b27;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex; flex-direction: column; gap: .75rem;
            position: relative; overflow: hidden;
            transition: border-color .2s, transform .2s;
        }
        .stat-card:hover { border-color: rgba(255,255,255,.14); transform: translateY(-1px); }
        .stat-card::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 2px;
        }
        .stat-card.indigo::after { background: linear-gradient(90deg, transparent, #6366f1, transparent); }
        .stat-card.green::after  { background: linear-gradient(90deg, transparent, #10b981, transparent); }
        .stat-card.amber::after  { background: linear-gradient(90deg, transparent, #f59e0b, transparent); }
        .stat-card.rose::after   { background: linear-gradient(90deg, transparent, #e01a6e, transparent); }
        .stat-card.cyan::after   { background: linear-gradient(90deg, transparent, #06b6d4, transparent); }
        .stat-card.red::after    { background: linear-gradient(90deg, transparent, #ef4444, transparent); }
        .stat-card.violet::after { background: linear-gradient(90deg, transparent, #8b5cf6, transparent); }
        .stat-card.teal::after   { background: linear-gradient(90deg, transparent, #14b8a6, transparent); }

        .stat-icon-wrap {
            width: 38px; height: 38px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon-wrap.indigo { background: rgba(99,102,241,.15); }
        .stat-icon-wrap.green  { background: rgba(16,185,129,.15); }
        .stat-icon-wrap.amber  { background: rgba(245,158,11,.15); }
        .stat-icon-wrap.rose   { background: rgba(224,26,110,.15); }
        .stat-icon-wrap.cyan   { background: rgba(6,182,212,.15); }
        .stat-icon-wrap.red    { background: rgba(239,68,68,.15); }
        .stat-icon-wrap.violet { background: rgba(139,92,246,.15); }
        .stat-icon-wrap.teal   { background: rgba(20,184,166,.15); }

        .stat-value {
            font-size: 32px; font-weight: 800; color: #fff;
            line-height: 1; font-family: 'DM Mono', monospace; letter-spacing: -.02em;
        }
        .stat-label { font-size: 11px; font-weight: 600; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .06em; }
        .stat-trend { font-size: 11px; color: rgba(255,255,255,.25); }
        .stat-trend.up   { color: #10b981; }
        .stat-trend.warn { color: #f59e0b; }
        .stat-trend.down { color: #ef4444; }

        /* ── CHART GRID ── */
        .chart-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.25rem; }

        .panel {
            background: #161b27;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            overflow: hidden;
        }
        .panel-head {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-head-title { font-size: 13px; font-weight: 700; color: #fff; }
        .panel-head-sub { font-size: 11px; color: rgba(255,255,255,.25); }
        .panel-body { padding: 1.25rem; }

        /* ── TABLE ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,.25);
            text-transform: uppercase; letter-spacing: .07em;
            padding: .5rem .75rem; text-align: left;
            background: rgba(255,255,255,.03);
        }
        .data-table td {
            font-size: 12px; color: rgba(255,255,255,.7);
            padding: .65rem .75rem; border-top: 1px solid rgba(255,255,255,.04);
        }
        .data-table tr:hover td { background: rgba(255,255,255,.02); }
        .permit-no {
            font-weight: 700; color: #a5b4fc;
            font-family: 'DM Mono', monospace; font-size: 11px;
        }

        /* ── STATUS BADGES ── */
        .badge {
            display: inline-flex; align-items: center;
            font-size: 10px; font-weight: 600;
            padding: 2px 8px; border-radius: 4px;
        }
        .badge-pending  { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
        .badge-approved { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
        .badge-released { background: rgba(99,102,241,.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,.3); }
        .badge-expired  { background: rgba(239,68,68,.15);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }

        /* ── LEGEND ── */
        .legend-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .legend-row:last-child { border: none; }
        .legend-left { display: flex; align-items: center; gap: 8px; }
        .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
        .legend-label { font-size: 12px; color: rgba(255,255,255,.6); }
        .legend-right { display: flex; align-items: center; gap: 8px; }
        .legend-count { font-size: 14px; font-weight: 800; color: #fff; font-family: 'DM Mono', monospace; }
        .legend-pct { font-size: 11px; color: rgba(255,255,255,.25); width: 32px; text-align: right; }

        /* ── MONTHLY BAR MINI ── */
        .mini-bars { display: flex; align-items: flex-end; gap: 5px; height: 80px; }
        .mini-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
        .mini-bar {
            width: 100%; border-radius: 3px 3px 0 0;
            background: linear-gradient(180deg, #6366f1, #4f46e5);
            min-height: 3px; transition: opacity .2s;
        }
        .mini-bar:hover { opacity: .75; }
        .mini-bar-label { font-size: 9px; color: rgba(255,255,255,.25); font-family: 'DM Mono', monospace; }

        /* ── BOTTOM WIDE PANEL ── */
        .wide-panel { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

        @media (max-width: 1200px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .chart-grid { grid-template-columns: 1fr; }
            .wide-panel { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- SUPER ADMIN SIDEBAR --}}
<aside class="sa-sidebar">
    <div class="sa-brand">
        <div class="sa-brand-top">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sa-seal">
            <div>
                <h1 style="font-size:12px;font-weight:700;color:#fff;line-height:1.3">LGU Carmen<br>Burial System</h1>
            </div>
        </div>
        <div class="sa-role-badge">⚡ Super Administrator</div>
    </div>

    <nav class="sa-nav">
        <div class="sa-nav-section">Overview</div>
        <a href="{{ route('superadmin.dashboard') }}" class="sa-nav-item active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        <div class="sa-nav-section">Records</div>
        <a href="{{ route('permits.index') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Deceased Records
        </a>
        <a href="{{ route('cemetery.map') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Cemetery Map
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">Analytics</div>
        <a href="{{ route('reports.index') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Reports
        </a>
        <a href="{{ route('superadmin.export') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export PDF
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">System</div>
        <a href="{{ route('admin.users.index') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            User Management
        </a>
        <a href="{{ route('settings.index') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            Settings
        </a>
    </nav>

    <div class="sa-footer">
        <div class="sa-user-card">
            <div class="sa-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="sa-user-name">{{ auth()->user()->name }}</div>
                <div class="sa-user-role">Super Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sa-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<div class="main">
    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <div>
                <div class="topbar-title">System Overview</div>
                <div class="topbar-date">{{ now()->format('D, d M Y · H:i') }}</div>
            </div>
        </div>
        <div class="topbar-right">
            <span class="sa-tag">⚡ Super Admin</span>
        </div>
    </div>

    <div class="content">

        {{-- HERO BANNER --}}
        <div class="hero-banner">
            <div class="hero-text">
                <h2>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
                <p>Municipality of Carmen, Davao del Norte &nbsp;·&nbsp; Municipal Civil Registrar &nbsp;·&nbsp; {{ now()->format('F Y') }}</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('reports.index') }}" class="btn-export">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Reports
                </a>
                <a href="{{ route('superadmin.export') }}" class="btn-export primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF Report
                </a>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="stat-card indigo">
                <div class="stat-icon-wrap indigo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $totalPermits }}</div>
                    <div class="stat-label">Total Permits</div>
                    <div class="stat-trend up">↑ {{ $permitsThisMonth }} this month</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon-wrap green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $releasedPermits }}</div>
                    <div class="stat-label">Released</div>
                    <div class="stat-trend up">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}% of total</div>
                </div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon-wrap amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $pendingPermits }}</div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-trend {{ $pendingPermits > 5 ? 'warn' : '' }}">Awaiting action</div>
                </div>
            </div>
            <div class="stat-card cyan">
                <div class="stat-icon-wrap cyan">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $approvedPermits }}</div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-trend">Ready to release</div>
                </div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon-wrap red">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $expiredPermits }}</div>
                    <div class="stat-label">Expired</div>
                    <div class="stat-trend down">Need renewal</div>
                </div>
            </div>
            <div class="stat-card violet">
                <div class="stat-icon-wrap violet">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $totalDeceased }}</div>
                    <div class="stat-label">Deceased Records</div>
                    <div class="stat-trend up">↑ {{ $deceasedThisMonth }} this month</div>
                </div>
            </div>
            <div class="stat-card rose">
                <div class="stat-icon-wrap rose">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e01a6e" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $newPermits }}</div>
                    <div class="stat-label">New This Year</div>
                    <div class="stat-trend">{{ now()->year }} permits issued</div>
                </div>
            </div>
            <div class="stat-card teal">
                <div class="stat-icon-wrap teal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $renewedPermits }}</div>
                    <div class="stat-label">Renewed This Year</div>
                    <div class="stat-trend">{{ now()->year }} renewals</div>
                </div>
            </div>
        </div>

        {{-- CHARTS ROW --}}
        <div class="chart-grid">
            {{-- Monthly Bar Chart --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">Monthly Permit Applications</div>
                    <div class="panel-head-sub">{{ now()->year }}</div>
                </div>
                <div class="panel-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>

            {{-- Status Doughnut + Legend --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">Status Distribution</div>
                    <div class="panel-head-sub">All permits</div>
                </div>
                <div class="panel-body" style="display:flex;flex-direction:column;gap:1.25rem">
                    <canvas id="statusChart" height="160"></canvas>
                    <div>
                        <div class="legend-row">
                            <div class="legend-left"><div class="legend-dot" style="background:#f59e0b"></div><span class="legend-label">Pending</span></div>
                            <div class="legend-right"><span class="legend-count">{{ $pendingPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($pendingPermits/$totalPermits)*100) : 0 }}%</span></div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-left"><div class="legend-dot" style="background:#10b981"></div><span class="legend-label">Approved</span></div>
                            <div class="legend-right"><span class="legend-count">{{ $approvedPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($approvedPermits/$totalPermits)*100) : 0 }}%</span></div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-left"><div class="legend-dot" style="background:#6366f1"></div><span class="legend-label">Released</span></div>
                            <div class="legend-right"><span class="legend-count">{{ $releasedPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}%</span></div>
                        </div>
                        <div class="legend-row">
                            <div class="legend-left"><div class="legend-dot" style="background:#ef4444"></div><span class="legend-label">Expired</span></div>
                            <div class="legend-right"><span class="legend-count">{{ $expiredPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($expiredPermits/$totalPermits)*100) : 0 }}%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTTOM: Recent Permits + Fee Breakdown --}}
        <div class="wide-panel">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">Recent Applications</div>
                    <a href="{{ route('permits.index') }}" style="font-size:12px;color:#6366f1;text-decoration:none;font-weight:600">View all →</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Deceased</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPermits as $permit)
                        <tr>
                            <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                            <td>{{ optional($permit->deceased)->last_name ?? '—' }}, {{ optional($permit->deceased)->first_name ?? '' }}</td>
                            <td>{{ $permit->created_at->format('M d, Y') }}</td>
                            <td>
                                @php $bc=['pending'=>'badge-pending','approved'=>'badge-approved','released'=>'badge-released','expired'=>'badge-expired']; @endphp
                                <span class="badge {{ $bc[$permit->status] ?? 'badge-pending' }}">{{ ucfirst($permit->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;color:rgba(255,255,255,.2);padding:2rem">No permits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-title">Permit Type Breakdown</div>
                </div>
                <div class="panel-body">
                    <canvas id="feeChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
const chartDefaults = {
    color: 'rgba(255,255,255,.5)',
    borderColor: 'rgba(255,255,255,.08)',
};
Chart.defaults.color = chartDefaults.color;

// Monthly
new Chart(document.getElementById('monthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Permits',
            data: @json($monthlyData),
            backgroundColor: function(ctx) {
                const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                g.addColorStop(0, 'rgba(99,102,241,.9)');
                g.addColorStop(1, 'rgba(224,26,110,.6)');
                return g;
            },
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 10, family: "'DM Mono'" }, color: 'rgba(255,255,255,.25)' },
                grid: { color: 'rgba(255,255,255,.05)' },
                border: { color: 'transparent' }
            },
            x: {
                ticks: { font: { size: 10, family: "'DM Mono'" }, color: 'rgba(255,255,255,.25)' },
                grid: { display: false },
                border: { color: 'transparent' }
            }
        }
    }
});

// Doughnut
new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Pending','Approved','Released','Expired'],
        datasets: [{
            data: [{{ $pendingPermits }},{{ $approvedPermits }},{{ $releasedPermits }},{{ $expiredPermits }}],
            backgroundColor: ['#f59e0b','#10b981','#6366f1','#ef4444'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '72%',
        plugins: { legend: { display: false } }
    }
});

// Fee type horizontal bar
new Chart(document.getElementById('feeChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Cemented','1st Floor','2nd Floor','3rd Floor','4th Floor','Bone Niches','Other'],
        datasets: [{
            label: 'Count',
            data: @json($feeTypeData),
            backgroundColor: [
                'rgba(99,102,241,.8)',
                'rgba(6,182,212,.8)',
                'rgba(16,185,129,.8)',
                'rgba(245,158,11,.8)',
                'rgba(139,92,246,.8)',
                'rgba(20,184,166,.8)',
                'rgba(255,255,255,.15)',
            ],
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 10, family: "'DM Mono'" }, color: 'rgba(255,255,255,.25)' },
                grid: { color: 'rgba(255,255,255,.05)' },
                border: { color: 'transparent' }
            },
            y: {
                ticks: { font: { size: 10 }, color: 'rgba(255,255,255,.4)' },
                grid: { display: false },
                border: { color: 'transparent' }
            }
        }
    }
});
</script>

</body>
</html>