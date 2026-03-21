<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }

        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 50px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 14px; font-weight: 700; color: #111827; }
        .topbar-date  { font-size: 11px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        .content { padding: .65rem 1.25rem; display: flex; flex-direction: column; gap: .55rem; }

        /* PAGE HEADER */
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
        .page-header h1 { font-size: 14px; font-weight: 800; color: #111827; display: inline; }
        .page-header p  { font-size: 11px; color: #9ca3af; display: inline; margin-left: .5rem; }
        .btn-pdf { display: inline-flex; align-items: center; gap: 5px; padding: .3rem .7rem; background: #1a2744; color: #fff; border: none; border-radius: 5px; font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: none; transition: background .15s; white-space: nowrap; }
        .btn-pdf:hover { background: #243459; }

        /* SECTION LABEL */
        .slabel { font-size: 9px; font-weight: 800; color: #9ca3af; text-transform: uppercase; letter-spacing: .1em; margin-bottom: 3px; }

        /* GRIDS */
        .g4 { display: grid; grid-template-columns: repeat(4,1fr); gap: .45rem; }
        .g2 { display: grid; grid-template-columns: repeat(2,1fr); gap: .45rem; }

        /* STAT CARD */
        .sc { background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; padding: .42rem .65rem; }
        .sc-ey  { font-size: 9px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; }
        .sc-val { font-size: 19px; font-weight: 800; line-height: 1.15; }
        .sc-lbl { font-size: 11px; font-weight: 600; color: #374151; }
        .sc-sub { font-size: 10px; color: #9ca3af; }

        .bl-g { border-left: 3px solid #10b981; } .c-g { color: #10b981; }
        .bl-a { border-left: 3px solid #f59e0b; } .c-a { color: #f59e0b; }
        .bl-b { border-left: 3px solid #3b82f6; } .c-b { color: #3b82f6; }
        .bl-r { border-left: 3px solid #ef4444; } .c-r { color: #ef4444; }
        .bl-n { border-left: 3px solid #1a2744; } .c-n { color: #1a2744; }
        .bl-i { border-left: 3px solid #6366f1; } .c-i { color: #6366f1; }
        .bl-t { border-left: 3px solid #14b8a6; } .c-t { color: #14b8a6; }

        /* RENEWAL TRIO */
        .trio { background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
        .trio-head { background: #1a2744; padding: .28rem .7rem; font-size: 10px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 5px; }
        .trio-body { display: grid; grid-template-columns: repeat(3,1fr); }
        .trio-cell { padding: .42rem .5rem; text-align: center; border-right: 1px solid #f3f4f6; }
        .trio-cell:last-child { border-right: none; }
        .trio-val { font-size: 19px; font-weight: 800; color: #1a2744; line-height: 1; }
        .trio-lbl { font-size: 10px; font-weight: 700; color: #374151; margin-top: 1px; }
        .trio-sub { font-size: 9px; color: #9ca3af; }

        /* BOTTOM: monthly + type */
        .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: .45rem; }

        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
        .card-head { padding: .35rem .7rem; border-bottom: 1px solid #f3f4f6; font-size: 11px; font-weight: 700; color: #111827; display: flex; align-items: center; justify-content: space-between; }
        .card-head span { font-size: 10px; color: #9ca3af; font-weight: 400; }

        /* MONTHLY GRID */
        .monthly-grid { display: grid; grid-template-columns: repeat(12,1fr); }
        .mc { padding: .32rem .1rem; text-align: center; border-right: 1px solid #f3f4f6; }
        .mc:last-child { border-right: none; }
        .mc.active { background: #f0f4ff; }
        .mc.top    { background: #1a2744; }
        .mc-name { font-size: 8px; font-weight: 600; color: #9ca3af; text-transform: uppercase; }
        .mc.top    .mc-name { color: rgba(255,255,255,.5); }
        .mc.active .mc-name { color: #1a2744; }
        .mc-val  { font-size: 13px; font-weight: 800; color: #d1d5db; margin-top: 1px; }
        .mc.top    .mc-val  { color: #fff; }
        .mc.active .mc-val  { color: #1a2744; }

        /* TYPE TABLE */
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 9px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .28rem .55rem; text-align: left; background: #fafafa; }
        th.r { text-align: right; }
        td { font-size: 11px; color: #374151; padding: .3rem .55rem; border-top: 1px solid #f3f4f6; }
        td.r { text-align: right; }
        td.b { font-weight: 700; color: #111827; }
        tr.tot td { background: #1a2744; color: #fff; font-weight: 700; font-size: 11px; }
    </style>
</head>
<body>
@include('partials.sidebar')
<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Reports</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">{{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'admin')) }}</span>
    </div>

    <div class="content">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1>Burial Permit Reports</h1>
                <p>Municipality of Carmen &nbsp;·&nbsp; {{ $year }} &nbsp;·&nbsp; Generated {{ now()->format('M d, Y g:i A') }} by {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('reports.export') }}" class="btn-pdf">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>

        {{-- ROW 1: STATUS --}}
        <div>
            <div class="slabel">Permit Status</div>
            <div class="g4">
                <div class="sc bl-n">
                    <div class="sc-ey">All Time</div>
                    <div class="sc-val c-n">{{ $totalPermits }}</div>
                    <div class="sc-lbl">Total Permits</div>
                    <div class="sc-sub">Since system began</div>
                </div>
                <div class="sc bl-a">
                    <div class="sc-ey">Action Required</div>
                    <div class="sc-val c-a">{{ $pendingPermits }}</div>
                    <div class="sc-lbl">Pending</div>
                    <div class="sc-sub">Awaiting approval</div>
                </div>
                <div class="sc bl-g">
                    <div class="sc-ey">Ready</div>
                    <div class="sc-val c-g">{{ $approvedPermits }}</div>
                    <div class="sc-lbl">Approved</div>
                    <div class="sc-sub">Ready to release</div>
                </div>
                <div class="sc bl-b">
                    <div class="sc-ey">Active</div>
                    <div class="sc-val c-b">{{ $releasedPermits }}</div>
                    <div class="sc-lbl">Released</div>
                    <div class="sc-sub">Currently valid</div>
                </div>
            </div>
        </div>

        {{-- ROW 2: ACTIVITY + EXPIRED + DECEASED + REVENUE --}}
        <div class="g4">
            <div class="sc bl-g">
                <div class="sc-ey">This Year ({{ $year }})</div>
                <div class="sc-val c-g">{{ $newThisYear }}</div>
                <div class="sc-lbl">New Permits</div>
                <div class="sc-sub">{{ $newThisMonth }} this month · {{ $newThisWeek }} this week</div>
            </div>
            <div class="sc bl-r">
                <div class="sc-ey">Needs Renewal</div>
                <div class="sc-val c-r">{{ $expiredPermits }}</div>
                <div class="sc-lbl">Expired</div>
                <div class="sc-sub">{{ $expiring7Days }} urgent · {{ $expiringSoon }} in 30 days</div>
            </div>
            <div class="sc bl-t">
                <div class="sc-ey">Deceased Records</div>
                <div class="sc-val c-t">{{ $totalDeceased }}</div>
                <div class="sc-lbl">Total on File</div>
                <div class="sc-sub">{{ $deceasedThisYear }} this year · {{ $deceasedThisMonth }} this month</div>
            </div>
            <div class="sc bl-i">
                <div class="sc-ey">Estimated Revenue</div>
                <div class="sc-val c-i" style="font-size:15px">₱{{ number_format($estimatedRevenue) }}</div>
                <div class="sc-lbl">All Time (Est.)</div>
                <div class="sc-sub">Based on permit fee rates</div>
            </div>
        </div>

        {{-- ROW 3: RENEWALS + EXPIRY --}}
        <div>
            <div class="slabel">Renewals &amp; Expiry Alerts</div>
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:.45rem;align-items:stretch">
                <div class="trio">
                    <div class="trio-head">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                        Renewals Processed (permits originally from a previous year)
                    </div>
                    <div class="trio-body">
                        <div class="trio-cell">
                            <div class="trio-val">{{ $renewedThisWeek }}</div>
                            <div class="trio-lbl">This Week</div>
                            <div class="trio-sub">{{ now()->startOfWeek()->format('M d') }}–{{ now()->endOfWeek()->format('M d') }}</div>
                        </div>
                        <div class="trio-cell">
                            <div class="trio-val">{{ $renewedThisMonth }}</div>
                            <div class="trio-lbl">This Month</div>
                            <div class="trio-sub">{{ now()->format('F Y') }}</div>
                        </div>
                        <div class="trio-cell">
                            <div class="trio-val">{{ $renewedThisYear }}</div>
                            <div class="trio-lbl">This Year</div>
                            <div class="trio-sub">Jan–{{ now()->format('M Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="sc bl-a">
                    <div class="sc-ey">⚠ Urgent</div>
                    <div class="sc-val c-a">{{ $expiring7Days }}</div>
                    <div class="sc-lbl">Expiring in 7 Days</div>
                    <div class="sc-sub">Contact holders now</div>
                </div>
                <div class="sc bl-r">
                    <div class="sc-ey">⏳ Warning</div>
                    <div class="sc-val c-r">{{ $expiringSoon }}</div>
                    <div class="sc-lbl">Expiring in 30 Days</div>
                    <div class="sc-sub">Incl. {{ $expiring7Days }} urgent</div>
                </div>
            </div>
        </div>

        {{-- ROW 4: MONTHLY + TYPE --}}
        <div class="bottom-row">
            <div class="card">
                <div class="card-head">
                    Monthly — {{ $year }}
                    <span>Busiest: <strong>{{ $topMonthName }}</strong> ({{ max($monthlyData) }})</span>
                </div>
                <div class="monthly-grid">
                    @php $mn=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
                    @foreach($monthlyData as $m => $count)
                    @php $isTop=($m==$topMonth&&$count>0); $has=($count>0&&!$isTop); @endphp
                    <div class="mc {{ $isTop?'top':($has?'active':'') }}">
                        <div class="mc-name">{{ $mn[$m-1] }}</div>
                        <div class="mc-val">{{ $count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-head">Permit Type Breakdown <span>All time</span></div>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th class="r">Count</th>
                            <th class="r">Revenue (Est.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grand=array_sum($feeCounts?:[0]); @endphp
                        @foreach($feeLabels as $key=>$label)
                        @php $cnt=$feeCounts[$key]??0; $amt=$feeAmounts[$key]??0; @endphp
                        <tr>
                            <td class="b">{{ $label }}</td>
                            <td class="r">{{ $cnt }}</td>
                            <td class="r b c-n">₱{{ number_format($cnt*$amt) }}</td>
                        </tr>
                        @endforeach
                        @php $kc=array_sum(array_map(fn($k)=>$feeCounts[$k]??0,array_keys($feeLabels))); $oth=$totalPermits-$kc; @endphp
                        @if($oth>0)
                        <tr>
                            <td style="color:#9ca3af">Other</td>
                            <td class="r" style="color:#9ca3af">{{ $oth }}</td>
                            <td class="r" style="color:#9ca3af">—</td>
                        </tr>
                        @endif
                        <tr class="tot">
                            <td>TOTAL</td>
                            <td class="r">{{ $totalPermits }}</td>
                            <td class="r">₱{{ number_format($estimatedRevenue) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
</html>