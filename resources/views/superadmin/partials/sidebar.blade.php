<style>
/* ═══════════════════════════════════════════
   SUPER ADMIN SIDEBAR - Minimalist Dark Blue
   Matches admin sidebar design system
═══════════════════════════════════════════ */

.sa-sidebar { 
    width: 220px; 
    min-height: 100vh; 
    background: #1a2744; 
    display: flex; 
    flex-direction: column; 
    position: fixed; 
    top: 0; 
    left: 0; 
    z-index: 50; 
}

.sa-brand { 
    padding: 1.25rem 1rem 1rem; 
    border-bottom: 1px solid rgba(255,255,255,.08); 
}

.sa-brand-top { 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    margin-bottom: .3rem; 
}

.sa-seal { 
    width: 34px; 
    height: 34px; 
    border-radius: 50%; 
    object-fit: cover; 
    flex-shrink: 0; 
    border: 1.5px solid rgba(255,255,255,0.2); 
}

.sa-brand h1 { 
    font-size: 12px; 
    font-weight: 600; 
    color: #fff; 
    line-height: 1.3; 
}

.sa-nav { 
    flex: 1; 
    padding: .75rem 0; 
    overflow-y: auto; 
}

.sa-nav-section { 
    font-size: 9px; 
    font-weight: 600; 
    letter-spacing: .1em; 
    text-transform: uppercase; 
    color: rgba(255,255,255,.3); 
    padding: .75rem 1rem .3rem; 
}

.sa-nav-item { 
    display: flex; 
    align-items: center; 
    gap: 9px; 
    padding: .55rem 1rem; 
    font-size: 13px; 
    color: rgba(255,255,255,.65); 
    text-decoration: none; 
    border-radius: 6px; 
    margin: 1px .5rem; 
    transition: background .15s, color .15s; 
}

.sa-nav-item:hover { 
    background: rgba(255,255,255,.08); 
    color: #fff; 
}

.sa-nav-item.active { 
    background: rgba(255,255,255,.12); 
    color: #fff; 
    font-weight: 500; 
}

.sa-nav-item svg { 
    flex-shrink: 0; 
    opacity: .7; 
    width: 15px;
    height: 15px;
}

.sa-nav-item.active svg { 
    opacity: 1; 
}

.sa-divider { 
    height: 1px; 
    background: rgba(255,255,255,.08); 
    margin: 0.5rem 1rem; 
}

.sa-footer { 
    padding: .75rem; 
    border-top: 1px solid rgba(255,255,255,.08); 
}

.sa-user-card { 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    padding: .5rem .75rem; 
    background: rgba(255,255,255,.06); 
    border-radius: 6px; 
    margin-bottom: .5rem; 
}

.sa-avatar { 
    width: 28px; 
    height: 28px; 
    background: rgba(255,255,255,.15); 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 11px; 
    font-weight: 600; 
    color: #fff; 
    flex-shrink: 0; 
}

.sa-user-name { 
    font-size: 12px; 
    color: #fff; 
    font-weight: 500; 
}

.sa-user-role { 
    font-size: 10px; 
    color: rgba(255,255,255,.4); 
}

.sa-logout { 
    width: 100%; 
    background: none; 
    border: 1px solid rgba(255,255,255,.15); 
    border-radius: 6px; 
    padding: .45rem; 
    font-family: 'DM Sans', sans-serif;
    font-size: 12px; 
    color: rgba(255,255,255,.5); 
    cursor: pointer; 
    transition: all .15s; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 6px; 
}

.sa-logout:hover { 
    border-color: #ef4444; 
    color: #ef4444; 
}

/* Dark mode toggle button */
.sa-dark-toggle { 
    width: 100%; 
    background: none; 
    border: 1px solid rgba(255,255,255,.12); 
    border-radius: 6px; 
    padding: .4rem .75rem; 
    font-family: 'DM Sans', sans-serif;
    font-size: 11px; 
    color: rgba(255,255,255,.45); 
    cursor: pointer; 
    transition: all .15s; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    margin-bottom: .4rem; 
}

.sa-dark-toggle:hover { 
    border-color: rgba(255,255,255,.3); 
    color: rgba(255,255,255,.8); 
}

