<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $permit->permit_number }} — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-left { display: flex; align-items: center; gap: .6rem; }
        .topbar-back { display: inline-flex; align-items: center; gap: 4px; font-size: 13px; color: #6b7280; text-decoration: none; transition: color .15s; }
        .topbar-back:hover { color: #1a2744; }
        .topbar-sep { color: #d1d5db; }
        .topbar-title { font-size: 14px; font-weight: 600; color: #111827; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }

        /* HERO */
        .hero { background: #1a2744; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; gap: 1rem; flex-wrap: wrap; }
        .hero-left { display: flex; flex-direction: column; gap: .2rem; }
        .hero-eyebrow { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .1em; }
        .hero-no { font-size: 26px; font-weight: 800; color: #fff; letter-spacing: .02em; line-height: 1.1; }
        .hero-meta { font-size: 12px; color: rgba(255,255,255,.4); margin-top: .2rem; }
        .hero-right { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }

        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .42rem .9rem; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; transition: all .15s; background: none; }
        .btn-renew  { background: #fff1f2; color: #b91c1c; border-color: #fca5a5; }
        .btn-renew:hover  { background: #fee2e2; }
        .btn-delete { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .btn-delete:hover { background: #fecaca; }

        /* PROGRESS */
        .progress-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.5rem 2.5rem; margin-bottom: 1.25rem; }
        .progress-track { display: flex; align-items: flex-start; }
        .progress-step { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; }
        .progress-step:not(:last-child)::after { content: ''; position: absolute; top: 15px; left: 50%; width: 100%; height: 2px; z-index: 0; }
        .progress-step.line-done:not(:last-child)::after   { background: #1a2744; }
        .progress-step.line-future:not(:last-child)::after { background: #e5e7eb; }
        .step-dot { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; position: relative; z-index: 1; flex-shrink: 0; }
        .step-dot.done   { background: #1a2744; color: #fff; }
        .step-dot.active { background: #1a2744; color: #fff; box-shadow: 0 0 0 5px rgba(26,39,68,.12); }
        .step-dot.future { background: #f3f4f6; color: #9ca3af; border: 2px dashed #d1d5db; }
        .step-info { margin-top: .6rem; text-align: center; }
        .step-name { font-size: 12px; font-weight: 600; color: #111827; }
        .step-name.muted { color: #9ca3af; font-weight: 500; }
        .step-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; line-height: 1.4; }

        /* INFO CARDS */
        .info-grid { display: grid; grid-template-columns: 1.3fr 1fr 1fr; gap: 1.25rem; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; display: flex; flex-direction: column; }
        .card-head { padding: .75rem 1.1rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: .5rem; }
        .card-head-title { font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: .07em; }
        .card-body { padding: 1.1rem; display: flex; flex-direction: column; gap: .9rem; flex: 1; }
        .field { display: flex; flex-direction: column; gap: 3px; }
        .fl { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; }
        .fv { font-size: 13px; font-weight: 500; color: #111827; }
        .fv-lg { font-size: 16px; font-weight: 700; color: #111827; }
        .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
        .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .75rem; }
        .fee-box { background: #1a2744; border-radius: 8px; padding: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; gap: .4rem; flex: 1; justify-content: center; }
        .fee-box-eyebrow { font-size: 9px; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .1em; }
        .fee-box-type { font-size: 14px; font-weight: 700; color: rgba(255,255,255,.9); }
        .fee-box-divider { width: 32px; height: 1.5px; background: rgba(255,255,255,.15); border-radius: 2px; }
        .fee-box-amount { font-size: 30px; font-weight: 800; color: #fff; letter-spacing: -.02em; }
        .fee-box-na { font-size: 16px; font-weight: 600; color: rgba(255,255,255,.3); }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('permits.index') }}" class="topbar-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Permits
            </a>
            <span class="topbar-sep">/</span>
            <span class="topbar-title">{{ $permit->permit_number }}</span>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        @if(session('success'))
            <div style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;border-radius:6px;padding:.7rem 1rem;margin-bottom:1rem;font-size:13px">{{ session('success') }}</div>
        @endif

        @php
            $statusColors = ['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red'];
            $feeMap = [
                'cemented'    => ['label'=>'Cemented',        'amount'=>'₱1,000.00'],
                'niche_1st'   => ['label'=>'1st Floor Niche', 'amount'=>'₱8,000.00'],
                'niche_2nd'   => ['label'=>'2nd Floor Niche', 'amount'=>'₱6,600.00'],
                'niche_3rd'   => ['label'=>'3rd Floor Niche', 'amount'=>'₱5,700.00'],
                'niche_4th'   => ['label'=>'4th Floor Niche', 'amount'=>'₱5,300.00'],
                'bone_niches' => ['label'=>'Bone Niches',     'amount'=>'₱5,000.00'],
            ];
            $feeInfo     = $feeMap[$permit->permit_type] ?? ['label'=>ucfirst(str_replace(['_','-'],' ',$permit->permit_type)),'amount'=>null];
            $statusOrder = ['pending'=>0,'approved'=>1,'released'=>2,'expired'=>2];
            $currentStep = $statusOrder[$permit->status] ?? 0;
            $steps = [
                ['label'=>'Submitted', 'sub'=>$permit->created_at->format('M d, Y · g:i A')],
                ['label'=>'Approved',  'sub'=>$currentStep>=1 ? 'Approved by admin' : 'Awaiting approval'],
                ['label'=>'Released',  'sub'=>$permit->expiry_date ? 'Expires '.$permit->expiry_date->format('M d, Y') : 'Awaiting release'],
            ];
        @endphp

        {{-- HERO --}}
        <div class="hero">
            <div class="hero-left">
                <div class="hero-eyebrow">Burial Permit</div>
                <div class="hero-no">{{ $permit->permit_number }}</div>
                <div class="hero-meta">Issued {{ $permit->created_at->format('F d, Y') }} &nbsp;·&nbsp; {{ $permit->created_at->diffForHumans() }}</div>
            </div>
            <div class="hero-right">
                <span class="badge {{ $statusColors[$permit->status] ?? 'badge-yellow' }}">{{ ucfirst($permit->status) }}</span>

                @if($permit->status === 'expired')
                    <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:contents"
                          onsubmit="return confirm('Renew this permit?')">
                        @csrf
                        <button type="submit" class="btn btn-renew">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                            Renew Permit
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('permits.destroy', $permit) }}" style="display:contents"
                      onsubmit="return confirm('Delete this permit?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-delete">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- PROGRESS --}}
        <div class="progress-card">
            <div class="progress-track">
                @foreach($steps as $i => $step)
                    @php
                        $dotState  = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : 'future');
                        $lineState = $i < $currentStep ? 'line-done' : 'line-future';
                    @endphp
                    <div class="progress-step {{ $lineState }}">
                        <div class="step-dot {{ $dotState }}">
                            @if($dotState === 'done')
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <div class="step-info">
                            <div class="step-name {{ $dotState === 'future' ? 'muted' : '' }}">{{ $step['label'] }}</div>
                            <div class="step-sub">{{ $step['sub'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="info-grid">

            <div class="card">
                <div class="card-head">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <span class="card-head-title">Deceased</span>
                </div>
                <div class="card-body">
                    <div class="field">
                        <div class="fl">Full Name</div>
                        <div class="fv-lg">{{ optional($permit->deceased)->first_name }} {{ optional($permit->deceased)->last_name }}</div>
                    </div>
                    <div class="g3">
                        <div class="field"><div class="fl">Nationality</div><div class="fv">{{ optional($permit->deceased)->nationality ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Age</div><div class="fv">{{ optional($permit->deceased)->age ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Sex</div><div class="fv">{{ optional($permit->deceased)->sex ?: '—' }}</div></div>
                    </div>
                    <div class="g2">
                        <div class="field">
                            <div class="fl">Date of Death</div>
                            <div class="fv">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</div>
                        </div>
                        <div class="field">
                            <div class="fl">Kind of Burial</div>
                            <div class="fv">{{ optional($permit->deceased)->kind_of_burial ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    <span class="card-head-title">Permit Fee</span>
                </div>
                <div class="card-body">
                    <div class="fee-box">
                        <div class="fee-box-eyebrow">Selected Type</div>
                        <div class="fee-box-type">{{ $feeInfo['label'] }}</div>
                        <div class="fee-box-divider"></div>
                        @if($feeInfo['amount'])
                            <div class="fee-box-amount">{{ $feeInfo['amount'] }}</div>
                        @else
                            <div class="fee-box-na">No fee on record</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="card-head-title">Application Info</span>
                </div>
                <div class="card-body">
                    <div class="field">
                        <div class="fl">Requestor</div>
                        <div class="fv">{{ $permit->applicant_name ?: '—' }}</div>
                    </div>
                    <div class="g2">
                        <div class="field"><div class="fl">Contact</div><div class="fv">{{ $permit->applicant_contact ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Processed By</div><div class="fv">{{ optional($permit->processedBy)->name ?? 'Admin' }}</div></div>
                    </div>
                    @if($permit->expiry_date)
                    <div class="field">
                        <div class="fl">Expiry Date</div>
                        <div class="fv">{{ $permit->expiry_date->format('F d, Y') }}</div>
                    </div>
                    @endif
                    @if($permit->remarks)
                    <div class="field">
                        <div class="fl">Remarks</div>
                        <div class="fv">{{ $permit->remarks }}</div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>