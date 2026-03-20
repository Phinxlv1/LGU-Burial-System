<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date  { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* Upload card */
        .upload-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.25rem 1.5rem; }
        .upload-card h3 { font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 1rem; }

        .dropzone { border: 2px dashed #d1d5db; border-radius: 8px; padding: 2.5rem 1rem; text-align: center; cursor: pointer; transition: border-color .15s, background .15s; position: relative; }
        .dropzone:hover, .dropzone.drag-over { border-color: #1a2744; background: #f8faff; }
        .dropzone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .dropzone-icon { width: 44px; height: 44px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem; }
        .dropzone-title { font-size: 14px; font-weight: 500; color: #111827; }
        .dropzone-sub   { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        .dropzone-file  { font-size: 13px; font-weight: 600; color: #1a2744; margin-top: .5rem; display: none; }

        .upload-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; flex-wrap: wrap; gap: .5rem; }
        .upload-note   { font-size: 12px; color: #9ca3af; }
        .btn-upload { display: inline-flex; align-items: center; gap: 6px; padding: .55rem 1.1rem; background: #1a2744; color: #fff; border: none; border-radius: 7px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; transition: background .15s; }
        .btn-upload:hover { background: #243459; }
        .btn-upload:disabled { opacity: .5; cursor: not-allowed; }

        /* History table */
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 600; color: #111827; }
        .panel-head-sub { font-size: 12px; color: #9ca3af; }

        table { width: 100%; border-collapse: collapse; }
        th { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: top; }

        .badge-green  { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 4px; background: #d1fae5; color: #065f46; }
        .badge-yellow { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 4px; background: #fef3c7; color: #92400e; }
        .badge-red    { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 4px; background: #fee2e2; color: #991b1b; }

        .reasons-toggle { font-size: 11px; color: #1a2744; cursor: pointer; text-decoration: underline; display: block; margin-top: 3px; }
        .reasons-list { font-size: 11px; color: #6b7280; margin-top: 4px; display: none; list-style: none; }
        .reasons-list li { padding: 1px 0; }
        .reasons-list.open { display: block; }
        .empty-row td { text-align: center; color: #9ca3af; padding: 2rem; }

        /* ── TOAST NOTIFICATIONS (top-left) ── */
        .toast-stack {
            position: fixed;
            top: 1rem;
            left: calc(220px + 1rem); /* sidebar width + gap */
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            pointer-events: none;
        }
        .toast {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .75rem 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,.12);
            min-width: 280px;
            max-width: 420px;
            pointer-events: auto;
            transform: translateX(-120%);
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
            position: relative;
            overflow: hidden;
        }
        .toast.show { transform: translateX(0); }

        .toast-green  { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .toast-yellow { background: #fffbeb; border: 1px solid #fde68a; }
        .toast-red    { background: #fef2f2; border: 1px solid #fecaca; }

        .toast-icon {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .toast-green  .toast-icon { background: #dcfce7; }
        .toast-yellow .toast-icon { background: #fef9c3; }
        .toast-red    .toast-icon { background: #fee2e2; }

        .toast-content { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 600; }
        .toast-green  .toast-title { color: #15803d; }
        .toast-yellow .toast-title { color: #92400e; }
        .toast-red    .toast-title { color: #991b1b; }
        .toast-msg  { font-size: 12px; margin-top: 2px; color: #374151; }

        .toast-close {
            background: none; border: none; cursor: pointer;
            font-size: 16px; line-height: 1; color: #9ca3af;
            padding: 0; flex-shrink: 0; transition: color .15s;
        }
        .toast-close:hover { color: #374151; }

        .toast-bar {
            position: absolute; bottom: 0; left: 0;
            height: 3px; width: 100%;
            transform-origin: left;
            animation: drainBar 6s linear forwards;
        }
        .toast-green  .toast-bar { background: #22c55e; }
        .toast-yellow .toast-bar { background: #f59e0b; }
        .toast-red    .toast-bar { background: #ef4444; }
        @keyframes drainBar { from { transform: scaleX(1); } to { transform: scaleX(0); } }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Import Excel</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        {{-- Upload Card --}}
        <div class="upload-card">
            <h3>Import Permits from Excel</h3>
            <form method="POST" action="{{ route('import.excel') }}" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="dropzone" id="dropzone"
                     ondragover="event.preventDefault();this.classList.add('drag-over')"
                     ondragleave="this.classList.remove('drag-over')"
                     ondrop="handleDrop(event)">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" onchange="handleFile(this)">
                    <div class="dropzone-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <div class="dropzone-title">Click to upload or drag &amp; drop</div>
                    <div class="dropzone-sub">.xlsx, .xls, or .csv — max 10MB</div>
                    <div class="dropzone-file" id="fileName"></div>
                </div>
                <div class="upload-footer">
                    <span class="upload-note">Rows missing first_name, last_name, or date_of_death will be skipped automatically.</span>
                    <button type="submit" class="btn-upload" id="submitBtn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Import Permits
                    </button>
                </div>
            </form>
        </div>

        {{-- Upload History — last 5 only --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Upload History</h3>
                <span class="panel-head-sub" id="historyCount">{{ $logs->total() }} total uploads</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Total Rows</th>
                        <th>Imported</th>
                        <th>Skipped</th>
                    </tr>
                </thead>
                <tbody id="historyTbody">
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-weight:500;color:#1a2744;max-width:200px;word-break:break-all">
                            {{ $log->file_name }}
                        </td>
                        <td style="color:#6b7280">{{ optional($log->user)->name ?? 'Admin' }}</td>
                        <td style="color:#6b7280;white-space:nowrap">{{ $log->created_at->format('M d, Y · g:i A') }}</td>
                        <td style="text-align:center">{{ $log->total_rows }}</td>
                        <td style="text-align:center">
                            @if($log->imported > 0)
                                <span class="badge-green">{{ $log->imported }} imported</span>
                            @else
                                <span class="badge-red">0 imported</span>
                            @endif
                        </td>
                        <td>
                            @if($log->skipped > 0)
                                <span class="badge-yellow">{{ $log->skipped }} skipped</span>
                                @php
                                    $reasons = is_array($log->skip_reasons)
                                        ? $log->skip_reasons
                                        : json_decode($log->skip_reasons ?? '[]', true);
                                @endphp
                                @if(!empty($reasons))
                                    <span class="reasons-toggle" onclick="toggleReasons(this)">show reasons</span>
                                    <ul class="reasons-list">
                                        @foreach($reasons as $r)
                                            <li>· {{ $r }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                <span style="color:#d1d5db;font-size:12px">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="6">No uploads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ── TOAST STACK (top-left, after sidebar) ── --}}
<div class="toast-stack" id="toastStack"></div>

<script>
// ── File picker helpers ──
function handleFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('fileName').style.display = 'block';
    document.getElementById('fileName').textContent   = '📎 ' + file.name;
    document.getElementById('submitBtn').disabled     = false;
}
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropzone').classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = document.getElementById('fileInput');
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    handleFile(input);
}
function toggleReasons(el) {
    const list = el.nextElementSibling;
    list.classList.toggle('open');
    el.textContent = list.classList.contains('open') ? 'hide reasons' : 'show reasons';
}
document.getElementById('importForm').addEventListener('submit', function() {
    document.getElementById('submitBtn').disabled    = true;
    document.getElementById('submitBtn').textContent = 'Importing…';
});

// ── Toast system ──
function showToast(type, title, message, duration = 6000) {
    // type: 'green' | 'yellow' | 'red'
    const icons = {
        green:  `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`,
        yellow: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
        red:    `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
    };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-icon">${icons[type]}</div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            ${message ? `<div class="toast-msg">${message}</div>` : ''}
        </div>
        <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">×</button>
        <div class="toast-bar"></div>
    `;

    document.getElementById('toastStack').appendChild(toast);
    requestAnimationFrame(() => setTimeout(() => toast.classList.add('show'), 30));

    const timer = setTimeout(() => dismissToast(toast), duration);
    toast._timer = timer;
}

function dismissToast(toast) {
    if (!toast) return;
    clearTimeout(toast._timer);
    toast.classList.remove('show');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

// ── Fire toasts from server session data ──
window.addEventListener('DOMContentLoaded', () => {

    @if(session('import_success'))
        @php
            $imported = (int) session('_import_imported', 0);
            $skipped  = (int) session('_import_skipped', 0);
            $reasons  = session('skip_reasons', []);
            $preview  = collect($reasons)->take(3)->implode(' | ');
            $extra    = count($reasons) > 3 ? ' …+' . (count($reasons) - 3) . ' more' : '';
        @endphp

        @if($skipped > 0)
            {{-- YELLOW: any rows were skipped — partial or total failure --}}
            @if($imported > 0)
                {{-- Some got in, some didn't --}}
                showToast('yellow',
                    '{{ $imported }} Imported, {{ $skipped }} Skipped',
                    '{{ addslashes($preview . $extra) }}',
                    9000);
            @else
                {{-- Nothing imported, everything skipped --}}
                showToast('yellow',
                    '{{ $skipped }} Row(s) Skipped — Nothing Imported',
                    '{{ addslashes($preview . $extra) }}',
                    9000);
            @endif

        @elseif($imported > 0)
            {{-- GREEN: all rows imported, zero skips --}}
            showToast('green',
                '{{ $imported }} Permit(s) Imported Successfully',
                'All rows were valid and saved to the database.');

        @else
            {{-- RED: no rows at all (empty file after header) --}}
            showToast('red', 'Nothing Imported', 'No valid rows found. Check file format.');
        @endif

    @endif

    {{-- Red: only ONE toast (import_error takes priority) --}}
    @if(session('import_error'))
        showToast('red', 'Import Failed', '{{ addslashes(session("import_error")) }}');
    @elseif($errors->has('file'))
        showToast('red', 'Invalid File', '{{ addslashes($errors->first("file")) }}');
    @endif
});

// ── Live-refresh history table every 4 seconds ──
function refreshHistory() {
    fetch('/import/history-json', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data) return;

            // Update count badge
            const countEl = document.getElementById('historyCount');
            if (countEl) countEl.textContent = data.total + ' total uploads';

            // Rebuild tbody
            const tbody = document.getElementById('historyTbody');
            if (!tbody) return;

            if (data.rows.length === 0) {
                tbody.innerHTML = '<tr class="empty-row"><td colspan="6">No uploads yet.</td></tr>';
                return;
            }

            tbody.innerHTML = data.rows.map(log => {
                const importedBadge = log.imported > 0
                    ? `<span class="badge-green">${log.imported} imported</span>`
                    : `<span class="badge-red">0 imported</span>`;

                let skippedCell = '<span style="color:#d1d5db;font-size:12px">—</span>';
                if (log.skipped > 0) {
                    const rid = 'r_' + log.id;
                    const reasons = log.skip_reasons || [];
                    const reasonsHtml = reasons.length
                        ? `<span class="reasons-toggle" onclick="toggleReasons(this)">show reasons</span>
                           <ul class="reasons-list" id="${rid}">
                             ${reasons.map(r => `<li>· ${r}</li>`).join('')}
                           </ul>`
                        : '';
                    skippedCell = `<span class="badge-yellow">${log.skipped} skipped</span>${reasonsHtml}`;
                }

                return `<tr>
                    <td style="font-weight:500;color:#1a2744;max-width:200px;word-break:break-all">${log.file_name}</td>
                    <td style="color:#6b7280">${log.uploaded_by}</td>
                    <td style="color:#6b7280;white-space:nowrap">${log.date}</td>
                    <td style="text-align:center">${log.total_rows}</td>
                    <td style="text-align:center">${importedBadge}</td>
                    <td>${skippedCell}</td>
                </tr>`;
            }).join('');
        })
        .catch(() => {});
}

// Poll every 4 seconds
setInterval(refreshHistory, 4000);

</script>

</body>
</html>