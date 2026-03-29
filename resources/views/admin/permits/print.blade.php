<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @media print {
    body { padding: 10px 20px; }
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 11px;
    color: #000;
    background: #fff;
    padding: 20px 36px;
}

/* HEADER */
.hdr { text-align: center; font-size: 10.5px; line-height: 1.55; margin-bottom: 4px; }
.hdr b { font-size: 13px; letter-spacing: .04em; display: block; margin-top: 1px; }

/* OVAL + RENEWAL row */
.top-row { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.oval-cell { width: 40%; text-align: center; vertical-align: middle; }
.oval {
    display: inline-block;
    border: 1.5px solid #000;
    border-radius: 60px;
    padding: 4px 0;
    width: 220px;
}
.oval table { width: 100%; border-collapse: collapse; }
.oval td {
    font-size: 8px; font-weight: bold;
    text-align: center; padding: 0 12px;
    line-height: 1.5;
}
.oval td:first-child { border-right: 1px solid #000; }
.oval .yr { font-size: 13px; font-weight: bold; display: block; color: #000; }
.oval .lbl { font-size: 8px; font-weight: bold; color: #000; display: block; }

.ren-cell { width: 22%; text-align: center; vertical-align: middle; }
.ren-box {
    display: inline-block;
    border: 1.5px solid #000;
    padding: 3px 12px;
    text-align: left;
}
.ren-box .rt { font-size: 9.5px; font-weight: bold; text-align: center; margin-bottom: 2px; }
.ren-row { font-size: 10px; line-height: 1.9; }
.cb {
    display: inline-block;
    width: 12px; height: 12px;
    border: 1px solid #000;
    text-align: center; line-height: 11px;
    font-size: 10px; font-weight: bold;
    vertical-align: middle;
    margin-right: 3px;
}

.date-cell { width: 38%; text-align: right; vertical-align: top; font-size: 10px; line-height: 1.9; }
.date-cell .reg { font-size: 26px; font-weight: bold; line-height: 1; }

/* APPLICANT */
.app { margin: 10px 0 6px; }
.app div { font-size: 11px; font-weight: bold; text-decoration: underline; text-transform: uppercase; line-height: 1.75; }
.app .contact { text-transform: none; }

/* BODY TEXT */
.para { font-size: 10.5px; line-height: 1.85; margin: 6px 0; text-align: justify; }
.ul { text-decoration: underline; font-weight: bold; }

/* SECTION A & B — single column, stacked like the real form */
.sec-a { margin: 10px 0 2px; }
.sec-a .head { font-size: 10.5px; font-weight: bold; }
.sec-a .brow { font-size: 10.5px; line-height: 1.95; padding-left: 10px; }
.space-label { font-size: 10px; font-weight: bold; text-align: right; margin-top: -52px; }

.sec-b { margin: 8px 0; }
.sec-b .head { font-size: 10.5px; font-weight: bold; margin-bottom: 3px; }
.fee-tbl { width: 100%; border-collapse: collapse; font-size: 10.5px; }
.fee-tbl td { padding: 1.5px 3px; }
.fn { width: 6%; }
.fl { width: 62%; }
.fp { width: 5%; text-align: center; }
.fa { width: 27%; text-align: right; }
.ftot td { font-weight: bold; border-top: 1.5px solid #000; padding-top: 3px; }
.ftot .fa { color: #cc0000; font-size: 12px; }

hr { border: none; border-top: 1px solid #000; margin: 8px 0; }

.foot { font-size: 10.5px; line-height: 1.9; margin: 8px 0; }
.expiry { font-weight: bold; color: #cc0000; text-decoration: underline; }

.sig { text-align: right; margin: 20px 0 10px; font-size: 11px; line-height: 1.8; }
.sig-name { font-size: 13px; font-weight: bold; }

.bot { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 10px; }
.orln { display: inline-block; min-width: 100px; border-bottom: 1px solid #000; }
</style>
</head>
<body>

@php
    $d    = $permit->deceased;
    $type = $permit->permit_type;
    $fee  = [
        'cemented'   => ['tomb'=>910,  'permit'=>20,'maint'=>50, 'app'=>20,'total'=>1000],
        'niche_1st'  => ['tomb'=>7960, 'permit'=>20,'maint'=>0,  'app'=>20,'total'=>8000],
        'niche_2nd'  => ['tomb'=>6560, 'permit'=>20,'maint'=>0,  'app'=>20,'total'=>6600],
        'niche_3rd'  => ['tomb'=>5660, 'permit'=>20,'maint'=>0,  'app'=>20,'total'=>5700],
        'niche_4th'  => ['tomb'=>5260, 'permit'=>20,'maint'=>0,  'app'=>20,'total'=>5300],
        'bone_niches'=> ['tomb'=>4960, 'permit'=>20,'maint'=>0,  'app'=>20,'total'=>5000],
    ][$type] ?? ['tomb'=>910,'permit'=>20,'maint'=>50,'app'=>20,'total'=>1000];
    $isC = $type === 'cemented';
    $isN = in_array($type, ['niche_1st','niche_2nd','niche_3rd','niche_4th']);
    $isB = $type === 'bone_niches';
    $ey  = $permit->expiry_date ? $permit->expiry_date->format('Y') : now()->addYears(5)->format('Y');
    $ef  = $permit->expiry_date ? $permit->expiry_date->format('F d, Y') : now()->addYears(5)->format('F d, Y');
@endphp

{{-- HEADER --}}
<div class="hdr">
    Republic of the Philippines<br>
    Province of Davao del Norte<br>
    MUNICIPALITY OF CARMEN<br>
    <b>BURIAL PERMIT</b>
</div>

{{-- OVAL + RENEWAL + DATE ROW --}}
<table class="top-row">
    <tr>
        <td class="oval-cell">
            <div class="oval">
                <table>
                    <tr>
                        <td>
                            <span class="lbl">DATE APPLIED</span>
                            <span class="yr">{{ $permit->created_at->format('Y') }}</span>
                        </td>
                        <td>
                            <span class="lbl">DATE EXPIRED</span>
                            <span class="yr">{{ $ey }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td class="ren-cell">
            <div class="ren-box">
                <div class="rt">RENEWAL &nbsp; NEW</div>
                <div class="ren-row"><span class="cb">{{ $permit->is_renewal ? 'X' : '' }}</span> RENEWAL</div>
                <div class="ren-row"><span class="cb">{{ !$permit->is_renewal ? 'X' : '' }}</span> NEW</div>
            </div>
        </td>
        <td class="date-cell">
            {{ $permit->created_at->format('F d, Y') }}<br>
            <span style="font-size:9px">DATE</span><br><br>
            <span style="font-size:9px">Registration No. {{ $permit->created_at->format('Y') }} -</span>
            <span class="reg"> {{ $permit->id }}</span>
        </td>
    </tr>
</table>

{{-- APPLICANT --}}
<div class="app">
    <div>{{ $permit->applicant_name ?? '' }}</div>
    <div>{{ $permit->applicant_relationship ?? 'APPLICANT' }}</div>
    <div>{{ $permit->applicant_address ?? '' }}</div>
    <div class="contact">CONTACT #: {{ $permit->applicant_contact ?? '' }}</div>
</div>

<div class="para">
    Dear Mr. / Ms. / Mrs.   <span class="ul">{{ strtoupper($permit->applicant_name ?? '') }}</span>
</div>

<div class="para">
      &nbsp;
    Pursuant to your application for permit to bury at Carmen Municipal Cemetery Located at
    Tuganay, Carmen, Davao del Norte of your &nbsp; deceased
    <span class="ul">{{ strtoupper(optional($d)->first_name . ' ' . optional($d)->last_name) }}</span>
    &nbsp; who<br>
    Died at &nbsp;
    <span class="ul">{{ strtoupper(optional($d)->address ?? 'CARMEN, DAVAO DEL NORTE') }}</span>
    &nbsp; on &nbsp;
    <span class="ul">{{ optional(optional($d)->date_of_death)->format('F d, Y') ?? '' }}</span>
    &nbsp; PERMISSION<br>
    <b>TO BURY IS HEREBY GRANTED</b> in compliance with existing local ordinance of this Municipality, to wit.
</div>

{{-- SECTION A --}}
<div class="sec-a">
    <div style="width:100%;display:block">
        <div style="float:left;width:48%">
            <div class="head">A. &nbsp; KIND OF BURIAL CLASSIFICATION</div>
            <div class="brow">( <b>{{ $isC ? ' X ' : ' ' }}</b> ) TOMB / CEMENTED</div>
            <div class="brow">(   ) GRAVE - INFANT</div>
            <div class="brow">( <b>{{ $isN ? ' X ' : '   ' }}</b> ) NICHES</div>
            <div class="brow">( <b>{{ $isB ? ' X ' : '   ' }}</b> ) BONE NICHES</div>
        </div>
        <div style="float:right;width:50%;text-align:right;font-size:10px;font-weight:bold;padding-top:2px">
            SPACE RENTAL PER LOT ( 5 YEARS )
        </div>
        <div style="clear:both"></div>
    </div>
</div>

{{-- SECTION B --}}
<div class="sec-b">
    <div class="head">B. &nbsp; FEES:</div>
    <table class="fee-tbl">
        <tr><td class="fn">1.</td><td class="fl">CEMENTED TOMB / CEMENTED DIGGING</td><td class="fp">P</td><td class="fa">{{ number_format($fee['tomb'],2) }}</td></tr>
        <tr><td class="fn">2.</td><td class="fl">BURIAL PERMIT FEE</td><td class="fp">P</td><td class="fa">{{ number_format($fee['permit'],2) }}</td></tr>
        <tr><td class="fn">3.</td><td class="fl">MAINTENANCE FEE</td><td class="fp">P</td><td class="fa">{{ $fee['maint']>0 ? number_format($fee['maint'],2) : '-' }}</td></tr>
        <tr><td class="fn">4.</td><td class="fl">APPLICATION FEE</td><td class="fp">P</td><td class="fa">{{ number_format($fee['app'],2) }}</td></tr>
        <tr><td class="fn">C.</td><td class="fl">PERIMETER NICHE</td><td class="fp">P</td><td class="fa">-</td></tr>
        <tr><td class="fn">D.</td><td class="fl">PERPETUAL LEASE ON NICHES FOR BONES</td><td class="fp"></td><td class="fa"></td></tr>
        <tr class="ftot">
            <td class="fn" colspan="2">TOTAL</td>
            <td class="fp">P</td>
            <td class="fa">{{ number_format($fee['total'],2) }}</td>
        </tr>
    </table>
</div>

<div class="foot">
    This permit is renewable every 5 years and can be revoked should public interest so demand .<br>
    expiration date of this permit is on &nbsp;&nbsp;
    <span class="expiry">{{ $ef }}</span>
</div>

<div class="sig">
    Very truly yours,<br><br><br>
    <span class="sig-name">LEONIDAS R. BAHAGUE</span><br>
    Municipal Mayor
</div>

<hr>

<table class="bot">
    <tr>
        <td style="width:35%;vertical-align:top">
            <div>O.R No.  &nbsp;&nbsp; <span class="orln">{{ $permit->or_number ?? '' }}</span></div>
            <div style="margin:4px 0">Paid on    <span class="orln">{{ $permit->created_at->format('F d, Y') }}</span></div>
            <div>Amount Paid &nbsp; <span class="orln">{{ number_format($fee['total'],2) }}</span></div>
            <br>
            <div style="font-weight:bold;margin-bottom:3px">Copy Distribution</div>
            <div>1 - Permit</div>
            <div>1 - MCR Office</div>
            <div>1 - Cemetery</div>
        </td>
        <td style="width:65%;vertical-align:top;padding-left:20px">
            <div style="font-weight:bold;margin-bottom:4px">Dimension of Burial Space:</div>
            <div>Infant  &nbsp;&nbsp; - 1.25m x 1.50m</div>
            <div>Children &nbsp; - 1.25m x 2.0m</div>
            <div>Adult    - 1.50m x 2.50m</div>
        </td>
    </tr>
</table>
<script>
window.onload = function() {
    window.print();
};

window.onafterprint = function() {
    window.close();
};
<script>
window.onload = function() {
    window.print();
};
</script>
</body>
</html>