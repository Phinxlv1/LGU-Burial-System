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
        /* ── PROFESSIONAL DOCUMENT-STYLE REPORT THEME ── */
        :root {
            --report-bg: #fdfdfd;
            --section-title: #1e293b;
            --data-val: #0f172a;
            --data-label: #64748b;
        }
        .dark {
            --report-bg: #0f1117;
            --section-title: #f1f5f9;
            --data-val: #f8fafc;
            --data-label: #94a3b8;
        }

        .report-header {
            border-bottom: 2px solid var(--accent);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .report-title h1 { font-size: 24px; font-weight: 800; color: var(--section-title); letter-spacing: -0.02em; }
        .report-title p { font-size: 12px; color: var(--data-label); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px; }
        
        /* Executive Summary Hub */
        .exec-hub {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .exec-item { display: flex; flex-direction: column; gap: 4px; }
        .exec-label { font-size: 11px; font-weight: 700; color: var(--data-label); text-transform: uppercase; letter-spacing: 0.05em; }
        .exec-value { font-size: 36px; font-weight: 800; color: var(--data-val); line-height: 1; margin: 4px 0; }
        .exec-sub { font-size: 12px; color: var(--green); font-weight: 600; font-family: var(--mono); }
        .exec-divider { width: 1px; background: var(--border); height: 100%; }

        /* Report Sections */
        .report-section { margin-bottom: 2.5rem; }
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
        }
        .section-header h3 { font-size: 15px; font-weight: 700; color: var(--section-title); white-space: nowrap; }
        .section-line { flex: 1; height: 1px; background: var(--border); }

        /* Grid Layouts */
        .report-grid-2 { display: grid; grid-template-columns: 1.6fr 1.4fr; gap: 1.5rem; }
        .report-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        
        @media (max-width: 1100px) {
            .report-grid-2, .report-grid-3, .exec-hub { grid-template-columns: 1fr; }
            .exec-divider { display: none; }
        }

        /* Trend Visualizer */
        .trend-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
        }

        /* Financial Summary Table */
        .financial-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        .financial-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            background: var(--surface-2);
            font-size: 11px;
            font-weight: 700;
            color: var(--data-label);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .financial-table td {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border-2);
            font-size: 14px;
            color: var(--data-val);
        }
        .financial-table tr:hover td { background: var(--surface-2); }
        .financial-table .total-row td {
            background: var(--surface-2);
            font-weight: 800;
            font-size: 16px;
            color: var(--accent);
            border-top: 2px solid var(--border);
        }

        /* Health & Status Pills */
        .status-strip {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .status-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-name { font-size: 13px; font-weight: 600; color: var(--data-label); }
        .status-count { font-size: 18px; font-weight: 700; color: var(--data-val); font-family: var(--mono); }
    </style>
</head>
<body>

@include('admin.partials.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">
                Statistical Analysis
                <a href="{{ route('support.manual') }}#reports" class="help-link-trigger" title="How to use reports" style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </a>
            </div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right" style="display:flex; gap:10px; align-items:center;">
            <span class="role-pill">Admin</span>
            <a href="{{ route('reports.export.excel') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 13px; background: #10b981; border-color: #059669;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                Export Excel
            </a>
            <a href="{{ route('reports.export') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="content" style="padding: 2.5rem;">

        <!-- DOCUMENT HEADER -->
        <div class="report-header fade-up">
            <div class="report-title">
                <h1>{{ now()->year }} Annual Burial Operations Report</h1>
                <p>MCR Office · Carmen Municipal Hall · Davao del Norte</p>
            </div>
            <div style="text-align: right;">
                <span class="badge badge-blue">REQUISITION AS OF {{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- EXECUTIVE SUMMARY HUB -->
        <div class="exec-hub fade-up d1">
            <div class="exec-item">
                <span class="exec-label">Total Permits Issued ({{ now()->year }})</span>
                <span class="exec-value">{{ $totalYearlyPermits }}</span>
                <span class="exec-sub">↑ {{ $permitsThisMonth }} this month</span>
            </div>
            <div class="exec-divider"></div>
            <div class="exec-item">
                <span class="exec-label">Expected Annual Revenue</span>
                <span class="exec-value">₱{{ number_format($estimatedRevenue) }}</span>
                <span class="exec-sub" style="color: var(--blue);">Active & Overdue Bookings</span>
            </div>
            <div class="exec-divider"></div>
            <div class="exec-item">
                <span class="exec-label">Renewals Tracked</span>
                <span class="exec-value">{{ $renewedPermits }}</span>
                <span class="exec-sub" style="color: var(--purple);">Successfully processing</span>
            </div>
        </div>

        <!-- ANALYTICAL SECTION -->
        <div class="report-section fade-up d2">
            <div class="section-header">
                <h3>Permit Performance & Seasonality</h3>
                <div class="section-line"></div>
            </div>
            <div class="report-grid-2">
                <div class="trend-box">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 1.5rem;">
                        <span style="font-size:12px; font-weight:700; color:var(--data-label); font-family:var(--mono);">MONTHLY TREND</span>
                        <span style="font-size:11px; color:var(--muted);">Peak Volume: {{ $busiestMonth }}</span>
                    </div>
                    <canvas id="reportsMonthlyChart" style="max-height: 220px;"></canvas>
                </div>
                
                <div class="status-strip">
                    <div class="status-item" style="border-left: 4px solid var(--green);">
                        <span class="status-name">Valid & Active</span>
                        <span class="status-count">{{ $activePermits }}</span>
                    </div>
                    <div class="status-item" style="border-left: 4px solid var(--amber);">
                        <span class="status-name">Expiring (30 Days)</span>
                        <span class="status-count">{{ $expiringSoon }}</span>
                    </div>
                    <div class="status-item" style="border-left: 4px solid var(--red);">
                        <span class="status-name">Expired / Overdue</span>
                        <span class="status-count">{{ $expiredPermits }}</span>
                    </div>
                    <div class="status-item" style="border-left: 4px solid var(--accent); background: var(--surface-2);">
                        <span class="status-name">Total Deceased Records</span>
                        <span class="status-count">{{ $totalDeceased }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FINANCIAL SECTION -->
        <div class="report-section fade-up d3">
            <div class="section-header">
                <h3>Financial Summary Breakdown</h3>
                <div class="section-line"></div>
            </div>
            
            <table class="financial-table">
                <thead>
                    <tr>
                        <th width="40%">Burial Category</th>
                        <th style="text-align: center;">Permit Count</th>
                        <th style="text-align: right;">Unit Price (Total)</th>
                        <th style="text-align: right;">Projected Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueBreakdown as $row)
                    <tr>
                        <td style="font-weight: 600;">{{ $row['label'] }}</td>
                        <td style="text-align: center; font-family: var(--mono);">{{ $row['count'] }}</td>
                        <td style="text-align: right; color: var(--muted); font-size: 13px;">₱{{ number_format($row['unitPrice']) }}</td>
                        <td style="text-align: right; font-weight: 700; color: var(--green); font-family: var(--mono);">₱{{ number_format($row['total']) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>TOTAL GROSS ESTIMATE</td>
                        <td style="text-align: center;">{{ array_sum(array_column($revenueBreakdown, 'count')) }}</td>
                        <td></td>
                        <td style="text-align: right; font-family: var(--mono);">₱{{ number_format($estimatedRevenue) }}</td>
                    </tr>
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
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.05)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 4,
            pointHoverRadius: 6
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
                padding: 12,
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
                ticks: { font: { family: 'DM Mono', size: 9 }, color: '#94a3b8', maxTicksLimit: 5 },
                grid: { color: 'rgba(0,0,0,0.05)' },
                border: { display: false }
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