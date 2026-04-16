<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('admin.partials.design-system')
    <link rel="stylesheet" href="{{ asset('css/sa-sidebar.css') }}">
    <style>
        /* ── SUPERADMIN AUDIT-STYLE REPORT THEME ── */
        :root {
            --report-bg: #f8fafc;
            --section-title: #0f172a;
            --data-label: #64748b;
            --accent-soft: rgba(59, 130, 246, 0.08);
        }
        .dark {
            --report-bg: #0b0d11;
            --section-title: #f8fafc;
            --data-label: #94a3b8;
            --accent-soft: rgba(59, 130, 246, 0.15);
        }

        .audit-header {
            border-bottom: 3px solid var(--accent);
            padding-bottom: 2rem;
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .audit-title h1 { font-size: 28px; font-weight: 800; color: var(--section-title); letter-spacing: -0.03em; }
        .audit-title p { font-size: 13px; color: var(--data-label); font-weight: 500; font-family: var(--mono); margin-top: 4px; }

        /* Performance KPI Hub */
        .kpi-hub {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .kpi-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        .kpi-label { font-size: 12px; font-weight: 700; color: var(--data-label); text-transform: uppercase; letter-spacing: 0.1em; }
        .kpi-value { font-size: 42px; font-weight: 800; color: var(--section-title); letter-spacing: -0.04em; line-height: 1; }
        .kpi-trend { font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px; width: fit-content; }

        /* Report Sections */
        .report-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
        
        .panel-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
        }
        .panel-title { font-size: 14px; font-weight: 800; color: var(--section-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
        
        /* Financial Table */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .summary-table th {
            text-align: left;
            padding: 1.15rem 1.5rem;
            background: var(--surface-2);
            font-size: 11px;
            font-weight: 700;
            color: var(--data-label);
            text-transform: uppercase;
        }
        .summary-table td {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border-2);
            font-size: 14px;
        }
        .summary-table .total-row td {
            background: var(--surface-2);
            font-weight: 800;
            color: var(--accent);
            font-size: 17px;
        }

        .status-pill-lg {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface-2);
            padding: 1rem 1.5rem;
            border-radius: 14px;
            margin-bottom: 10px;
        }

        @media (max-width: 1024px) {
            .kpi-hub, .report-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@include('superadmin.partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">
                SuperAdmin Intelligence Audit
                <a href="{{ route('support.manual') }}#reports" class="help-link-trigger" title="How to use this report" style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </a>
            </div>
            <div class="topbar-date">{{ now()->format('M d, Y · g:i A') }}</div>
        </div>
        <div class="topbar-right" style="display:flex; gap:10px; align-items:center;">
            <span class="role-pill">⚡ Super Admin</span>
            <div style="width:34px;height:34px;border-radius:50%;overflow:hidden;border:2px solid #e2e8f0;flex-shrink:0;background:#1a2744;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <a href="{{ route('superadmin.reports.excel') }}" class="btn-export" style="background: var(--green);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                Export Excel
            </a>
            <a href="{{ route('superadmin.reports.pdf') }}" class="btn-export">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </div>


    <div class="content" style="padding: 3rem;">

        <!-- AUDIT HEADER -->
        <div class="audit-header fade-up">
            <div class="audit-title">
                <h1>{{ now()->year }} Operational Audit Report</h1>
                <p>INTERNAL REVENUE & COMPLIANCE MONITORING · CARMEN LGU</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase;">Generated By</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text);">{{ auth()->user()->name }}</div>
            </div>
        </div>

        <!-- KPI HUB -->
        <div class="kpi-hub fade-up d1">
            <div class="kpi-card">
                <span class="kpi-label">Yearly Permits ({{ $year }})</span>
                <span class="kpi-value">{{ $totalYearlyPermits }}</span>
                <span class="kpi-trend" style="background: var(--blue-bg); color: var(--blue);">SYSTEM WIDE PERFORMANCE</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Projected Revenue Pool</span>
                <span class="kpi-value">₱{{ number_format($estimatedRevenue) }}</span>
                <span class="kpi-trend" style="background: var(--green-bg); color: var(--green);">FINANCIAL SUSTAINABILITY</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Renewals Finalized</span>
                <span class="kpi-value">{{ $renewedPermits }}</span>
                <span class="kpi-trend" style="background: var(--purple-bg); color: var(--purple);">GOVERNANCE COMPLIANCE</span>
            </div>
        </div>

        <!-- ANALYTICS GRID -->
        <div class="report-grid fade-up d2">
            <div class="panel-box">
                <div class="panel-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Registration Trends (Monthly)
                </div>
                <div style="height: 250px;">
                    <canvas id="monthlyTrendChartAudit"></canvas>
                </div>
            </div>

            <div class="panel-box">
                <div class="panel-title">Service Compliance Health</div>
                <div class="status-pill-lg" style="border-left: 4px solid var(--green);">
                    <span style="font-weight: 600; color: var(--data-label);">Active & Compliant</span>
                    <span style="font-size: 20px; font-weight: 800; font-family:var(--mono);">{{ $activePermits }}</span>
                </div>
                <div class="status-pill-lg" style="border-left: 4px solid var(--amber);">
                    <span style="font-weight: 600; color: var(--data-label);">Upcoming Expirations</span>
                    <span style="font-size: 20px; font-weight: 800; font-family:var(--mono);">{{ $expiringSoon }}</span>
                </div>
                <div class="status-pill-lg" style="border-left: 4px solid var(--red);">
                    <span style="font-weight: 600; color: var(--data-label);">Expired / Overdue</span>
                    <span style="font-size: 20px; font-weight: 800; font-family:var(--mono);">{{ $expiredPermits }}</span>
                </div>
                <div class="status-pill-lg" style="border-left: 4px solid var(--blue); background: var(--accent-soft);">
                    <span style="font-weight: 600; color: var(--blue);">System Records</span>
                    <span style="font-size: 20px; font-weight: 800; font-family:var(--mono); color: var(--blue);">{{ $totalDeceased }}</span>
                </div>
            </div>
        </div>

        <!-- FINANCIAL HUB -->
        <div class="section-label" style="margin-bottom: 1.5rem;">Financial Breakdown Audit</div>
        <table class="summary-table fade-up d3">
            <thead>
                <tr>
                    <th width="45%">Burial Service Category</th>
                    <th style="text-align: center;">Record Count</th>
                    <th style="text-align: right;">Service rate</th>
                    <th style="text-align: right;">Accumulated revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueBreakdown as $row)
                <tr>
                    <td style="font-weight: 700; color: var(--section-title);">{{ $row['label'] }}</td>
                    <td style="text-align: center; font-weight: 700; font-family: var(--mono);">{{ $row['count'] }}</td>
                    <td style="text-align: right; color: var(--data-label);">₱{{ number_format($row['unitPrice']) }}</td>
                    <td style="text-align: right; font-weight: 800; color: var(--green); font-family: var(--mono);">₱{{ number_format($row['total']) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL ESTIMATED GROSS</td>
                    <td style="text-align: center;">{{ array_sum(array_column($revenueBreakdown, 'count')) }}</td>
                    <td></td>
                    <td style="text-align: right; font-family: var(--mono);">₱{{ number_format($estimatedRevenue) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Monthly chart
const monthlyData = @json($monthlyData ?? []);
const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

new Chart(document.getElementById('monthlyTrendChartAudit').getContext('2d'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.04)',
            borderWidth: 4,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { family: 'DM Mono', size: 12, weight: '700' },
                bodyFont:  { family: 'DM Mono', size: 13 },
                padding: 15,
                displayColors: false,
                callbacks: {
                    label: item => ` ${item.raw} Permits Issuance`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { font: { family: 'DM Mono', size: 10 }, color: '#94a3b8', maxTicksLimit: 6 },
                grid: { color: 'rgba(0,0,0,0.03)' },
                border: { display: false }
            },
            x: {
                ticks: { font: { family: 'DM Mono', size: 10, weight: '700' }, color: '#64748b' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>
</body>
</html>
