<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page {
        margin: 0.75in;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'DejaVu Serif', serif;
        font-size: 10pt;
        color: #000;
        background: #fff;
        line-height: 1.6;
    }

    /* ── LETTERHEAD ── */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .header-table td {
        text-align: center;
        vertical-align: middle;
    }
    .republic {
        font-size: 10pt;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .province {
        font-size: 10pt;
        font-style: italic;
    }
    .lgu-name {
        font-size: 16pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 2px 0;
        color: #0c1a4a;
    }
    .office {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        border-top: 1px solid #000;
        display: inline-block;
        padding-top: 2px;
        margin-top: 5px;
    }
    .divider {
        border-bottom: 3px double #000;
        margin: 15px 0 20px;
        clear: both;
    }

    /* ── DOCUMENT TITLE ── */
    .doc-title {
        text-align: center;
        margin-bottom: 25px;
    }
    .doc-title h1 {
        font-size: 14pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .doc-title .year-badge {
        display: inline-block;
        background: #f1f5f9;
        padding: 2px 15px;
        border: 1px solid #000;
        font-size: 10pt;
        font-weight: bold;
        margin-top: 5px;
    }

    /* ── DATA SECTION ── */
    .section-title {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        background: #f8fafc;
        border-left: 5px solid #0c1a4a;
        padding: 5px 10px;
        margin: 20px 0 10px;
    }

    .overview-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .overview-table td {
        padding: 4px 0;
    }
    .overview-table .label {
        width: 250px;
        font-weight: bold;
    }
    .overview-table .colon {
        width: 20px;
        text-align: center;
    }

    /* ── TABLES ── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .data-table th, .data-table td {
        border: 1px solid #000;
        padding: 6px 8px;
    }
    .data-table th {
        background: #f1f5f9;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 9pt;
        text-align: center;
    }
    .data-table .center { text-align: center; }
    .data-table .right { text-align: right; }
    .data-table .bold { font-weight: bold; }
    
    .data-table tfoot td {
        background: #f8fafc;
        font-weight: bold;
    }

    /* ── SIGNATURE ── */
    .signature-area {
        margin-top: 40px;
        width: 100%;
    }
    .sig-box {
        width: 45%;
        display: inline-block;
        vertical-align: top;
    }
    .sig-spacer { width: 10%; display: inline-block; }
    .sig-line {
        border-bottom: 1px solid #000;
        margin-bottom: 5px;
        height: 60px;
    }
    .sig-name {
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    .sig-title {
        text-align: center;
        font-size: 9pt;
    }

    .certification-text {
        text-align: justify;
        font-size: 10pt;
        margin-top: 30px;
        line-height: 1.5;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        font-size: 8pt;
        text-align: center;
        border-top: 1px solid #ccc;
        padding-top: 5px;
        color: #666;
    }
</style>
</head>
<body>

<div class="header-table">
    <div style="text-align: center;">
        <div class="republic">Republic of the Philippines</div>
        <div class="province">Province of Davao del Norte</div>
        <div class="lgu-name">Municipality of Carmen</div>
        <div class="office">Office of the Municipal Civil Registrar</div>
    </div>
</div>

<div class="divider"></div>

<div class="doc-title">
    <h1>Annual Summary Report</h1>
    <div class="year-badge">Calendar Year {{ $year }}</div>
</div>

<p style="font-size: 9pt; text-align: right; margin-bottom: 10px;">
    Report Generated: {{ now()->format('F d, Y · h:i A') }}
</p>

<div class="section-title">Section I: General Statistics</div>
<table class="overview-table">
    <tr><td class="label">Total Permits Registered</td><td class="colon">:</td><td>{{ $totalPermits }}</td></tr>
    <tr><td class="label">Active/Current Permits</td><td class="colon">:</td><td>{{ $activePermits }}</td></tr>
    <tr><td class="label">Expiring/Due for Renewal</td><td class="colon">:</td><td>{{ $expiringPermits }}</td></tr>
    <tr><td class="label">Expired Records</td><td class="colon">:</td><td>{{ $expiredPermits }}</td></tr>
    <tr><td class="label">Total Registered Deceased</td><td class="colon">:</td><td>{{ $totalDeceased }}</td></tr>
</table>

<div class="section-title">Section II: Monthly Activity Breakdown</div>
<table class="data-table">
    <thead>
        <tr>
            <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th><th>Jun</th>
            <th>Jul</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($monthlyData as $count)
            <td class="center">{{ $count }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<div class="section-title">Section III: Revenue & Burial Type Distribution</div>
@php
$feeLabels = [
    'cemented' => 'Cemented (Tomb)', 'niche_1st' => '1st Floor Niche', 'niche_2nd' => '2nd Floor Niche',
    'niche_3rd' => '3rd Floor Niche', 'niche_4th' => '4th Floor Niche', 'bone_niches' => 'Bone Niches',
];
$feeAmounts = [
    'cemented' => 1000, 'niche_1st' => 8000, 'niche_2nd' => 6600,
    'niche_3rd' => 5700, 'niche_4th' => 5300, 'bone_niches' => 5000,
];
$grandTotal = 0;
@endphp
<table class="data-table">
    <thead>
        <tr>
            <th>Burial Type</th>
            <th>Unit Fee (PHP)</th>
            <th>Count</th>
            <th>Total Estimated (PHP)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($feeLabels as $key => $label)
        @php
            $cnt = $feeCounts[$key] ?? 0;
            $sub = $cnt * $feeAmounts[$key];
            $grandTotal += $sub;
        @endphp
        <tr>
            <td>{{ $label }}</td>
            <td class="right">{{ number_format($feeAmounts[$key], 2) }}</td>
            <td class="center">{{ $cnt }}</td>
            <td class="right">{{ number_format($sub, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="bold">GRAND TOTAL ESTIMATED REVENUE</td>
            <td class="center bold">{{ $totalPermits }}</td>
            <td class="right bold">PHP {{ number_format($grandTotal, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="certification-text">
    <strong>CERTIFICATION:</strong> This is to certify that the statistical data and figures presented in this report are 
    accurate and reflect the actual records maintained by the Office of the Municipal Civil Registrar 
    of Carmen, Davao del Norte for the year {{ $year }}. This document is system-generated for 
    official audit and administrative review.
</div>

<div class="signature-area">
    <div class="sig-box">
        <div class="sig-line"></div>
        <div class="sig-name">{{ auth()->user()->name }}</div>
        <div class="sig-title">System Administrator / Prepared By</div>
    </div>
    <div class="sig-spacer"></div>
    <div class="sig-box">
        <div class="sig-line"></div>
        <div class="sig-name">MUNICIPAL CIVIL REGISTRAR</div>
        <div class="sig-title">Noted By / Office of the Civil Registrar</div>
    </div>
</div>

<div class="footer">
    Municipality of Carmen · Office of the Municipal Civil Registrar · Burial Permit System · Confidential Government Record
</div>

</body>
</html>