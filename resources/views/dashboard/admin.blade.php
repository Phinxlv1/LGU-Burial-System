<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .nav-section { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px; color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px; margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem; background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar { width: 28px; height: 28px; background: rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
        .user-name { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.4); }
        .btn-logout { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-left { display: flex; flex-direction: column; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 11px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── WELCOME BANNER ── */
        .welcome { background: #1a2744; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
        .welcome-text h2 { font-size: 18px; font-weight: 700; color: #fff; }
        .welcome-text p { font-size: 12px; color: rgba(255,255,255,.5); margin-top: .2rem; }
        .welcome-action { display: inline-flex; align-items: center; gap: 6px; padding: .55rem 1.1rem; background: #fff; color: #1a2744; border-radius: 7px; font-size: 13px; font-weight: 600; text-decoration: none; transition: background .15s; }
        .welcome-action:hover { background: #f0f4ff; }

        /* ── STAT CARDS ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.blue::before  { background: #3b82f6; }
        .stat-card.amber::before { background: #f59e0b; }
        .stat-card.green::before { background: #10b981; }
        .stat-card.red::before   { background: #ef4444; }
        .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon.blue  { background: #eff6ff; }
        .stat-icon.amber { background: #fef3c7; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.red   { background: #fee2e2; }
        .stat-info { flex: 1; }
        .stat-value { font-size: 26px; font-weight: 800; color: #111827; line-height: 1; }
        .stat-label { font-size: 11px; font-weight: 500; color: #6b7280; margin-top: 3px; }
        .stat-sub { font-size: 10px; color: #10b981; font-weight: 600; margin-top: 2px; }
        .stat-sub.warn { color: #f59e0b; }

        /* ── RECENT TABLE ── */
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head a { font-size: 12px; color: #1a2744; text-decoration: none; font-weight: 600; }
        .panel-head a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; }
        .permit-no { font-weight: 700; color: #1a2744; font-size: 12px; }
        .badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .btn-view { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-view:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .empty-row { text-align: center; color: #9ca3af; padding: 2.5rem; font-size: 13px; }
    </style>
</head>
<body>

@include('partials.sidebar')


<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">Dashboard</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        {{-- WELCOME BANNER --}}
        <div class="welcome">
            <div class="welcome-text">
                <h2>Welcome back, {{ auth()->user()->name }} 👋</h2>
                <p>Here's what's happening with burial permits today.</p>
            </div>
            <a href="{{ route('permits.index') }}" class="welcome-action">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </a>
        </div>

        {{-- STAT CARDS --}}
        @php
            $total    = \App\Models\BurialPermit::count();
            $pending  = \App\Models\BurialPermit::where('status','pending')->count();
            $approved = \App\Models\BurialPermit::where('status','approved')->count();
            $released = \App\Models\BurialPermit::where('status','released')->count();
        @endphp
        <div class="stat-grid">
            <div class="stat-card blue">
                <div class="stat-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total Permits</div>
                    <div class="stat-sub">All time</div>
                </div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $pending }}</div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-sub warn">Needs action</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $approved }}</div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-sub">Awaiting release</div>
                </div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon red">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $released }}</div>
                    <div class="stat-label">Released</div>
                    <div class="stat-sub">{{ $total > 0 ? round(($released/$total)*100) : 0 }}% of total</div>
                </div>
            </div>
        </div>

        {{-- RECENT PERMITS --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Recent Permit Applications</h3>
                <a href="{{ route('permits.index') }}">View all →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Permit No.</th>
                        <th>Deceased</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermits as $permit)
                    <tr>
                        <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                        <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $permit->created_at->format('M d, Y') }}</td>
                        <td>
                            @php $colors=['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red']; @endphp
                            <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">{{ ucfirst($permit->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('permits.show', $permit) }}" class="btn-view">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">No permits yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>