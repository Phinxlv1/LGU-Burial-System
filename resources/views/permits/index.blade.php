<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burial Permits — LGU Carmen</title>
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
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-date { font-size: 11px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head span { font-size: 12px; color: #9ca3af; }
        .table-wrap { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; min-width: 820px; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; white-space: nowrap; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }
        .permit-no { font-weight: 700; color: #1a2744; font-size: 13px; }
        .badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-approve { background: #d1fae5; border-color: #6ee7b7; color: #065f46; }
        .btn-approve:hover { background: #a7f3d0; }
        .btn-release { background: #dbeafe; border-color: #93c5fd; color: #1e40af; }
        .btn-release:hover { background: #bfdbfe; }
        .btn-delete { color: #ef4444; border-color: #fca5a5; }
        .btn-delete:hover { background: #fee2e2; border-color: #ef4444; }
        .action-cell { display: flex; gap: .4rem; align-items: center; flex-wrap: nowrap; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: 13px; }
        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .55rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }
        .pagination { display: flex; gap: .4rem; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 4px 10px; border-radius: 5px; font-size: 12px; border: 1px solid #e5e7eb; color: #374151; text-decoration: none; }
        .pagination .active { background: #1a2744; color: #fff; border-color: #1a2744; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 100; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 10px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; border-bottom: 2px solid #1a2744; display: flex; align-items: center; justify-content: space-between; background: #1a2744; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); padding: 4px; border-radius: 4px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; max-height: 70vh; overflow-y: auto; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: #fff; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .25rem; border-bottom: 1px solid #e5e7eb; }
        .fee-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .fee-row:hover { background: #f8faff; border-color: #1a2744; }
        .fee-row input[type=radio] { accent-color: #1a2744; width: 15px; height: 15px; cursor: pointer; flex-shrink: 0; }
        .fee-row label { font-size: 13px; font-weight: 500; color: #111827; cursor: pointer; flex: 1; margin-left: .6rem; }
        .fee-amount { font-size: 13px; font-weight: 600; color: #1a2744; }
        .fee-grid { display: flex; flex-direction: column; gap: .4rem; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; }
        .btn-cancel:hover { background: #f9fafb; }
    </style>
</head>
<body>

@include('partials.sidebar')
    

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Burial Permits</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <span class="role-tag">Admin</span>
            <button class="btn-primary" onclick="document.getElementById('permitModal').classList.add('open')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </button>
        </div>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="panel">
            <div class="panel-head">
                <h3>All Burial Permits</h3>
                <span>{{ $permits->total() }} total</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
    <tr>
        <th>No.</th>
        <th>Name of Deceased</th>
        <th>Date of Death</th>
        <th>Date Applied</th>
        <th>Date Expired</th>
        <th>Requesting Party</th>
        <th>Address</th>
        <th>Kind of Burial</th>
        <th>Amount</th>
       
        <th>Contact No.</th>
        <th></th>
    </tr>
</thead>
                    <tbody>
                        @forelse($permits as $permit)
                        <tr>
    <td style="color:#6b7280;font-size:12px">{{ $loop->iteration }}</td>
    <td><span class="permit-no">{{ optional($permit->deceased)->first_name }} {{ optional($permit->deceased)->last_name }}</span></td>
    <td style="color:#6b7280;font-size:12px">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</td>
    <td style="color:#6b7280;font-size:12px">{{ $permit->created_at->format('M d, Y') }}</td>
    <td style="color:#6b7280;font-size:12px">{{ $permit->expiry_date ? $permit->expiry_date->format('M d, Y') : '—' }}</td>
    <td style="font-size:12px">{{ $permit->applicant_name ?: '—' }}</td>
    <td style="color:#6b7280;font-size:12px">{{ $permit->applicant_address ?: '—' }}</td>
    <td style="color:#6b7280;font-size:12px">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
    <td style="font-size:12px;font-weight:600;color:#1a2744">
        @php
        $amounts = ['cemented'=>'₱1,000','niche_1st'=>'₱8,000','niche_2nd'=>'₱6,600','niche_3rd'=>'₱5,700','niche_4th'=>'₱5,300','bone_niches'=>'₱5,000'];
        @endphp
        {{ $amounts[$permit->permit_type] ?? '—' }}
    </td>
   
    <td style="color:#6b7280;font-size:12px">{{ $permit->applicant_contact ?: '—' }}</td>
    <td style="display:flex;gap:.4rem;align-items:center">
        <button onclick="printPermit('{{ route('permits.print', $permit->id) }}')" 
        class="btn-action">
    Print
</button>
        <a href="{{ route('permits.show', $permit) }}" class="btn-action">View</a>
        @if($permit->status === 'pending')
            <form method="POST" action="{{ route('permits.approve', $permit) }}" style="display:contents">
                @csrf
                <button type="submit" class="btn-action btn-approve">Approve</button>
            </form>
        @elseif($permit->status === 'approved')
            <form method="POST" action="{{ route('permits.release', $permit) }}" style="display:contents">
                @csrf
                <button type="submit" class="btn-action btn-release">Release</button>
            </form>
        @endif
        @if($permit->status === 'released')
    <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:contents" onsubmit="return confirm('Renew this permit?')">
        @csrf
        <button type="submit" class="btn-action" style="background:#fef3c7;border-color:#fcd34d;color:#92400e">Renew</button>
    </form>
@endif
    </td>
</tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:2rem">No permits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($permits->hasPages())
            <div class="pagination">{{ $permits->links() }}</div>
            @endif
        </div>

    </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="permitModal" onclick="if(event.target===this)closeModal()">
    <div class="modal">
        <div class="modal-header">
            <h3>🪦 New Burial Permit</h3>
            <button class="modal-close" onclick="closeModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('permits.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Requestor's Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="requestor_name" class="form-control" placeholder="Full name of requestor" required>
                </div>
                <div class="section-divider">Deceased Information</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
    <div class="form-group">
        <label class="form-label">First Name <span style="color:#ef4444">*</span></label>
        <input type="text" name="first_name" class="form-control" placeholder="First name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middle_name" class="form-control" placeholder="Middle name">
    </div>
    <div class="form-group">
        <label class="form-label">Last Name <span style="color:#ef4444">*</span></label>
        <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
    </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
    <div class="form-group">
        <label class="form-label">Contact No.</label>
        <input type="text" name="applicant_contact" class="form-control" placeholder="e.g. 09XX-XXX-XXXX">
    </div>
    <div class="form-group">
        <label class="form-label">Address</label>
        <input type="text" name="applicant_address" class="form-control" placeholder="Barangay, Municipality">
    </div>
</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" placeholder="e.g. Filipino">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select…</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                    <div class="form-group">
                        <label class="form-label">Date of Death <span style="color:#ef4444">*</span></label>
                        <input type="date" name="date_of_death" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kind of Burial</label>
                        <select name="kind_of_burial" class="form-control">
                            <option value="">Select…</option>
                            <option value="Ground">Ground</option>
                            <option value="Niche">Niche</option>
                        </select>
                    </div>
                </div>
                <div class="section-divider">Burial Permit Fees</div>
                <div class="fee-grid">
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="cemented">
                        <label>Cemented</label>
                        <span class="fee-amount">₱1,000.00</span>
                    </div>
                    <div style="font-size:11px;font-weight:600;color:#6b7280;padding:.3rem .25rem 0;text-transform:uppercase;letter-spacing:.05em">Niches (New)</div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_1st">
                        <label>1st Floor</label>
                        <span class="fee-amount">₱8,000.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_2nd">
                        <label>2nd Floor</label>
                        <span class="fee-amount">₱6,600.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_3rd">
                        <label>3rd Floor</label>
                        <span class="fee-amount">₱5,700.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="niche_4th">
                        <label>4th Floor</label>
                        <span class="fee-amount">₱5,300.00</span>
                    </div>
                    <div class="fee-row" onclick="this.querySelector('input').checked=true">
                        <input type="radio" name="burial_fee_type" value="bone_niches">
                        <label>Bone Niches</label>
                        <span class="fee-amount">₱5,000.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Create Permit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal() { document.getElementById('permitModal').classList.remove('open'); }
    document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });
</script>

</body>
</html>