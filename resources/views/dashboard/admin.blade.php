<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LGU Carmen</title>
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
        .topbar-left { display: flex; flex-direction: column; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 11px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* WELCOME BANNER */
        .welcome { background: #1a2744; border-radius: 10px; padding: 1.25rem 1.5rem; }
        .welcome h2 { font-size: 18px; font-weight: 700; color: #fff; }
        .welcome p { font-size: 12px; color: rgba(255,255,255,.5); margin-top: .2rem; }

        /* RECENT TABLE */
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head a { font-size: 12px; color: #1a2744; text-decoration: none; font-weight: 600; }
        .panel-head a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }

        .permit-no { font-weight: 700; color: #1a2744; font-size: 12px; }

        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 4px; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* ── EXPIRED ROW HIGHLIGHT ── */
        tr.row-expired td {
            background: #fff5f5;
            border-top-color: #fecaca;
        }
        tr.row-expired td:first-child {
            border-left: 3px solid #ef4444;
        }

        .btn-view { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-view:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .empty-row { text-align: center; color: #9ca3af; padding: 2.5rem; font-size: 13px; }

        /* FLOATING + BUTTON */
        .fab {
            position: fixed; bottom: 2rem; right: 2rem;
            width: 54px; height: 54px;
            background: #1a2744; color: #fff;
            border: none; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 300; line-height: 1;
            box-shadow: 0 4px 20px rgba(26,39,68,.45);
            cursor: pointer; z-index: 999;
            transition: transform .15s, box-shadow .15s;
            text-decoration: none;
        }
        .fab:hover { transform: scale(1.1); box-shadow: 0 8px 28px rgba(26,39,68,.55); }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 10px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; border-bottom: 2px solid #1a2744; display: flex; align-items: center; justify-content: space-between; background: #1a2744; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); font-size: 20px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .25rem; border-bottom: 1px solid #e5e7eb; margin-top: .25rem; }
        .fee-grid { display: flex; flex-direction: column; gap: .4rem; }
        .fee-row { display: flex; align-items: center; gap: 9px; padding: .5rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .fee-row:hover { background: #f8faff; border-color: #1a2744; }
        .fee-row.selected { background: #eff6ff; border-color: #1a2744; }
        .fee-row input[type=radio] { accent-color: #1a2744; width: 15px; height: 15px; cursor: pointer; flex-shrink: 0; }
        .fee-row label { font-size: 13px; font-weight: 500; color: #111827; cursor: pointer; flex: 1; }
        .fee-amount { font-size: 13px; font-weight: 600; color: #1a2744; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; }
        .btn-cancel:hover { background: #f9fafb; }
        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .5rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; color: #fff; background: #1a2744; cursor: pointer; transition: background .15s; }
        .btn-primary:hover { background: #243459; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">Dashboard</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        {{-- WELCOME BANNER --}}
        <div class="welcome">
            <h2>Welcome back, {{ auth()->user()->name }} 👋</h2>
            <p>Here's what's happening with burial permits today.</p>
        </div>

        {{-- RECENT PERMITS --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Recent Permit Applications</h3>
                <a href="{{ route('permits.index') }}">View all →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Permit No.</th>
                        <th>Deceased</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermits as $permit)
                    @php
                        $expiring = $permit->status === 'released'
                            && $permit->expiry_date
                            && $permit->expiry_date->diffInDays(now()) <= 30
                            && $permit->expiry_date->isFuture();
                    @endphp
                    <tr class="{{ $permit->status === 'expired' ? 'row-expired' : '' }}">
                        <td>
                            <span class="permit-no">{{ $permit->permit_number }}</span>
                            @if($permit->status === 'expired')
                                <span style="font-size:10px;font-weight:700;color:#ef4444;margin-left:5px;vertical-align:middle">⚠ RENEWAL NEEDED</span>
                            @endif
                        </td>
                        <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                        <td style="color:#6b7280;font-size:12px">{{ $permit->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($permit->status === 'expired')
                                <span class="badge badge-red" style="font-weight:700;letter-spacing:.02em">
                                    ⚠ Expired
                                </span>
                            @elseif($expiring)
                                <span class="badge badge-yellow">
                                    ⏳ Expiring Soon
                                </span>
                            @elseif($permit->status === 'released')
                                <span class="badge badge-blue">Released</span>
                            @elseif($permit->status === 'approved')
                                <span class="badge badge-green">Approved</span>
                            @else
                                <span class="badge badge-yellow">{{ ucfirst($permit->status) }}</span>
                            @endif
                        </td>
                        <td><a href="{{ route('permits.show', $permit) }}" class="btn-view">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-row">No permits yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- FLOATING + BUTTON — goes to permits page and auto-opens modal --}}
<a href="{{ route('permits.index') }}#new" class="fab" title="New Permit">+</a>

</body>
</html>