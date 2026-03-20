<style>
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
    @else
        <div class="nav-section">Permits</div>
        <a href="{{ route('permits.index') }}" class="nav-item {{ request()->routeIs('permits.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="nav-item {{ request()->routeIs('deceased.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Deceased Records
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
    @endif
</nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(str_replace('_',' ', auth()->user()->role)) }}</div>
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
<style>
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
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Settings
        </a>
    @else
        <div class="nav-section">Permits</div>
        <a href="{{ route('permits.index') }}" class="nav-item {{ request()->routeIs('permits.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Burial Permits
        </a>
        <a href="{{ route('deceased.index') }}" class="nav-item {{ request()->routeIs('deceased.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Deceased Records
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
        <div class="nav-section">System</div>
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Settings
        </a>
    @endif
</nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(str_replace('_',' ', auth()->user()->role)) }}</div>
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