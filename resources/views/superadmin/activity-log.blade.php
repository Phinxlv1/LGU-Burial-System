<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sa-sidebar.css') }}">
    <style>
        :root {
            --navy:       #0f1e3d;
            --navy-mid:   #1a2f5e;
            --navy-light: #243459;
            --accent:     #3b82f6;
            --accent-bg:  #eff6ff;
            --red:        #ef4444;
            --amber:      #f59e0b;
            --green:      #10b981;
            --violet:     #8b5cf6;
            --cyan:       #06b6d4;
            --teal:       #14b8a6;
            --surface:    #ffffff;
            --surface-2:  #f8fafc;
            --border:     #e2e8f0;
            --border-2:   #f1f5f9;
            --text-1:     #0f172a;
            --text-2:     #475569;
            --text-3:     #94a3b8;
            --mono:       'DM Mono', monospace;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--text-1);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-left { display: flex; align-items: center; gap: .75rem; }
        .topbar-title { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .topbar-date  { font-size: 11px; color: var(--text-3); font-family: var(--mono); }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        .role-pill {
            font-family: var(--mono);
            font-size: 10px; font-weight: 500;
            color: #7c3aed;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            padding: 3px 10px; border-radius: 20px;
            letter-spacing: .06em; text-transform: uppercase;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: .38rem .85rem; border-radius: 7px;
            font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 500;
            color: var(--text-2); text-decoration: none;
            border: 1px solid var(--border);
            background: var(--surface);
            transition: all .15s;
        }
        .back-btn:hover { background: var(--surface-2); color: var(--text-1); border-color: #cbd5e1; }

        /* ── CONTENT ── */
        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── PAGE HEADER ── */
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
        .page-header h2 { font-size: 18px; font-weight: 700; color: var(--text-1); letter-spacing: -.02em; }
        .page-header p  { font-size: 12px; color: var(--text-3); margin-top: 3px; font-family: var(--mono); }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .65rem 1rem;
            display: flex; align-items: center; gap: .5rem; flex-wrap: wrap;
        }
        .filter-label {
            font-size: 10.5px; font-weight: 600; color: var(--text-3);
            text-transform: uppercase; letter-spacing: .07em; margin-right: .25rem;
        }
        .filter-btn {
            padding: .28rem .75rem; border-radius: 6px;
            font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 500;
            cursor: pointer; border: 1px solid var(--border);
            background: transparent; color: var(--text-2);
            transition: all .15s;
        }
        .filter-btn:hover  { background: var(--surface-2); color: var(--text-1); }
        .filter-btn.active { background: var(--accent-bg); border-color: #bfdbfe; color: var(--accent); font-weight: 600; }

        /* ── PANEL ── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .panel-head {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid var(--border-2);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 13px; font-weight: 600; color: var(--text-1); }
        .panel-count {
            font-size: 11px; font-family: var(--mono); color: var(--text-3);
            background: var(--surface-2); border: 1px solid var(--border);
            padding: 2px 8px; border-radius: 20px;
        }

        /* ── TABLE ── */
        .log-table { width: 100%; border-collapse: collapse; }

        .log-table thead tr {
            border-bottom: 1px solid var(--border-2);
        }
        .log-table thead th {
            padding: .6rem 1.25rem;
            font-size: 10.5px; font-weight: 700;
            color: var(--text-3); text-transform: uppercase; letter-spacing: .07em;
            text-align: left; background: var(--surface-2);
            white-space: nowrap;
        }
        .log-table thead th:last-child { text-align: right; }

        .log-table tbody tr {
            border-bottom: 1px solid var(--border-2);
            transition: background .12s;
        }
        .log-table tbody tr:last-child { border-bottom: none; }
        .log-table tbody tr:hover { background: #f8fafc; }

        .log-table tbody td {
            padding: .75rem 1.25rem;
            font-size: 12.5px;
            color: var(--text-2);
            vertical-align: middle;
        }

        /* col: icon+action */
        .td-action { display: flex; align-items: center; gap: .6rem; white-space: nowrap; }

        .action-dot {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; flex-shrink: 0;
        }
        .dot-green  { background: #dcfce7; color: #16a34a; }
        .dot-indigo { background: #e0e7ff; color: #4338ca; }
        .dot-teal   { background: #ccfbf1; color: #0d9488; }
        .dot-red    { background: #fee2e2; color: #dc2626; }
        .dot-gray   { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }
        .dot-amber  { background: #fef3c7; color: #d97706; }
        .dot-violet { background: #ede9fe; color: #7c3aed; }
        .dot-blue   { background: #dbeafe; color: #1d4ed8; }
        .dot-red    { background: #fee2e2; color: #dc2626; }
        .dot-amber  { background: #fef3c7; color: #d97706; }
        .dot-violet { background: #ede9fe; color: #7c3aed; }

        /* action badge */
        .badge {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 9.5px; font-weight: 700;
            padding: 2px 7px; border-radius: 5px;
            text-transform: uppercase; letter-spacing: .05em;
            font-family: var(--mono);
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-indigo { background: #e0e7ff; color: #4338ca; }
        .badge-teal   { background: #ccfbf1; color: #0d9488; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-gray   { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }
        .badge-amber  { background: #fef3c7; color: #d97706; }
        .badge-violet { background: #ede9fe; color: #7c3aed; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-amber  { background: #fef3c7; color: #d97706; }
        .badge-violet { background: #ede9fe; color: #7c3aed; }

        /* col: description */
        .td-desc { max-width: 340px; }
        .td-desc-text {
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            color: var(--text-1); font-size: 13px;
        }
        .td-desc-text strong { font-weight: 600; }
        .permit-code {
            display: inline-flex; align-items: center;
            font-size: 10.5px; font-weight: 600; color: var(--accent);
            font-family: var(--mono);
            background: var(--accent-bg);
            border: 1px solid #bfdbfe;
            border-radius: 4px; padding: 1px 5px; margin-left: 4px;
        }

        /* col: user */
        .td-user { white-space: nowrap; }
        .user-chip {
            display: inline-flex; align-items: center; gap: 5px;
        }
        .user-avatar {
            width: 22px; height: 22px; border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff; font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-name { font-size: 12px; color: var(--text-2); font-weight: 500; }

        /* col: time */
        .td-time { text-align: right; white-space: nowrap; }
        .time-ago  { font-size: 12px; color: var(--text-2); font-family: var(--mono); }
        .time-full { font-size: 10.5px; color: var(--text-3); margin-top: 2px; font-family: var(--mono); }

        /* ── EMPTY STATE ── */
        .empty-state {
            padding: 3.5rem 1.5rem;
            text-align: center;
            color: var(--text-3);
            font-size: 13px;
        }
        .empty-state svg { opacity: .3; margin: 0 auto .75rem; display: block; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: .85rem 1.25rem;
            border-top: 1px solid var(--border-2);
            display: flex; align-items: center; justify-content: space-between;
        }
        .pagination-info { font-size: 11.5px; color: var(--text-3); font-family: var(--mono); }
        .pagination-links { display: flex; gap: .3rem; }
        .pagination-links a,
        .pagination-links span {
            padding: .3rem .65rem; border-radius: 6px;
            font-size: 12px; font-weight: 500; text-decoration: none;
            border: 1px solid var(--border);
            color: var(--text-2);
            transition: all .15s;
        }
        .pagination-links a:hover { background: var(--surface-2); color: var(--text-1); }
        .pagination-links span.current {
            background: var(--accent-bg); border-color: #bfdbfe; color: var(--accent); font-weight: 600;
        }
        .pagination-links span.disabled {
            opacity: .4; cursor: not-allowed;
        }

        /* ── ANIMATIONS ── */
        .fade-up { opacity: 0; transform: translateY(10px); animation: fadeUp .35s cubic-bezier(.4,0,.2,1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: none; } }
        .d1 { animation-delay: .04s; }
        .d2 { animation-delay: .10s; }
        .d3 { animation-delay: .16s; }
    </style>
</head>
<body>

@include('superadmin.partials.sidebar')

<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('superadmin.dashboard') }}" class="back-btn">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </a>
            <div>
                <div class="topbar-title">Activity Log</div>
                <div class="topbar-date">{{ now()->format('D, d M Y · H:i') }}</div>
            </div>
        </div>
        <div class="topbar-right">
            <span class="role-pill">⚡ Super Admin</span>
        </div>
    </div>

    <div class="content">

        {{-- PAGE HEADER --}}
        <div class="page-header fade-up d1">
            <div>
                <h2>Full Activity Log</h2>
                <p>MUNICIPALITY OF CARMEN · DAVAO DEL NORTE · ALL SYSTEM EVENTS</p>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="filter-bar fade-up d2">
            <span class="filter-label">Filter</span>
            <button class="filter-btn active" onclick="filterTable('all', this)">All</button>
            <button class="filter-btn" onclick="filterTable('created', this)">Created</button>
            <button class="filter-btn" onclick="filterTable('approved', this)">Approved</button>
            <button class="filter-btn" onclick="filterTable('released', this)">Released</button>
            <button class="filter-btn" onclick="filterTable('renewed', this)">Renewed</button>
            <button class="filter-btn" onclick="filterTable('imported', this)">Imported</button>
            <button class="filter-btn" onclick="filterTable('deleted', this)">Deleted</button>
            <button class="filter-btn" onclick="filterTable('updated', this)">Updated</button>
        </div>

        {{-- LOG TABLE PANEL --}}
        <div class="panel fade-up d3">
            <div class="panel-head">
                <div class="panel-title">All Events</div>
                <span class="panel-count">{{ count($activity) }} records</span>
            </div>

            @if(count($activity) > 0)
            <table class="log-table" id="logTable">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Performed By</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity as $log)
                    @php
                        $action      = $log->action      ?? 'updated';
                        $description = $log->description ?? '—';
                        $modelLabel  = $log->model_label  ?? null;
                        $userName    = $log->user?->name  ?? 'System';
                        $createdAt   = $log->created_at;

                        $dotClass = match($action) {
                            'created'  => 'dot-green',
                            'approved' => 'dot-indigo',
                            'released' => 'dot-teal',
                            'expired'  => 'dot-red',
                            'renewed'  => 'dot-amber',
                            'imported' => 'dot-violet',
                            'updated'  => 'dot-blue',
                            'deleted'  => 'dot-red',
                            default    => 'dot-gray',
                        };
                        $icon = match($action) {
                            'created'  => '+',
                            'approved' => '✓',
                            'released' => '↑',
                            'expired'  => '!',
                            'renewed'  => '↻',
                            'imported' => '⇩',
                            'updated'  => '✎',
                            'deleted'  => '✕',
                            default    => '•',
                        };
                        $badgeClass = match($action) {
                            'created'  => 'badge-green',
                            'approved' => 'badge-indigo',
                            'released' => 'badge-teal',
                            'expired'  => 'badge-red',
                            'renewed'  => 'badge-amber',
                            'imported' => 'badge-violet',
                            'updated'  => 'badge-blue',
                            'deleted'  => 'badge-red',
                            default    => 'badge-gray',
                        };
                    @endphp
                    <tr data-action="{{ $action }}">
                        {{-- Action --}}
                        <td>
                            <div class="td-action">
                                <div class="action-dot {{ $dotClass }}">{{ $icon }}</div>
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($action) }}</span>
                            </div>
                        </td>

                        {{-- Description --}}
                        <td class="td-desc">
                            <div class="td-desc-text">
                                {{ $description }}
                                @if($modelLabel)
                                    — <strong>{{ $modelLabel }}</strong>
                                @endif
                            </div>
                        </td>

                        {{-- User --}}
                        <td class="td-user">
                            <div class="user-chip">
                                <div class="user-avatar">{{ strtoupper(substr($userName, 0, 1)) }}</div>
                                <span class="user-name">{{ $userName }}</span>
                            </div>
                        </td>

                        {{-- Timestamp --}}
                        <td class="td-time">
                            <div class="time-ago">{{ \Carbon\Carbon::parse($createdAt)->diffForHumans() }}</div>
                            <div class="time-full">{{ \Carbon\Carbon::parse($createdAt)->format('d M Y, H:i') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
                No activity recorded yet.
            </div>
            @endif

        </div>{{-- /panel --}}

    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
function filterTable(action, btn) {
    // update button states
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // filter rows
    document.querySelectorAll('#logTable tbody tr').forEach(row => {
        if (action === 'all' || row.dataset.action === action) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // update count
    const visible = action === 'all'
        ? document.querySelectorAll('#logTable tbody tr').length
        : document.querySelectorAll(`#logTable tbody tr[data-action="${action}"]`).length;
    document.querySelector('.panel-count').textContent = visible + ' records';
}
</script>

</body>
</html>