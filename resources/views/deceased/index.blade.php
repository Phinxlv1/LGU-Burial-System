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

        /* Sidebar */
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

        /* Main */
        .main        { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar      { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title{ font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }
        .role-tag    { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content     { padding: 1.5rem; }

        /* Panel */
        .panel        { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .panel-header { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #111827; }

        /* Search */
        .search-input { font-family: 'Inter', sans-serif; font-size: 13px; padding: .38rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; width: 220px; color: #111827; }
        .search-input:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.07); }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        th    { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; background: #fafafa; white-space: nowrap; }
        td    { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; }

        .name-main   { font-weight: 600; color: #111827; }
        .name-sub    { font-size: 11px; color: #9ca3af; margin-top: 1px; }
        .badge       { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
        .badge-male  { background: #dbeafe; color: #1e40af; }
        .badge-female{ background: #fce7f3; color: #9d174d; }

        .btn-action  { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>

<aside class="sidebar">
    @include('partials.sidebar')
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Deceased Records</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">
            {{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}
        </span>
    </div>

    <div class="content">

        <div class="panel">
            <div class="panel-header">
                <h3>All Deceased Records
                    <span style="font-size:11px;font-weight:400;color:#9ca3af;margin-left:.5rem">{{ $deceased->total() }} total</span>
                </h3>
                <input
                    type="text"
                    class="search-input"
                    placeholder="Search by name…"
                    oninput="filterTable(this.value)"
                >
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
            <div style="padding:.75rem 1.25rem;border-top:1px solid #f3f4f6">
                {{ $deceased->links() }}
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