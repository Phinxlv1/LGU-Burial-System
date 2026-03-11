<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #1a2744;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand-top {
            display: flex; align-items: center; gap: 8px; margin-bottom: .4rem;
        }

        .sidebar-seal { width: 28px; height: 28px; flex-shrink: 0; }

        .sidebar-brand h1 {
            font-size: 12px; font-weight: 600;
            color: #fff; line-height: 1.3;
        }

        .sidebar-brand p {
            font-size: 10px; color: rgba(255,255,255,.4);
            margin-top: 2px; padding-left: 36px;
        }

        .sidebar-nav { flex: 1; padding: .75rem 0; }

        .nav-section {
            font-size: 9px; font-weight: 600;
            letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.3);
            padding: .75rem 1rem .3rem;
        }

        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: .55rem 1rem;
            font-size: 13px; color: rgba(255,255,255,.65);
            text-decoration: none;
            border-radius: 6px;
            margin: 1px .5rem;
            transition: background .15s, color .15s;
            cursor: pointer;
        }

        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }

        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            padding: .75rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .user-info {
            display: flex; align-items: center; gap: 8px;
            padding: .5rem .75rem;
            background: rgba(255,255,255,.06);
            border-radius: 6px;
            margin-bottom: .5rem;
        }

        .user-avatar {
            width: 28px; height: 28px;
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.4); }

        .btn-logout {
            width: 100%;
            background: none;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 6px;
            padding: .45rem;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: rgba(255,255,255,.5);
            cursor: pointer;
            transition: all .15s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }

        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 40;
        }

        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }

        .role-tag {
            background: #1a2744; color: #fff;
            font-size: 10px; font-weight: 600;
            padding: 3px 8px; border-radius: 4px;
            letter-spacing: .04em; text-transform: uppercase;
        }

        /* Content */
        .content { padding: 1.5rem; }

        /* Panel */
        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .panel-header {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; justify-content: space-between;
        }

        .panel-header h3 { font-size: 13px; font-weight: 600; color: #111827; }
        .panel-header span { font-size: 11px; color: #9ca3af; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        th {
            font-size: 11px; font-weight: 500; color: #9ca3af;
            text-transform: uppercase; letter-spacing: .06em;
            padding: .5rem .75rem; text-align: left;
            background: #fafafa;
        }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; }

        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 500;
            padding: 2px 8px; border-radius: 4px;
        }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        .permit-no { font-weight: 600; color: #1a2744; font-size: 12px; }

        /* Action buttons */
        .btn-action {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif; font-size: 12px; color: #374151;
            background: #fff; cursor: pointer; text-decoration: none;
            transition: all .15s;
        }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 5px;
            padding: .55rem 1rem; border-radius: 6px; border: none;
            font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500;
            color: #fff; background: #1a2744; cursor: pointer; text-decoration: none;
            transition: background .15s;
        }
        .btn-primary:hover { background: #243459; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-top">
            <svg class="sidebar-seal" viewBox="0 0 28 28" fill="none">
                <circle cx="14" cy="14" r="13" stroke="rgba(255,255,255,.5)" stroke-width="1"/>
                <circle cx="14" cy="14" r="8" stroke="rgba(255,255,255,.3)" stroke-width=".8"/>
                <circle cx="14" cy="14" r="3" fill="rgba(255,255,255,.8)"/>
                <line x1="14" y1="2" x2="14" y2="6" stroke="rgba(255,255,255,.5)" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="14" y1="22" x2="14" y2="26" stroke="rgba(255,255,255,.5)" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="2" y1="14" x2="6" y2="14" stroke="rgba(255,255,255,.5)" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="22" y1="14" x2="26" y2="14" stroke="rgba(255,255,255,.5)" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            <h1>LGU Carmen<br>Burial System</h1>
        </div>
        <p>Municipal Civil Registrar</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Permits</div>
        <a href="#" class="nav-item active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Burial Permits
        </a>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Deceased Records
        </a>

        <div class="nav-section">Cemetery</div>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Cemetery Map
        </a>

        <div class="nav-section">Tools</div>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Reports
        </a>
        <a href="#" class="nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Import Excel
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div>
            <div class="topbar-title">Burial Permits</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <span class="role-tag">Admin</span>
            <a href="#" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                New Permit
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- Recent Permits Table -->
        <div class="panel">
            <div class="panel-header">
                <h3>Recent Permit Applications</h3>
                <a href="#" style="font-size:12px;color:#1a2744;text-decoration:none;font-weight:500">View all →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Reg. No.</th>
                        <th>Deceased</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermits as $permit)
                    <tr>
                        <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                        <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>
                        <td style="font-size:12px;color:#6b7280">New</td>
                        <td style="font-size:12px;color:#6b7280">{{ $permit->created_at->format('M d, Y') }}</td>
                        <td>
                            @php $colors = ['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red']; @endphp
                            <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">
                                {{ ucfirst($permit->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn-action">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#9ca3af;padding:2rem">
                            No permits yet. <a href="#" style="color:#1a2744">Create the first one →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>