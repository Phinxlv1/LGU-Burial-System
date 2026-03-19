<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel — LGU Carmen</title>
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
        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head span { font-size: 12px; color: #9ca3af; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; padding: .75rem 1rem; font-size: 13px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 6px; padding: .75rem 1rem; font-size: 13px; }
        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .55rem 1.25rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: #fff; background: #1a2744; cursor: pointer; transition: background .15s; }
        .btn-primary:hover { background: #243459; }
        .drop-zone { border: 2px dashed #d1d5db; border-radius: 8px; padding: 3rem 2rem; text-align: center; transition: border-color .15s, background .15s; cursor: pointer; }
        .drop-zone:hover, .drop-zone.dragover { border-color: #1a2744; background: #f8faff; }
        .drop-zone input[type=file] { display: none; }
        .drop-zone-icon { width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto .85rem; }
        .drop-zone-text { font-size: 14px; font-weight: 600; color: #374151; }
        .drop-zone-sub { font-size: 12px; color: #9ca3af; margin-top: .3rem; }
        .file-selected { font-size: 12px; color: #065f46; font-weight: 600; margin-top: .6rem; }

        /* History table */
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 12px; color: #374151; padding: .6rem .75rem; border-top: 1px solid #f3f4f6; }
        .badge { display: inline-flex; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-amber { background: #fef3c7; color: #92400e; }

        /* Skipped details toggle */
        .skipped-list { font-size: 11px; color: #6b7280; margin-top: .3rem; display: none; }
        .toggle-skipped { font-size: 11px; color: #3b82f6; cursor: pointer; text-decoration: underline; background: none; border: none; font-family: inherit; padding: 0; }
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

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        {{-- Upload Panel --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Import Permits from Excel</h3>
            </div>
            <div style="padding:1.5rem">
                <form method="POST" action="{{ route('import.excel') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" onchange="showFile(this)">
                        <div class="drop-zone-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div class="drop-zone-text">Click to upload or drag & drop</div>
                        <div class="drop-zone-sub">.xlsx, .xls, or .csv — max 10MB</div>
                        <div class="file-selected" id="fileName" style="display:none"></div>
                    </div>
                    <div style="margin-top:1rem;display:flex;align-items:center;justify-content:space-between">
                        <span style="font-size:12px;color:#9ca3af">Rows missing first_name, last_name, or date_of_death will be skipped automatically.</span>
                        <button type="submit" class="btn-primary" id="importBtn" disabled style="opacity:.5;cursor:not-allowed">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Import Permits
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Upload History --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Upload History</h3>
                <span>{{ $logs->total() }} uploads</span>
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
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-weight:600;color:#1a2744">{{ $log->filename }}</td>
                        <td>{{ optional($log->importedBy)->name ?? 'Admin' }}</td>
                        <td style="color:#6b7280">{{ $log->created_at->format('M d, Y · g:i A') }}</td>
                        <td style="color:#6b7280">{{ $log->total_rows }}</td>
                        <td><span class="badge badge-green">{{ $log->imported }} imported</span></td>
                        <td>
                            @if($log->skipped > 0)
                                <span class="badge badge-amber">{{ $log->skipped }} skipped</span>
                                @if($log->skipped_details && count($log->skipped_details) > 0)
                                    <br>
                                    <button class="toggle-skipped" onclick="toggleSkipped({{ $log->id }})">show reasons</button>
                                    <div class="skipped-list" id="skipped-{{ $log->id }}">
                                        @foreach($log->skipped_details as $detail)
                                            <div>· {{ $detail }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <span style="color:#9ca3af;font-size:12px">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:2rem">No uploads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($logs->hasPages())
            <div style="padding:.75rem 1.25rem;border-top:1px solid #f3f4f6">{{ $logs->links() }}</div>
            @endif
        </div>

    </div>
</div>

<script>
function showFile(input) {
    const fileName = document.getElementById('fileName');
    const importBtn = document.getElementById('importBtn');
    if (input.files && input.files[0]) {
        fileName.textContent = '✓ ' + input.files[0].name;
        fileName.style.display = 'block';
        importBtn.disabled = false;
        importBtn.style.opacity = '1';
        importBtn.style.cursor = 'pointer';
    }
}
function toggleSkipped(id) {
    const el = document.getElementById('skipped-' + id);
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
}
const dropZone = document.getElementById('dropZone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('fileInput').files = e.dataTransfer.files;
        showFile(document.getElementById('fileInput'));
    }
});
</script>

</body>
</html>