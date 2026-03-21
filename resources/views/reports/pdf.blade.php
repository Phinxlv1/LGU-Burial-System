<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #000; background: #fff; padding: 28px 36px; line-height: 1.5; }
.doc-title { font-size: 14px; font-weight: bold; }
.doc-sub   { font-size: 10px; color: #444; }
.doc-meta  { font-size: 10px; color: #666; margin-top: 6px; }
hr.thick { border: none; border-top: 1.5px solid #000; margin: 8px 0 6px; }
.sec { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .08em; border-bottom: 1.5px solid #000; padding-bottom: 2px; margin: 11px 0 5px; }
.row { display: flex; justify-content: space-between; padding: 1.5px 0; font-size: 11px; }
.row .k { color: #333; }
.row .v { font-weight: bold; }
.row.ind { padding-left: 14px; }
.row.tot { border-top: 1px solid #000; margin-top: 2px; padding-top: 3px; font-weight: bold; }
table { width: 100%; border-collapse: collapse; font-size: 10.5px; margin-top: 3px; }
th { text-align: left; border-bottom: 1.5px solid #000; padding: 2px 4px; font-size: 9px; text-transform: uppercase; letter-spacing: .05em; }
th.r { text-align: right; }
td { padding: 2.5px 4px; border-bottom: 1px solid #e0e0e0; }
td.r { text-align: right; }
tr.tot td { border-top: 1.5px solid #000; border-bottom: none; font-weight: bold; }
.mtbl th, .mtbl td { text-align: center; border: 1px solid #ccc; padding: 2px; font-size: 9px; }
.mtbl th { background: #f0f0f0; }
.prow { display: flex; gap: 6px; padding: 2px 0; border-bottom: 1px solid #eee; font-size: 10.5px; }
.prow:last-child { border: none; }
.footer { margin-top: 16px; font-size: 9px; color: #888; border-top: 1px solid #ccc; padding-top: 5px; }
</style>
</head>
<body>

<div class="doc-title">LGU Carmen — Burial Permit System</div>
<div class="doc-sub">Municipality of Carmen, Davao del Norte · Municipal Civil Registrar</div>
<div class="doc-sub">Annual Summary Report — {{ $year }}</div>
<div class="doc-meta">Generated: {{ now()->format('F d, Y g:i A') }} | By: {{ auth()->user()->name }} ({{ ucfirst(str_replace('_',' ', auth()->user()->role)) }})</div>

<hr class="thick">

<div class="sec">Quick Snapshot</div>
<div class="row"><span class="k">Total Permits (All Time)</span><span class="v">{{ $totalPermits }}</span></div>
<div class="row"><span class="k">New Permits This Year ({{ $year }})</span><span class="v">{{ $newThisYear }}</span></div>
<div class="row ind"><span class="k">This Month ({{ now()->format('F') }})</span><span class="v">{{ $newThisMonth }}</span></div>
<div class="row ind"><span class="k">This Week</span><span class="v">{{ $newThisWeek }}</span></div>
<div class="row"><span class="k">Total Deceased Records on File</span><span class="v">{{ $totalDeceased }}</span></div>
<div class="row ind"><span class="k">Added This Year</span><span class="v">{{ $deceasedThisYear }}</span></div>
<div class="row ind"><span class="k">Added This Month</span><span class="v">{{ $deceasedThisMonth }}</span></div>
<div class="row"><span class="k">Estimated Total Revenue (All Time)</span><span class="v">P{{ number_format($estimatedRevenue) }}</span></div>

<div class="sec">Permit Status</div>
<div class="row"><span class="k">Pending (Awaiting Approval)</span><span class="v">{{ $pendingPermits }}</span></div>
<div class="row"><span class="k">Approved (Ready to Release)</span><span class="v">{{ $approvedPermits }}</span></div>
<div class="row"><span class="k">Released (Currently Valid)</span><span class="v">{{ $releasedPermits }}</span></div>
<div class="row"><span class="k">Expired (Past Expiry Date)</span><span class="v">{{ $expiredPermits }}</span></div>
<div class="row tot"><span class="k">Total Permits</span><span class="v">{{ $totalPermits }}</span></div>

<div class="sec">Renewals</div>
<div class="row"><span class="k">Renewed This Week</span><span class="v">{{ $renewedThisWeek }}</span></div>
<div class="row"><span class="k">Renewed This Month ({{ now()->format('F Y') }})</span><span class="v">{{ $renewedThisMonth }}</span></div>
<div class="row"><span class="k">Renewed This Year (Jan - {{ now()->format('M Y') }})</span><span class="v">{{ $renewedThisYear }}</span></div>

<div class="sec">Expiry Alerts</div>
<div class="row"><span class="k">Expiring Within 7 Days (Urgent)</span><span class="v">{{ $expiring7Days }}</span></div>
<div class="row"><span class="k">Expiring Within 30 Days</span><span class="v">{{ $expiringSoon }}</span></div>

@if(!empty($sexBreakdown))
<div class="sec">Deceased - Sex Breakdown</div>
<div class="row"><span class="k">Male</span><span class="v">{{ $sexBreakdown['Male'] ?? 0 }}</span></div>
<div class="row"><span class="k">Female</span><span class="v">{{ $sexBreakdown['Female'] ?? 0 }}</span></div>
<div class="row"><span class="k">Not Specified</span><span class="v">{{ $totalDeceased - array_sum($sexBreakdown) }}</span></div>
@endif

<div class="sec">Monthly Breakdown - {{ $year }}</div>
@php $mn=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
<table class="mtbl">
    <thead><tr>@foreach($mn as $m)<th>{{ $m }}</th>@endforeach</tr></thead>
    <tbody><tr>@foreach($monthlyData as $c)<td>{{ $c }}</td>@endforeach</tr></tbody>
</table>
<div style="font-size:10px;color:#555;margin-top:3px">Busiest month: {{ $topMonthName }} ({{ max($monthlyData) }} permits)</div>

<div class="sec">Permit Type Breakdown</div>
<table>
    <thead>
        <tr><th>Type</th><th class="r">Fee (P)</th><th class="r">Count</th><th class="r">% of Total</th><th class="r">Revenue Est. (P)</th></tr>
    </thead>
    <tbody>
        @php $grand=array_sum($feeCounts?:[0]); @endphp
        @foreach($feeLabels as $key=>$label)
        @php $cnt=$feeCounts[$key]??0; $amt=$feeAmounts[$key]??0; $pct=$grand>0?round(($cnt/$grand)*100,1):0; @endphp
        <tr>
            <td>{{ $label }}</td>
            <td class="r">{{ number_format($amt) }}</td>
            <td class="r">{{ $cnt }}</td>
            <td class="r">{{ $pct }}%</td>
            <td class="r">{{ number_format($cnt*$amt) }}</td>
        </tr>
        @endforeach
        @php $kc=array_sum(array_map(fn($k)=>$feeCounts[$k]??0,array_keys($feeLabels))); $oth=$totalPermits-$kc; @endphp
        @if($oth>0)
        <tr><td>Other / Unknown</td><td class="r">-</td><td class="r">{{ $oth }}</td><td class="r">-</td><td class="r">-</td></tr>
        @endif
        <tr class="tot">
            <td colspan="2">TOTAL</td>
            <td class="r">{{ $totalPermits }}</td>
            <td class="r">100%</td>
            <td class="r">{{ number_format($estimatedRevenue) }}</td>
        </tr>
    </tbody>
</table>

<div class="sec">Recent Permits (Latest 15)</div>
<div style="display:flex;gap:6px;padding:2px 0;border-bottom:1.5px solid #000;font-size:9px;font-weight:bold;text-transform:uppercase;">
    <span style="width:115px">Permit No.</span>
    <span style="flex:1">Deceased</span>
    <span style="width:105px">Type</span>
    <span style="width:60px">Status</span>
    <span style="width:75px;text-align:right">Issued</span>
</div>
@foreach($recentPermits as $p)
<div class="prow">
    <span style="width:115px;font-weight:bold">{{ $p->permit_number }}</span>
    <span style="flex:1">{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</span>
    <span style="width:105px;color:#444">{{ ucfirst(str_replace('_',' ',$p->permit_type)) }}</span>
    <span style="width:60px;font-weight:bold">{{ ucfirst($p->status) }}</span>
    <span style="width:75px;text-align:right;color:#666">{{ $p->created_at->format('M d, Y') }}</span>
</div>
@endforeach

<div class="footer">
    LGU Carmen Burial Permit Processing System - Municipal Civil Registrar -
    Report generated {{ now()->format('F d, Y') }} - Confidential Government Document
</div>
</body>
</html>