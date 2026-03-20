<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burial Permits — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .panel-header { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #111827; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table colgroup col:nth-child(1) { width: 160px; }
        table colgroup col:nth-child(2) { width: 200px; }
        table colgroup col:nth-child(3) { width: 130px; }
        table colgroup col:nth-child(4) { width: 130px; }
        table colgroup col:nth-child(5) { width: 120px; }
        table colgroup col:nth-child(6) { width: 150px; }
        table colgroup col:nth-child(7) { width: 120px; }
        td, th { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        th { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; background: #fafafa; cursor: pointer; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }

        /* ── EXPIRED ROW HIGHLIGHT ── */
        tr.row-expired td {
            background: #fff5f5;
            border-top-color: #fecaca;
        }
        tr.row-expired td:first-child {
            border-left: 3px solid #ef4444;
        }

        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 4px; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .permit-no { font-weight: 600; color: #1a2744; font-size: 12px; }

        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-renew { background: #fff1f2; border-color: #fca5a5; color: #b91c1c; font-weight: 600; }
        .btn-renew:hover { background: #fee2e2; border-color: #ef4444; color: #991b1b; }

        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .55rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }

        /* PAGINATION */
        .pager { display: flex; align-items: center; justify-content: center; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; gap: 1.5rem; }
        .pager-info { font-size: 12px; color: #6b7280; }
        .pager-btns { display: flex; align-items: center; gap: 3px; }
        .pager-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; text-decoration: none; background: #fff; cursor: pointer; transition: border-color .15s, color .15s; white-space: nowrap; line-height: 1; }
        .pager-btn:hover { border-color: #1a2744; color: #1a2744; }
        .pager-btn.active { background: #1a2744; color: #fff; border-color: #1a2744; font-weight: 600; cursor: default; }
        .pager-btn.disabled { color: #d1d5db; cursor: not-allowed; pointer-events: none; }

        /* SEARCH */
        .search-input { font-family: 'Inter', sans-serif; font-size: 13px; padding: .38rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; width: 220px; color: #111827; }
        .search-input:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.07); }

        /* SORTABLE HEADERS */
        .sort-link { display: inline-flex; align-items: center; gap: 4px; color: #9ca3af; text-decoration: none; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .06em; transition: color .15s; white-space: nowrap; }
        .sort-link:hover { color: #1a2744; }
        .sort-link.active { color: #1a2744; font-weight: 700; }
        .sort-icon { opacity: .4; font-size: 10px; }
        .sort-icon.asc::after  { content: ' ↑'; }
        .sort-icon.desc::after { content: ' ↓'; }
        .sort-icon.none::after { content: ' ↕'; }

        /* TOAST */
        .toast { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.12); width: 320px; overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .35s cubic-bezier(.34,1.56,.64,1); pointer-events: none; }
        .toast.show { transform: translateX(0); pointer-events: auto; }
        .toast-body { display: flex; align-items: flex-start; gap: .75rem; padding: .9rem 1rem; }
        .toast-icon { width: 34px; height: 34px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-text { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 600; color: #111827; }
        .toast-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .toast-close { background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px; line-height: 1; transition: color .15s; flex-shrink: 0; }
        .toast-close:hover { color: #374151; }
        .toast-progress { height: 3px; background: #e5e7eb; position: relative; overflow: hidden; }
        .toast-progress-bar { position: absolute; top: 0; left: 0; height: 100%; width: 100%; background: #10b981; transform-origin: left; animation: toastDrain 5s linear forwards; }
        @keyframes toastDrain { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 100; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 10px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; border-bottom: 2px solid #1a2744; display: flex; align-items: center; justify-content: space-between; background: #1a2744; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; letter-spacing: .02em; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); padding: 4px; border-radius: 4px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: #fff; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .25rem; border-bottom: 1px solid #e5e7eb; margin-top: .25rem; }
        .fee-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .fee-row:hover { background: #f8faff; border-color: #1a2744; }
        .fee-row input[type=radio] { accent-color: #1a2744; width: 15px; height: 15px; cursor: pointer; }
        .fee-row label { font-size: 13px; font-weight: 500; color: #111827; cursor: pointer; flex: 1; margin-left: .6rem; }
        .fee-amount { font-size: 13px; font-weight: 600; color: #1a2744; }
        .fee-grid { display: flex; flex-direction: column; gap: .4rem; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; transition: all .15s; }
        .btn-cancel:hover { background: #f9fafb; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Burial Permits</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <span class="role-tag">Admin</span>
            <button class="btn-primary" onclick="document.getElementById('permitModal').classList.add('open')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                New Permit
            </button>
        </div>
    </div>

    <div class="content">
        <div class="panel">
            <div class="panel-header">
                <h3>All Burial Permits
                    <span style="font-size:11px;font-weight:400;color:#9ca3af;margin-left:.5rem">{{ $permits->total() }} total</span>
                </h3>
                <input type="text" class="search-input" placeholder="Search by name or permit no…" oninput="filterTable(this.value)">
            </div>
            <table>
                <colgroup>
                    <col/><col/><col/><col/><col/><col/><col/>
                </colgroup>
                <thead>
                    <tr>
                        <th><a href="{{ $sortUrl('permit_number') }}" class="sort-link">Permit No. {!! $sortIcon('permit_number') !!}</a></th>
                        <th><a href="{{ $sortUrl('last_name') }}" class="sort-link">Deceased {!! $sortIcon('last_name') !!}</a></th>
                        <th><a href="{{ $sortUrl('permit_type') }}" class="sort-link">Type {!! $sortIcon('permit_type') !!}</a></th>
                        <th><a href="{{ $sortUrl('date_of_death') }}" class="sort-link">Date of Death {!! $sortIcon('date_of_death') !!}</a></th>
                        <th><a href="{{ $sortUrl('created_at') }}" class="sort-link">Issued {!! $sortIcon('created_at') !!}</a></th>
                        <th><a href="{{ $sortUrl('status') }}" class="sort-link">Status {!! $sortIcon('status') !!}</a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permits as $permit)
                    <tr class="permit-row {{ $permit->status === 'expired' ? 'row-expired' : '' }}">
                        <td>
                            <span class="permit-no">{{ $permit->permit_number }}</span>
                            @if($permit->status === 'expired')
                                <span style="font-size:10px;font-weight:700;color:#ef4444;margin-left:5px;vertical-align:middle">⚠ RENEWAL NEEDED</span>
                            @endif
                        </td>
                        <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>
                        <td style="font-size:12px;color:#6b7280;text-transform:capitalize">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                        <td style="font-size:12px;color:#6b7280">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</td>
                        <td style="font-size:12px;color:#6b7280">{{ $permit->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($permit->status === 'expired')
                                <span class="badge badge-red" style="font-weight:700;letter-spacing:.02em">
                                    ⚠ Expired
                                </span>
                            @elseif($permit->status === 'released')
                                @php
                                    $expiring = $permit->expiry_date && $permit->expiry_date->diffInDays(now()) <= 30 && $permit->expiry_date->isFuture();
                                @endphp
                                <span class="badge badge-blue" style="{{ $expiring ? 'background:#fef3c7;color:#92400e;' : '' }}">
                                    {{ $expiring ? '⏳ Expiring Soon' : 'Released' }}
                                </span>
                            @else
                                @php $colors = ['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue']; @endphp
                                <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="display:flex;gap:5px;align-items:center">
                            <a href="{{ route('permits.show', $permit) }}" class="btn-action">View</a>
                            @if($permit->status === 'expired')
                                <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:inline"
                                      onsubmit="return confirm('Renew this permit?')">
                                    @csrf
                                    <button type="submit" class="btn-action btn-renew">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                                        Renew
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#9ca3af;padding:2.5rem">No permits yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($permits->hasPages())
            <div class="pager">
                <span class="pager-info">
                    Showing {{ $permits->firstItem() }}–{{ $permits->lastItem() }} of {{ $permits->total() }} results
                </span>
                <div class="pager-btns">
                    @if($permits->onFirstPage())
                        <span class="pager-btn disabled">‹ Prev</span>
                    @else
                        <a href="{{ $permits->previousPageUrl() }}" class="pager-btn">‹ Prev</a>
                    @endif

                    @php
                        $current = $permits->currentPage();
                        $last    = $permits->lastPage();
                        $pages   = [];
                        for ($p = 1; $p <= $last; $p++) {
                            if ($p == 1 || $p == $last || abs($p - $current) <= 2) $pages[] = $p;
                        }
                        $pages = array_unique($pages); sort($pages);
                    @endphp

                    @php $prev = null; @endphp
                    @foreach($pages as $page)
                        @if($prev !== null && $page - $prev > 1)
                            <span class="pager-btn disabled" style="border:none;padding:0 2px;">…</span>
                        @endif
                        @if($page == $current)
                            <span class="pager-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $permits->url($page) }}" class="pager-btn">{{ $page }}</a>
                        @endif
                        @php $prev = $page; @endphp
                    @endforeach

                    @if($permits->hasMorePages())
                        <a href="{{ $permits->nextPageUrl() }}" class="pager-btn">Next ›</a>
                    @else
                        <span class="pager-btn disabled">Next ›</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- TOAST --}}
@if(session('success'))
<div class="toast" id="successToast">
    <div class="toast-body">
        <div class="toast-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Success</div>
            <div class="toast-sub">{{ session('success') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar"></div></div>
</div>
@endif

<!-- NEW PERMIT MODAL -->
<div class="modal-overlay" id="permitModal" onclick="if(event.target===this)closeModal()">
    <div class="modal">
        <div class="modal-header">
            <h3>🪦 Burial Permit (New)</h3>
            <button class="modal-close" onclick="closeModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('permits.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Requestor's Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="requestor_name" class="form-control" placeholder="Full name of requestor" required>
                </div>
                <div class="section-divider">Deceased Information</div>
                <div class="form-group">
                    <label class="form-label">Deceased Name <span style="color:#ef4444">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                        <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                        <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" placeholder="e.g. Filipino">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select…</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Date of Death <span style="color:#ef4444">*</span></label>
                        <input type="date" name="date_of_death" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kind of Burial</label>
                        <select name="kind_of_burial" class="form-control">
                            <option value="">Select…</option>
                            <option value="Ground">Ground</option>
                            <option value="Niche">Niche</option>
                            <option value="Cremation">Cremation</option>
                        </select>
                    </div>
                </div>
                <div class="section-divider">Burial Permit Fees</div>
                <div class="fee-grid">
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="cemented" id="fee_cemented">
                        <label for="fee_cemented">Cemented</label>
                        <span class="fee-amount">₱1,000.00</span>
                    </div>
                    <div style="font-size:11px;font-weight:600;color:#6b7280;padding:.4rem .25rem 0;text-transform:uppercase;letter-spacing:.05em">Niches (New)</div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_1st" id="fee_1st">
                        <label for="fee_1st">1st Floor</label>
                        <span class="fee-amount">₱8,000.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_2nd" id="fee_2nd">
                        <label for="fee_2nd">2nd Floor</label>
                        <span class="fee-amount">₱6,600.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_3rd" id="fee_3rd">
                        <label for="fee_3rd">3rd Floor</label>
                        <span class="fee-amount">₱5,700.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_4th" id="fee_4th">
                        <label for="fee_4th">4th Floor</label>
                        <span class="fee-amount">₱5,300.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="bone_niches" id="fee_bone">
                        <label for="fee_bone">Bone Niches</label>
                        <span class="fee-amount">₱5,000.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Create Permit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() { document.getElementById('permitModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });

(function () {
    const toast = document.getElementById('successToast');
    if (!toast) return;
    requestAnimationFrame(() => setTimeout(() => toast.classList.add('show'), 50));
    window._toastTimer = setTimeout(dismissToast, 5000);
})();
function dismissToast() {
    clearTimeout(window._toastTimer);
    const toast = document.getElementById('successToast');
    if (!toast) return;
    toast.classList.remove('show');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

if (window.location.hash === '#new') {
    document.getElementById('permitModal').classList.add('open');
    history.replaceState(null, '', window.location.pathname);
}

function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.permit-row').forEach(row => {
        const permitNo = row.querySelector('.permit-no')?.textContent.toLowerCase() ?? '';
        const deceased = row.querySelectorAll('td')[1]?.textContent.toLowerCase() ?? '';
        row.style.display = (permitNo.includes(q) || deceased.includes(q)) ? '' : 'none';
    });
}
</script>

</body>
</html>