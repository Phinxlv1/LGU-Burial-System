<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Map — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827; -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p  { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav      { flex: 1; padding: .75rem 0; }
        .nav-section      { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item         { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px; color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px; margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover   { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active  { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg     { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer   { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info        { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem; background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar      { width: 28px; height: 28px; background: rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
        .user-name        { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role        { font-size: 10px; color: rgba(255,255,255,.4); }
        .btn-logout       { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15); border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }
        .main  { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar{ background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px; display: flex; align-items: center; padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .content { padding: 1.5rem; }
        .placeholder { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 3rem; text-align: center; color: #6b7280; }
        .placeholder svg { margin: 0 auto 1rem; display: block; opacity: .3; }
        .placeholder h3 { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: .4rem; }
        .placeholder p  { font-size: 13px; line-height: 1.6; }
    </style>
</head>
<body>

<aside class="sidebar">
    @include('partials.sidebar')
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">Cemetery Map</div>
    </div>
    <div class="content">
        <div class="placeholder">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <h3>Cemetery Map</h3>
            <p>Interactive cemetery plot map coming soon.<br>This feature is under development.</p>
        </div>
    </div>
</div>

</body>
</html>