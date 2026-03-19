<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Records — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
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
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 11px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: visible; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head span { font-size: 12px; color: #9ca3af; }
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; }
        .name { font-weight: 700; color: #1a2744; font-size: 13px; }
        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-delete { color: #ef4444; border-color: #fca5a5; }
        .btn-delete:hover { background: #fee2e2; border-color: #ef4444; }
        .permit-count { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #eff6ff; color: #1e40af; font-size: 11px; font-weight: 700; border-radius: 50%; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: 13px; }
        .pagination { display: flex; gap: .4rem; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; }
        .pagination a, .pagination span { padding: 4px 10px; border-radius: 5px; font-size: 12px; border: 1px solid #e5e7eb; color: #374151; text-decoration: none; }
        .pagination .active { background: #1a2744; color: #fff; border-color: #1a2744; }
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
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="panel">
            <div class="panel-head">
    <h3>All Deceased Records</h3>
    <div style="display:flex;align-items:center;gap:.75rem">
        <form method="GET" action="{{ route('deceased.index') }}" style="display:flex;gap:.5rem">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search name..."
                style="font-family:'Inter',sans-serif;font-size:12px;padding:.38rem .75rem;border:1px solid #d1d5db;border-radius:6px;outline:none;width:200px;color:#111827"
                onfocus="this.style.borderColor='#1a2744'" onblur="this.style.borderColor='#d1d5db'">
            <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:.38rem .75rem;border-radius:6px;border:none;font-family:'Inter',sans-serif;font-size:12px;font-weight:600;color:#fff;background:#1a2744;cursor:pointer">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('deceased.index') }}" style="display:inline-flex;align-items:center;padding:.38rem .75rem;border-radius:6px;border:1px solid #e5e7eb;font-size:12px;color:#6b7280;text-decoration:none">Clear</a>
            @endif
        </form>
        <span style="font-size:12px;color:#9ca3af">{{ $deceased->total() }} total</span>
    </div>
</div>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Nationality</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Date of Death</th>
                        <th>Kind of Burial</th>
                        <th>Permits</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deceased as $person)
                    <tr>
                        <td><span class="name">{{ $person->first_name }} {{ $person->last_name }}</span></td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->nationality ?: '—' }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->age ?: '—' }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->sex ?: '—' }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->date_of_death ? $person->date_of_death->format('M d, Y') : '—' }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $person->kind_of_burial ?: '—' }}</td>
                        <td><span class="permit-count">{{ $person->permits_count }}</span></td>
                        <td style="display:flex;gap:.4rem">
                            <a href="{{ route('deceased.show', $person) }}" class="btn-action">View</a>
                            <form method="POST" action="{{ route('deceased.destroy', $person) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:2rem">No deceased records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($deceased->hasPages())
            <div class="pagination">
                {{ $deceased->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
     
<div id="autocomplete" style="display:none;position:absolute;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;min-width:280px;overflow:hidden"></div>

<script>
const searchInput = document.querySelector('input[name="search"]');
const dropdown = document.getElementById('autocomplete');
const tableBody = document.querySelector('tbody');

function positionDropdown() {
    const rect = searchInput.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
    dropdown.style.left = rect.left + 'px';
    dropdown.style.width = rect.width + 'px';
}

let debounce;
searchInput.addEventListener('input', function () {
    clearTimeout(debounce);
    const q = this.value.trim();

    if (q.length < 2) {
        dropdown.style.display = 'none';
        // Show all rows again
        tableBody.querySelectorAll('tr').forEach(r => r.style.display = '');
        return;
    }

    debounce = setTimeout(() => {
        fetch('/deceased/search?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                // Update dropdown
                if (data.length) {
                    dropdown.innerHTML = data.map(p => `
                        <div onclick="selectName('${p.name}')"
                             style="padding:.6rem 1rem;font-size:13px;cursor:pointer;color:#111827;border-bottom:1px solid #f3f4f6"
                             onmouseover="this.style.background='#f8faff'"
                             onmouseout="this.style.background='#fff'">
                            <span style="font-weight:600">${p.name}</span>
                            <span style="font-size:11px;color:#9ca3af;margin-left:.5rem">${p.date_of_death ?? ''}</span>
                        </div>`).join('');
                    positionDropdown();
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }

                // Filter table rows live
                const qLower = q.toLowerCase();
tableBody.querySelectorAll('tr').forEach(row => {
    const nameCell = row.querySelector('.name');
    if (!nameCell) { row.style.display = ''; return; }
    const rowName = nameCell.textContent.trim().toLowerCase();
    row.style.display = rowName.includes(qLower) ? '' : 'none';
});

            })
            .catch(() => dropdown.style.display = 'none');
    }, 250);
});

function selectName(name) {
    searchInput.value = name;
    dropdown.style.display = 'none';
    searchInput.closest('form').submit();
}

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>
</body>
</html>