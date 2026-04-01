<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 11pt;
        color: #000;
        background: #fff;
        line-height: 1.5;
    }

    /* ── LETTERHEAD ── */
    .letterhead {
        text-align: center;
        border-bottom: 3px double #000;
        padding-bottom: 10px;
        margin-bottom: 14px;
    }
    .letterhead .republic {
        font-size: 9pt;
        letter-spacing: .04em;
    }
    .letterhead .province {
        font-size: 9pt;
    }
    .letterhead .lgu-name {
        font-size: 15pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 4px 0 2px;
    }
    .letterhead .office {
        font-size: 10pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .letterhead .address {
        font-size: 8.5pt;
        color: #333;
        margin-top: 2px;
    }

    /* ── DOCUMENT TITLE ── */
    .doc-title {
        text-align: center;
        margin: 10px 0 4px;
    }
    .doc-title h1 {
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .06em;
        text-decoration: underline;
    }
    .doc-title .subtitle {
        font-size: 10pt;
        margin-top: 2px;
    }

    /* ── META LINE ── */
    .meta-line {
        font-size: 9pt;
        text-align: right;
        margin-bottom: 14px;
        border-bottom: 1px solid #000;
        padding-bottom: 6px;
    }

    /* ── SECTION HEADING ── */
    .section-heading {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        margin: 18px 0 8px;
        letter-spacing: .04em;
    }

    /* ── FIELD ROWS (label: value) ── */
    .field-row {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
    }
    .field-row td.label {
        font-weight: bold;
        width: 220px;
        vertical-align: top;
        padding: 2px 6px 2px 0;
        font-size: 10.5pt;
    }
    .field-row td.colon {
        width: 12px;
        vertical-align: top;
        padding: 2px 4px 2px 0;
        font-size: 10.5pt;
    }
    .field-row td.value {
        vertical-align: top;
        padding: 2px 0;
        font-size: 10.5pt;
    }

    /* ── PLAIN TABLE ── */
    .plain-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        font-size: 10pt;
    }
    .plain-table th {
        border: 1px solid #000;
        padding: 5px 8px;
        font-weight: bold;
        background: #f0f0f0;
        text-align: left;
    }
    .plain-table td {
        border: 1px solid #000;
        padding: 4px 8px;
        vertical-align: middle;
    }
    .plain-table .center { text-align: center; }
    .plain-table .right  { text-align: right; }
    .plain-table .bold   { font-weight: bold; }
    .plain-table tfoot td {
        font-weight: bold;
        background: #e8e8e8;
    }

    /* ── MONTHLY GRID ── */
    .monthly-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5pt;
        margin-bottom: 8px;
    }
    .monthly-table th {
        border: 1px solid #000;
        padding: 4px 6px;
        text-align: center;
        font-weight: bold;
        background: #f0f0f0;
    }
    .monthly-table td {
        border: 1px solid #000;
        padding: 5px 6px;
        text-align: center;
    }

    /* ── SIGNATURE BLOCK ── */
    .sig-block {
        margin-top: 30px;
        width: 100%;
        border-collapse: collapse;
    }
    .sig-block td {
        width: 50%;
        vertical-align: bottom;
        padding: 0 10px;
        font-size: 10pt;
    }
    .sig-block .sig-line {
        border-top: 1px solid #000;
        padding-top: 4px;
        margin-top: 35px;
        font-weight: bold;
        text-align: center;
    }
    .sig-block .sig-sub {
        font-size: 9pt;
        text-align: center;
    }

    /* ── FOOTER ── */
    .doc-footer {
        margin-top: 20px;
        border-top: 1px solid #000;
        padding-top: 5px;
        font-size: 8.5pt;
        text-align: center;
        color: #333;
    }

    .note-text {
        font-size: 9pt;
        font-style: italic;
        color: #444;
        margin-top: 4px;
    }

    .page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════
     LETTERHEAD
══════════════════════════════════════════════════════ --}}
<div class="letterhead">
    <div class="republic">Republic of the Philippines</div>
    <div class="province">Province of Davao del Norte</div>
    <div class="lgu-name">Municipality of Carmen</div>
    <div class="office">Office of the Municipal Civil Registrar</div>
    <div class="address">Carmen, Davao del Norte &nbsp;|&nbsp; Burial Permit Processing System</div>
