<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <script>if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');</script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* ── LIGHT MODE (default) ── */
        :root {
            --bg:        #f0f2f5;
            --surface:   #ffffff;
            --surface2:  #f8fafc;
            --border:    #e5e7eb;
            --border2:   #d1d5db;
            --text:      #111827;
            --muted:     #6b7280;
            --subtle:    #9ca3af;

            --blue:      #2563eb;
            --blue-bg:   rgba(37,99,235,.07);
            --blue-bd:   rgba(37,99,235,.18);

            --green:     #16a34a;
            --green-bg:  rgba(22,163,74,.07);
            --green-bd:  rgba(22,163,74,.2);

            --purple:    #7c3aed;
            --purple-bg: rgba(124,58,237,.07);
            --purple-bd: rgba(124,58,237,.18);

            --amber:     #d97706;
            --amber-bg:  rgba(217,119,6,.07);
            --amber-bd:  rgba(217,119,6,.2);

            --red:       #dc2626;
            --red-bg:    rgba(220,38,38,.07);
            --red-bd:    rgba(220,38,38,.18);

            --teal:      #0d9488;
            --teal-bg:   rgba(13,148,136,.07);
            --teal-bd:   rgba(13,148,136,.18);
        }

        /* ── DARK MODE ── */
        .dark {
            --bg:        #0d1117;
            --surface:   #161b22;
            --surface2:  #1c2230;
            --border:    rgba(255,255,255,.07);
            --border2:   rgba(255,255,255,.13);
            --text:      #e6edf3;
            --muted:     #7d8590;
            --subtle:    #4d5566;

            --blue:      #58a6ff;
            --blue-bg:   rgba(88,166,255,.1);
            --blue-bd:   rgba(88,166,255,.22);

            --green:     #3fb950;
            --green-bg:  rgba(63,185,80,.1);
            --green-bd:  rgba(63,185,80,.25);

            --purple:    #bc8cff;
            --purple-bg: rgba(188,140,255,.1);
            --purple-bd: rgba(188,140,255,.22);

            --amber:     #e3b341;
            --amber-bg:  rgba(227,179,65,.1);
            --amber-bd:  rgba(227,179,65,.22);

            --red:       #f85149;
            --red-bg:    rgba(248,81,73,.1);
            --red-bd:    rgba(248,81,73,.22);

            --teal:      #2dd4bf;
            --teal-bg:   rgba(45,212,191,.1);
            --teal-bd:   rgba(45,212,191,.2);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex; min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            transition: background .2s, color .2s;
        }

        /* sidebar always stays dark navy */
        .sidebar { background: #1a2744 !important; }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 15px; font-weight: 700; color: var(--text); letter-spacing: -.01em; }
        .topbar-meta  { font-size: 11px; color: var(--muted); font-family: 'DM Mono', monospace; margin-top: 2px; }

        .btn-export {
            display: inline-flex; align-items: center; gap: 7px;
            padding: .45rem 1.1rem;
            background: var(--blue); color: #fff;
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: opacity .15s, transform .15s;
        }
        .btn-export:hover { opacity: .88; transform: translateY(-1px); }

        .role-pill {
            font-size: 10px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            letter-spacing: .06em; text-transform: uppercase;
            background: #1a2744; color: #fff;
        }

        /* ── CONTENT ── */
        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1.5rem; }

        .section-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; color: var(--subtle);
            display: flex; align-items: center; gap: 10px;
        }
        .section-label::after { content:''; flex:1; height:1px; background:var(--border); }

        /* ── BANNER ── */
        .banner {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
            position: relative; overflow: hidden;
        }
        .banner::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--green), var(--blue), var(--purple));
        }
        .banner-title { font-size: 20px; font-weight: 700; color: var(--text); letter-spacing: -.02em; }
        .banner-sub   { font-size: 12px; color: var(--muted); margin-top: 3px; font-family: 'DM Mono', monospace; }
        .chip {
            font-size: 11px; font-weight: 500;
            padding: 4px 11px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .chip-g { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-bd); }
        .chip-b { background: var(--blue-bg);  color: var(--blue);  border: 1px solid var(--blue-bd); }

        /* ── STAT GRID ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.2rem 1.25rem 1.5rem;
            position: relative; overflow: hidden;
            transition: box-shadow .2s, transform .2s, border-color .2s;
            cursor: default;
        }
        .stat-card:hover { border-color: var(--border2); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.06); }
        .dark .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.28); }

        /* accent stripe bottom */
        .stat-card::after {
            content:''; position:absolute;
            bottom:0; left:0; right:0; height:3px;
            border-radius: 0 0 14px 14px;
        }
        .s-blue::after   { background: var(--blue); }
        .s-amber::after  { background: var(--amber); }
        .s-green::after  { background: var(--green); }
        .s-purple::after { background: var(--purple); }
        .s-red::after    { background: var(--red); }
        .s-teal::after   { background: var(--teal); }
        .s-grad::after   { background: linear-gradient(90deg, var(--green), var(--blue)); }

        .stat-eyebrow {
            font-size: 10px; font-weight: 600; text-transform: uppercase;
            letter-spacing: .08em; color: var(--subtle); margin-bottom: .55rem;
        }
        .stat-val { font-size: 34px; font-weight: 700; line-height: 1; letter-spacing: -.03em; }
        .stat-val.sm { font-size: 24px; }
        .stat-lbl { font-size: 13px; font-weight: 600; color: var(--text); margin-top: 5px; }
        .stat-sub { font-size: 11px; color: var(--muted); margin-top: 3px; font-family: 'DM Mono', monospace; }

        .stat-ico {
            position: absolute; top: 1.1rem; right: 1.1rem;
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .ico-blue   { background: var(--blue-bg); }
        .ico-amber  { background: var(--amber-bg); }
        .ico-green  { background: var(--green-bg); }
        .ico-purple { background: var(--purple-bg); }
        .ico-red    { background: var(--red-bg); }
        .ico-teal   { background: var(--teal-bg); }

        /* ── ALERTS ── */
        .alerts-row { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden;
        }
        .panel-hd {
            padding: .9rem 1.25rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-hd-t { font-size: 13px; font-weight: 700; color: var(--text); display:flex; align-items:center; gap:6px; }
        .panel-hd-s { font-size: 11px; color: var(--muted); font-family: 'DM Mono', monospace; }

        .renew-cells { display: grid; grid-template-columns: repeat(3,1fr); }
        .renew-cell  {
            padding: 1.25rem;
            border-right: 1px solid var(--border);
        }
        .renew-cell:last-child { border-right: none; }
        .renew-num   { font-size: 30px; font-weight: 700; color: var(--text); line-height: 1; letter-spacing: -.03em; }
        .renew-lbl   { font-size: 12px; font-weight: 600; color: var(--text); margin-top: 5px; }
        .renew-sub   { font-size: 10px; color: var(--muted); font-family: 'DM Mono', monospace; margin-top: 2px; }

        .alert-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            display: flex; align-items: center; gap: 1rem;
        }
        .alert-ico {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .alert-tag  { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 3px; }
        .alert-num  { font-size: 28px; font-weight: 700; line-height: 1; letter-spacing: -.02em; }
        .alert-lbl  { font-size: 12px; font-weight: 600; color: var(--text); margin-top: 4px; }
        .alert-sub  { font-size: 11px; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }

        /* ── CHARTS ── */
        .chart-row { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1rem; }

        .monthly-grid {
            display: grid; grid-template-columns: repeat(12,1fr);
            gap: 6px; align-items: end;
            padding: 1.25rem;
        }
        .mo-col { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .mo-bar-wrap { width: 100%; height: 80px; display: flex; align-items: flex-end; }
        .mo-bar {
            width: 100%; min-height: 3px;
            border-radius: 5px 5px 0 0;
            background: var(--blue-bg);
            border: 1px solid var(--blue-bd);
            position: relative; overflow: hidden;
            transition: opacity .15s; cursor: pointer;
        }
        .mo-bar::after {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 35%;
            background: var(--blue); border-radius: 4px 4px 0 0;
        }
        .mo-bar.peak { background: var(--green-bg); border-color: var(--green-bd); }
        .mo-bar.peak::after { background: var(--green); }
        .mo-bar:hover { opacity: .7; }
        .mo-lbl   { font-size: 9px; color: var(--subtle); font-family: 'DM Mono', monospace; text-transform: uppercase; }
        .mo-count { font-size: 11px; font-weight: 700; color: var(--text); }

        /* fee table */
        .fee-tbl { width: 100%; border-collapse: collapse; }
        .fee-tbl th {
            font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em;
            color: var(--subtle); padding: .5rem .75rem; text-align: left;
            background: var(--surface2); border-bottom: 1px solid var(--border);
        }
        .fee-tbl td { font-size: 13px; color: var(--text); padding: .6rem .75rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .fee-tbl td.r { text-align: right; font-weight: 600; color: var(--green); font-family: 'DM Mono', monospace; }
        .fee-tbl td.c { text-align: center; font-weight: 700; }
        .fee-tbl tr:last-child td { border: none; }
        .fee-tbl .tot td { font-weight: 700; background: var(--surface2); border-top: 1px solid var(--border2); border-bottom: none; }
        .fee-tbl .tot td.r { color: var(--blue); font-size: 14px; }

        .fee-bar { height: 5px; border-radius: 3px; background: var(--blue); opacity: .45; display:inline-block; vertical-align: middle; margin-right: 7px; }

        /* recent table */
        .rec-tbl { width: 100%; border-collapse: collapse; }
        .rec-tbl th {
            font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em;
            color: var(--subtle); padding: .5rem .75rem; text-align: left;
            background: var(--surface2); border-bottom: 1px solid var(--border);
        }
        .rec-tbl td { font-size: 12px; color: var(--muted); padding: .6rem .75rem; border-bottom: 1px solid var(--border); }
        .rec-tbl td.pno { color: var(--text); font-weight: 700; font-family: 'DM Mono', monospace; font-size: 11px; }
        .rec-tbl td.nm  { color: var(--text); font-weight: 500; }
        .rec-tbl tr:last-child td { border: none; }

        .badge {
            display: inline-flex; font-size: 10px; font-weight: 600;
            padding: 2px 9px; border-radius: 20px;
        }
        .b-pending  { background: var(--amber-bg);  color: var(--amber);  border: 1px solid var(--amber-bd); }
        .b-approved { background: var(--green-bg);  color: var(--green);  border: 1px solid var(--green-bd); }
        .b-released { background: var(--blue-bg);   color: var(--blue);   border: 1px solid var(--blue-bd); }
        .b-expired  { background: var(--red-bg);    color: var(--red);    border: 1px solid var(--red-bd); }

        @media (max-width: 1100px) {
            .stat-grid  { grid-template-columns: repeat(2,1fr); }
            .chart-row  { grid-template-columns: 1fr; }
            .alerts-row { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Burial Permit Reports</div>
            <div class="topbar-meta">Municipality of Carmen &nbsp;·&nbsp; {{ now()->year }} &nbsp;·&nbsp; Generated {{ now()->format('M d, Y g:i A') }} by {{ auth()->user()->name }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:.75rem">
            <span class="role-pill">Admin</span>
            <a href="{{ route('reports.export') }}" class="btn-export">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="content">

        {{-- BANNER --}}
        <div class="banner">
            <div>
                <div class="banner-title">Annual Summary &mdash; {{ now()->year }}</div>
                <div class="banner-sub">Carmen Public Cemetery &nbsp;·&nbsp; Davao del Norte &nbsp;·&nbsp; Civil Registrar Office</div>
            </div>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                <span class="chip chip-g">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Live Data
                </span>
                <span class="chip chip-b">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ now()->format('D, M d · g:i A') }}
                </span>
            </div>
        </div>

        {{-- PERMIT STATUS --}}
        <div class="section-label">Permit Status</div>
        <div class="stat-grid">

            <div class="stat-card s-blue">
                <div class="stat-ico ico-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div class="stat-eyebrow">All Time</div>
                <div class="stat-val" style="color:var(--blue)">{{ $totalPermits }}</div>
                <div class="stat-lbl">Total Permits</div>
                <div class="stat-sub">Since system began</div>
            </div>

            <div class="stat-card s-amber">
                <div class="stat-ico ico-amber"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div class="stat-eyebrow">Action Required</div>
                <div class="stat-val" style="color:var(--amber)">{{ $pendingPermits }}</div>
                <div class="stat-lbl">Pending</div>
                <div class="stat-sub">Awaiting approval</div>
            </div>

            <div class="stat-card s-green">
                <div class="stat-ico ico-green"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="stat-eyebrow">Ready</div>
                <div class="stat-val" style="color:var(--green)">{{ $approvedPermits }}</div>
                <div class="stat-lbl">Approved</div>
                <div class="stat-sub">Ready to release</div>
            </div>

            <div class="stat-card s-purple">
                <div class="stat-ico ico-purple"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--purple)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                <div class="stat-eyebrow">Active</div>
                <div class="stat-val" style="color:var(--purple)">{{ $releasedPermits }}</div>
                <div class="stat-lbl">Released</div>
                <div class="stat-sub">Currently valid</div>
            </div>

            <div class="stat-card s-red">
                <div class="stat-ico ico-red"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                <div class="stat-eyebrow">Needs Renewal</div>
                <div class="stat-val" style="color:var(--red)">{{ $expiredPermits }}</div>
                <div class="stat-lbl">Expired</div>
                <div class="stat-sub">{{ $urgentExpiring ?? 0 }} urgent &nbsp;·&nbsp; {{ $expiringSoon ?? 0 }} in 30 days</div>
            </div>

            <div class="stat-card s-blue">
                <div class="stat-ico ico-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
                <div class="stat-eyebrow">This Year ({{ now()->year }})</div>
                <div class="stat-val" style="color:var(--blue)">{{ $newPermits }}</div>
                <div class="stat-lbl">New Permits</div>
                <div class="stat-sub">{{ $permitsThisMonth ?? 0 }} this month &nbsp;·&nbsp; {{ $permitsThisWeek ?? 0 }} this week</div>
            </div>

            <div class="stat-card s-teal">
                <div class="stat-ico ico-teal"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                <div class="stat-eyebrow">Deceased Records</div>
                <div class="stat-val" style="color:var(--teal)">{{ $totalDeceased }}</div>
                <div class="stat-lbl">Total on File</div>
                <div class="stat-sub">{{ $deceasedThisYear ?? 0 }} this year &nbsp;·&nbsp; {{ $deceasedThisMonth ?? 0 }} this month</div>
            </div>

            <div class="stat-card s-grad">
                <div class="stat-ico ico-green"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
                <div class="stat-eyebrow">Estimated Revenue</div>
                <div class="stat-val sm" style="color:var(--green)">₱{{ number_format($estimatedRevenue ?? 0) }}</div>
                <div class="stat-lbl">All Time (Est.)</div>
                <div class="stat-sub">Based on permit fee rates</div>
            </div>

        </div>

        {{-- RENEWALS & EXPIRY --}}
        <div class="section-label">Renewals &amp; Expiry Alerts</div>
        <div class="alerts-row">

            <div class="panel">
                <div class="panel-hd">
                    <span class="panel-hd-t">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                        Renewals Processed
                        <span style="font-size:10px;color:var(--muted);font-weight:400">(permits originally from a previous year)</span>
                    </span>
                </div>
                <div class="renew-cells">
                    <div class="renew-cell">
                        <div class="renew-num">{{ $renewalsThisWeek ?? 0 }}</div>
                        <div class="renew-lbl">This Week</div>
                        <div class="renew-sub">{{ now()->startOfWeek()->format('M d') }}–{{ now()->format('M d') }}</div>
                    </div>
                    <div class="renew-cell">
                        <div class="renew-num">{{ $renewalsThisMonth ?? 0 }}</div>
                        <div class="renew-lbl">This Month</div>
                        <div class="renew-sub">{{ now()->format('F Y') }}</div>
                    </div>
                    <div class="renew-cell">
                        <div class="renew-num">{{ $renewalsThisYear ?? 0 }}</div>
                        <div class="renew-lbl">This Year</div>
                        <div class="renew-sub">Jan–{{ now()->format('M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="alert-card" style="border-left:3px solid var(--red)">
                <div class="alert-ico" style="background:var(--red-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <div class="alert-tag" style="color:var(--red)">Urgent</div>
                    <div class="alert-num" style="color:var(--red)">{{ $urgentExpiring ?? 0 }}</div>
                    <div class="alert-lbl">Expiring in 7 Days</div>
                    <div class="alert-sub">Contact holders now</div>
                </div>
            </div>

            <div class="alert-card" style="border-left:3px solid var(--amber)">
                <div class="alert-ico" style="background:var(--amber-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="alert-tag" style="color:var(--amber)">Warning</div>
                    <div class="alert-num" style="color:var(--amber)">{{ $expiringSoon ?? 0 }}</div>
                    <div class="alert-lbl">Expiring in 30 Days</div>
                    <div class="alert-sub">Incl. {{ $urgentExpiring ?? 0 }} urgent</div>
                </div>
            </div>

        </div>

        {{-- ANALYTICS --}}
        <div class="section-label">Analytics</div>
        <div class="chart-row">

            <div class="panel">
                <div class="panel-hd">
                    <span class="panel-hd-t">Monthly Permits — {{ now()->year }}</span>
                    <span class="panel-hd-s">Busiest: {{ $busiestMonth ?? '—' }} ({{ $busiestCount ?? 0 }})</span>
                </div>
                @php
                    $mos    = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
                    $maxV   = max(array_merge($monthlyData ?? [0], [1]));
                    $peakI  = array_search($maxV, $monthlyData ?? []);
                @endphp
                <div class="monthly-grid">
                    @foreach($mos as $i => $m)
                    @php $c = $monthlyData[$i] ?? 0; @endphp
                    <div class="mo-col">
                        <div class="mo-count">{{ $c }}</div>
                        <div class="mo-bar-wrap">
                            <div class="mo-bar {{ $i === $peakI ? 'peak' : '' }}"
                                 style="height:{{ $maxV > 0 ? max(4, round(($c/$maxV)*100)) : 4 }}%"></div>
                        </div>
                        <div class="mo-lbl">{{ $m }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-hd">
                    <span class="panel-hd-t">Permit Type Breakdown</span>
                    <span class="panel-hd-s">All time</span>
                </div>
                @php
                    $fL = ['cemented'=>'Cemented','niche_1st'=>'1st Floor Niche','niche_2nd'=>'2nd Floor Niche','niche_3rd'=>'3rd Floor Niche','niche_4th'=>'4th Floor Niche','bone_niches'=>'Bone Niches'];
                    $fA = ['cemented'=>1000,'niche_1st'=>8000,'niche_2nd'=>6600,'niche_3rd'=>5700,'niche_4th'=>5300,'bone_niches'=>5000];
                    $fTotal = array_sum(array_values($feeCounts ?? []));
                    $fRev   = 0; foreach(($feeCounts??[]) as $k=>$c){ $fRev += $c*($fA[$k]??0); }
                    $fMax   = max(array_merge(array_values($feeCounts??[]),[1]));
                @endphp
                <table class="fee-tbl">
                    <thead><tr><th>Type</th><th style="text-align:center">Count</th><th style="text-align:right">Revenue</th></tr></thead>
                    <tbody>
                        @foreach($fL as $k => $lbl)
                        @php $c = $feeCounts[$k]??0; $r=$c*($fA[$k]??0); @endphp
                        <tr>
                            <td><div style="display:flex;align-items:center"><div class="fee-bar" style="width:{{ $fMax>0?max(4,round(($c/$fMax)*36)):4 }}px"></div>{{ $lbl }}</div></td>
                            <td class="c">{{ $c }}</td>
                            <td class="r">₱{{ number_format($r) }}</td>
                        </tr>
                        @endforeach
                        <tr class="tot">
                            <td>TOTAL</td>
                            <td class="c">{{ $fTotal }}</td>
                            <td class="r">₱{{ number_format($fRev) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        {{-- RECENT --}}
        <div class="section-label">Recent Permit Applications</div>
        <div class="panel">
            <div class="panel-hd">
                <span class="panel-hd-t">Latest Activity</span>
                <a href="{{ route('permits.index') }}" style="font-size:11px;color:var(--blue);text-decoration:none;font-weight:600">View all →</a>
            </div>
            <table class="rec-tbl">
                <thead>
                    <tr><th>Permit No.</th><th>Deceased</th><th>Type</th><th>Requestor</th><th>Date</th><th>Expiry</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recentPermits ?? [] as $p)
                    <tr>
                        <td class="pno">{{ $p->permit_number }}</td>
                        <td class="nm">{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</td>
                        <td>{{ ucfirst(str_replace('_',' ',$p->permit_type)) }}</td>
                        <td>{{ $p->applicant_name ?? '—' }}</td>
                        <td>{{ $p->created_at->format('M d, Y') }}</td>
                        <td>{{ $p->expiry_date ? $p->expiry_date->format('M d, Y') : '—' }}</td>
                        <td><span class="badge b-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:2rem;font-size:13px">No permits yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>