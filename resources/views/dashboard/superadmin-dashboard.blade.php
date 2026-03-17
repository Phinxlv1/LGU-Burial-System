<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; }

        /* Topbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; }
        .topbar-seal { width: 32px; height: 32px; }
        .topbar-title { font-size: 14px; font-weight: 600; color: #1a2744; }
        .topbar-sub { font-size: 11px; color: #6b7280; margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .user-badge {
            display: flex; align-items: center; gap: 8px;
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: 6px; padding: 5px 10px;
            font-size: 13px; color: #374151;
        }
        .role-tag {
            background: #1a2744; color: #fff;
            font-size: 10px; font-weight: 600;
            padding: 2px 7px; border-radius: 4px;
            letter-spacing: .04em; text-transform: uppercase;
        }
        .btn-logout {
            background: none; border: 1px solid #e5e7eb;
            border-radius: 6px; padding: 5px 12px;
            font-family: 'Inter', sans-serif; font-size: 13px;
            color: #6b7280; cursor: pointer; transition: all .15s;
        }
        .btn-logout:hover { border-color: #dc2626; color: #dc2626; }

        /* Main */
        .main { padding: 1.75rem 1.5rem; max-width: 1100px; margin: 0 auto; }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 20px; font-weight: 600; color: #111827; }
        .page-header p { font-size: 13px; color: #6b7280; margin-top: 3px; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.25rem;
            transition: box-shadow .15s;
        }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.06); }

        .stat-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .5rem; }
        .stat-value { font-size: 28px; font-weight: 700; color: #111827; line-height: 1; }
        .stat-sub { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        .stat-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 5px; }

        .dot-blue   { background: #1a2744; }
        .dot-green  { background: #10b981; }
        .dot-yellow { background: #f59e0b; }
        .dot-red    { background: #ef4444; }

        /* Cards row */
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }

        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .panel-header {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-body { padding: 1.25rem; }

        /* Recent table */
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; }
        td { font-size: 13px; color: #374151; padding: .6rem .75rem; border-top: 1px solid #f3f4f6; }
        tr:first-child td { border-top: none; }

        .badge {
            display: inline-block;
            font-size: 11px; font-weight: 500;
            padding: 2px 8px; border-radius: 4px;
        }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* Chart placeholder */
        .chart-bar { display: flex; align-items: flex-end; gap: 8px; height: 120px; padding: 0 .25rem; }
        .bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
        .bar { width: 100%; background: #1a2744; border-radius: 4px 4px 0 0; transition: opacity .2s; min-height: 4px; }
        .bar:hover { opacity: .75; }
        .bar-label { font-size: 10px; color: #9ca3af; }

        .notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: .85rem 1rem;
            font-size: 13px;
            color: #92400e;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 640px) {
            .row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="topbar-left">
        <svg class="topbar-seal" viewBox="0 0 32 32" fill="none">
            <circle cx="16" cy="16" r="14" stroke="#1a2744" stroke-width="1.2"/>
            <circle cx="16" cy="16" r="9" stroke="#1a2744" stroke-width=".8"/>
            <circle cx="16" cy="16" r="3" fill="#1a2744"/>
            <line x1="16" y1="3" x2="16" y2="8" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round"/>
            <line x1="16" y1="24" x2="16" y2="29" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round"/>
            <line x1="3" y1="16" x2="8" y2="16" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round"/>
            <line x1="24" y1="16" x2="29" y2="16" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round"/>
        </svg>
        <div>
            <div class="topbar-title">LGU Carmen — Burial Permit System</div>
            <div class="topbar-sub">Municipal Civil Registrar Office</div>
        </div>
    </div>
    <div class="topbar-right">
        <div class="user-badge">
            <span>{{ auth()->user()->name }}</span>
            <span class="role-tag">Super Admin</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<!-- Main -->
<div class="main">

    <div class="notice">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        You are logged in as <strong>&nbsp;Super Admin&nbsp;</strong>. You have read-only access to system overview and reports.
    </div>

    <div class="page-header">
        <h1>System Overview</h1>
        <p>{{ now()->format('l, F d, Y') }} &nbsp;·&nbsp; Municipality of Carmen, Davao del Norte</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Permits Issued</div>
            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-sub">All time</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">{{ $stats['this_month'] ?? 0 }}</div>
            <div class="stat-sub">{{ now()->format('F Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><span class="stat-dot dot-yellow"></span>Pending</div>
            <div class="stat-value" style="color:#f59e0b">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-sub">Awaiting approval</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><span class="stat-dot dot-green"></span>Approved</div>
            <div class="stat-value" style="color:#10b981">{{ $stats['approved'] ?? 0 }}</div>
            <div class="stat-sub">Ready to release</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><span class="stat-dot dot-blue"></span>Released</div>
            <div class="stat-value" style="color:#1a2744">{{ $stats['released'] ?? 0 }}</div>
            <div class="stat-sub">Permits released</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><span class="stat-dot dot-red"></span>Expiring Soon</div>
            <div class="stat-value" style="color:#ef4444">{{ $stats['expiring'] ?? 0 }}</div>
            <div class="stat-sub">Within 30 days</div>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <!-- Recent Permits -->
        <div class="panel">
            <div class="panel-header">
                Recent Permit Applications
                <span style="font-size:11px;color:#9ca3af;font-weight:400">Latest 5</span>
            </div>
            <div class="panel-body" style="padding:0">
                <table>
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Deceased</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPermits ?? [] as $permit)
                        <tr>
                            <td style="font-weight:500;color:#1a2744">{{ $permit->permit_number }}</td>
                            <td>{{ $permit->deceased->last_name ?? '—' }}, {{ $permit->deceased->first_name ?? '' }}</td>
                            <td>{{ $permit->created_at->format('M d') }}</td>
                            <td>
                                @php
                                    $colors = ['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red'];
                                @endphp
                                <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:1.5rem">No permits yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly chart -->
        <div class="panel">
            <div class="panel-header">Monthly Permits (Last 6 Months)</div>
            <div class="panel-body">
                <div class="chart-bar">
                    @php
                        $monthly = $stats['monthly'] ?? [0,0,0,0,0,0];
                        $max = max($monthly) ?: 1;
                        $months = [];
                        for($i=5; $i>=0; $i--) {
                            $months[] = now()->subMonths($i)->format('M');
                        }
                    @endphp
                    @foreach($monthly as $i => $count)
                    <div class="bar-wrap">
                        <div class="bar" style="height:{{ round(($count/$max)*100) }}%"></div>
                        <div class="bar-label">{{ $months[$i] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>