.sa-dark-toggle-pill { 
    width: 30px; 
    height: 16px; 
    background: rgba(255,255,255,.15); 
    border-radius: 8px; 
    position: relative; 
    transition: background .2s; 
    flex-shrink: 0; 
}

.sa-dark-toggle-pill::after { 
    content: ''; 
    position: absolute; 
    top: 2px; 
    left: 2px; 
    width: 12px; 
    height: 12px; 
    background: #fff; 
    border-radius: 50%; 
    transition: transform .2s; 
}

html.dark .sa-dark-toggle-pill { 
    background: #6366f1; 
}

html.dark .sa-dark-toggle-pill::after { 
    transform: translateX(14px); 
}

/* ── SCROLLBAR ──────────────────────────────────────────────── */
.sa-sidebar::-webkit-scrollbar {
    width: 4px;
}

.sa-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sa-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.15);
    border-radius: 2px;
}

.sa-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,.25);
}

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media (max-width: 768px) {
    .sa-sidebar {
        transform: translateX(-100%);
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3);
    }
    
    .sa-sidebar.mobile-open {
        transform: translateX(0);
    }
    
    /* Mobile toggle button */
    .sa-sidebar-toggle {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        width: 40px;
        height: 40px;
        background: #1a2744;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s;
    }
    
    .sa-sidebar-toggle:hover {
        background: rgba(255,255,255,.1);
    }
    
    .sa-sidebar-toggle svg {
        width: 18px;
        height: 18px;
        color: #fff;
    }
}

/* ── PRINT STYLES ───────────────────────────────────────────── */
@media print {
    .sa-sidebar {
        display: none;
    }
}
</style>

<aside class="sa-sidebar">
    <div class="sa-brand">
        <div class="sa-brand-top">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="LGU Carmen" class="sa-seal">
            <div>
                <h1>LGU Carmen<br>Burial System</h1>
            </div>
        </div>
    </div>

    <nav class="sa-nav">
        <div class="sa-nav-section">Overview</div>
        <a href="{{ route('superadmin.dashboard') }}"
           class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">Analytics</div>
        <a href="{{ route('superadmin.reports') }}"
            class="sa-nav-item {{ request()->routeIs('superadmin.reports') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Reports
        </a>
        <a href="{{ route('superadmin.activity') }}"
           class="sa-nav-item {{ request()->routeIs('superadmin.activity') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
            Activity Log
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">System</div>
        <a href="{{ route('settings.index') }}"
           class="sa-nav-item {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Settings
        </a>
        <a href="{{ route('superadmin.dataquality') }}"
   class="sa-nav-item {{ request()->routeIs('superadmin.dataquality') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M9 11l3 3L22 4"/>
                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
            </svg>
            Data Quality
        </a>
    </nav>

    <div class="sa-footer">
        {{-- Dark mode toggle --}}
        <button class="sa-dark-toggle" onclick="toggleDark()" id="darkToggleBtn">
            <span id="darkToggleLabel">🌙 Dark Mode</span>
            <div class="sa-dark-toggle-pill" id="darkPill"></div>
        </button>

        <div class="sa-user-card">
            <div class="sa-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="sa-user-name">{{ auth()->user()->name }}</div>
                <div class="sa-user-role">Super Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sa-logout">
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

<!-- Mobile Toggle Button -->
<button class="sa-sidebar-toggle" id="sidebarToggle" style="display: none;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
</button>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sa-sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main');
    
    function checkScreenSize() {
        if (window.innerWidth <= 768) {
            toggleBtn.style.display = 'flex';
            sidebar.classList.remove('mobile-open');
            if (mainContent) mainContent.style.marginLeft = '0';
        } else {
            toggleBtn.style.display = 'none';
            sidebar.classList.remove('mobile-open');
            if (mainContent) mainContent.style.marginLeft = '220px';
        }
    }
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
            if (sidebar.classList.contains('mobile-open')) {
                if (mainContent) mainContent.style.marginLeft = '220px';
            } else {
                if (mainContent) mainContent.style.marginLeft = '0';
            }
        });
    }
    
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            sidebar && 
            !sidebar.contains(event.target) && 
            toggleBtn && 
            !toggleBtn.contains(event.target) &&
            sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            if (mainContent) mainContent.style.marginLeft = '0';
        }
    });
    
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
});

// Dark mode functionality
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
