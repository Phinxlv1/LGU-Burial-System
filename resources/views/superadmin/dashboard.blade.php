<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal {
    width: 34px; height: 34px;
    border-radius: 50%; object-fit: cover;
    flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2);
}
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .nav-section { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px; color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px; margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }

        /* Super Admin badge in nav */
        .nav-badge { font-size: 9px; font-weight: 700; background: #e01a6e; color: #fff; padding: 1px 6px; border-radius: 3px; margin-left: auto; letter-spacing: .04em; }

        .sidebar-footer { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem; background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar { width: 28px; height: 28px; background: linear-gradient(135deg,#e01a6e,#1a2744); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .user-name { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role { font-size: 10px; color: #e01a6e; font-weight: 600; }
        .btn-logout { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }
        .superadmin-tag { background: linear-gradient(90deg,#1a2744,#e01a6e); color: #fff; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 4px; letter-spacing: .05em; text-transform: uppercase; }

        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── STAT CARDS ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.1rem 1.25rem; display: flex; flex-direction: column; gap: .5rem; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.blue::before  { background: #3b82f6; }
        .stat-card.green::before { background: #10b981; }
        .stat-card.amber::before { background: #f59e0b; }
        .stat-card.rose::before  { background: #e01a6e; }
        .stat-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .stat-icon.blue  { background: #eff6ff; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.amber { background: #fef3c7; }
        .stat-icon.rose  { background: #fce7f3; }
        .stat-value { font-size: 28px; font-weight: 800; color: #111827; line-height: 1; }
        .stat-label { font-size: 12px; font-weight: 500; color: #6b7280; }
        .stat-trend { font-size: 11px; font-weight: 600; color: #10b981; margin-top: .1rem; }
        .stat-trend.down { color: #ef4444; }

        /* ── CHART CARDS ── */
        .chart-row { display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.25rem; }
        .chart-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }

        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .card-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .card-head-title { font-size: 13px; font-weight: 700; color: #111827; }
        .card-head-sub { font-size: 11px; color: #9ca3af; }
        .card-body { padding: 1.25rem; }
        .card-body-sm { padding: 1rem; }

        canvas { display: block; }

        /* ── RECENT TABLE ── */
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 12px; color: #374151; padding: .6rem .75rem; border-top: 1px solid #f3f4f6; }
        .permit-no { font-weight: 700; color: #1a2744; }
        .badge { display: inline-flex; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 3px; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* ── DISTRIBUTION LEGEND ── */
        .legend-item { display: flex; align-items: center; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid #f3f4f6; }
        .legend-item:last-child { border: none; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-label { font-size: 12px; color: #374151; flex: 1; margin-left: .6rem; }
        .legend-count { font-size: 12px; font-weight: 700; color: #111827; }
        .legend-pct { font-size: 11px; color: #9ca3af; margin-left: .4rem; width: 36px; text-align: right; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-top">
           <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sidebar-seal">
     alt="Municipality of Carmen Seal"
     class="sidebar-seal">
            </svg>
            <h1>LGU Carmen<br>Burial System</h1>
        </div>
        <p>Municipal Civil Registrar</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-item active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
            <span class="nav-badge">SA</span>
        </a>

        <div class="nav-section">Management</div>
        <a href="{{ route('permits.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Deceased Records
        </a>
        <a href="{{ route('cemetery.map') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Cemetery Map
        </a>

        <div class="nav-section">Analytics</div>
        <a href="{{ route('reports.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Reports
        </a>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Excel
        </a>

        <div class="nav-section">System</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            User Management
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Super Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">System Overview</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="superadmin-tag">⚡ Super Admin</span>
    </div>

    <div class="content">

        {{-- ── STAT CARDS ── --}}
        <div class="stat-grid">
            <div class="stat-card blue">
                <div class="stat-icon blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="stat-value">{{ $totalPermits }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-trend">↑ {{ $permitsThisMonth }} this month</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon green">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="stat-value">{{ $releasedPermits }}</div>
                <div class="stat-label">Released</div>
                <div class="stat-trend">{{ $totalPermits > 0 ? round(($releasedPermits / $totalPermits) * 100) : 0 }}% of total</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon amber">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="stat-value">{{ $pendingPermits }}</div>
                <div class="stat-label">Pending</div>
                <div class="stat-trend {{ $pendingPermits > 5 ? 'down' : '' }}">Awaiting action</div>
            </div>
            <div class="stat-card rose">
                <div class="stat-icon rose">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e01a6e" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="stat-value">{{ $totalDeceased }}</div>
                <div class="stat-label">Deceased Records</div>
                <div class="stat-trend">↑ {{ $deceasedThisMonth }} this month</div>
            </div>
        </div>

        {{-- ── CHARTS ROW 1: Monthly + Donut ── --}}
        <div class="chart-row">
            {{-- Monthly Bar Chart --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-head-title">Monthly Permit Applications</div>
                        <div class="card-head-sub">{{ now()->year }} — permits issued per month</div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>

            {{-- Status Donut --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Permit Status Distribution</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:1rem">
                    <canvas id="statusChart" height="160"></canvas>
                    <div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#f59e0b"></div>
                            <div class="legend-label">Pending</div>
                            <div class="legend-count">{{ $pendingPermits }}</div>
                            <div class="legend-pct">{{ $totalPermits > 0 ? round(($pendingPermits/$totalPermits)*100) : 0 }}%</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#10b981"></div>
                            <div class="legend-label">Approved</div>
                            <div class="legend-count">{{ $approvedPermits }}</div>
                            <div class="legend-pct">{{ $totalPermits > 0 ? round(($approvedPermits/$totalPermits)*100) : 0 }}%</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#3b82f6"></div>
                            <div class="legend-label">Released</div>
                            <div class="legend-count">{{ $releasedPermits }}</div>
                            <div class="legend-pct">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}%</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#ef4444"></div>
                            <div class="legend-label">Expired</div>
                            <div class="legend-count">{{ $expiredPermits }}</div>
                            <div class="legend-pct">{{ $totalPermits > 0 ? round(($expiredPermits/$totalPermits)*100) : 0 }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CHARTS ROW 2: Fee type + Recent ── --}}
        <div class="chart-row">
            {{-- Recent Permits Table --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Recent Permit Applications</div>
                    <a href="{{ route('permits.index') }}" style="font-size:12px;color:#1a2744;text-decoration:none;font-weight:600">View all →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Deceased</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPermits as $permit)
                        <tr>
                            <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                            <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>
                            <td style="text-transform:capitalize;color:#6b7280">{{ str_replace('_',' ',$permit->permit_type) }}</td>
                            <td style="color:#6b7280">{{ $permit->created_at->format('M d, Y') }}</td>
                            <td>
                                @php $colors=['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red']; @endphp
                                <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">{{ ucfirst($permit->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:1.5rem">No permits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Fee Type Breakdown --}}
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Permit Type Breakdown</div>
                </div>
                <div class="card-body">
                    <canvas id="feeChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// ── MONTHLY BAR CHART ──
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Permits',
            data: @json($monthlyData),
            backgroundColor: 'rgba(26,39,68,0.85)',
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
            x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
    }
});

// ── STATUS DONUT CHART ──
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Released', 'Expired'],
        datasets: [{
            data: [{{ $pendingPermits }}, {{ $approvedPermits }}, {{ $releasedPermits }}, {{ $expiredPermits }}],
            backgroundColor: ['#f59e0b','#10b981','#3b82f6','#ef4444'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
        }
    }
});

// ── FEE TYPE HORIZONTAL BAR ──
const feeCtx = document.getElementById('feeChart').getContext('2d');
new Chart(feeCtx, {
    type: 'bar',
    data: {
        labels: ['Cemented','1st Floor','2nd Floor','3rd Floor','4th Floor','Bone Niches','Other'],
        datasets: [{
            label: 'Count',
            data: @json($feeTypeData),
            backgroundColor: [
                '#1a2744','#3b82f6','#6366f1','#8b5cf6','#a78bfa','#10b981','#9ca3af'
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
            x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
            y: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
    }
});
</script>

</body>
</html>


