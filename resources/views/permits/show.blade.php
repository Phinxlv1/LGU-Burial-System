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

        /* ── SIDEBAR ── */
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal {
    width: 34px; height: 34px;
    border-radius: 50%; object-fit: cover;
    flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2);
}
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .nav-section { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px; color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px; margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem; background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar { width: 28px; height: 28px; background: rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
        .user-name { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.4); }
        .btn-logout { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-left { display: flex; align-items: center; gap: .6rem; }
        .topbar-back { display: inline-flex; align-items: center; gap: 4px; font-size: 13px; color: #6b7280; text-decoration: none; transition: color .15s; }
        .topbar-back:hover { color: #1a2744; }
        .topbar-sep { color: #d1d5db; }
        .topbar-title { font-size: 14px; font-weight: 600; color: #111827; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }

        /* ── HERO ── */
        .hero { background: #1a2744; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; gap: 1rem; flex-wrap: wrap; }
        .hero-left { display: flex; flex-direction: column; gap: .2rem; }
        .hero-eyebrow { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: .1em; }
        .hero-no { font-size: 26px; font-weight: 800; color: #fff; letter-spacing: .02em; line-height: 1.1; }
        .hero-meta { font-size: 12px; color: rgba(255,255,255,.4); margin-top: .2rem; }
        .hero-right { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* Action buttons */
        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .42rem .9rem; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; transition: all .15s; background: none; }
        .btn-approve { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
        .btn-approve:hover { background: #a7f3d0; }
        .btn-release { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
        .btn-release:hover { background: #bfdbfe; }
        .btn-delete { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .btn-delete:hover { background: #fecaca; }

        /* ── PROGRESS CARD ── */
        .progress-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.5rem 2.5rem; margin-bottom: 1.25rem; }
        .progress-track { display: flex; align-items: flex-start; position: relative; }
        .progress-step { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; }

        /* Horizontal line between steps */
        .progress-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            z-index: 0;
        }
        .progress-step.line-done:not(:last-child)::after { background: #1a2744; }
        .progress-step.line-future:not(:last-child)::after { background: #e5e7eb; }

        .step-dot {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            position: relative; z-index: 1; flex-shrink: 0;
        }
        .step-dot.done   { background: #1a2744; color: #fff; }
        .step-dot.active { background: #1a2744; color: #fff; box-shadow: 0 0 0 5px rgba(26,39,68,.12); }
        .step-dot.future { background: #f3f4f6; color: #9ca3af; border: 2px dashed #d1d5db; }

        .step-info { margin-top: .6rem; text-align: center; }
        .step-name { font-size: 12px; font-weight: 600; color: #111827; }
        .step-name.muted { color: #9ca3af; font-weight: 500; }
        .step-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; line-height: 1.4; }

        /* ── INFO CARDS ── */
        .info-grid { display: grid; grid-template-columns: 1.3fr 1fr 1fr; gap: 1.25rem; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; display: flex; flex-direction: column; }
        .card-head { padding: .75rem 1.1rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: .5rem; }
        .card-head-title { font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: .07em; }
        .card-body { padding: 1.1rem; display: flex; flex-direction: column; gap: .9rem; flex: 1; }

        /* Fields */
        .field { display: flex; flex-direction: column; gap: 3px; }
        .fl { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; }
        .fv { font-size: 13px; font-weight: 500; color: #111827; }
        .fv-lg { font-size: 16px; font-weight: 700; color: #111827; }
        .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
        .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .75rem; }

        /* Fee highlight box */
        .fee-box { background: #1a2744; border-radius: 8px; padding: 1.25rem; text-align: center; display: flex; flex-direction: column; align-items: center; gap: .4rem; flex: 1; justify-content: center; }
        .fee-box-eyebrow { font-size: 9px; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .1em; }
        .fee-box-type { font-size: 14px; font-weight: 700; color: rgba(255,255,255,.9); }
        .fee-box-divider { width: 32px; height: 1.5px; background: rgba(255,255,255,.15); border-radius: 2px; }
        .fee-box-amount { font-size: 30px; font-weight: 800; color: #fff; letter-spacing: -.02em; }
        .fee-box-na { font-size: 16px; font-weight: 600; color: rgba(255,255,255,.3); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-top">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sidebar-seal">
            <h1>LGU Carmen<br>Burial System</h1>
        </div>
        <p>Municipal Civil Registrar</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Permits</div>
        <a href="{{ route('permits.index') }}" class="nav-item active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Deceased Records
        </a>
        <div class="nav-section">Cemetery</div>
        <a href="{{ route('cemetery.map') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Cemetery Map
        </a>
        <div class="nav-section">Tools</div>
        <a href="{{ route('reports.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Reports
        </a>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Excel
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

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
                'new'         => ['label'=>'New Burial',      'amount'=>null],
                'transfer'    => ['label'=>'Transfer',        'amount'=>null],
                'exhumation'  => ['label'=>'Exhumation',      'amount'=>null],
                'cremation'   => ['label'=>'Cremation',       'amount'=>null],
            ];
            $feeInfo = $feeMap[$permit->permit_type] ?? ['label'=>ucfirst(str_replace(['_','-'],' ',$permit->permit_type)),'amount'=>null];
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
                @if($permit->status === 'pending')
                    <form method="POST" action="{{ route('permits.approve', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn btn-approve">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Approve
                        </button>
                    </form>
                @elseif($permit->status === 'approved')
                    <form method="POST" action="{{ route('permits.release', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn btn-release">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Release
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('permits.destroy', $permit) }}" style="display:contents" onsubmit="return confirm('Delete this permit?')">
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

            {{-- Deceased --}}
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

            {{-- Fee --}}
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

            {{-- Application Info --}}
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


