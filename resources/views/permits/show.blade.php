    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <script>
        (function(){try{var k='lgu_dark_{{ auth()->id() }}';if(localStorage.getItem(k)==='1')document.documentElement.classList.add('dark');}catch(e){}})();
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $permit->permit_number }} — LGU Carmen</title>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

        @include('partials.design-system')

        <style>
            .hero {
                background: var(--navy);
                border-radius: 16px;
                padding: 1.4rem 1.75rem;
                display: flex; align-items: center; justify-content: space-between;
                gap: 1rem; flex-wrap: wrap;
                position: relative; overflow: hidden;
            }
            .hero::before { content:''; position:absolute; top:-60px; right:-40px; width:240px; height:240px; background:radial-gradient(circle,rgba(59,130,246,.1) 0%,transparent 65%); pointer-events:none; }
            .hero-eyebrow { font-size:10px; font-weight:600; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:.1em; font-family:var(--mono); }
            .hero-no      { font-size:28px; font-weight:700; color:#fff; letter-spacing:-.03em; line-height:1.1; margin-top:.2rem; }
            .hero-meta    { font-size:11px; color:rgba(255,255,255,.35); margin-top:.3rem; font-family:var(--mono); }
            .hero-actions { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }

            .info-grid { display:grid; grid-template-columns:1.3fr 1fr 1fr; gap:1rem; }
            .info-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; overflow:hidden; display:flex; flex-direction:column; }
            .info-card-head { padding:.75rem 1.1rem; border-bottom:1px solid var(--border-2); display:flex; align-items:center; gap:.5rem; background:var(--surface-2); }
            .info-card-title { font-size:10px; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:.08em; font-family:var(--mono); }
            .info-card-body { padding:1.1rem; display:flex; flex-direction:column; gap:.85rem; flex:1; }
            .field { display:flex; flex-direction:column; gap:3px; }
            .fl { font-size:10px; font-weight:600; color:var(--text-3); text-transform:uppercase; letter-spacing:.07em; font-family:var(--mono); }
            .fv { font-size:13px; font-weight:500; color:var(--text-1); }
            .fv-lg { font-size:17px; font-weight:700; color:var(--text-1); letter-spacing:-.02em; }
            .g2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
            .g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.75rem; }

            .fee-box { background:var(--navy); border-radius:10px; padding:1.25rem; text-align:center; display:flex; flex-direction:column; align-items:center; gap:.4rem; flex:1; justify-content:center; }
            .fee-box-eyebrow { font-size:9px; font-weight:700; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:.1em; font-family:var(--mono); }
            .fee-box-type    { font-size:13px; font-weight:600; color:rgba(255,255,255,.85); }
            .fee-box-divider { width:28px; height:1.5px; background:rgba(255,255,255,.12); border-radius:2px; }
            .fee-box-amount  { font-size:32px; font-weight:700; color:#fff; letter-spacing:-.04em; }
            .fee-box-na      { font-size:14px; font-weight:500; color:rgba(255,255,255,.25); }

            .docs-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
            .docs-head { padding:.85rem 1.25rem; border-bottom:1px solid var(--border-2); display:flex; align-items:center; justify-content:space-between; background:var(--surface-2); }
            .docs-head-left { display:flex; align-items:center; gap:.5rem; }
            .docs-head-title { font-size:13px; font-weight:600; color:var(--text-1); }
            .docs-head-sub   { font-size:11px; color:var(--text-3); font-family:var(--mono); }
            .docs-body { display:grid; grid-template-columns:1fr 1fr; min-height:240px; }
            .docs-col-files  { border-right:1px solid var(--border-2); padding:1rem; display:flex; flex-direction:column; gap:.45rem; overflow-y:auto; max-height:380px; }
            .docs-col-upload { padding:1rem; display:flex; flex-direction:column; gap:.75rem; background:var(--surface-2); }
            .docs-col-label  { font-size:10px; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:.07em; font-family:var(--mono); margin-bottom:.2rem; }
            .doc-item { display:flex; align-items:center; gap:.65rem; padding:.55rem .75rem; border:1px solid var(--border-2); border-radius:8px; background:var(--surface); transition:all .15s; cursor:pointer; }
            .doc-item:hover { background:var(--accent-bg); border-color:#bfdbfe; }
            .doc-icon { width:30px; height:30px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
            .doc-icon.img  { background:#dbeafe; }
            .doc-icon.pdf  { background:#fee2e2; }
            .doc-icon.word { background:#dcfce7; }
            .doc-icon.other{ background:var(--surface-2); }
            .doc-info  { flex:1; min-width:0; }
            .doc-name  { font-size:12px; font-weight:600; color:var(--text-1); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
            .doc-meta  { font-size:10px; color:var(--text-3); font-family:var(--mono); margin-top:1px; }
            .doc-actions { display:flex; gap:.3rem; flex-shrink:0; }
            .btn-doc { display:inline-flex; align-items:center; gap:3px; padding:3px 8px; border-radius:5px; border:1px solid var(--border); font-family:'DM Sans',sans-serif; font-size:10px; color:var(--text-2); background:var(--surface); cursor:pointer; text-decoration:none; transition:all .15s; white-space:nowrap; }
            .btn-doc:hover { border-color:var(--navy); color:var(--navy); }
            .btn-doc-delete { border-color:#fca5a5; color:#dc2626; }
            .btn-doc-delete:hover { background:#fee2e2; border-color:var(--red); }
            .docs-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:var(--border); font-size:12px; gap:.4rem; padding:1.5rem; }
            .img-thumb { width:30px; height:30px; border-radius:6px; object-fit:cover; flex-shrink:0; border:1px solid var(--border-2); cursor:zoom-in; }
            .dropzone { border:2px dashed var(--border); border-radius:10px; padding:1.5rem 1rem; text-align:center; cursor:pointer; transition:all .15s; position:relative; flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; }
            .dropzone:hover,.dropzone.drag-over { border-color:var(--accent); background:var(--accent-bg); }
            .dropzone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
            .dropzone-icon  { width:34px; height:34px; background:var(--surface-2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto .5rem; }
            .dropzone-title { font-size:13px; font-weight:500; color:var(--text-2); }
            .dropzone-sub   { font-size:11px; color:var(--text-3); margin-top:3px; }
            .dropzone-file  { font-size:11px; font-weight:600; color:var(--accent); margin-top:.4rem; display:none; }
            .btn-upload { display:inline-flex; align-items:center; justify-content:center; gap:5px; padding:.5rem 1rem; background:var(--navy); color:#fff; border:none; border-radius:8px; font-family:'DM Sans',sans-serif; font-size:12px; font-weight:500; cursor:pointer; transition:background .15s; width:100%; }
            .btn-upload:hover { background:var(--navy-light); }
            .btn-upload:disabled { opacity:.4; cursor:not-allowed; }
            .upload-note { font-size:10px; color:var(--text-3); text-align:center; font-family:var(--mono); }
            .lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,.88); z-index:9999; align-items:center; justify-content:center; }
            .lightbox.open { display:flex; }
            .lightbox img { max-width:90vw; max-height:90vh; border-radius:10px; box-shadow:0 24px 64px rgba(0,0,0,.5); }
            .lightbox-close { position:fixed; top:1.25rem; right:1.25rem; background:rgba(255,255,255,.12); border:none; color:#fff; font-size:22px; width:38px; height:38px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .15s; }
            .lightbox-close:hover { background:rgba(255,255,255,.22); }

            html.dark .hero { background:#111827 !important; }
            html.dark .info-card { background:#1e2130 !important; border-color:#2d3148 !important; }
            html.dark .info-card-head { background:#181b29 !important; border-bottom-color:#2d3148 !important; }
            html.dark .info-card-title { color:#64748b !important; }
            html.dark .info-card-body { background:#1e2130 !important; }
            html.dark .fl  { color:#64748b !important; }
            html.dark .fv  { color:#e2e8f0 !important; }
            html.dark .fv-lg { color:#f1f5f9 !important; }
            html.dark .docs-card  { background:#1e2130 !important; border-color:#2d3148 !important; }
            html.dark .docs-head  { background:#181b29 !important; border-bottom-color:#2d3148 !important; }
            html.dark .docs-head-title { color:#e2e8f0 !important; }
            html.dark .docs-col-files  { border-right-color:#2d3148 !important; background:#1e2130 !important; }
            html.dark .docs-col-upload { background:#181b29 !important; }
            html.dark .docs-col-label  { color:#64748b !important; }
            html.dark .doc-item  { background:#1e2130 !important; border-color:#2d3148 !important; }
            html.dark .doc-item:hover { background:#252840 !important; border-color:#6366f1 !important; }
            html.dark .doc-name  { color:#e2e8f0 !important; }
            html.dark .doc-meta  { color:#64748b !important; }
            html.dark .btn-doc   { background:#252840 !important; border-color:#374151 !important; color:#cbd5e1 !important; }
            html.dark .btn-doc-delete { border-color:#7f1d1d !important; color:#fca5a5 !important; }
            html.dark .dropzone { border-color:#374151 !important; }
            html.dark .dropzone:hover { border-color:#6366f1 !important; background:#1e2d6b !important; }
            html.dark .dropzone-icon { background:#252840 !important; }
            html.dark .dropzone-title { color:#cbd5e1 !important; }
            html.dark .dropzone-sub { color:#64748b !important; }
            html.dark .btn-upload { background:#6366f1 !important; }
            html.dark .btn-upload:hover { background:#4f46e5 !important; }
            html.dark .upload-note { color:#64748b !important; }
            html.dark .sms-panel { background:#1e2130 !important; border-color:#2d3148 !important; }
            html.dark .sms-head  { background:#181b29 !important; border-bottom-color:#2d3148 !important; }
            html.dark .sms-head-title { color:#e2e8f0 !important; }
            html.dark .sms-warning { background:#2a1f00 !important; border-color:#854d0e !important; }
            html.dark .sms-warning.expired { background:#2a0a0a !important; border-color:#7f1d1d !important; }
            html.dark .sms-warning-title { color:#fde68a !important; }
            html.dark .sms-warning.expired .sms-warning-title { color:#fca5a5 !important; }
            html.dark .sms-log { border-color:#2d3148 !important; }
            html.dark .sms-log table th { background:#181b29 !important; color:#64748b !important; }
            html.dark .sms-log table td { color:#cbd5e1 !important; border-top-color:#2d3148 !important; }
            html.dark .btn-sms-amber { background:#2a1f00 !important; border-color:#854d0e !important; color:#fde68a !important; }
            html.dark .btn-sms-green { background:#052e16 !important; border-color:#166534 !important; color:#86efac !important; }
            html.dark .btn-sms-blue  { background:#1e3a5f !important; border-color:#1e40af !important; color:#93c5fd !important; }
        </style>
    </head>
    <body>

    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div style="display:flex;align-items:center;gap:.6rem">
                <a href="{{ route('permits.index') }}" class="topbar-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Permits
                </a>
                <span class="topbar-sep">/</span>
                <span class="topbar-title">{{ $permit->permit_number }}</span>
            </div>
            <span class="role-pill">Admin</span>
        </div>

        <div class="content">

            @php
                $feeMap = [
                    'cemented'    => ['label'=>'Cemented',        'amount'=>'₱1,000.00'],
                    'niche_1st'   => ['label'=>'1st Floor Niche', 'amount'=>'₱8,000.00'],
                    'niche_2nd'   => ['label'=>'2nd Floor Niche', 'amount'=>'₱6,600.00'],
                    'niche_3rd'   => ['label'=>'3rd Floor Niche', 'amount'=>'₱5,700.00'],
                    'niche_4th'   => ['label'=>'4th Floor Niche', 'amount'=>'₱5,300.00'],
                    'bone_niches' => ['label'=>'Bone Niches',     'amount'=>'₱5,000.00'],
                ];
                $feeInfo = $feeMap[$permit->permit_type] ?? ['label'=>ucfirst(str_replace(['_','-'],' ',$permit->permit_type)),'amount'=>null];
            @endphp

            {{-- HERO --}}
            <div class="hero fade-up">
                <div>
                    <div class="hero-eyebrow">Burial Permit</div>
                    <div class="hero-no">{{ $permit->permit_number }}</div>
                    <div class="hero-meta">Issued {{ $permit->created_at->format('F d, Y') }} · {{ $permit->created_at->diffForHumans() }}</div>
                </div>
                <div class="hero-actions">
                    @if($permit->status === 'expired')
                        <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                    @elseif($permit->status === 'released' && $permit->expiry_date && $permit->expiry_date->isFuture() && $permit->expiry_date->diffInDays(now()) <= 30)
                        <span class="badge badge-orange"><span class="badge-dot"></span>Expiring Soon</span>
                    @elseif($permit->status === 'released')
                        <span class="badge badge-blue"><span class="badge-dot"></span>Released</span>
                    @elseif($permit->status === 'approved')
                        <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                    @else
                        <span class="badge badge-yellow"><span class="badge-dot"></span>Pending</span>
                    @endif

                    <a href="{{ route('permits.print', $permit) }}" class="btn btn-ghost">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print Permit
                    </a>

                    @if($permit->status === 'pending')
                    <form method="POST" action="{{ route('permits.approve', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Approve
                        </button>
                    </form>
                    @endif

                    @if($permit->status === 'approved')
                    <form method="POST" action="{{ route('permits.release', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn btn-info">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Release
                        </button>
                    </form>
                    @endif

                    @if($permit->status === 'expired')
                    <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:contents" onsubmit="return confirm('Renew this permit?')">
                        @csrf
                        <button type="submit" class="btn btn-warn">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                            Renew Permit
                        </button>
                    </form>
                    @endif

                    <form method="POST" action="{{ route('permits.destroy', $permit) }}" style="display:contents" onsubmit="return confirm('Delete this permit permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- INFO GRID --}}
            <div class="info-grid fade-up d1">
                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <span class="info-card-title">Deceased</span>
                    </div>
                    <div class="info-card-body">
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
                            <div class="field"><div class="fl">Date of Death</div><div class="fv">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</div></div>
                            <div class="field"><div class="fl">Kind of Burial</div><div class="fv">{{ optional($permit->deceased)->kind_of_burial ?: '—' }}</div></div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        <span class="info-card-title">Permit Fee</span>
                    </div>
                    <div class="info-card-body">
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

                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span class="info-card-title">Application Info</span>
                    </div>
                    <div class="info-card-body">
                        <div class="field"><div class="fl">Requestor</div><div class="fv">{{ $permit->applicant_name ?: '—' }}</div></div>
                        <div class="g2">
                            <div class="field"><div class="fl">Contact</div><div class="fv">{{ $permit->applicant_contact ?: '—' }}</div></div>
                            <div class="field"><div class="fl">Processed By</div><div class="fv">{{ optional($permit->processedBy)->name ?? 'Admin' }}</div></div>
                        </div>
                        @if($permit->expiry_date)
                        <div class="field">
                            <div class="fl">Expiry Date</div>
                            <div class="fv" style="{{ $permit->status === 'expired' ? 'color:var(--red);font-weight:700' : '' }}">
                                {{ $permit->expiry_date->format('F d, Y') }}
                                @if($permit->status === 'expired') <span style="font-size:11px;font-family:var(--mono)"> — Expired</span> @endif
                            </div>
                        </div>
                        @endif
                        @if($permit->remarks)
                        <div class="field"><div class="fl">Remarks</div><div class="fv" style="font-size:12px">{{ $permit->remarks }}</div></div>
                        @endif
                    </div>
                </div>
            </div>

           

            {{-- DOCUMENTS --}}
            <div class="docs-card fade-up d3">
                <div class="docs-head">
                    <div class="docs-head-left">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span class="docs-head-title">Attached Documents</span>
                        <span class="docs-head-sub">— {{ $permit->documents->count() }} file{{ $permit->documents->count() !== 1 ? 's' : '' }}</span>
                    </div>
                    <span style="font-size:11px;color:var(--text-3);font-family:var(--mono)">Optional · photos, certificates, receipts, IDs</span>
                </div>
                <div class="docs-body">
                    <div class="docs-col-files">
                        <div class="docs-col-label">Uploaded Files</div>
                        @forelse($permit->documents as $doc)
                        @php
                            $ext=$doc->file_name?strtolower(pathinfo($doc->file_name,PATHINFO_EXTENSION)):'';
                            $isImage=in_array($ext,['jpg','jpeg','png','gif','webp']);
                            $isPdf=$ext==='pdf';$isWord=in_array($ext,['doc','docx']);
                            $iconClass=$isImage?'img':($isPdf?'pdf':($isWord?'word':'other'));
                            $iconColor=match($iconClass){'img'=>'#3b82f6','pdf'=>'#ef4444','word'=>'#16a34a',default=>'#94a3b8'};
                            $viewUrl=route('documents.download',$doc);
                        @endphp
                        <div class="doc-item" onclick="openDoc('{{ $viewUrl }}','{{ $isImage?'image':'file' }}','{{ addslashes($doc->file_name) }}')" title="Click to view">
                            @if($isImage)
                                <img src="{{ $viewUrl }}" class="img-thumb" alt="{{ $doc->file_name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="doc-icon img" style="display:none"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                            @else
                                <div class="doc-icon {{ $iconClass }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                            @endif
                            <div class="doc-info">
                                <div class="doc-name">{{ $doc->file_name }}</div>
                                <div class="doc-meta">{{ strtoupper($ext) }} · {{ $doc->created_at->format('M d, Y') }}@if($doc->uploadedBy) · {{ $doc->uploadedBy->name }}@endif</div>
                            </div>
                            <div class="doc-actions" onclick="event.stopPropagation()">
                                <a href="{{ $viewUrl }}" class="btn-doc" target="_blank">↓ Download</a>
                                <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this file?')" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-doc btn-doc-delete">✕ Delete</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="docs-empty">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span>No files yet</span>
                        </div>
                        @endforelse
                    </div>
                    <div class="docs-col-upload">
                        <div class="docs-col-label">Add a File</div>
                        <form id="uploadForm" method="POST" action="{{ route('documents.upload', $permit) }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:.75rem;flex:1">
                            @csrf
                            <div class="dropzone" id="dropzone" ondragover="event.preventDefault();this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
                                <input type="file" name="document" id="docInput" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx" onchange="handleFile(this)">
                                <div class="dropzone-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                                <div class="dropzone-title">Click to upload or drag & drop</div>
                                <div class="dropzone-sub">JPG, PNG, PDF, Word — max 10MB</div>
                                <div class="dropzone-file" id="fileName"></div>
                            </div>
                            <button type="submit" class="btn-upload" id="uploadBtn" disabled>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Attach File
                            </button>
                            <span class="upload-note">Stored privately · only visible to admins</span>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lightbox" id="lightbox" onclick="closeLightbox()">
                <button class="lightbox-close" onclick="closeLightbox()">×</button>
                <img id="lightboxImg" src="" alt="">
            </div>

        </div>
    </div>

    @if(session('success'))
    <div class="toast show" id="sToast">
        <div class="toast-body">
            <div class="toast-icon green"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
            <div><div class="toast-title">Success</div><div class="toast-msg">{{ session('success') }}</div></div>
        </div>
        <div class="toast-bar green"></div>
    </div>
    @endif

    @if($errors->has('sms'))
    <div class="toast show" id="smsErrToast">
        <div class="toast-body">
            <div class="toast-icon red"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
            <div><div class="toast-title" style="color:var(--red)">SMS Failed</div><div class="toast-msg">{{ $errors->first('sms') }}</div></div>
        </div>
        <div class="toast-bar red"></div>
    </div>
    @endif

    <script>
    function handleFile(input) {
        const file = input.files[0]; if (!file) return;
        document.getElementById('fileName').style.display = 'block';
        document.getElementById('fileName').textContent = '📎 ' + file.name;
        document.getElementById('uploadBtn').disabled = false;
    }
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropzone').classList.remove('drag-over');
        const file = e.dataTransfer.files[0]; if (!file) return;
        const input = document.getElementById('docInput');
        const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
        handleFile(input);
    }
    document.getElementById('uploadForm').addEventListener('submit', function() {
        document.getElementById('uploadBtn').disabled = true;
        document.getElementById('uploadBtn').textContent = 'Uploading…';
    });
    function openDoc(url, type, name) {
        if (type === 'image') {
            document.getElementById('lightboxImg').src = url;
            document.getElementById('lightboxImg').alt = name;
            document.getElementById('lightbox').classList.add('open');
            document.body.style.overflow = 'hidden';
        } else { window.open(url, '_blank'); }
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
    (function(){
        const s = document.getElementById('sToast');
        if (s) setTimeout(() => s.classList.remove('show'), 5000);
        const e = document.getElementById('smsErrToast');
        if (e) setTimeout(() => e.classList.remove('show'), 6000);
    })();
    </script>
    </body>
    </html>