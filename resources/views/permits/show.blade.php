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
        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── HERO ── */
        .hero { background: #1a2744; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
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

        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .42rem .9rem; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; transition: all .15s; background: none; text-decoration: none; }
        .btn-renew  { background: #fff1f2; color: #b91c1c; border-color: #fca5a5; }
        .btn-renew:hover  { background: #fee2e2; }
        .btn-print  { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.25); }
        .btn-print:hover  { background: rgba(255,255,255,.18); border-color: rgba(255,255,255,.4); }
        .btn-delete { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .btn-delete:hover { background: #fecaca; }


        /* ── INFO CARDS ── */
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

        /* ── DOCUMENTS SECTION ── */
        .docs-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .docs-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .docs-head-left { display: flex; align-items: center; gap: .5rem; }
        .docs-head-title { font-size: 13px; font-weight: 700; color: #111827; }
        .docs-head-sub { font-size: 11px; color: #9ca3af; }

        /* Two-column body */
        .docs-body { display: grid; grid-template-columns: 1fr 1fr; min-height: 260px; }
        .docs-col-files  { border-right: 1px solid #f3f4f6; padding: 1rem; display: flex; flex-direction: column; gap: .5rem; overflow-y: auto; max-height: 400px; }
        .docs-col-upload { padding: 1rem; display: flex; flex-direction: column; gap: .75rem; background: #fafafa; }

        .docs-col-label { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .25rem; }

        /* File list items */
        .doc-item { display: flex; align-items: center; gap: .65rem; padding: .55rem .75rem; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; transition: all .15s; cursor: pointer; }
        .doc-item:hover { background: #f0f4ff; border-color: #c7d2fe; }
        .doc-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .doc-icon.img  { background: #dbeafe; }
        .doc-icon.pdf  { background: #fee2e2; }
        .doc-icon.word { background: #d1fae5; }
        .doc-icon.other{ background: #f3f4f6; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-name { font-size: 12px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doc-meta { font-size: 10px; color: #9ca3af; margin-top: 1px; }
        .doc-actions { display: flex; gap: .3rem; flex-shrink: 0; }
        .btn-doc { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 4px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 10px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-doc:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-doc-delete { border-color: #fca5a5; color: #991b1b; }
        .btn-doc-delete:hover { background: #fee2e2; border-color: #ef4444; }

        .docs-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; flex: 1; color: #d1d5db; font-size: 12px; gap: .4rem; padding: 1rem; }

        /* Image preview popup */
        .img-thumb { width: 36px; height: 36px; border-radius: 6px; object-fit: cover; flex-shrink: 0; border: 1px solid #e5e7eb; cursor: zoom-in; }

        /* Dropzone */
        .dropzone { border: 2px dashed #d1d5db; border-radius: 8px; padding: 1.5rem 1rem; text-align: center; cursor: pointer; transition: border-color .15s, background .15s; position: relative; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .dropzone:hover, .dropzone.drag-over { border-color: #1a2744; background: #f0f4ff; }
        .dropzone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .dropzone-icon { width: 36px; height: 36px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto .5rem; }
        .dropzone-title { font-size: 13px; font-weight: 500; color: #374151; }
        .dropzone-sub   { font-size: 11px; color: #9ca3af; margin-top: 3px; }
        .dropzone-file  { font-size: 11px; font-weight: 600; color: #1a2744; margin-top: .4rem; display: none; }
        .btn-upload { display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: .5rem 1rem; background: #1a2744; color: #fff; border: none; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; cursor: pointer; transition: background .15s; width: 100%; }
        .btn-upload:hover { background: #243459; }
        .btn-upload:disabled { opacity: .45; cursor: not-allowed; }
        .upload-note { font-size: 10px; color: #9ca3af; text-align: center; }

        /* Lightbox */
        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.85); z-index: 9999; align-items: center; justify-content: center; }
        .lightbox.open { display: flex; }
        .lightbox img { max-width: 90vw; max-height: 90vh; border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,.5); }
        .lightbox-close { position: fixed; top: 1.25rem; right: 1.25rem; background: rgba(255,255,255,.15); border: none; color: #fff; font-size: 24px; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
        .lightbox-close:hover { background: rgba(255,255,255,.25); }

        /* ── TOAST ── */
        .toast { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.12); width: 310px; overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .35s cubic-bezier(.34,1.56,.64,1); }
        .toast.show { transform: translateX(0); }
        .toast-body { display: flex; align-items: center; gap: .75rem; padding: .85rem 1rem; }
        .toast-icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-icon.green { background: #d1fae5; }
        .toast-icon.red   { background: #fee2e2; }
        .toast-title { font-size: 13px; font-weight: 600; color: #111827; }
        .toast-sub   { font-size: 11px; color: #6b7280; margin-top: 1px; }
        .toast-bar { height: 3px; background: #e5e7eb; }
        .toast-fill { height: 100%; width: 100%; transform-origin: left; animation: drain 4s linear forwards; }
        .toast-fill.green { background: #10b981; }
        .toast-fill.red   { background: #ef4444; }
        @keyframes drain { from{transform:scaleX(1)} to{transform:scaleX(0)} }
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
            $feeInfo = $feeMap[$permit->permit_type] ?? ['label'=>ucfirst(str_replace(['_','-'],' ',$permit->permit_type)),'amount'=>null];


        @endphp

        {{-- HERO --}}
        <div class="hero">
            <div class="hero-left">
                <div class="hero-eyebrow">Burial Permit</div>
                <div class="hero-no">{{ $permit->permit_number }}</div>
                <div class="hero-meta">Issued {{ $permit->created_at->format('F d, Y') }} &nbsp;·&nbsp; {{ $permit->created_at->diffForHumans() }}</div>
            </div>
            <div class="hero-right">
                {{-- Status badge --}}
                @if($permit->status === 'expired')
                    <span class="badge badge-red" style="font-weight:700">⚠ Expired</span>
                @elseif($permit->status === 'released' && $permit->expiry_date && $permit->expiry_date->isFuture() && $permit->expiry_date->diffInDays(now()) <= 30)
                    <span class="badge badge-yellow">⏳ Expiring Soon</span>
                @else
                    <span class="badge {{ $statusColors[$permit->status] ?? 'badge-yellow' }}">{{ ucfirst($permit->status) }}</span>
                @endif

                {{-- Print .docx --}}
                <a href="{{ route('permits.print', $permit) }}" class="btn btn-print">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print Permit
                </a>

                {{-- Approve (pending only) --}}
                @if($permit->status === 'pending')
                    <form method="POST" action="{{ route('permits.approve', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Approve
                        </button>
                    </form>
                @endif

                {{-- Release (approved only) --}}
                @if($permit->status === 'approved')
                    <form method="POST" action="{{ route('permits.release', $permit) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn" style="background:#dbeafe;color:#1e40af;border-color:#93c5fd">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Release
                        </button>
                    </form>
                @endif

                {{-- Renew (expired only) --}}
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

                {{-- Delete --}}
                <form method="POST" action="{{ route('permits.destroy', $permit) }}" style="display:contents"
                      onsubmit="return confirm('Delete this permit permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-delete">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                        Delete
                    </button>
                </form>
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
                        <div class="fv" style="{{ $permit->status === 'expired' ? 'color:#ef4444;font-weight:700' : '' }}">
                            {{ $permit->expiry_date->format('F d, Y') }}
                            @if($permit->status === 'expired') <span style="font-size:11px"> — Expired</span> @endif
                        </div>
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

        {{-- ══════════ DOCUMENTS ══════════ --}}
        <div class="docs-card">
            <div class="docs-head">
                <div class="docs-head-left">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span class="docs-head-title">Attached Documents</span>
                    <span class="docs-head-sub">
                        — {{ $permit->documents->count() }} file{{ $permit->documents->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
                <span style="font-size:11px;color:#9ca3af">Optional · photos, certificates, receipts, IDs</span>
            </div>

            <div class="docs-body">

                {{-- LEFT: file list --}}
                <div class="docs-col-files">
                    <div class="docs-col-label">Uploaded Files</div>

                    @forelse($permit->documents as $doc)
                    @php
                        $ext       = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                        $isImage   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                        $isPdf     = $ext === 'pdf';
                        $isWord    = in_array($ext, ['doc','docx']);
                        $iconClass = $isImage ? 'img' : ($isPdf ? 'pdf' : ($isWord ? 'word' : 'other'));
                        $iconColor = match($iconClass) { 'img'=>'#3b82f6', 'pdf'=>'#ef4444', 'word'=>'#10b981', default=>'#9ca3af' };
                        $viewUrl   = route('documents.download', $doc);
                    @endphp
                    <div class="doc-item" onclick="openDoc('{{ $viewUrl }}', '{{ $isImage ? 'image' : 'file' }}', '{{ addslashes($doc->file_name) }}')" title="Click to view">
                        {{-- Thumbnail for images, icon for others --}}
                        @if($isImage)
                            <img src="{{ $viewUrl }}" class="img-thumb" alt="{{ $doc->file_name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="doc-icon img" style="display:none">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @else
                            <div class="doc-icon {{ $iconClass }}">
                                @if($isPdf)
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                @elseif($isWord)
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                @else
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                @endif
                            </div>
                        @endif

                        <div class="doc-info">
                            <div class="doc-name">{{ $doc->file_name }}</div>
                            <div class="doc-meta">
                                {{ strtoupper($ext) }}
                                · {{ $doc->created_at->format('M d, Y') }}
                                @if($doc->uploadedBy) · {{ $doc->uploadedBy->name }} @endif
                            </div>
                        </div>

                        <div class="doc-actions" onclick="event.stopPropagation()">
                            <a href="{{ $viewUrl }}" class="btn-doc" target="_blank">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Download
                            </a>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                                  onsubmit="return confirm('Delete this file?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-doc btn-doc-delete">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="docs-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e5e7eb" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span>No files yet</span>
                    </div>
                    @endforelse
                </div>

                {{-- RIGHT: upload --}}
                <div class="docs-col-upload">
                    <div class="docs-col-label">Add a File</div>
                    <form id="uploadForm" method="POST"
                          action="{{ route('documents.upload', $permit) }}"
                          enctype="multipart/form-data"
                          style="display:flex;flex-direction:column;gap:.75rem;flex:1">
                        @csrf
                        <div class="dropzone" id="dropzone"
                             ondragover="event.preventDefault();this.classList.add('drag-over')"
                             ondragleave="this.classList.remove('drag-over')"
                             ondrop="handleDrop(event)">
                            <input type="file" name="document" id="docInput"
                                   accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx"
                                   onchange="handleFile(this)">
                            <div class="dropzone-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <div class="dropzone-title">Click to upload or drag & drop</div>
                            <div class="dropzone-sub">JPG, PNG, PDF, Word — max 10MB</div>
                            <div class="dropzone-file" id="fileName"></div>
                        </div>
                        <button type="submit" class="btn-upload" id="uploadBtn" disabled>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Attach File
                        </button>
                        <span class="upload-note">Stored privately · only visible to admins</span>
                    </form>
                </div>

            </div>
        </div>

        {{-- LIGHTBOX for image preview --}}
        <div class="lightbox" id="lightbox" onclick="closeLightbox()">
            <button class="lightbox-close" onclick="closeLightbox()">×</button>
            <img id="lightboxImg" src="" alt="">
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

{{-- TOAST --}}
@if(session('success'))
<div class="toast show" id="sToast">
    <div class="toast-body">
        <div class="toast-icon green"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div><div class="toast-title">Success</div><div class="toast-sub">{{ session('success') }}</div></div>
    </div>
    <div class="toast-bar"><div class="toast-fill green"></div></div>
</div>
@endif

<script>
// ── File picker ──
function handleFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('fileName').style.display = 'block';
    document.getElementById('fileName').textContent   = '📎 ' + file.name;
    document.getElementById('uploadBtn').disabled     = false;
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropzone').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = document.getElementById('docInput');
    const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
    handleFile(input);
}
document.getElementById('uploadForm').addEventListener('submit', function() {
    document.getElementById('uploadBtn').disabled    = true;
    document.getElementById('uploadBtn').textContent = 'Uploading…';
});

// ── Open file: image → lightbox, others → new tab ──
function openDoc(url, type, name) {
    if (type === 'image') {
        const lb  = document.getElementById('lightbox');
        const img = document.getElementById('lightboxImg');
        img.src = url;
        img.alt = name;
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
    } else {
        window.open(url, '_blank');
    }
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// ── Session toast auto-dismiss ──
(function() {
    const t = document.getElementById('sToast');
    if (!t) return;
    setTimeout(() => t.classList.remove('show'), 5000);
})();
</script>

</body>
</html>