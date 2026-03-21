<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{var k='lgu_dark_{{ auth()->id() }}';if(localStorage.getItem(k)==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Records — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .main        { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar      { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title{ font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }
        .role-tag    { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content     { padding: 1.5rem; }

        .panel        { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .panel-header { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #111827; }

        .search-input { font-family: 'Inter', sans-serif; font-size: 13px; padding: .38rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; width: 220px; color: #111827; }
        .search-input:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.07); }

        table { width: 100%; border-collapse: collapse; }
        th    { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; background: #fafafa; white-space: nowrap; }
        td    { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; }

        .name-main  { font-weight: 600; color: #111827; }
        .name-sub   { font-size: 11px; color: #9ca3af; margin-top: 1px; }
        .badge      { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
        .badge-male   { background: #dbeafe; color: #1e40af; }
        .badge-female { background: #fce7f3; color: #9d174d; }

        .btn-action  { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        /* Pagination */
        .pager { display: flex; align-items: center; justify-content: center; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; gap: 1.5rem; }
        .pager-info { font-size: 12px; color: #6b7280; }
        .pager-btns { display: flex; align-items: center; gap: 3px; }
        .pager-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; text-decoration: none; background: #fff; cursor: pointer; transition: border-color .15s, color .15s; white-space: nowrap; line-height: 1; }
        .pager-btn:hover { border-color: #1a2744; color: #1a2744; }
        .pager-btn.active   { background: #1a2744; color: #fff; border-color: #1a2744; font-weight: 600; cursor: default; }
        .pager-btn.disabled { color: #d1d5db; cursor: not-allowed; pointer-events: none; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: 13px; }
    
    /* ══════════════════════════════
       DARK MODE OVERRIDES
    ══════════════════════════════ */
    html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
    html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
    html.dark .topbar-title, html.dark .topbar-sub, html.dark .topbar-date { color: #e2e8f0 !important; }
    html.dark .topbar-date { color: #64748b !important; }
    html.dark .role-tag { background: #6366f1 !important; }
    html.dark .panel, html.dark .card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .panel-head, html.dark .panel-header { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .panel-head h3, html.dark .panel-header h3 { color: #e2e8f0 !important; }
    html.dark .panel-head a { color: #818cf8 !important; }
    html.dark table th { background: #181b29 !important; color: #64748b !important; }
    html.dark table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark tr.row-expired td { background: #2a1a1a !important; border-top-color: #7f1d1d !important; }
    html.dark .permit-no { color: #818cf8 !important; }
    html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
    html.dark .badge-green  { background: #052e16 !important; color: #86efac !important; }
    html.dark .badge-blue   { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .badge-red    { background: #450a0a !important; color: #fca5a5 !important; }
    html.dark .btn-view, html.dark .btn-action { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-view:hover, html.dark .btn-action:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .welcome { background: #111827 !important; }
    html.dark .name-main { color: #e2e8f0 !important; }
    html.dark .name-sub { color: #64748b !important; }
    html.dark .search-input { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .search-input:focus { border-color: #6366f1 !important; }
    html.dark .pager { border-top-color: #2d3148 !important; }
    html.dark .pager-info { color: #64748b !important; }
    html.dark .pager-btn { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .pager-btn:hover { border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .pager-btn.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
    html.dark .pager-btn.disabled { color: #374151 !important; }
    html.dark .sort-link { color: #64748b !important; }
    html.dark .sort-link.active, html.dark .sort-link:hover { color: #818cf8 !important; }
    html.dark .hero { background: #111827 !important; }
    html.dark .card-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .card-head-title { color: #94a3b8 !important; }
    html.dark .card-body { background: #1e2130 !important; }
    html.dark .fl { color: #64748b !important; }
    html.dark .fv, html.dark .fv-lg { color: #e2e8f0 !important; }
    html.dark .fee-box { background: #111827 !important; }
    html.dark .section-divider { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .form-control, html.dark select.form-control { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .form-control:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important; }
    html.dark .form-label { color: #94a3b8 !important; }
    html.dark .modal { background: #1e2130 !important; }
    html.dark .modal-header { background: #111827 !important; }
    html.dark .modal-body { background: #1e2130 !important; }
    html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .fee-row { border-color: #2d3148 !important; }
    html.dark .fee-row:hover { background: #252840 !important; border-color: #6366f1 !important; }
    html.dark .fee-row label { color: #e2e8f0 !important; }
    html.dark .fee-amount { color: #818cf8 !important; }
    html.dark .upload-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .dropzone { border-color: #374151 !important; }
    html.dark .dropzone:hover, html.dark .dropzone.drag-over { border-color: #6366f1 !important; background: #1e2d6b !important; }
    html.dark .dropzone-icon { background: #252840 !important; }
    html.dark .dropzone-title { color: #cbd5e1 !important; }
    html.dark .dropzone-sub { color: #64748b !important; }
    html.dark .docs-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .docs-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .docs-head-title { color: #e2e8f0 !important; }
    html.dark .docs-head-sub { color: #64748b !important; }
    html.dark .docs-col-files { border-right-color: #2d3148 !important; }
    html.dark .docs-col-upload { background: #181b29 !important; }
    html.dark .doc-item { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .doc-item:hover { background: #252840 !important; border-color: #6366f1 !important; }
    html.dark .doc-name { color: #e2e8f0 !important; }
    html.dark .doc-meta { color: #64748b !important; }
    html.dark .btn-doc { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-doc:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .info-item { border-color: #2d3148 !important; }
    html.dark .info-label { color: #64748b !important; }
    html.dark .info-value { color: #e2e8f0 !important; }
    html.dark .info-value.empty { color: #374151 !important; }
    html.dark .panel-head { background: #181b29 !important; }
    html.dark .danger-panel { background: #1e2130 !important; border-color: #7f1d1d !important; }
    html.dark .danger-head { background: #2a1a1a !important; border-bottom-color: #7f1d1d !important; }
    html.dark .danger-desc { color: #e2e8f0 !important; }
    html.dark .danger-sub { color: #94a3b8 !important; }
    html.dark .topbar-back { color: #94a3b8 !important; }
    html.dark .topbar-back:hover { color: #e2e8f0 !important; }
    html.dark .topbar-sep { color: #334155 !important; }
    html.dark .btn-print { background: rgba(255,255,255,.08) !important; }
    html.dark .toggle-row { border-color: #2d3148 !important; }
    html.dark .toggle-row:hover { background: #252840 !important; }
    html.dark .toggle-label { color: #e2e8f0 !important; }
    html.dark .toggle-sub { color: #64748b !important; }
    html.dark .snav-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .snav-item { color: #94a3b8 !important; }
    html.dark .snav-item:hover { background: #252840 !important; color: #e2e8f0 !important; }
    html.dark .snav-item.active { background: #1e2d6b !important; color: #818cf8 !important; border-left-color: #6366f1 !important; }
    html.dark .snav-divider { background: #2d3148 !important; }
    html.dark .section-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .section-head { border-bottom-color: #2d3148 !important; }
    html.dark .section-head h2 { color: #e2e8f0 !important; }
    html.dark .section-head p { color: #64748b !important; }
    html.dark .section-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .fee-table { border-color: #2d3148 !important; }
    html.dark .fee-table th { background: #181b29 !important; color: #64748b !important; }
    html.dark .fee-table td { border-top-color: #2d3148 !important; color: #cbd5e1 !important; }
    html.dark .fee-table input { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .fee-type-badge { background: #1e2d6b !important; color: #818cf8 !important; }
    html.dark .user-table th { background: #181b29 !important; color: #64748b !important; }
    html.dark .user-table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark .appearance-preview { border-color: #2d3148 !important; }
    html.dark .ap-light { background: #252840 !important; }
    html.dark .ap-light:hover { background: #2d3148 !important; }
    html.dark .ap-light .ap-name { color: #e2e8f0 !important; }
    html.dark .ap-light .ap-sub { color: #64748b !important; }
    html.dark .ap-divider { background: #2d3148 !important; }
    html.dark .danger-item { background: #2a1a1a !important; border-color: #7f1d1d !important; }
    html.dark .danger-title { color: #fca5a5 !important; }
    html.dark .danger-sub { color: #f87171 !important; }
    html.dark .badge-male   { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .badge-female { background: #3b0764 !important; color: #e9d5ff !important; }
    html.dark .toast { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .toast-title { color: #e2e8f0 !important; }
    html.dark .toast-sub { color: #94a3b8 !important; }
    html.dark .upload-note { color: #64748b !important; }
    html.dark .btn-upload { background: #6366f1 !important; }
    html.dark .btn-upload:hover { background: #4f46e5 !important; }
    html.dark .btn-primary { background: #6366f1 !important; }
    html.dark .btn-primary:hover { background: #4f46e5 !important; }
    html.dark .dq-stat { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .dq-issue { border-color: #2d3148 !important; }
    html.dark .dq-issue-head { background: #181b29 !important; }
    html.dark .dq-issue-head:hover, html.dark .dq-issue.open .dq-issue-head { background: #252840 !important; }
    html.dark .dq-issue-title { color: #e2e8f0 !important; }
    html.dark .dq-issue-count { color: #64748b !important; }
    html.dark .dq-issue-body { border-top-color: #2d3148 !important; }
    html.dark .dq-desc { background: #181b29 !important; color: #94a3b8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .dq-record { border-bottom-color: #2d3148 !important; }
    html.dark .dq-record-title { color: #e2e8f0 !important; }
    html.dark .dq-record-sub { color: #64748b !important; }
    html.dark hr { border-color: #2d3148 !important; }
    html.dark .docs-empty { color: #374151 !important; }
    html.dark .lightbox { background: rgba(0,0,0,.92) !important; }

    
        /* Deceased list specific */
        html.dark .badge-male   { background: #1e3a5f !important; color: #93c5fd !important; }
        html.dark .badge-female { background: #4a044e !important; color: #f5d0fe !important; }
        html.dark .name-main { color: #e2e8f0 !important; }
        html.dark .name-sub  { color: #64748b !important; }
        html.dark .pager { border-top-color: #2d3148 !important; }
        html.dark .pager-info { color: #64748b !important; }
        html.dark .empty-state { color: #4b5563 !important; }

    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Deceased Records</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">{{ ucfirst(str_replace('_',' ', auth()->user()->getRoleNames()->first() ?? auth()->user()->role ?? 'User')) }}</span>
    </div>

    <div class="content">
        <div class="panel">
            <div class="panel-header">
                <h3>All Deceased Records
                    <span style="font-size:11px;font-weight:400;color:#9ca3af;margin-left:.5rem">{{ $deceased->total() }} total</span>
                </h3>
                <input type="text" class="search-input" placeholder="Search by name…" oninput="filterTable(this.value)">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Nationality</th>
                        <th>Date of Death</th>
                        <th>Burial Type</th>
                        <th>Permits</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="deceasedBody">
                    @forelse($deceased as $person)
                    <tr class="deceased-row">
                        <td>
                            <div class="name-main">{{ $person->last_name }}, {{ $person->first_name }}{{ $person->middle_name ? ' '.$person->middle_name : '' }}</div>
                            <div class="name-sub">Record #{{ $person->id }} · Added {{ $person->created_at->format('M d, Y') }}</div>
                        </td>
                        <td>
                            @if($person->sex)
                                <span class="badge {{ $person->sex === 'Male' ? 'badge-male' : 'badge-female' }}">{{ $person->sex }}</span>
                            @else
                                <span style="color:#d1d5db">—</span>
                            @endif
                        </td>
                        <td style="color:#6b7280">{{ $person->age ?? '—' }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->nationality ?? '—' }}</td>
                        <td style="font-size:12px;color:#6b7280">
                            {{ $person->date_of_death ? \Carbon\Carbon::parse($person->date_of_death)->format('M d, Y') : '—' }}
                        </td>
                        <td style="font-size:12px;color:#6b7280">{{ $person->kind_of_burial ?? '—' }}</td>
                        <td>
                            <span style="font-size:12px;font-weight:{{ $person->permits_count > 0 ? '600' : '400' }};color:{{ $person->permits_count > 0 ? '#1a2744' : '#d1d5db' }}">
                                {{ $person->permits_count }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('deceased.show', $person) }}" class="btn-action">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">No deceased records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($deceased->hasPages())
            <div class="pager">
                <span class="pager-info">
                    Showing {{ $deceased->firstItem() }}–{{ $deceased->lastItem() }} of {{ $deceased->total() }} results
                </span>
                <div class="pager-btns">
                    @if($deceased->onFirstPage())
                        <span class="pager-btn disabled">‹ Prev</span>
                    @else
                        <a href="{{ $deceased->previousPageUrl() }}" class="pager-btn">‹ Prev</a>
                    @endif

                    @php
                        $cur  = $deceased->currentPage();
                        $last = $deceased->lastPage();
                        $pages = [];
                        for ($p = 1; $p <= $last; $p++) {
                            if ($p == 1 || $p == $last || abs($p - $cur) <= 2) $pages[] = $p;
                        }
                        $pages = array_unique($pages); sort($pages);
                    @endphp

                    @php $prev = null; @endphp
                    @foreach($pages as $page)
                        @if($prev !== null && $page - $prev > 1)
                            <span class="pager-btn disabled" style="border:none;min-width:16px;padding:0">…</span>
                        @endif
                        @if($page == $cur)
                            <span class="pager-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $deceased->url($page) }}" class="pager-btn">{{ $page }}</a>
                        @endif
                        @php $prev = $page; @endphp
                    @endforeach

                    @if($deceased->hasMorePages())
                        <a href="{{ $deceased->nextPageUrl() }}" class="pager-btn">Next ›</a>
                    @else
                        <span class="pager-btn disabled">Next ›</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.deceased-row').forEach(row => {
        const name = row.querySelector('.name-main').textContent.toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
}
</script>
</body>
</html>