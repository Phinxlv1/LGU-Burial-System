<style>
/* ═══════════════════════════════════════════
   SIDEBAR — shared across all pages
   Dark mode: toggled via html.dark class
   Persisted in localStorage key: lgu_dark
═══════════════════════════════════════════ */
.sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
.sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
.sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
.sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
.sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
.sidebar-brand p { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
.sidebar-nav { flex: 1; padding: .75rem 0; overflow-y: auto; }
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

/* Dark mode toggle button */
.dark-toggle { width: 100%; background: none; border: 1px solid rgba(255,255,255,.12); border-radius: 6px; padding: .4rem .75rem; font-family: 'Inter', sans-serif; font-size: 11px; color: rgba(255,255,255,.45); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: space-between; margin-bottom: .4rem; }
.dark-toggle:hover { border-color: rgba(255,255,255,.3); color: rgba(255,255,255,.8); }
.dark-toggle-pill { width: 30px; height: 16px; background: rgba(255,255,255,.15); border-radius: 8px; position: relative; transition: background .2s; flex-shrink: 0; }
.dark-toggle-pill::after { content: ''; position: absolute; top: 2px; left: 2px; width: 12px; height: 12px; background: #fff; border-radius: 50%; transition: transform .2s; }
html.dark .dark-toggle-pill { background: #6366f1; }
html.dark .dark-toggle-pill::after { transform: translateX(14px); }

/* ═══ GLOBAL DARK MODE STYLES ═══
   Applied to EVERY page via this shared partial */
html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
html.dark .main { background: #0f1117 !important; }

/* Topbar */
html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
html.dark .topbar-title, html.dark .topbar-left .topbar-title { color: #e2e8f0 !important; }
html.dark .topbar-date { color: #64748b !important; }
html.dark .role-tag { background: #4f46e5 !important; }

/* Panels / Cards */
html.dark .panel, html.dark .upload-card, html.dark .stat-card, html.dark .card, html.dark .docs-card, html.dark .guide-card { background: #1e2130 !important; border-color: #2d3148 !important; }

html.dark .panel-head, html.dark .panel-header, html.dark .card-head, html.dark .docs-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }

html.dark .panel-head h3, html.dark .panel-header h3, html.dark .card-head-title, html.dark .upload-card h3, html.dark .docs-head-title { color: #e2e8f0 !important; }

html.dark .panel-head-sub, html.dark .panel-head a, html.dark .docs-head-sub { color: #64748b !important; }

html.dark .view-all { color: #818cf8 !important; }

/* Tables */
html.dark table th { background: #181b29 !important; color: #64748b !important; }
html.dark table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
html.dark tr.row-expired td { background: #2a1515 !important; border-top-color: #7f1d1d !important; }
html.dark tr.row-expired td:first-child { border-left-color: #ef4444 !important; }
html.dark .empty-row td, html.dark .empty-state { color: #4b5563 !important; }

/* Permit number & file name blue links */
html.dark .permit-no { color: #818cf8 !important; }

/* Badges */
html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
html.dark .badge-green  { background: #052e16 !important; color: #86efac !important; }
html.dark .badge-blue   { background: #0c1a4a !important; color: #93c5fd !important; }
html.dark .badge-red    { background: #450a0a !important; color: #fca5a5 !important; }
html.dark .badge-male   { background: #0c1a4a !important; color: #93c5fd !important; }
html.dark .badge-female { background: #3b0764 !important; color: #d8b4fe !important; }

/* Buttons */
html.dark .btn-action { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
html.dark .btn-action:hover { border-color: #6366f1 !important; color: #818cf8 !important; background: #1e2d6b !important; }
html.dark .btn-renew { background: #2a0a0a !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
html.dark .btn-print { background: #0c1a2e !important; border-color: #1e3a5f !important; color: #7dd3fc !important; }
html.dark .btn-view  { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
html.dark .btn-view:hover { border-color: #6366f1 !important; color: #818cf8 !important; }

html.dark .btn-upload { background: #4f46e5 !important; }
html.dark .btn-upload:hover { background: #4338ca !important; }

/* Sort links */
html.dark .sort-link { color: #64748b !important; }
html.dark .sort-link:hover, html.dark .sort-link.active { color: #818cf8 !important; }

/* Search inputs */
html.dark .search-input { background: #252840 !important; border-color: #2d3148 !important; color: #e2e8f0 !important; }
html.dark .search-input:focus { border-color: #6366f1 !important; }

/* Pagination */
html.dark .pager-info { color: #64748b !important; }
html.dark .pager-btn { background: #1e2130 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
html.dark .pager-btn:hover { border-color: #6366f1 !important; color: #818cf8 !important; }
html.dark .pager-btn.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
html.dark .pager-btn.disabled { color: #374151 !important; border-color: #2d3148 !important; }

/* Dropzone */
html.dark .dropzone { border-color: #374151 !important; background: #181b29 !important; }
html.dark .dropzone:hover, html.dark .dropzone.drag-over { border-color: #6366f1 !important; background: #1e2d6b !important; }
html.dark .dropzone-icon { background: #252840 !important; }
html.dark .dropzone-title { color: #cbd5e1 !important; }
html.dark .dropzone-sub { color: #64748b !important; }
html.dark .dropzone-file { color: #818cf8 !important; }
html.dark .upload-note { color: #64748b !important; }

/* Upload history specific */
html.dark .reasons-toggle { color: #818cf8 !important; }
html.dark .reasons-list { color: #94a3b8 !important; }

/* Stat cards */
html.dark .sc-label { color: #64748b !important; }
html.dark .sc-main  { color: #cbd5e1 !important; }
html.dark .sc-sub   { color: #64748b !important; }
html.dark .sc-value.navy   { color: #818cf8 !important; }
html.dark .sc-value.red    { color: #f87171 !important; }
html.dark .sc-value.amber  { color: #fbbf24 !important; }
html.dark .sc-value.orange { color: #fb923c !important; }

/* Forms / Modals */
html.dark .modal { background: #1e2130 !important; }
html.dark .modal-body { background: #1e2130 !important; }
html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
html.dark .form-control, html.dark select.form-control { background: #252840 !important; border-color: #2d3148 !important; color: #e2e8f0 !important; }
html.dark .form-control:focus { border-color: #6366f1 !important; }
html.dark .form-label, html.dark .field-label { color: #94a3b8 !important; }
html.dark .section-divider, html.dark .divider-label { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
html.dark .fee-row { border-color: #2d3148 !important; background: #1e2130 !important; }
html.dark .fee-row:hover { background: #1e2d6b !important; border-color: #6366f1 !important; }
html.dark .fee-row label { color: #cbd5e1 !important; }
html.dark .fee-amount { color: #818cf8 !important; }
html.dark .btn-cancel { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }

/* Guide card on import page */
html.dark .guide-card { background: #0c1a4a !important; border-color: #1e3a8a !important; }
html.dark .guide-card h3 { color: #93c5fd !important; }
html.dark .col-tag { background: #0c1a4a !important; color: #93c5fd !important; border-color: #1e3a8a !important; }
html.dark .col-tag.required { background: #422006 !important; color: #fde68a !important; border-color: #854d0e !important; }
html.dark .guide-note { color: #60a5fa !important; }

/* Info rows (deceased show page) */
html.dark .info-item { border-bottom-color: #2d3148 !important; }
html.dark .info-label { color: #64748b !important; }
html.dark .info-value { color: #e2e8f0 !important; }
html.dark .info-value.empty { color: #374151 !important; }
</style>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-top">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sidebar-seal">
            <h1>LGU Carmen<br>Burial System</h1>
        </div>
        <p>Municipal Civil Registrar</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ auth()->user()->role === 'super_admin' ? route('superadmin.dashboard') : route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        @if(auth()->user()->role === 'super_admin')
            <div class="nav-section">Reports</div>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Reports
            </a>
            <a href="{{ route('superadmin.export') }}" class="nav-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
            <div class="nav-section">System</div>
            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                Settings
            </a>
        @else
            <div class="nav-section">Permits</div>
            <a href="{{ route('permits.index') }}" class="nav-item {{ request()->routeIs('permits.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Burial Permits
            </a>
            <div class="nav-section">Cemetery</div>
            <a href="{{ route('cemetery.map') }}" class="nav-item {{ request()->routeIs('cemetery.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Cemetery Map
            </a>
            <div class="nav-section">Tools</div>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Reports
            </a>
            <a href="{{ route('import.show') }}" class="nav-item {{ request()->routeIs('import.*') ? 'active' : '' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Import Excel
            </a>
            {{-- REMOVED Settings link for regular admin users --}}
        @endif
    </nav>

    <div class="sidebar-footer">
        {{-- Dark mode toggle --}}
        <button class="dark-toggle" onclick="toggleDark()" id="darkToggleBtn">
            <span id="darkToggleLabel">🌙 Dark Mode</span>
            <div class="dark-toggle-pill" id="darkPill"></div>
        </button>

        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<script>
// ── Dark mode: persisted in localStorage ──
(function() {
    if (localStorage.getItem('lgu_dark') === '1') {
        document.documentElement.classList.add('dark');
    }
})();

function toggleDark() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('lgu_dark', isDark ? '1' : '0');
    updateDarkLabel(isDark);
}

function updateDarkLabel(isDark) {
    const label = document.getElementById('darkToggleLabel');
    if (label) label.textContent = isDark ? '☀️ Light Mode' : '🌙 Dark Mode';
}

document.addEventListener('DOMContentLoaded', function() {
    updateDarkLabel(document.documentElement.classList.contains('dark'));
});
</script>
