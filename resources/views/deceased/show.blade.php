<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{var k='lgu_dark_{{ auth()->id() }}';if(localStorage.getItem(k)==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $deceased->last_name }}, {{ $deceased->first_name }} — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 700; color: #111827; }
        .topbar-sub { font-size: 11px; color: #9ca3af; }
        .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
        .btn-back { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; color: #6b7280; text-decoration: none; }
        .btn-back:hover { color: #1a2744; }

        /* Panel */
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .panel-head { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 13px; font-weight: 700; color: #111827; }
        .panel-head span { font-size: 12px; color: #9ca3af; }

        /* Info grid — 3 equal columns, each field stacked */
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); }
        .info-item {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            border-right: 1px solid #f3f4f6;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        /* Remove right border on last column of each row */
        .info-item:nth-child(3n) { border-right: none; }
        /* Remove bottom border on last row */
        .info-item:nth-last-child(-n+3):nth-child(3n+1),
        .info-item:nth-last-child(-n+3):nth-child(3n+2),
        .info-item:nth-last-child(-n+3):nth-child(3n)  { border-bottom: none; }
        /* Fallback for when last row has fewer items */
        .info-item:last-child { border-bottom: none; }

        .info-label { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; }
        .info-value { font-size: 13px; color: #111827; font-weight: 500; }
        .info-value.empty { color: #d1d5db; font-style: italic; font-weight: 400; }

        /* Permit table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .5rem .75rem; text-align: left; background: #fafafa; white-space: nowrap; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }

        .permit-no { font-weight: 700; color: #1a2744; }

        /* Status badges — matching permits/index */
        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 9px; border-radius: 4px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        /* Renewal count chip */
        .renew-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
        }
        .renew-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 11px;
            font-size: 11px;
            font-weight: 700;
        }
        .renew-count.zero   { background: #f3f4f6; color: #9ca3af; }
        .renew-count.active { background: #dbeafe; color: #1e40af; }

        /* Action button */
        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .5rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }

        /* Danger zone */
        .danger-panel { background: #fff; border: 1px solid #fee2e2; border-radius: 10px; overflow: hidden; }
        .danger-head { padding: .85rem 1.25rem; border-bottom: 1px solid #fee2e2; background: #fff5f5; }
        .danger-head h3 { font-size: 13px; font-weight: 700; color: #991b1b; }
        .danger-body { padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; }
        .danger-desc { font-size: 13px; font-weight: 600; color: #111827; }
        .danger-sub  { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .btn-danger { display: inline-flex; align-items: center; gap: 5px; padding: .45rem .9rem; border-radius: 6px; border: 1.5px solid #fca5a5; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; color: #991b1b; background: #fff; cursor: pointer; transition: all .15s; }
        .btn-danger:hover { background: #fee2e2; border-color: #ef4444; }

        /* Edit modal */
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

        /* Toast */
        .toast { position: fixed; top: 1.1rem; right: 1.25rem; z-index: 9999; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.12); width: 300px; overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .35s cubic-bezier(.34,1.56,.64,1); }
        .toast.show { transform: translateX(0); }
        .toast-body { display: flex; align-items: center; gap: .75rem; padding: .85rem 1rem; }
        .toast-icon { width: 30px; height: 30px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-title { font-size: 13px; font-weight: 600; color: #111827; }
        .toast-sub   { font-size: 11px; color: #6b7280; }
        .toast-bar { height: 3px; background: #e5e7eb; }
        .toast-fill { height: 100%; width: 100%; background: #10b981; transform-origin: left; animation: drain 4s linear forwards; }
        @keyframes drain { from{transform:scaleX(1)} to{transform:scaleX(0)} }
    
    /* ══════════════════════════════
       DARK MODE OVERRIDES
    ══════════════════════════════ */
    html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
    html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
    html.dark .topbar-title, html.dark .topbar-sub, html.dark .topbar-date { color: #e2e8f0 !important; }
    html.dark .topbar-date { color: #64748b !important; }
    html.dark .role-tag { background: #6366f1 !important; }
    html.dark .panel, html.dark .card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .panel-head, html.dark .panel-header { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .panel-head h3, html.dark .panel-header h3 { color: #e2e8f0 !important; }
    html.dark .panel-head a { color: #818cf8 !important; }
    html.dark table th { background: #181b29 !important; color: #64748b !important; }
    html.dark table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark tr.row-expired td { background: #2a1a1a !important; border-top-color: #7f1d1d !important; }
    html.dark .permit-no { color: #818cf8 !important; }
    html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
    html.dark .badge-green  { background: #052e16 !important; color: #86efac !important; }
    html.dark .badge-blue   { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .badge-red    { background: #450a0a !important; color: #fca5a5 !important; }
    html.dark .btn-view, html.dark .btn-action { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-view:hover, html.dark .btn-action:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .welcome { background: #111827 !important; }
    html.dark .name-main { color: #e2e8f0 !important; }
    html.dark .name-sub { color: #64748b !important; }
    html.dark .search-input { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .search-input:focus { border-color: #6366f1 !important; }
    html.dark .pager { border-top-color: #2d3148 !important; }
    html.dark .pager-info { color: #64748b !important; }
    html.dark .pager-btn { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .pager-btn:hover { border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .pager-btn.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
    html.dark .pager-btn.disabled { color: #374151 !important; }
    html.dark .sort-link { color: #64748b !important; }
    html.dark .sort-link.active, html.dark .sort-link:hover { color: #818cf8 !important; }
    html.dark .hero { background: #111827 !important; }
    html.dark .card-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .card-head-title { color: #94a3b8 !important; }
    html.dark .card-body { background: #1e2130 !important; }
    html.dark .fl { color: #64748b !important; }
    html.dark .fv, html.dark .fv-lg { color: #e2e8f0 !important; }
    html.dark .fee-box { background: #111827 !important; }
    html.dark .section-divider { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .form-control, html.dark select.form-control { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .form-control:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important; }
    html.dark .form-label { color: #94a3b8 !important; }
    html.dark .modal { background: #1e2130 !important; }
    html.dark .modal-header { background: #111827 !important; }
    html.dark .modal-body { background: #1e2130 !important; }
    html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .fee-row { border-color: #2d3148 !important; }
    html.dark .fee-row:hover { background: #252840 !important; border-color: #6366f1 !important; }
    html.dark .fee-row label { color: #e2e8f0 !important; }
    html.dark .fee-amount { color: #818cf8 !important; }
    html.dark .upload-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .dropzone { border-color: #374151 !important; }
    html.dark .dropzone:hover, html.dark .dropzone.drag-over { border-color: #6366f1 !important; background: #1e2d6b !important; }
    html.dark .dropzone-icon { background: #252840 !important; }
    html.dark .dropzone-title { color: #cbd5e1 !important; }
    html.dark .dropzone-sub { color: #64748b !important; }
    html.dark .docs-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .docs-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .docs-head-title { color: #e2e8f0 !important; }
    html.dark .docs-head-sub { color: #64748b !important; }
    html.dark .docs-col-files { border-right-color: #2d3148 !important; }
    html.dark .docs-col-upload { background: #181b29 !important; }
    html.dark .doc-item { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .doc-item:hover { background: #252840 !important; border-color: #6366f1 !important; }
    html.dark .doc-name { color: #e2e8f0 !important; }
    html.dark .doc-meta { color: #64748b !important; }
    html.dark .btn-doc { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-doc:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .info-item { border-color: #2d3148 !important; }
    html.dark .info-label { color: #64748b !important; }
    html.dark .info-value { color: #e2e8f0 !important; }
    html.dark .info-value.empty { color: #374151 !important; }
    html.dark .panel-head { background: #181b29 !important; }
    html.dark .danger-panel { background: #1e2130 !important; border-color: #7f1d1d !important; }
    html.dark .danger-head { background: #2a1a1a !important; border-bottom-color: #7f1d1d !important; }
    html.dark .danger-desc { color: #e2e8f0 !important; }
    html.dark .danger-sub { color: #94a3b8 !important; }
    html.dark .topbar-back { color: #94a3b8 !important; }
    html.dark .topbar-back:hover { color: #e2e8f0 !important; }
    html.dark .topbar-sep { color: #334155 !important; }
    html.dark .btn-print { background: rgba(255,255,255,.08) !important; }
    html.dark .toggle-row { border-color: #2d3148 !important; }
    html.dark .toggle-row:hover { background: #252840 !important; }
    html.dark .toggle-label { color: #e2e8f0 !important; }
    html.dark .toggle-sub { color: #64748b !important; }
    html.dark .snav-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .snav-item { color: #94a3b8 !important; }
    html.dark .snav-item:hover { background: #252840 !important; color: #e2e8f0 !important; }
    html.dark .snav-item.active { background: #1e2d6b !important; color: #818cf8 !important; border-left-color: #6366f1 !important; }
    html.dark .snav-divider { background: #2d3148 !important; }
    html.dark .section-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .section-head { border-bottom-color: #2d3148 !important; }
    html.dark .section-head h2 { color: #e2e8f0 !important; }
    html.dark .section-head p { color: #64748b !important; }
    html.dark .section-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .fee-table { border-color: #2d3148 !important; }
    html.dark .fee-table th { background: #181b29 !important; color: #64748b !important; }
    html.dark .fee-table td { border-top-color: #2d3148 !important; color: #cbd5e1 !important; }
    html.dark .fee-table input { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .fee-type-badge { background: #1e2d6b !important; color: #818cf8 !important; }
    html.dark .user-table th { background: #181b29 !important; color: #64748b !important; }
    html.dark .user-table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark .appearance-preview { border-color: #2d3148 !important; }
    html.dark .ap-light { background: #252840 !important; }
    html.dark .ap-light:hover { background: #2d3148 !important; }
    html.dark .ap-light .ap-name { color: #e2e8f0 !important; }
    html.dark .ap-light .ap-sub { color: #64748b !important; }
    html.dark .ap-divider { background: #2d3148 !important; }
    html.dark .danger-item { background: #2a1a1a !important; border-color: #7f1d1d !important; }
    html.dark .danger-title { color: #fca5a5 !important; }
    html.dark .danger-sub { color: #f87171 !important; }
    html.dark .badge-male   { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .badge-female { background: #3b0764 !important; color: #e9d5ff !important; }
    html.dark .toast { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .toast-title { color: #e2e8f0 !important; }
    html.dark .toast-sub { color: #94a3b8 !important; }
    html.dark .upload-note { color: #64748b !important; }
    html.dark .btn-upload { background: #6366f1 !important; }
    html.dark .btn-upload:hover { background: #4f46e5 !important; }
    html.dark .btn-primary { background: #6366f1 !important; }
    html.dark .btn-primary:hover { background: #4f46e5 !important; }
    html.dark .dq-stat { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .dq-issue { border-color: #2d3148 !important; }
    html.dark .dq-issue-head { background: #181b29 !important; }
    html.dark .dq-issue-head:hover, html.dark .dq-issue.open .dq-issue-head { background: #252840 !important; }
    html.dark .dq-issue-title { color: #e2e8f0 !important; }
    html.dark .dq-issue-count { color: #64748b !important; }
    html.dark .dq-issue-body { border-top-color: #2d3148 !important; }
    html.dark .dq-desc { background: #181b29 !important; color: #94a3b8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .dq-record { border-bottom-color: #2d3148 !important; }
    html.dark .dq-record-title { color: #e2e8f0 !important; }
    html.dark .dq-record-sub { color: #64748b !important; }
    html.dark hr { border-color: #2d3148 !important; }
    html.dark .docs-empty { color: #374151 !important; }
    html.dark .lightbox { background: rgba(0,0,0,.92) !important; }

    
        /* Deceased show specific */
        html.dark .info-item { border-bottom-color: #2d3148 !important; border-right-color: #2d3148 !important; }
        html.dark .info-label { color: #64748b !important; }
        html.dark .info-value { color: #e2e8f0 !important; }
        html.dark .info-value.empty { color: #374151 !important; }
        html.dark .badge-male   { background: #1e3a5f !important; color: #93c5fd !important; }
        html.dark .badge-female { background: #4a044e !important; color: #f5d0fe !important; }
        html.dark .renew-chip { color: #cbd5e1 !important; }
        html.dark .renew-count.zero   { background: #252840 !important; color: #64748b !important; }
        html.dark .renew-count.active { background: #1e3a5f !important; color: #93c5fd !important; }
        html.dark .btn-back { color: #94a3b8 !important; }
        html.dark .btn-back:hover { color: #e2e8f0 !important; }
        html.dark .danger-panel { border-color: #7f1d1d !important; }
        html.dark .danger-head  { background: #2a1a1a !important; border-color: #7f1d1d !important; }
        html.dark .danger-head h3 { color: #fca5a5 !important; }
        html.dark .danger-body { background: #1e2130 !important; }
        html.dark .danger-desc { color: #e2e8f0 !important; }
        html.dark .danger-sub  { color: #94a3b8 !important; }
        html.dark .btn-danger { background: #1e2130 !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
        html.dark .btn-danger:hover { background: #450a0a !important; border-color: #ef4444 !important; }
        html.dark .modal-overlay { background: rgba(0,0,0,.75) !important; }
        html.dark .modal { background: #1e2130 !important; border-color: #2d3148 !important; }
        html.dark .modal-body { background: #1e2130 !important; }
        html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
        html.dark .section-divider { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
        html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
        html.dark .permit-no { color: #818cf8 !important; }
        html.dark .table-wrap { background: #1e2130 !important; }

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

        <a href="{{ route('deceased.index') }}" class="btn-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Deceased Records
        </a>

        {{-- PERSONAL INFO --}}
        <div class="panel">
            <div class="panel-head"><h3>Personal Information</h3></div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Last Name</div>
                    <div class="info-value">{{ $deceased->last_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">First Name</div>
                    <div class="info-value">{{ $deceased->first_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Middle Name</div>
                    <div class="info-value {{ !$deceased->middle_name ? 'empty' : '' }}">
                        {{ $deceased->middle_name ?? '—' }}
                    </div>
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

        {{-- BURIAL PERMITS --}}
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
                            <th>Renewals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deceased->permits as $permit)
                        @php
                            $expiring = $permit->status === 'released'
                                && $permit->expiry_date
                                && $permit->expiry_date->isFuture()
                                && $permit->expiry_date->diffInDays(now()) <= 30;

                            // Renewal count — safely query; defaults to 0 if table missing
                            try {
                                $renewalCount = \Illuminate\Support\Facades\DB::table('transactions')
                                    ->where('permit_id', $permit->id)
                                    ->where('action', 'like', '%renew%')
                                    ->count();
                            } catch (\Throwable) {
                                $renewalCount = 0;
                            }
                        @endphp
                        <tr>
                            <td><span class="permit-no">{{ $permit->permit_number }}</span></td>
                            <td style="font-size:12px;color:#6b7280">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                            <td style="font-size:12px">{{ $permit->applicant_name ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $permit->created_at->format('M d, Y') }}</td>

                            {{-- Status badge matching permits/index --}}
                            <td>
                                @if($permit->status === 'expired')
                                    <span class="badge badge-red" style="font-weight:700">⚠ Expired</span>
                                @elseif($expiring)
                                    <span class="badge badge-yellow">⏳ Expiring Soon</span>
                                @elseif($permit->status === 'released')
                                    <span class="badge badge-blue">Released</span>
                                @elseif($permit->status === 'approved')
                                    <span class="badge badge-green">Approved</span>
                                @else
                                    <span class="badge badge-yellow">{{ ucfirst($permit->status) }}</span>
                                @endif
                            </td>

                            {{-- Renewals count instead of View button --}}
                            <td>
                                <div class="renew-chip">
                                    <span class="renew-count {{ $renewalCount > 0 ? 'active' : 'zero' }}">
                                        {{ $renewalCount }}
                                    </span>
                                    <span style="font-size:11px;color:#9ca3af">
                                        {{ $renewalCount === 1 ? 'renewal' : 'renewals' }}
                                    </span>
                                    <a href="{{ route('permits.show', $permit) }}"
                                       style="margin-left:.35rem;font-size:11px;color:#1a2744;text-decoration:none;opacity:.6"
                                       title="View permit">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:2rem">No permits linked to this record.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DANGER ZONE --}}
        <div class="danger-panel">
            <div class="danger-head"><h3>Danger Zone</h3></div>
            <div class="danger-body">
                <div>
                    <div class="danger-desc">Delete this record</div>
                    <div class="danger-sub">This will also delete all linked burial permits. This cannot be undone.</div>
                </div>
                <form method="POST" action="{{ route('deceased.destroy', $deceased) }}"
                      onsubmit="return confirm('Delete this record and all linked permits?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                        Delete Record
                    </button>
                </form>
            </div>
        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

{{-- EDIT MODAL --}}
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
                            <option value="Male"   {{ old('sex',$deceased->sex)==='Male'   ?'selected':'' }}>Male</option>
                            <option value="Female" {{ old('sex',$deceased->sex)==='Female' ?'selected':'' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" min="0" value="{{ old('age',$deceased->age) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-control">
                            <option value="">Select…</option>
                            @foreach(['Single','Married','Widowed','Separated'] as $cs)
                                <option value="{{ $cs }}" {{ old('civil_status',$deceased->civil_status)===$cs?'selected':'' }}>{{ $cs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality',$deceased->nationality) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control" value="{{ old('religion',$deceased->religion) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address',$deceased->address) }}">
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
                        <input type="text" name="place_of_death" class="form-control" value="{{ old('place_of_death',$deceased->place_of_death) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cause of Death</label>
                        <input type="text" name="cause_of_death" class="form-control" value="{{ old('cause_of_death',$deceased->cause_of_death) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kind of Burial</label>
                    <select name="kind_of_burial" class="form-control">
                        <option value="">Select…</option>
                        @foreach(['Ground','Niche','Cremation'] as $kb)
                            <option value="{{ $kb }}" {{ old('kind_of_burial',$deceased->kind_of_burial)===$kb?'selected':'' }}>{{ $kb }}</option>
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

{{-- TOAST --}}
@if(session('success'))
<div class="toast show" id="sToast">
    <div class="toast-body">
        <div class="toast-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div><div class="toast-title">Saved</div><div class="toast-sub">{{ session('success') }}</div></div>
    </div>
    <div class="toast-bar"><div class="toast-fill"></div></div>
</div>
@endif

<script>
function closeEdit() { document.getElementById('editModal').classList.remove('open'); }
@if($errors->any()) document.getElementById('editModal').classList.add('open'); @endif
document.addEventListener('keydown', e => { if(e.key==='Escape') closeEdit(); });
(function(){ const t=document.getElementById('sToast'); if(!t) return; setTimeout(()=>t.classList.remove('show'),4500); })();
</script>

</body>
</html>