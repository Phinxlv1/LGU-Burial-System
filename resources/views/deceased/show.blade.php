<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $deceased->last_name }}, {{ $deceased->first_name }} — LGU Carmen</title>
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
        .topbar-sub { font-size: 11px; color: #9ca3af; }
        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
        .btn-back { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; color: #6b7280; text-decoration: none; }
        .btn-back:hover { color: #1a2744; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head span { font-size: 12px; color: #9ca3af; }
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); }
        .info-item { padding: .9rem 1.25rem; border-bottom: 1px solid #f3f4f6; border-right: 1px solid #f3f4f6; }
        .info-item:nth-child(3n) { border-right: none; }
        .info-label { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 3px; }
        .info-value { font-size: 13px; color: #111827; font-weight: 500; }
        .info-value.empty { color: #d1d5db; font-style: italic; font-weight: 400; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; white-space: nowrap; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }
        .permit-no { font-weight: 700; color: #1a2744; }
        .badge { display: inline-flex; align-items: center; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 4px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-delete { color: #ef4444; border-color: #fca5a5; }
        .btn-delete:hover { background: #fee2e2; border-color: #ef4444; }
        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .5rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 6px; padding: .75rem 1rem; font-size: 13px; }

        /* Edit Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 100; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 10px; width: 100%; max-width: 620px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; background: #1a2744; display: flex; align-items: center; justify-content: space-between; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); padding: 4px; border-radius: 4px; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; max-height: 72vh; overflow-y: auto; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .form-row { display: grid; gap: .6rem; }
        .form-row.cols-2 { grid-template-columns: 1fr 1fr; }
        .form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: #fff; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .4rem 0 .2rem; border-bottom: 1px solid #e5e7eb; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; }
        .btn-cancel:hover { background: #f9fafb; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">{{ $deceased->last_name }}, {{ $deceased->first_name }}</div>
            <div class="topbar-sub">Deceased Record #{{ $deceased->id }}</div>
        </div>
        <button class="btn-primary" onclick="document.getElementById('editModal').classList.add('open')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Record
        </button>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('deceased.index') }}" class="btn-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Deceased Records
        </a>

        <!-- Personal Info -->
        <div class="panel">
            <div class="panel-head">
                <h3>Personal Information</h3>
                
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Last Name</div>
                    <div class="info-value">{{ $deceased->last_name ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">First Name</div>
                    <div class="info-value">{{ $deceased->first_name ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Middle Name</div>
                    <div class="info-value {{ !$deceased->middle_name ? 'empty' : '' }}">{{ $deceased->middle_name ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Sex</div>
                    <div class="info-value">{{ $deceased->sex ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Age</div>
                    <div class="info-value">{{ $deceased->age ?? '—' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Nationality</div>
                    <div class="info-value">{{ $deceased->nationality ?? '—' }}</div>
                </div>
                
                
                
                <div class="info-item">
                    <div class="info-label">Date of Death</div>
                    <div class="info-value">
                        {{ $deceased->date_of_death ? \Carbon\Carbon::parse($deceased->date_of_death)->format('M d, Y') : '—' }}
                    </div>
                </div>
                
               
                <div class="info-item">
                    <div class="info-label">Kind of Burial</div>
                    <div class="info-value">{{ $deceased->kind_of_burial ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Record Created</div>
                    <div class="info-value">{{ $deceased->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Linked Permits -->
        <div class="panel">
            <div class="panel-head">
                <h3>Burial Permits</h3>
                <span>{{ $deceased->permits->count() }} permit(s)</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Type</th>
                            <th>Requestor</th>
                            <th>Issued</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deceased->permits as $permit)
                        <tr>
                            <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                            <td style="font-size:12px;color:#6b7280">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                            <td style="font-size:12px">{{ $permit->requestor_name ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $permit->created_at->format('M d, Y') }}</td>
                            <td>
                                @php $colors=['pending'=>'badge-yellow','approved'=>'badge-green','released'=>'badge-blue','expired'=>'badge-red']; @endphp
                                <span class="badge {{ $colors[$permit->status] ?? 'badge-yellow' }}">{{ ucfirst($permit->status) }}</span>
                            </td>
                            <td><a href="{{ route('permits.show', $permit) }}" class="btn-action">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:2rem">No permits linked to this record.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="panel" style="border-color:#fee2e2">
            <div class="panel-head" style="background:#fff5f5">
                <h3 style="color:#991b1b">Danger Zone</h3>
            </div>
            <div style="padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#111827">Delete this record</div>
                    <div style="font-size:12px;color:#6b7280;margin-top:2px">This will also delete all linked burial permits. This cannot be undone.</div>
                </div>
                <form method="POST" action="{{ route('deceased.destroy', $deceased) }}" onsubmit="return confirm('Delete this record and all linked permits? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-delete">Delete Record</button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal" onclick="if(event.target===this)closeEdit()">
    <div class="modal">
        <div class="modal-header">
            <h3>✏️ Edit Deceased Record</h3>
            <button class="modal-close" onclick="closeEdit()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('deceased.update', $deceased) }}">
            @csrf @method('PUT')
            <div class="modal-body">

                <div class="section-divider">Name</div>
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label class="form-label">Last Name <span style="color:#ef4444">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $deceased->last_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">First Name <span style="color:#ef4444">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $deceased->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $deceased->middle_name) }}">
                    </div>
                </div>

                <div class="section-divider">Personal Details</div>
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-control">
                            <option value="">Select…</option>
                            <option value="Male"   {{ old('sex', $deceased->sex) === 'Male'   ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $deceased->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" min="0" value="{{ old('age', $deceased->age) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-control">
                            <option value="">Select…</option>
                            @foreach(['Single','Married','Widowed','Separated'] as $cs)
                                <option value="{{ $cs }}" {{ old('civil_status', $deceased->civil_status) === $cs ? 'selected' : '' }}>{{ $cs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $deceased->nationality) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control" value="{{ old('religion', $deceased->religion) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $deceased->address) }}">
                </div>

                <div class="section-divider">Death Information</div>
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $deceased->date_of_birth ? \Carbon\Carbon::parse($deceased->date_of_birth)->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Death <span style="color:#ef4444">*</span></label>
                        <input type="date" name="date_of_death" class="form-control" value="{{ old('date_of_death', $deceased->date_of_death ? \Carbon\Carbon::parse($deceased->date_of_death)->format('Y-m-d') : '') }}" required>
                    </div>
                </div>
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Place of Death</label>
                        <input type="text" name="place_of_death" class="form-control" value="{{ old('place_of_death', $deceased->place_of_death) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cause of Death</label>
                        <input type="text" name="cause_of_death" class="form-control" value="{{ old('cause_of_death', $deceased->cause_of_death) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kind of Burial</label>
                    <select name="kind_of_burial" class="form-control">
                        <option value="">Select…</option>
                        @foreach(['Ground','Niche','Cremation'] as $kb)
                            <option value="{{ $kb }}" {{ old('kind_of_burial', $deceased->kind_of_burial) === $kb ? 'selected' : '' }}>{{ $kb }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeEdit() { document.getElementById('editModal').classList.remove('open'); }
    document.addEventListener('keydown', e => { if(e.key === 'Escape') closeEdit(); });

    // Re-open modal on validation errors
    @if($errors->any())
        document.getElementById('editModal').classList.add('open');
    @endif
</script>

</body>
</html>