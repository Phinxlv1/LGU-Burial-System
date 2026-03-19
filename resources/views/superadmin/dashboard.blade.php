<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Overview — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-left { display: flex; flex-direction: column; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 11px; color: #9ca3af; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }
        .sa-tag { background: linear-gradient(90deg,#1a2744,#2563eb); color: #fff; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 4px; letter-spacing: .05em; text-transform: uppercase; }
        .btn-export { display: inline-flex; align-items: center; gap: 5px; padding: .42rem .9rem; border-radius: 6px; border: 1px solid #1a2744; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #1a2744; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-export:hover { background: #1a2744; color: #fff; }

        .content { padding: 1rem; display: flex; flex-direction: column; gap: .85rem; }

        /* STAT CARDS */
        .stat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: .6rem; }
        .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: .7rem .9rem; display: flex; flex-direction: column; gap: .25rem; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.c1::before { background: #3b82f6; }
        .stat-card.c2::before { background: #f59e0b; }
        .stat-card.c3::before { background: #10b981; }
        .stat-card.c4::before { background: #6366f1; }
        .stat-card.c5::before { background: #e01a6e; }
        .stat-icon { width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
        .stat-icon.c1 { background: #eff6ff; }
        .stat-icon.c2 { background: #fef3c7; }
        .stat-icon.c3 { background: #d1fae5; }
        .stat-icon.c4 { background: #ede9fe; }
        .stat-icon.c5 { background: #fce7f3; }
        .stat-value { font-size: 20px; font-weight: 800; color: #111827; line-height: 1; }
        .stat-label { font-size: 10px; font-weight: 500; color: #6b7280; }
        .stat-sub { font-size: 9px; font-weight: 600; color: #10b981; }
        .stat-sub.warn { color: #f59e0b; }
        .stat-sub.neutral { color: #9ca3af; }

        /* CHART GRID */
        .chart-row { display: grid; gap: .85rem; }
        .chart-row-2 { grid-template-columns: 1.5fr 1fr; }

        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .card-head { padding: .55rem .9rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .card-head-title { font-size: 12px; font-weight: 700; color: #111827; }
        .card-head-sub { font-size: 10px; color: #9ca3af; }
        .card-body { padding: .6rem .8rem; }
        canvas { display: block; }

        /* LEGEND */
        .legend { display: flex; flex-direction: column; gap: .3rem; }
        .legend-row { display: flex; align-items: center; justify-content: space-between; padding: .3rem 0; border-bottom: 1px solid #f9fafb; }
        .legend-row:last-child { border: none; }
        .legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .legend-label { font-size: 11px; color: #374151; flex: 1; margin-left: .5rem; }
        .legend-val { font-size: 11px; font-weight: 700; color: #111827; }
        .legend-pct { font-size: 10px; color: #9ca3af; margin-left: .3rem; width: 28px; text-align: right; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 9px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .35rem .6rem; text-align: left; background: #fafafa; }
        td { font-size: 11px; color: #374151; padding: .4rem .6rem; border-top: 1px solid #f3f4f6; }
        .pno { font-weight: 700; color: #1a2744; font-size: 11px; }
        .badge { display: inline-flex; font-size: 9px; font-weight: 600; padding: 2px 6px; border-radius: 3px; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* READ-ONLY NOTICE */
        .readonly-bar { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: .4rem .85rem; display: flex; align-items: center; gap: .6rem; font-size: 11px; color: #1e40af; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">System Overview</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('superadmin.export') }}" class="btn-export">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export Report
            </a>
            <span class="sa-tag">⚡ Super Admin</span>
        </div>
    </div>

    <div class="content">

        <div class="readonly-bar">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Read-only mode — system-wide statistics. Click Export Report to download a PDF.
        </div>

        @php
            $totalPermits     = \App\Models\BurialPermit::count();
            $pendingPermits   = \App\Models\BurialPermit::where('status','pending')->count();
            $approvedPermits  = \App\Models\BurialPermit::where('status','approved')->count();
            $releasedPermits  = \App\Models\BurialPermit::where('status','released')->count();
            $expiredPermits   = \App\Models\BurialPermit::where('status','expired')->count();
            $totalDeceased    = \App\Models\DeceasedPerson::count();
            $permitsThisMonth = \App\Models\BurialPermit::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $deceasedThisMonth= \App\Models\DeceasedPerson::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $monthly = \App\Models\BurialPermit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', now()->year)->groupBy('month')->pluck('total','month')->toArray();
            $monthlyData = [];
            for($m=1;$m<=12;$m++) $monthlyData[] = $monthly[$m] ?? 0;
            $feeCounts = \App\Models\BurialPermit::selectRaw('permit_type, COUNT(*) as total')
                ->groupBy('permit_type')->pluck('total','permit_type')->toArray();
            $feeLabels = ['cemented'=>'Cemented','niche_1st'=>'1st Floor','niche_2nd'=>'2nd Floor',
                          'niche_3rd'=>'3rd Floor','niche_4th'=>'4th Floor','bone_niches'=>'Bone Niches'];
            $feeData = array_values(array_map(fn($k) => $feeCounts[$k] ?? 0, array_keys($feeLabels)));
            $recentPermits = \App\Models\BurialPermit::with('deceased')->latest()->limit(10)->get();
        @endphp

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="stat-card c1">
                <div class="stat-icon c1"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div class="stat-value">{{ $totalPermits }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-sub">↑ {{ $permitsThisMonth }} this month</div>
            </div>
            <div class="stat-card c2">
                <div class="stat-icon c2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div class="stat-value">{{ $pendingPermits }}</div>
                <div class="stat-label">Pending</div>
                <div class="stat-sub warn">Awaiting action</div>
            </div>
            <div class="stat-card c3">
                <div class="stat-icon c3"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="stat-value">{{ $releasedPermits }}</div>
                <div class="stat-label">Released</div>
                <div class="stat-sub">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}% of total</div>
            </div>
            <div class="stat-card c4">
                <div class="stat-icon c4"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                <div class="stat-value">{{ $totalDeceased }}</div>
                <div class="stat-label">Deceased Records</div>
                <div class="stat-sub">↑ {{ $deceasedThisMonth }} this month</div>
            </div>
            <div class="stat-card c5">
                <div class="stat-icon c5"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e01a6e" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></div>
                <div class="stat-value">{{ $approvedPermits }}</div>
                <div class="stat-label">Approved</div>
                <div class="stat-sub neutral">Awaiting release</div>
            </div>
        </div>

        {{-- ROW 1: Monthly + Donut --}}
        <div class="chart-row chart-row-2">
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Monthly Applications — {{ now()->year }}</div>
                    <div class="card-head-sub">Permits per month</div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="110"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Status Distribution</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem">
                    <canvas id="statusChart" height="140" style="max-height:140px"></canvas>
                    <div class="legend">
                        <div class="legend-row"><div class="legend-dot" style="background:#f59e0b"></div><div class="legend-label">Pending</div><div class="legend-val">{{ $pendingPermits }}</div><div class="legend-pct">{{ $totalPermits > 0 ? round(($pendingPermits/$totalPermits)*100) : 0 }}%</div></div>
                        <div class="legend-row"><div class="legend-dot" style="background:#10b981"></div><div class="legend-label">Approved</div><div class="legend-val">{{ $approvedPermits }}</div><div class="legend-pct">{{ $totalPermits > 0 ? round(($approvedPermits/$totalPermits)*100) : 0 }}%</div></div>
                        <div class="legend-row"><div class="legend-dot" style="background:#3b82f6"></div><div class="legend-label">Released</div><div class="legend-val">{{ $releasedPermits }}</div><div class="legend-pct">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}%</div></div>
                        <div class="legend-row"><div class="legend-dot" style="background:#ef4444"></div><div class="legend-label">Expired</div><div class="legend-val">{{ $expiredPermits }}</div><div class="legend-pct">{{ $totalPermits > 0 ? round(($expiredPermits/$totalPermits)*100) : 0 }}%</div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 2: Recent + Fee type --}}
        <div class="chart-row chart-row-2">
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Recent Permit Applications</div>
                    <a href="{{ route('permits.index') }}" style="font-size:11px;color:#1a2744;text-decoration:none;font-weight:600">View all →</a>
                </div>
                <table>
                    <thead><tr><th>Permit No.</th><th>Deceased</th><th>Type</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentPermits as $p)
                        <tr>
                            <td><span class="pno">{{ $p->permit_number }}</span></td>
                            <td>{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</td>
                            <td style="color:#6b7280">{{ ucfirst(str_replace('_',' ',$p->permit_type)) }}</td>
                            <td style="color:#6b7280">{{ $p->created_at->format('M d, Y') }}</td>
                            <td>@php $c=['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red']; @endphp
                                <span class="badge {{ $c[$p->status] ?? 'badge-yellow' }}">{{ ucfirst($p->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:1rem">No permits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">Permit Type Breakdown</div>
                </div>
                <div class="card-body">
                    <canvas id="feeChart" height="150"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{ data: @json($monthlyData), backgroundColor: 'rgba(26,39,68,0.82)', borderRadius: 3, borderSkipped: false }] },
    options: { responsive: true, plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f3f4f6' } },
                  x: { ticks: { font: { size: 9 } }, grid: { display: false } } } }
});
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: ['Pending','Approved','Released','Expired'],
        datasets: [{ data: [{{ $pendingPermits }},{{ $approvedPermits }},{{ $releasedPermits }},{{ $expiredPermits }}],
            backgroundColor: ['#f59e0b','#10b981','#3b82f6','#ef4444'], borderWidth: 0, hoverOffset: 4 }] },
    options: { responsive: true, maintainAspectRatio: true, cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw}` } } } }
});
new Chart(document.getElementById('feeChart'), {
    type: 'bar',
    data: { labels: @json(array_values($feeLabels)),
        datasets: [{ data: @json($feeData),
            backgroundColor: ['#1a2744','#3b82f6','#6366f1','#8b5cf6','#a78bfa','#10b981'],
            borderRadius: 3, borderSkipped: false }] },
    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f3f4f6' } },
                  y: { ticks: { font: { size: 9 } }, grid: { display: false } } } }
});
</script>
</body>
</html>