</div>

{{-- ══════════════════════════════════════════════════════
     DOCUMENT TITLE
══════════════════════════════════════════════════════ --}}
<div class="doc-title">
    <h1>Annual Summary Report</h1>
    <div class="subtitle">Burial Permit System &mdash; Calendar Year {{ $year }}</div>
</div>

{{-- ══════════════════════════════════════════════════════
     META INFO
══════════════════════════════════════════════════════ --}}
<div class="meta-line">
    Date Generated: {{ now()->format('F d, Y \a\t g:i A') }}
    &nbsp;&nbsp;&nbsp;
    Prepared By: {{ auth()->user()->name }}
    &nbsp;&nbsp;&nbsp;
    Role: Super Administrator
</div>


{{-- ══════════════════════════════════════════════════════
     SECTION I — GENERAL OVERVIEW
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Section I &mdash; General Overview</div>
<table class="field-row"><tr><td class="label">Total Permits on Record</td><td class="colon">:</td><td class="value">{{ $totalPermits }}</td></tr></table>
<table class="field-row"><tr><td class="label">Active Permits</td><td class="colon">:</td><td class="value">{{ $activePermits }} ({{ $totalPermits > 0 ? round(($activePermits/$totalPermits)*100) : 0 }}% of total)</td></tr></table>
<table class="field-row"><tr><td class="label">Expiring Soon</td><td class="colon">:</td><td class="value">{{ $expiringPermits }}</td></tr></table>
<table class="field-row"><tr><td class="label">Expired Permits</td><td class="colon">:</td><td class="value">{{ $expiredPermits }}</td></tr></table>
<table class="field-row"><tr><td class="label">Total Deceased Records</td><td class="colon">:</td><td class="value">{{ $totalDeceased }}</td></tr></table>


{{-- ══════════════════════════════════════════════════════
     SECTION II — PERMIT ACTIVITY FOR {{ $year }}
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Section II &mdash; Permit Activity for {{ $year }}</div>
<table class="field-row"><tr><td class="label">New Permits Issued</td><td class="colon">:</td><td class="value">{{ $newPermits }} (first-time burial permit applications filed in {{ $year }})</td></tr></table>
<table class="field-row"><tr><td class="label">Permits Renewed</td><td class="colon">:</td><td class="value">{{ $renewedPermits }} (renewal transactions processed in {{ $year }})</td></tr></table>
<table class="field-row"><tr><td class="label">Busiest Month</td><td class="colon">:</td><td class="value">{{ $busiestMonth }} ({{ $busiestCount }} permit(s) processed)</td></tr></table>


{{-- ══════════════════════════════════════════════════════
     SECTION III — MONTHLY BREAKDOWN
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Section III &mdash; Monthly Breakdown of Permits ({{ $year }})</div>
<table class="monthly-table">
    <thead>
        <tr>
            <th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th>
            <th>May</th><th>Jun</th><th>Jul</th><th>Aug</th>
            <th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($monthlyData as $count)
            <td>{{ $count }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
<p class="note-text">Note: The table above shows the number of burial permits issued per month for the calendar year {{ $year }}.</p>


{{-- ══════════════════════════════════════════════════════
     SECTION IV — PERMIT TYPE BREAKDOWN AND ESTIMATED REVENUE
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Section IV &mdash; Permit Type Breakdown and Estimated Revenue</div>
@php
$feeLabels  = [
    'cemented'   => 'Cemented (Tomb)',
    'niche_1st'  => '1st Floor Niche',
    'niche_2nd'  => '2nd Floor Niche',
    'niche_3rd'  => '3rd Floor Niche',
    'niche_4th'  => '4th Floor Niche',
    'bone_niches'=> 'Bone Niches',
];
$feeAmounts = [
    'cemented'   => 1000,
    'niche_1st'  => 8000,
    'niche_2nd'  => 6600,
    'niche_3rd'  => 5700,
    'niche_4th'  => 5300,
    'bone_niches'=> 5000,
];
$grandTotal = 0;
foreach ($feeLabels as $key => $label) {
    $cnt = $feeCounts[$key] ?? 0;
    $grandTotal += $cnt * ($feeAmounts[$key] ?? 0);
}
@endphp

<table class="plain-table">
    <thead>
        <tr>
            <th style="width:30%">Permit / Burial Type</th>
            <th class="center" style="width:15%">Fee (₱)</th>
            <th class="center" style="width:10%">Count</th>
            <th class="center" style="width:15%">% of Total</th>
            <th class="right"  style="width:30%">Estimated Revenue (₱)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($feeLabels as $key => $label)
        @php $cnt = $feeCounts[$key] ?? 0; @endphp
        <tr>
            <td>{{ $label }}</td>
            <td class="center">{{ number_format($feeAmounts[$key]) }}</td>
            <td class="center bold">{{ $cnt }}</td>
            <td class="center">{{ $totalPermits > 0 ? number_format(($cnt/$totalPermits)*100, 1) : '0.0' }}%</td>
            <td class="right">{{ number_format($cnt * $feeAmounts[$key]) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="bold">TOTAL</td>
            <td class="center bold">{{ $totalPermits }}</td>
            <td class="center bold">100%</td>
            <td class="right bold">₱{{ number_format($grandTotal) }}</td>
        </tr>
    </tfoot>
</table>
<p class="note-text">Note: Revenue figures are estimates based on the standard schedule of fees. Actual collected amounts may vary depending on discounts, exemptions, or adjustments.</p>


{{-- ══════════════════════════════════════════════════════
     SECTION V — RECENT PERMIT APPLICATIONS
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Section V &mdash; Recent Permit Applications (Latest 15)</div>
<table class="plain-table">
    <thead>
        <tr>
            <th class="center" style="width:4%">No.</th>
            <th style="width:18%">Permit No.</th>
            <th style="width:22%">Deceased</th>
            <th style="width:15%">Type</th>
            <th style="width:17%">Applicant</th>
            <th class="center" style="width:12%">Date Applied</th>
            <th class="center" style="width:12%">Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($recentPermits as $i => $p)
    <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td class="bold">{{ $p->permit_number }}</td>
        <td>{{ optional($p->deceased)->last_name }}, {{ optional($p->deceased)->first_name }}</td>
        <td>{{ ucwords(str_replace('_', ' ', $p->permit_type)) }}</td>
        <td>{{ $p->applicant_name ?? '—' }}</td>
        <td class="center">{{ $p->created_at->format('M d, Y') }}</td>
        <td class="center">{{ ucfirst($p->status) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>


{{-- ══════════════════════════════════════════════════════
     CERTIFICATION / SIGNATURE BLOCK
══════════════════════════════════════════════════════ --}}
<div class="section-heading">Certification</div>
<p style="font-size:10.5pt; text-align:justify; line-height:1.7">
    This is to certify that the foregoing data and figures are true and correct based on the official records of the
    Municipal Civil Registrar, Municipality of Carmen, Davao del Norte, as of
    <strong>{{ now()->format('F d, Y') }}</strong>. This report is issued for administrative and statistical purposes only.
</p>

<table class="sig-block">
    <tr>
        <td style="padding-left:0; padding-right:30px;">
            <div class="sig-line">Prepared By:</div>
            <div class="sig-sub">{{ auth()->user()->name }}</div>
            <div class="sig-sub">System Administrator</div>
        </td>
        <td style="padding-right:0; padding-left:30px;">
            <div class="sig-line">Noted By:</div>
            <div class="sig-sub">Municipal Civil Registrar</div>
            <div class="sig-sub">Municipality of Carmen, Davao del Norte</div>
        </td>
    </tr>
</table>


{{-- ══════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════ --}}
<div class="doc-footer">
    LGU Carmen Burial Permit System &nbsp;&mdash;&nbsp; Municipal Civil Registrar &nbsp;&mdash;&nbsp;
    Annual Report {{ $year }} &nbsp;&mdash;&nbsp; Generated: {{ now()->format('F d, Y') }}
    &nbsp;&mdash;&nbsp; <em>This is a system-generated document. Confidential Government Record.</em>
</div>

</body>
</html>