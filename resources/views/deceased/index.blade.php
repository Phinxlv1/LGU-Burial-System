<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Records — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p  { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav      { flex: 1; padding: .75rem 0; }
        .nav-section      { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item         { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px; color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px; margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover   { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active  { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg     { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer   { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info        { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem; background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar      { width: 28px; height: 28px; background: rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
        .user-name        { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role        { font-size: 10px; color: rgba(255,255,255,.4); }
        .btn-logout       { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

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
        .badge-male { background: #dbeafe; color: #1e40af; }
        .badge-female { background: #fce7f3; color: #9d174d; }

        .btn-action  { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        /* Custom pagination — no SVG arrows */
        .pager { display: flex; align-items: center; justify-content: space-between; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; gap: .5rem; }
        .pager-info { font-size: 12px; color: #6b7280; }
        .pager-btns { display: flex; align-items: center; gap: 3px; }
        .pager-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; text-decoration: none; background: #fff; cursor: pointer; transition: border-color .15s, color .15s; white-space: nowrap; line-height: 1; }
        .pager-btn:hover { border-color: #1a2744; color: #1a2744; }
        .pager-btn.active { background: #1a2744; color: #fff; border-color: #1a2744; font-weight: 600; cursor: default; }
        .pager-btn.disabled { color: #d1d5db; cursor: not-allowed; pointer-events: none; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: 13px; }
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
        <a href="{{ route('permits.index') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="nav-item active">
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
        <a href="{{ route('import.show') }}" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import Excel
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(str_replace('_',' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}</div>
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
        <div>
            <div class="topbar-title">Deceased Records</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">{{ ucfirst(str_replace('_',' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}</span>
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