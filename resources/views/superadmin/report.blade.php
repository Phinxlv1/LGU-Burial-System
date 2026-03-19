<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; background: #fff; }

    /* HEADER */
    .header { background: #1a2744; padding: 16px 20px; margin-bottom: 16px; }
    .header-inner { display: flex; justify-content: space-between; align-items: flex-start; }
    .header h1 { font-size: 15px; font-weight: bold; color: #fff; margin-bottom: 2px; }
    .header .sub { font-size: 9px; color: rgba(255,255,255,.65); }
    .header .meta { text-align: right; font-size: 9px; color: rgba(255,255,255,.65); }

    /* SECTION */
    .section-title { font-size: 10px; font-weight: bold; color: #1a2744; text-transform: uppercase; letter-spacing: .06em; border-bottom: 2px solid #1a2744; padding-bottom: 3px; margin: 14px 0 8px; }

    /* SUMMARY CARDS — 4 per row */
    .summary-grid { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 4px; }
    .summary-grid td { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; text-align: center; width: 25%; background: #fafafa; }
    .summary-grid .val { font-size: 22px; font-weight: bold; color: #1a2744; display: block; }
    .summary-grid .lbl { font-size: 8px; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; display: block; }
    .summary-grid .sub { font-size: 8px; font-weight: bold; margin-top: 2px; display: block; }
    .sub-green { color: #10b981; }
    .sub-amber { color: #f59e0b; }
    .sub-blue  { color: #3b82f6; }
    .sub-red   { color: #ef4444; }

    /* PERMIT ACTIVITY */
    .activity-grid { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 4px; }
    .activity-grid td { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; width: 33.3%; background: #fafafa; }
    .activity-grid .a-val { font-size: 20px; font-weight: bold; color: #1a2744; }
    .activity-grid .a-lbl { font-size: 9px; color: #374151; font-weight: bold; margin-top: 1px; }
    .activity-grid .a-sub { font-size: 8px; color: #6b7280; margin-top: 2px; }

    /* MONTHLY TABLE */
    .monthly-wrap { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 4px; }
    .monthly-table { width: 100%; border-collapse: collapse; }
    .monthly-table th { background: #1a2744; color: #fff; font-size: 8px; padding: 5px 4px; text-align: center; }
    .monthly-table td { text-align: center; font-size: 10px; font-weight: bold; color: #1a2744; padding: 6px 4px; border-top: 1px solid #f3f4f6; }

    /* FEE TYPE TABLE */
    .data-table { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .data-table th { background: #1a2744; color: #fff; padding: 5px 8px; font-size: 9px; text-align: left; }
    .data-table td { padding: 5px 8px; border-top: 1px solid #f3f4f6; font-size: 10px; }
    .data-table tr:nth-child(even) td { background: #fafafa; }

    /* RECENT PERMITS */
    .permits-table { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .permits-table th { background: #1a2744; color: #fff; padding: 5px 8px; font-size: 8px; text-align: left; }
    .permits-table td { padding: 4px 8px; border-top: 1px solid #f3f4f6; font-size: 9px; }
    .permits-table tr:nth-child(even) td { background: #fafafa; }
    .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-size: 8px; font-weight: bold; }
    .badge-y { background: #fef3c7; color: #92400e; }
    .badge-g { background: #d1fae5; color: #065f46; }
    .badge-b { background: #dbeafe; color: #1e40af; }
    .badge-r { background: #fee2e2; color: #991b1b; }

    /* FOOTER */
    .footer { margin-top: 16px; padding-top: 8px; border-top: 1px solid #e5e7eb; font-size: 8px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-inner">
        <div>
            <h1>LGU Carmen — Burial Permit System</h1>
            <div class="sub">Municipality of Carmen, Davao del Norte &nbsp;·&nbsp; Municipal Civil Registrar</div>
            <div class="sub" style="margin-top:3px">Annual Summary Report — {{ $year }}</div>
        </div>
        <div class="meta">
            Generated: {{ now()->format('F d, Y g:i A') }}<br>
            By: {{ auth()->user()->name }}<br>
            Role: Super Administrator
        </div>
    </div>
</div>

{{-- OVERVIEW STATS --}}
<div class="section-title">System Overview</div>
<table class="summary-grid">
    <tr>
        <td><span class="val">{{ $totalPermits }}</span><span class="lbl">Total Permits</span><span class="sub sub-blue">All time</span></td>
        <td><span class="val" style="color:#10b981">{{ $releasedPermits }}</span><span class="lbl">Released</span><span class="sub sub-green">{{ $totalPermits > 0 ? round(($releasedPermits/$totalPermits)*100) : 0 }}% of total</span></td>
        <td><span class="val" style="color:#f59e0b">{{ $pendingPermits }}</span><span class="lbl">Pending</span><span class="sub sub-amber">Awaiting action</span></td>
        <td><span class="val" style="color:#6366f1">{{ $totalDeceased }}</span><span class="lbl">Deceased Records</span><span class="sub" style="color:#6366f1">In database</span></td>
    </tr>
</table>

{{-- PERMIT ACTIVITY --}}
<div class="section-title">Permit Activity Summary</div>
<table class="activity-grid">
    <tr>
        <td>
            <div class="a-val" style="color:#1a2744">{{ $newPermits }}</div>
            <div class="a-lbl">New Permits Issued</div>
            <div class="a-sub">First-time burial permits in {{ $year }}</div>
        </td>
        <td>
            <div class="a-val" style="color:#10b981">{{ $renewedPermits }}</div>
            <div class="a-lbl">Permits Renewed</div>
            <div class="a-sub">Renewals processed in {{ $year }}</div>
        </td>
        <td>
            <div class="a-val" style="color:#ef4444">{{ $expiredPermits }}</div>
            <div class="a-lbl">Expired Permits</div>
            <div class="a-sub">Permits past expiry date</div>
        </td>
    </tr>
</table>

{{-- MONTHLY BREAKDOWN --}}
<div class="section-title">Monthly Breakdown — {{ $year }}</div>
<div class="monthly-wrap">
    <table class="monthly-table">
        <thead><tr>
            @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
            <th>{{ $m }}</th>
            @endforeach
        </tr></thead>
        <tbody><tr>
            @foreach($monthlyData as $count)
            <td>{{ $count }}</td>
            @endforeach
        </tr></tbody>
    </table>
</div>

{{-- FEE TYPE --}}
<div class="section-title">Permit Type Breakdown</div>
@php
$feeLabels=['cemented'=>'Cemented','niche_1st'=>'1st Floor Niche','niche_2nd'=>'2nd Floor Niche',
            'niche_3rd'=>'3rd Floor Niche','niche_4th'=>'4th Floor Niche','bone_niches'=>'Bone Niches'];
$feeAmounts=['cemented'=>'₱1,000','niche_1st'=>'₱8,000','niche_2nd'=>'₱6,600',
             'niche_3rd'=>'₱5,700','niche_4th'=>'₱5,300','bone_niches'=>'₱5,000'];
@endphp
<table class="data-table" style="margin-bottom:4px">
    <thead><tr><th>Permit Type</th><th>Fee</th><th>Count</th><th>% of Total</th><th>Revenue (Est.)</th></tr></thead>
    <tbody>
    @foreach($feeLabels as $key => $label)
    @php $cnt = $feeCounts[$key] ?? 0; $amt = (int)str_replace(['₱',','],'',$feeAmounts[$key]); @endphp
    <tr>
        <td>{{ $label }}</td>
        <td>{{ $feeAmounts[$key] }}</td>
        <td style="font-weight:bold;color:#1a2744">{{ $cnt }}</td>
        <td>{{ $totalPermits > 0 ? round(($cnt/$totalPermits)*100,1) : 0 }}%</td>
        <td style="font-weight:bold">₱{{ number_format($cnt * $amt) }}</td>
    </tr>
    @endforeach
    <tr style="background:#1a2744">
        <td colspan="2" style="color:#fff;font-weight:bold;font-size:9px">TOTAL ESTIMATED REVENUE</td>
        <td style="color:#fff;font-weight:bold">{{ $totalPermits }}</td>
        <td style="color:#fff">100%</td>
        @php
        $totalRev = collect($feeLabels)->keys()->sum(function($k) use ($feeCounts,$feeAmounts){
            return ($feeCounts[$k] ?? 0) * (int)str_replace(['₱',','],'',$feeAmounts[$k]);
        });
        @endphp
        <td style="color:#fff;font-weight:bold">₱{{ number_format($totalRev) }}</td>
    </tr>
    </tbody>
</table>

{{-- RECENT PERMITS --}}
<div class="section-title">Recent Permit Applications (Latest 15)</div>
<table class="permits-table">
    <thead><tr><th>#</th><th>Permit No.</th><th>Deceased</th><th>Type</th><th>Requestor</th><th>Date Applied</th><th>Expiry</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($recentPermits as $i => $p)
    <tr>
        <td>{{ $i+1 }}</td>
        <td style="font-weight:bold">{{ $p->permit_number }}</td>
        <td>{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</td>
        <td>{{ ucfirst(str_replace('_',' ',$p->permit_type)) }}</td>
        <td>{{ $p->applicant_name ?? '—' }}</td>
        <td>{{ $p->created_at->format('M d, Y') }}</td>
        <td>{{ $p->expiry_date ? $p->expiry_date->format('M d, Y') : '—' }}</td>
        <td><span class="badge badge-{{ ['pending'=>'y','approved'=>'g','released'=>'b','expired'=>'r'][$p->status] ?? 'y' }}">{{ ucfirst($p->status) }}</span></td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    LGU Carmen Burial Permit Processing System &nbsp;·&nbsp; Municipal Civil Registrar &nbsp;·&nbsp; Report generated {{ now()->format('F d, Y') }} &nbsp;·&nbsp; Confidential Government Document
</div>

</body>
</html>