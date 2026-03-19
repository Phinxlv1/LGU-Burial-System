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

        .dropzone {
            border: 2px dashed #d1d5db; border-radius: 8px;
            padding: 2.5rem 1rem; text-align: center;
            cursor: pointer; transition: border-color .15s, background .15s;
            position: relative;
        }
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

        /* Alerts */
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 7px; padding: .75rem 1rem; font-size: 13px; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 7px; padding: .75rem 1rem; font-size: 13px; }
        .skip-list { margin-top: .5rem; font-size: 12px; max-height: 120px; overflow-y: auto; list-style: none; }
        .skip-list li { padding: 2px 0; border-top: 1px solid rgba(0,0,0,.06); }

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

        .empty-row td { text-align: center; color: #9ca3af; padding: 2.5rem; }
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

        {{-- Alerts --}}
        @if(session('import_success'))
        <div class="alert-success">
            {{ session('import_success') }}
            @if(session('skip_reasons') && count(session('skip_reasons')) > 0)
                <ul class="skip-list">
                    @foreach(session('skip_reasons') as $r)
                        <li>· {{ $r }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        @endif

        @if($errors->has('file'))
        <div class="alert-error">{{ $errors->first('file') }}</div>
        @endif

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

        {{-- Upload History --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Upload History</h3>
                <span class="panel-head-sub">{{ $logs->total() }} uploads</span>
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
                        <td style="font-weight:500;color:#1a2744;max-width:220px;word-break:break-all">{{ $log->file_name }}</td>
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
                                    $reasons = is_string($log->skip_reasons)
                                        ? json_decode($log->skip_reasons, true)
                                        : ($log->skip_reasons ?? []);
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

<script>
function handleFile(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('fileName').style.display = 'block';
    document.getElementById('fileName').textContent = '📎 ' + file.name;
    document.getElementById('submitBtn').disabled = false;
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
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').textContent = 'Importing…';
});
</script>

</body>
</html>