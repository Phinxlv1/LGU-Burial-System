<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <script>/* dark-mode anti-flash */
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('admin.partials.design-system')
    <style>
        /* Report Specific Styles (not in design-system) */
        .analytics-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 1024px) {
            .analytics-grid { grid-template-columns: 1fr; }
        }

        .renew-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }
        .renew-item {
            padding: 1.25rem;
            border-right: 1px solid var(--border-2);
            text-align: center;
        }
        .renew-item:last-child { border-right: none; }
        .renew-val { font-size: 28px; font-weight: 700; color: var(--text-1); line-height: 1; }
        .renew-lbl { font-size: 11px; font-weight: 600; color: var(--text-2); margin-top: 5px; text-transform: uppercase; letter-spacing: 0.05em; }
        .renew-sub { font-size: 10px; color: var(--text-3); font-family: var(--mono); margin-top: 2px; }

        .fee-table td.r { text-align: right; font-weight: 600; color: var(--green); font-family: var(--mono); }
        .fee-table td.c { text-align: center; font-weight: 700; }
        .fee-bar { height: 4px; border-radius: 2px; background: var(--accent); opacity: 0.4; display: inline-block; vertical-align: middle; margin-right: 8px; }

        .alert-panel-list { display: flex; flex-direction: column; }
        .alert-panel-item {
            display: flex; align-items: center; gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-2);
            text-decoration: none; color: inherit;
            transition: background 0.15s;
        }
        .alert-panel-item:last-child { border-bottom: none; }
        .alert-panel-item:hover { background: var(--surface-2); }
    </style>
</head>
<body>

