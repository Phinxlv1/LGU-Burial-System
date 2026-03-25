<style>
/* ── SUPER ADMIN SIDEBAR ── */
.sa-sidebar {
    width: 240px;
    min-height: 100vh;
    background: #0c0f1a;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0;
    z-index: 50;
    border-right: 1px solid rgba(255,255,255,.05);
}

/* Gradient accent bar at top */
.sa-sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #e01a6e, #f59e0b);
}

.sa-brand {
    padding: 1.4rem 1.25rem 1rem;
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.sa-brand-top {
    display: flex; align-items: center; gap: 10px; margin-bottom: .4rem;
}
.sa-seal {
    width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
    flex-shrink: 0;
    border: 1.5px solid rgba(255,255,255,.15);
    filter: brightness(.9);
}
.sa-brand-text h1 {
    font-size: 12px; font-weight: 700; color: #fff; line-height: 1.3;
    letter-spacing: .01em;
}
.sa-role-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: linear-gradient(90deg, rgba(99,102,241,.25), rgba(224,26,110,.2));
    border: 1px solid rgba(99,102,241,.4);
    border-radius: 20px;
    padding: 2px 8px;
    font-size: 9px; font-weight: 800; color: #a5b4fc;
    letter-spacing: .08em; text-transform: uppercase;
    margin-top: 5px;
}
.sa-role-badge::before {
    content: '⚡';
    font-size: 9px;
}

/* NAV */
.sa-nav { flex: 1; padding: .85rem 0; overflow-y: auto; }

.sa-nav-section {
    font-size: 8.5px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: rgba(255,255,255,.2);
    padding: .85rem 1.25rem .35rem;
}

.sa-nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: .52rem 1rem;
    font-size: 12.5px; color: rgba(255,255,255,.5);
    text-decoration: none;
    border-radius: 7px;
    margin: 1px .65rem;
    transition: background .15s, color .15s;
    position: relative;
}
.sa-nav-item:hover {
    background: rgba(255,255,255,.06);
    color: rgba(255,255,255,.9);
}
.sa-nav-item.active {
    background: rgba(99,102,241,.15);
    color: #a5b4fc;
    font-weight: 600;
}
.sa-nav-item.active::before {
    content: '';
    position: absolute; left: -0.65rem; top: 50%; transform: translateY(-50%);
    width: 3px; height: 60%;
    background: linear-gradient(180deg, #6366f1, #e01a6e);
    border-radius: 0 2px 2px 0;
}
.sa-nav-item svg { flex-shrink: 0; opacity: .6; }
.sa-nav-item.active svg { opacity: 1; }
.sa-nav-item .nav-count {
    margin-left: auto;
    font-size: 10px; font-weight: 700;
    background: rgba(99,102,241,.25);
    color: #a5b4fc;
    padding: 1px 6px; border-radius: 10px;
    min-width: 20px; text-align: center;
}

/* DIVIDER */
.sa-divider {
    height: 1px;
    background: rgba(255,255,255,.05);
    margin: .5rem 1.25rem;
}

/* FOOTER */
.sa-footer {
    padding: .9rem;
    border-top: 1px solid rgba(255,255,255,.06);
}
.sa-user-card {
    display: flex; align-items: center; gap: 9px;
    padding: .6rem .85rem;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 8px;
    margin-bottom: .6rem;
}
.sa-avatar {
    width: 30px; height: 30px;
    background: linear-gradient(135deg, #6366f1, #e01a6e);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.sa-user-name { font-size: 12px; color: #fff; font-weight: 600; }
.sa-user-role { font-size: 10px; color: #6366f1; font-weight: 600; margin-top: 1px; }

.sa-logout {
    width: 100%;
    background: none;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 7px;
    padding: .45rem;
    font-family: inherit;
    font-size: 12px;
    color: rgba(255,255,255,.35);
    cursor: pointer;
    transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.sa-logout:hover { border-color: #ef4444; color: #ef4444; }
</style>

<aside class="sa-sidebar">
    <div class="sa-brand">
        <div class="sa-brand-top">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sa-seal">
            <div class="sa-brand-text">
                <h1>LGU Carmen<br>Burial System</h1>
            </div>
        </div>
        <div class="sa-role-badge">Super Administrator</div>
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

        <div class="sa-nav-section">Records</div>
        <a href="{{ route('permits.index') }}"
           class="sa-nav-item {{ request()->routeIs('permits.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}"
           class="sa-nav-item {{ request()->routeIs('deceased.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Deceased Records
        </a>
        <a href="{{ route('cemetery.map') }}"
           class="sa-nav-item {{ request()->routeIs('cemetery.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Cemetery Map
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">Analytics</div>
        <a href="{{ route('reports.index') }}"
           class="sa-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Reports
        </a>
        <a href="{{ route('superadmin.export') }}" class="sa-nav-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export PDF
        </a>

        <div class="sa-divider"></div>

        <div class="sa-nav-section">System</div>
        <a href="{{ route('admin.users.index') }}"
           class="sa-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            User Management
        </a>
        <a href="{{ route('settings.index') }}"
           class="sa-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Settings
        </a>
    </nav>

    <div class="sa-footer">
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