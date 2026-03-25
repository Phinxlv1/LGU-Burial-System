<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burial Permits — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }
        .content { padding: 1.5rem; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .panel-header { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #111827; }

        .empty-row td { text-align: center; color: var(--text-3); padding: 2.5rem; font-size: 13px; }

        /* ── Scrollable table ── */
        .table-scroll { max-height: 75vh; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #d1d5db transparent; }
        .table-scroll::-webkit-scrollbar { width: 6px; }
        .table-scroll::-webkit-scrollbar-track { background: transparent; }
        .table-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .table-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        .table-scroll thead th { position: sticky; top: 0; z-index: 2; }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table colgroup col:nth-child(1) { width: 155px; }
        table colgroup col:nth-child(2) { width: 185px; }
        table colgroup col:nth-child(3) { width: 120px; }
        table colgroup col:nth-child(4) { width: 125px; }
        table colgroup col:nth-child(5) { width: 110px; }
        table colgroup col:nth-child(6) { width: 145px; }
        table colgroup col:nth-child(7) { width: 185px; } /* wider for 3 buttons */
        td, th { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        th { font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; padding: .5rem .75rem; text-align: left; background: #fafafa; }
        td { font-size: 13px; color: #374151; padding: .65rem .75rem; border-top: 1px solid #f3f4f6; vertical-align: middle; }

        /* ── EXPIRED ROW HIGHLIGHT ── */
        tr.row-expired td { background: #fff5f5; border-top-color: #fecaca; }
        tr.row-expired td:first-child { border-left: 3px solid #ef4444; }

        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 4px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .permit-no { font-weight: 600; color: #1a2744; font-size: 12px; }

        /* ACTION BUTTONS */
        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 11px; color: #374151; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-action:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }

        .btn-renew { background: #fff1f2; border-color: #fca5a5; color: #b91c1c; font-weight: 600; }
        .btn-renew:hover { background: #fee2e2; border-color: #ef4444; color: #991b1b; }

        .btn-print { background: #f0f9ff; border-color: #7dd3fc; color: #0369a1; font-weight: 500; }
        .btn-print:hover { background: #e0f2fe; border-color: #0ea5e9; color: #0c4a6e; }
        .btn-print.loading { opacity: .6; pointer-events: none; }

        .actions-cell { display: flex; gap: 4px; align-items: center; flex-wrap: nowrap; }

        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .55rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }

        /* PAGINATION */
        .pager { display: flex; align-items: center; justify-content: center; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; gap: 1.5rem; }
        .pager-info { font-size: 12px; color: #6b7280; }
        .pager-btns { display: flex; align-items: center; gap: 3px; }
        .pager-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; text-decoration: none; background: #fff; cursor: pointer; transition: border-color .15s, color .15s; line-height: 1; }
        .pager-btn:hover { border-color: #1a2744; color: #1a2744; }
        .pager-btn.active { background: #1a2744; color: #fff; border-color: #1a2744; font-weight: 600; cursor: default; }
        .pager-btn.disabled { color: #d1d5db; cursor: not-allowed; pointer-events: none; }

        /* SEARCH */
        .search-input { font-family: 'Inter', sans-serif; font-size: 13px; padding: .38rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; width: 220px; color: #111827; }
        .search-input:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.07); }

        /* SORT */
        .sort-link { display: inline-flex; align-items: center; gap: 4px; color: #9ca3af; text-decoration: none; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .06em; transition: color .15s; white-space: nowrap; }
        .sort-link:hover { color: #1a2744; }
        .sort-link.active { color: #1a2744; font-weight: 700; }
        .sort-icon { opacity: .4; font-size: 10px; }
        .sort-icon.asc::after  { content: ' ↑'; }
        .sort-icon.desc::after { content: ' ↓'; }
        .sort-icon.none::after { content: ' ↕'; }

        /* TOAST */
        .toast { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.12); width: 320px; overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .35s cubic-bezier(.34,1.56,.64,1); pointer-events: none; }
        .toast.show { transform: translateX(0); pointer-events: auto; }
        .toast-body { display: flex; align-items: flex-start; gap: .75rem; padding: .9rem 1rem; }
        .toast-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-icon.green { background: #d1fae5; }
        .toast-icon.blue  { background: #dbeafe; }
        .toast-text { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 600; color: #111827; }
        .toast-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .toast-close { background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px; line-height: 1; transition: color .15s; flex-shrink: 0; }
        .toast-close:hover { color: #374151; }
        .toast-progress { height: 3px; background: #e5e7eb; overflow: hidden; }
        .toast-progress-bar { height: 100%; width: 100%; transform-origin: left; animation: toastDrain 5s linear forwards; }
        .toast-progress-bar.green { background: #10b981; }
        .toast-progress-bar.blue  { background: #3b82f6; }
        @keyframes toastDrain { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,30,61,.45); z-index: 100; align-items: center; justify-content: center; padding: 2rem 1rem; overflow-y: auto; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 10px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; border-bottom: 2px solid #1a2744; display: flex; align-items: center; justify-content: space-between; background: #1a2744; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); padding: 4px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: #fff; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .25rem; border-bottom: 1px solid #e5e7eb; margin-top: .25rem; }
        .fee-grid { display: flex; flex-direction: column; gap: .4rem; }
        .fee-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .fee-row:hover { background: #f8faff; border-color: #1a2744; }
        .fee-row input[type=radio] { accent-color: #1a2744; width: 15px; height: 15px; cursor: pointer; }
        .fee-row label { font-size: 13px; font-weight: 500; color: #111827; cursor: pointer; flex: 1; margin-left: .6rem; }
        .fee-amount { font-size: 13px; font-weight: 600; color: #1a2744; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; }
        .btn-cancel:hover { background: #f9fafb; }
    
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
    html.dark .table-scroll { scrollbar-color: #374151 transparent; }
    html.dark .table-scroll::-webkit-scrollbar-thumb { background: #374151; }
    html.dark .table-scroll::-webkit-scrollbar-thumb:hover { background: #4b5563; }
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

    
        /* Permits list specific */
        html.dark .section-divider { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
        html.dark .btn-print { background: rgba(255,255,255,.08) !important; color: #e2e8f0 !important; border-color: rgba(255,255,255,.15) !important; }
        html.dark .btn-print:hover { background: rgba(255,255,255,.14) !important; }
        html.dark .modal-overlay { background: rgba(0,0,0,.75) !important; }
        html.dark .fee-grid > div:not(.fee-row) { color: #64748b !important; }
        html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
        html.dark .btn-cancel:hover { background: #2d3148 !important; }
        html.dark .btn-renew { background: #450a0a !important; border-color: #991b1b !important; color: #fca5a5 !important; }
        html.dark .btn-renew:hover { background: #7f1d1d !important; }
        html.dark .loading { opacity: .5 !important; }
        html.dark .permit-row td { background: #1e2130 !important; }
        html.dark tr.row-expired td { background: #2a1a1a !important; }
        html.dark .pager { border-top-color: #2d3148 !important; }
        html.dark .pager-info { color: #64748b !important; }

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
            <button class="btn-primary" onclick="openPM()">

                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </button>
        </div>
    </div>

    <div class="content">
        <div class="panel">
            <div class="panel-header">
                <h3>All Burial Permits
                    <span style="font-size:11px;font-weight:400;color:#9ca3af;margin-left:.5rem">{{ $permits->count() }} records</span>
                </h3>
                <input type="text" class="search-input" placeholder="Search by name or permit no…" oninput="filterTable(this.value)">
            </div>
            <div class="table-scroll">
            <table>
                <colgroup><col/><col/><col/><col/><col/><col/><col/></colgroup>
                <thead>
                    <tr>
                        <th><a href="{{ $sortUrl('permit_number') }}" class="sort-link {{ request('sort', 'status')==='permit_number'?'active':'' }}">Permit No. {!! $sortIcon('permit_number') !!}</a></th>
                        <th><a href="{{ $sortUrl('last_name') }}"     class="sort-link {{ request('sort', 'status')==='last_name'?'active':'' }}">Deceased {!! $sortIcon('last_name') !!}</a></th>
                        <th><a href="{{ $sortUrl('permit_type') }}"   class="sort-link {{ request('sort', 'status')==='permit_type'?'active':'' }}">Type {!! $sortIcon('permit_type') !!}</a></th>
                        <th><a href="{{ $sortUrl('date_of_death') }}" class="sort-link {{ request('sort', 'status')==='date_of_death'?'active':'' }}">Date of Death {!! $sortIcon('date_of_death') !!}</a></th>
                        <th style="text-align:center">
    <a href="{{ $sortUrl('renewal_count') }}" class="sort-link {{ request('sort', 'status')==='renewal_count'?'active':'' }}" style="justify-content:center">
        Renewals {!! $sortIcon('renewal_count') !!}
    </a>
</th>
                        <th><a href="{{ $sortUrl('status') }}"        class="sort-link {{ request('sort', 'status')==='status'?'active':'' }}">Status {!! $sortIcon('status') !!}</a></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permits as $permit)
                    <tr class="permit-row {{ $permit->status === 'expired' ? 'row-expired' : '' }}">

                        {{-- PERMIT NO --}}
                        <td>
                            <span class="permit-no">{{ $permit->permit_number }}</span>
                            @if($permit->status === 'expired')
                                <span style="font-size:10px;font-weight:700;color:#ef4444;margin-left:4px;vertical-align:middle">⚠ RENEWA…</span>
                            @endif
                        </td>

                        {{-- DECEASED --}}
                        <td>{{ optional($permit->deceased)->last_name }}, {{ optional($permit->deceased)->first_name }}</td>

                        {{-- TYPE --}}
                        <td style="font-size:12px;color:#6b7280;text-transform:capitalize">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>

                        {{-- DATE OF DEATH --}}
                        <td style="font-size:12px;color:#6b7280">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</td>

                        {{-- ISSUED --}}
                        <td style="text-align:center">
    @if(($permit->renewal_count ?? 0) > 0)
        <span style="font-size:12px;font-weight:700;color:#f59e0b;background:#fef3c7;padding:2px 8px;border-radius:4px">{{ $permit->renewal_count }}×</span>
    @else
        <span style="font-size:12px;color:#d1d5db">—</span>
    @endif
</td>

                        {{-- STATUS --}}
                        <td>
                            @if($permit->status === 'expired')
                                <span class="badge badge-red" style="font-weight:700">⚠ Expired</span>
                            @else
                                <span class="badge badge-yellow">⏳ Expiring Soon</span>
                            @endif
                        </td>

                        {{-- ACTIONS: View · Print · Renew --}}
                        <td>
                            <div class="actions-cell">

                                {{-- View --}}
                                <a href="{{ route('permits.show', $permit) }}" class="btn-action">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    View
                                </a>

                                {{-- Print (download .docx) --}}
                                <a href="{{ route('permits.print', $permit) }}"
                                   class="btn-action btn-print"
                                   onclick="handlePrint(event, this, '{{ $permit->permit_number }}')"
                                   title="Download filled permit as .docx">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                    Print
                                </a>

                                {{-- Renew (only for expired) --}}
                                @if($permit->status === 'expired')
                                <form method="POST" action="{{ route('permits.renew', $permit) }}" style="display:inline"
                                      onsubmit="return confirm('Renew this permit?')">
                                    @csrf
                                    <button type="submit" class="btn-action btn-renew">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                                        Renew
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#9ca3af;padding:2.5rem">No permits yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

{{-- SUCCESS TOAST (from session) --}}
@if(session('success'))
<div class="toast" id="successToast">
    <div class="toast-body">
        <div class="toast-icon green">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Success</div>
            <div class="toast-sub">{{ session('success') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('successToast')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar green"></div></div>
</div>
@endif

{{-- PRINT TOAST --}}
<div class="toast" id="printToast">
    <div class="toast-body">
        <div class="toast-icon blue">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Generating permit…</div>
            <div class="toast-sub" id="printToastSub">Preparing your document</div>
        </div>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar blue" id="printToastBar"></div></div>
</div>

@include('partials.permit-modal')

<script>
function closeModal() { document.getElementById('permitModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });

// ── Session toast ──
(function () {
    const t = document.getElementById('successToast');
    if (!t) return;
    requestAnimationFrame(() => setTimeout(() => t.classList.add('show'), 50));
    t._timer = setTimeout(() => dismissToast('successToast'), 5000);
})();

function dismissToast(id) {
    const t = document.getElementById(id);
    if (!t) return;
    clearTimeout(t._timer);
    t.classList.remove('show');
    t.addEventListener('transitionend', () => t.remove(), { once: true });
}

// ── Open new permit modal via hash ──
if (window.location.hash === '#new') {
    document.getElementById('permitModal').classList.add('open');
    history.replaceState(null, '', window.location.pathname);
}

// ── Search filter ──
function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.permit-row').forEach(row => {
        const no  = row.querySelector('.permit-no')?.textContent.toLowerCase() ?? '';
        const dec = row.querySelectorAll('td')[1]?.textContent.toLowerCase() ?? '';
        row.style.display = (no.includes(q) || dec.includes(q)) ? '' : 'none';
    });
}

// ── Print handler: shows toast then lets the browser follow the download link ──
function handlePrint(e, link, permitNo) {
    // Don't prevent default — let the browser follow the href to download the .docx
    // Just show a feedback toast so the admin knows something is happening
    const toast   = document.getElementById('printToast');
    const subEl   = document.getElementById('printToastSub');
    const barEl   = document.getElementById('printToastBar');

    subEl.textContent = 'Downloading ' + permitNo + '.docx…';

    // Reset bar animation
    barEl.style.animation = 'none';
    barEl.offsetHeight; // reflow
    barEl.style.animation = '';

    toast.classList.add('show');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 5000);

    // Briefly dim the button as visual feedback
    link.classList.add('loading');
    setTimeout(() => link.classList.remove('loading'), 2000);
}
</script>

</body>
</html>