@include('admin.partials.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">Burial Permit Reports</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right">
            <span class="role-pill">Admin</span>
            <a href="{{ route('reports.export') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="content" style="gap: 1.5rem;">

        <!-- HERO SUMMARY -->
        <div class="hero fade-up">
            <div class="hero-text">
                <h2>Annual Summary — {{ now()->year }}</h2>
                <p>CARMEN PUBLIC CEMETERY · DAVAO DEL NORTE · CIVIL REGISTRAR OFFICE</p>
                <div style="display: flex; gap: 8px; margin-top: 1rem;">
                    <span class="badge badge-green" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981;">
                        <span class="badge-dot"></span> Live Data
                    </span>
                    <span class="badge badge-blue" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.2); color: #3b82f6;">
                         {{ now()->format('D, M d · g:i A') }}
                    </span>
                </div>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-val">{{ $activePermits }}</div>
                    <div class="hero-stat-label">Active</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val" style="color: #ef4444;">{{ $expiredPermits }}</div>
                    <div class="hero-stat-label">Expired</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val" style="color: #f59e0b;">{{ $expiringPermits }}</div>
                    <div class="hero-stat-label">Alerts</div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">
            <!-- Total Permits -->
            <div class="stat-card fade-up d1" style="cursor: default;">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="stat-pill neu">ALL TIME</span>
                </div>
                <div class="stat-value blue">{{ $totalPermits }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-sub">Since system began</div>
            </div>

            <!-- New This Year -->
            <div class="stat-card fade-up d2" style="cursor: default;">
                <div class="stat-top">
                    <div class="stat-icon green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <span class="stat-pill neu">{{ now()->year }} NEW</span>
                </div>
                <div class="stat-value green">{{ $newPermits }}</div>
                <div class="stat-label">New Permits</div>
                <div class="stat-sub">{{ $permitsThisMonth ?? 0 }} this month</div>
            </div>

            <!-- Potential Revenue -->
            <div class="stat-card fade-up d3" style="cursor: default;">
                <div class="stat-top">
                    <div class="stat-icon amber">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <span class="stat-pill neu">POTENTIAL</span>
                </div>
                <div class="stat-value amber" style="font-size: 26px;">₱{{ number_format($estimatedRevenue ?? 0) }}</div>
                <div class="stat-label">Expected Revenue</div>
                <div class="stat-sub">Active & Overdue</div>
            </div>

            <!-- Deceased Records -->
            <div class="stat-card fade-up d4" style="cursor: default;">
                <div class="stat-top">
                    <div class="stat-icon blue" style="background: var(--accent-bg); color: var(--accent);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <span class="stat-pill neu">RECORDS</span>
                </div>
                <div class="stat-value blue" style="color: var(--accent);">{{ $totalDeceased }}</div>
                <div class="stat-label">Total Deceased</div>
                <div class="stat-sub">{{ $deceasedThisYear ?? 0 }} this year</div>
            </div>
        </div>

        <div class="analytics-grid">
            <!-- RENEWALS PANEL -->
            <div class="panel fade-up d5">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Renewals Tracker</div>
                        <div class="panel-sub">Permits successfully renewed this period</div>
                    </div>
                </div>
                <div class="renew-grid">
                    <div class="renew-item">
                        <div class="renew-val">{{ $renewalsThisWeek ?? 0 }}</div>
                        <div class="renew-lbl">This Week</div>
                        <div class="renew-sub">{{ now()->startOfWeek()->format('M d') }}–{{ now()->format('M d') }}</div>
                    </div>
                    <div class="renew-item">
                        <div class="renew-val">{{ $renewalsThisMonth ?? 0 }}</div>
                        <div class="renew-lbl">This Month</div>
                        <div class="renew-sub">{{ now()->format('F Y') }}</div>
                    </div>
                    <div class="renew-item">
                        <div class="renew-val">{{ $renewalsThisYear ?? 0 }}</div>
                        <div class="renew-lbl">This Year</div>
                        <div class="renew-sub">Jan–{{ now()->format('M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- TREND PANEL -->
            <div class="panel fade-up d6">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Monthly Trends</div>
                        <div class="panel-sub">Volume comparison per month</div>
                    </div>
                    <div class="panel-hd-s" style="font-size: 11px; color: var(--text-3); font-family: var(--mono);">
                        Peak: {{ $busiestMonth ?? '—' }}
                    </div>
                </div>
                <div style="padding: 1rem 1.25rem;">
                    <canvas id="reportsMonthlyChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="analytics-grid">
            <!-- BREAKDOWN PANEL -->
            <div class="panel fade-up d7">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Permit Category Breakdown</div>
                        <div class="panel-sub">Revenue and volume distribution</div>
                    </div>
                </div>
                @php
                    $fL = ['cemented'=>'Cemented','niche_1st'=>'1st Floor Niche','niche_2nd'=>'2nd Floor Niche','niche_3rd'=>'3rd Floor Niche','niche_4th'=>'4th Floor Niche','bone_niches'=>'Bone Niches'];
                    $fA = ['cemented'=>1000,'niche_1st'=>8000,'niche_2nd'=>6600,'niche_3rd'=>5700,'niche_4th'=>5300,'bone_niches'=>5000];
                    $fTotal = array_sum(array_values($feeCounts ?? []));
                    $fRev   = 0; foreach(($feeCounts??[]) as $k=>$c){ $fRev += $c*($fA[$k]??0); }
                    $fMax   = max(array_merge(array_values($feeCounts??[]),[1]));
                @endphp
                <table class="fee-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: left; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Burial Type</th>
                            <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: center; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Count</th>
                            <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: right; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fL as $k => $lbl)
                        @php $c = $feeCounts[$k]??0; $r=$c*($fA[$k]??0); @endphp
                        <tr>
                            <td style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 13px; color: var(--text-1);">
                                <div style="display: flex; align-items: center;">
                                    <div class="fee-bar" style="width:{{ $fMax>0?max(4,round(($c/$fMax)*40)):4 }}px"></div>
                                    {{ $lbl }}
                                </div>
                            </td>
                            <td class="c" style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 13px; color: var(--text-2);">{{ $c }}</td>
                            <td class="r" style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 13px; color: var(--green); text-align: right; font-family: var(--mono);">₱{{ number_format($r) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--surface-2); font-weight: 700;">
                            <td style="padding: 0.75rem 1.25rem; font-size: 12px; color: var(--text-1);">TOTAL SUMMARY</td>
                            <td style="padding: 0.75rem 1.25rem; text-align: center; font-size: 13px; color: var(--text-1);">{{ $fTotal }}</td>
                            <td style="padding: 0.75rem 1.25rem; text-align: right; font-size: 14px; color: var(--accent); font-family: var(--mono);">₱{{ number_format($fRev) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- ALERTS PANEL -->
             <div class="panel fade-up d8">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Critical Alerts</div>
                        <div class="panel-sub">Permits requiring immediate attention</div>
                    </div>
                </div>
                <div class="alert-panel-list">
                    <a href="{{ route('permits.index', ['status' => 'expired']) }}" class="alert-panel-item">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);"></div>
                        <div style="flex: 1;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-1);">Urgent Expiry (7 Days)</div>
                            <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">{{ $urgentExpiring ?? 0 }} permits need contact</div>
                        </div>
                        <span class="badge badge-red">URGENT</span>
                    </a>
                    <a href="{{ route('permits.index', ['status' => 'expiring']) }}" class="alert-panel-item">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);"></div>
                        <div style="flex: 1;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-1);">Expiring Soon (30 Days)</div>
                            <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">{{ $expiringSoon ?? 0 }} total alerts overall</div>
                        </div>
                        <span class="badge badge-yellow">WARNING</span>
                    </a>
                    <div style="padding: 1.25rem; text-align: center; opacity: 0.5;">
                         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="1.5" style="margin-bottom: 8px;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                         <div style="font-size: 11px; color: var(--text-3); font-family: var(--mono);">SYSTEM MONITORING ACTIVE</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITY TABLE -->
        <div class="panel fade-up d9" style="margin-bottom: 2rem;">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Latest Permit Activity</div>
                    <div class="panel-sub">Recently issued or updated permits across all categories</div>
                </div>
                <a href="{{ route('permits.index') }}" class="btn-xs" style="font-weight: 600; color: var(--accent); border-color: var(--accent-bg); background: var(--accent-bg);">
                    All Permits →
                </a>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: left; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Permit No</th>
                        <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: left; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Deceased Name</th>
                        <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: left; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Date Issued</th>
                        <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: left; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Status</th>
                        <th style="padding: 0.75rem 1.25rem; background: var(--surface-2); text-align: right; font-size: 10px; text-transform: uppercase; color: var(--text-3); font-family: var(--mono);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermits ?? [] as $p)
                    <tr style="transition: background 0.1s;">
                        <td style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 12px; font-weight: 700; color: var(--text-1); font-family: var(--mono);">{{ $p->permit_number }}</td>
                        <td style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 13px; font-weight: 500; color: var(--text-1);">{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</td>
                        <td style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-2); font-size: 12px; color: var(--text-2);">{{ $p->created_at->format('M d, Y') }}</td>
                        <td style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-2);">
                            @if($p->status === 'active') <span class="badge badge-green"><span class="badge-dot"></span>Active</span>
                            @elseif($p->status === 'expired') <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                            @else <span class="badge badge-yellow"><span class="badge-dot"></span>{{ ucfirst($p->status) }}</span> @endif
                        </td>
                        <td style="padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-2); text-align: right;">
                            <a href="{{ route('permits.show', $p) }}" class="btn-xs">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-3); font-size: 13px;">No recent records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div><!-- /content -->

</div><!-- /main -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Monthly chart
const monthlyData = @json($monthlyData ?? []);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const maxVal = Math.max(...monthlyData, 1);

new Chart(document.getElementById('reportsMonthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            backgroundColor: monthlyData.map(v => v === maxVal && v > 0 ? '#3b82f6' : '#e2e8f0'),
            hoverBackgroundColor: '#2563eb',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { family: 'DM Mono', size: 11 },
                bodyFont:  { family: 'DM Mono', size: 12 },
                padding: 10,
                displayColors: false,
                callbacks: {
                    title: items => months[items[0].dataIndex],
                    label: item => ` ${item.raw} permits`,
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { display: false },
                grid: { display: false },
                border: { display: false }
            },
            x: {
                ticks: { font: { family: 'DM Mono', size: 9 }, color: '#94a3b8' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>
</body>
</html>