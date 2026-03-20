<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cemetery Map — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #111827;
               -webkit-font-smoothing: antialiased; display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar { width: 220px; min-height: 100vh; background: #1a2744; display: flex;
                   flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50; }
        .sidebar-brand { padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-top { display: flex; align-items: center; gap: 8px; margin-bottom: .3rem; }
        .sidebar-seal { width: 34px; height: 34px; border-radius: 50%; object-fit: cover;
                        flex-shrink: 0; border: 1.5px solid rgba(255,255,255,0.2); }
        .sidebar-brand h1 { font-size: 12px; font-weight: 600; color: #fff; line-height: 1.3; }
        .sidebar-brand p  { font-size: 10px; color: rgba(255,255,255,.4); margin-top: 2px; padding-left: 42px; }
        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .nav-section { font-size: 9px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
                       color: rgba(255,255,255,.3); padding: .75rem 1rem .3rem; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: .55rem 1rem; font-size: 13px;
                    color: rgba(255,255,255,.65); text-decoration: none; border-radius: 6px;
                    margin: 1px .5rem; transition: background .15s, color .15s; }
        .nav-item:hover  { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; font-weight: 500; }
        .nav-item svg { flex-shrink: 0; opacity: .7; }
        .nav-item.active svg { opacity: 1; }
        .sidebar-footer { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .user-info { display: flex; align-items: center; gap: 8px; padding: .5rem .75rem;
                     background: rgba(255,255,255,.06); border-radius: 6px; margin-bottom: .5rem; }
        .user-avatar { width: 28px; height: 28px; background: rgba(255,255,255,.15); border-radius: 50%;
                       display: flex; align-items: center; justify-content: center;
                       font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0; }
        .user-name { font-size: 12px; color: #fff; font-weight: 500; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.4); }
        .btn-logout { width: 100%; background: none; border: 1px solid rgba(255,255,255,.15);
                      border-radius: 6px; padding: .45rem; font-family: 'Inter', sans-serif;
                      font-size: 12px; color: rgba(255,255,255,.5); cursor: pointer;
                      transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; }

        /* ── MAIN ── */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 52px;
                  display: flex; align-items: center; justify-content: space-between;
                  padding: 0 1.5rem; position: sticky; top: 0; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date  { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600;
                    padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        .content { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }

        /* ── STAT STRIP ── */
        .stat-strip { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; }
        .stat-box { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
                    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .75rem; }
        .stat-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .dot-gray   { background: #9ca3af; }
        .dot-green  { background: #10b981; }
        .dot-red    { background: #ef4444; }
        .dot-yellow { background: #f59e0b; }
        .stat-box-label { font-size: 11px; color: #9ca3af; font-weight: 500; }
        .stat-box-value { font-size: 22px; font-weight: 700; color: #111827; line-height: 1; }

        /* ── MAP CARD ── */
        .map-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .map-toolbar { padding: .7rem 1rem; border-bottom: 1px solid #f3f4f6;
                       display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
        .map-toolbar-title { font-size: 13px; font-weight: 600; color: #111827; margin-right: auto; }

        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .42rem .9rem;
               border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 12px;
               font-weight: 500; cursor: pointer; border: 1px solid #e5e7eb;
               background: #fff; color: #374151; transition: all .15s; }
        .btn:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-primary { background: #1a2744; color: #fff; border-color: #1a2744; }
        .btn-primary:hover { background: #243459; }
        .btn-danger  { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .btn-danger:hover { background: #fecaca; }
        .btn-active  { background: #dbeafe !important; color: #1e40af !important; border-color: #93c5fd !important; }

        .legend-strip { display: flex; align-items: center; gap: 1rem; }
        .legend-item  { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #6b7280; }
        .legend-dot   { width: 10px; height: 10px; border-radius: 50%; }

        #map { height: 560px; width: 100%; }

        /* ── SIDE PANEL ── */
        .side-panel {
            position: fixed; top: 52px; right: -360px; width: 345px;
            height: calc(100vh - 52px); background: #fff;
            border-left: 1px solid #e5e7eb; z-index: 300;
            display: flex; flex-direction: column;
            transition: right .28s cubic-bezier(.4,0,.2,1);
            box-shadow: -4px 0 24px rgba(0,0,0,.09);
        }
        .side-panel.open { right: 0; }
        .panel-head { padding: .9rem 1.1rem; border-bottom: 1px solid #f3f4f6;
                      display: flex; align-items: center; justify-content: space-between; background: #fafafa; }
        .panel-head h3 { font-size: 14px; font-weight: 700; color: #111827; }
        .panel-close { background: none; border: none; cursor: pointer; color: #9ca3af;
                       font-size: 20px; line-height: 1; transition: color .15s; padding: 0 4px; }
        .panel-close:hover { color: #374151; }
        .panel-body   { flex: 1; overflow-y: auto; padding: 1rem 1.1rem; display: flex; flex-direction: column; gap: .85rem; }
        .panel-footer { padding: .85rem 1.1rem; border-top: 1px solid #f3f4f6;
                        display: flex; gap: .5rem; justify-content: flex-end; background: #fafafa; }

        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #6b7280;
                      text-transform: uppercase; letter-spacing: .06em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827;
                        padding: .42rem .7rem; border: 1px solid #e5e7eb; border-radius: 6px;
                        outline: none; width: 100%; transition: border-color .15s, box-shadow .15s; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }

        .status-pill { display: inline-flex; align-items: center; font-size: 11px; font-weight: 600;
                       padding: 3px 10px; border-radius: 20px; }
        .pill-available { background: #d1fae5; color: #065f46; }
        .pill-occupied  { background: #fee2e2; color: #991b1b; }
        .pill-reserved  { background: #fef3c7; color: #92400e; }

        .info-row { display: flex; justify-content: space-between; align-items: flex-start;
                    padding: .4rem 0; border-bottom: 1px solid #f9fafb; }
        .info-row:last-child { border: none; }
        .info-key { font-size: 10px; font-weight: 700; color: #9ca3af;
                    text-transform: uppercase; letter-spacing: .06em; min-width: 80px; padding-top: 2px; }
        .info-val { font-size: 13px; font-weight: 500; color: #111827; text-align: right; }

        .section-div { font-size: 10px; font-weight: 700; color: #1a2744;
                       text-transform: uppercase; letter-spacing: .08em;
                       padding: .5rem 0 .3rem; border-bottom: 1.5px solid #e5e7eb; margin-top: .25rem; }

        /* Coord info box */
        .coord-box { font-size: 12px; color: #374151; background: #f0f4ff;
                     border: 1px solid #c7d2fe; border-radius: 6px;
                     padding: .6rem .8rem; display: flex; align-items: center; gap: 6px; }

        /* Deceased search */
        .search-wrap { position: relative; }
        .search-icon { position: absolute; left: 8px; top: 50%; transform: translateY(-50%);
                       color: #9ca3af; pointer-events: none; }
        .search-wrap .form-control { padding-left: 28px; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff;
                          border: 1px solid #e5e7eb; border-radius: 6px; z-index: 999;
                          box-shadow: 0 4px 12px rgba(0,0,0,.1); display: none;
                          max-height: 180px; overflow-y: auto; margin-top: 2px; }
        .search-results.open { display: block; }
        .sr-item { padding: .5rem .75rem; font-size: 13px; cursor: pointer;
                   border-bottom: 1px solid #f3f4f6; }
        .sr-item:last-child { border: none; }
        .sr-item:hover { background: #f0f4ff; }
        .sr-sub { font-size: 11px; color: #9ca3af; }

        /* Add-mode cursor */
        #map.adding { cursor: crosshair !important; }

        /* Toast */
        .toast-wrap { position: fixed; bottom: 1.5rem; left: calc(220px + 1.5rem); z-index: 9999; }
        .toast { padding: .7rem 1.1rem 1rem; border-radius: 8px; font-size: 13px; font-weight: 500;
                 box-shadow: 0 4px 14px rgba(0,0,0,.14); transform: translateY(20px);
                 opacity: 0; transition: all .3s; min-width: 220px; position: relative; overflow: hidden; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-green { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .toast-red   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .toast-bar { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
                     transform-origin: left; animation: drain 3.5s linear forwards; }
        .toast-green .toast-bar { background: #22c55e; }
        .toast-red   .toast-bar { background: #ef4444; }
        @keyframes drain { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* Add-mode banner */
        .add-banner { background: #1a2744; color: #fff; font-size: 13px; font-weight: 500;
                      text-align: center; padding: .55rem 1rem; display: none;
                      align-items: center; justify-content: center; gap: .5rem; }
        .add-banner.show { display: flex; }

        /* API key notice */
        .api-notice { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;
                      padding: .75rem 1rem; font-size: 13px; color: #92400e;
                      display: flex; align-items: flex-start; gap: 8px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
@include('partials.sidebar')

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Cemetery Map</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="content">

        {{-- API key notice --}}
        @if(!config('services.google_maps.key'))
        <div class="api-notice">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <strong>Google Maps API key not set.</strong>
                Add <code>GOOGLE_MAPS_KEY=AIza...</code> to your <code>.env</code> file, then add
                <code>'google_maps' => ['key' => env('GOOGLE_MAPS_KEY')]</code> to <code>config/services.php</code>.
                The map will still load but some features may be limited.
            </div>
        </div>
        @endif

        {{-- Stat strip --}}
        <div class="stat-strip">
            <div class="stat-box">
                <div class="stat-dot dot-gray"></div>
                <div><div class="stat-box-label">Total Plots</div>
                     <div class="stat-box-value" id="stat-total">{{ $stats['total'] }}</div></div>
            </div>
            <div class="stat-box">
                <div class="stat-dot dot-green"></div>
                <div><div class="stat-box-label">Available</div>
                     <div class="stat-box-value" id="stat-available">{{ $stats['available'] }}</div></div>
            </div>
            <div class="stat-box">
                <div class="stat-dot dot-red"></div>
                <div><div class="stat-box-label">Occupied</div>
                     <div class="stat-box-value" id="stat-occupied">{{ $stats['occupied'] }}</div></div>
            </div>
            <div class="stat-box">
                <div class="stat-dot dot-yellow"></div>
                <div><div class="stat-box-label">Reserved</div>
                     <div class="stat-box-value" id="stat-reserved">{{ $stats['reserved'] }}</div></div>
            </div>
        </div>

        {{-- Map card --}}
        <div class="map-card">
            {{-- Add-mode banner --}}
            <div class="add-banner" id="addBanner">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Click anywhere on the map to place a new plot marker
                <button class="btn" style="padding:2px 10px;font-size:11px;margin-left:.5rem"
                        onclick="cancelAddMode()">Cancel</button>
            </div>

            <div class="map-toolbar">
                <span class="map-toolbar-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Carmen Public Cemetery — Carmen, Davao del Norte
                </span>

                <button id="btnAddPlot" class="btn btn-primary" onclick="startAddMode()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Plot
                </button>

                <button class="btn" onclick="resetView()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                    Reset View
                </button>

                {{-- Map type toggle --}}
                <button id="btnSat" class="btn" onclick="toggleMapType()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    Satellite
                </button>

                <div class="legend-strip">
                    <div class="legend-item"><div class="legend-dot" style="background:#10b981"></div>Available</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div>Occupied</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div>Reserved</div>
                </div>
            </div>

            <div id="map"></div>
        </div>

    </div><!-- /content -->
</div><!-- /main -->

<!-- SIDE PANEL -->
<div class="side-panel" id="sidePanel">
    <div class="panel-head">
        <h3 id="panelTitle">Plot Details</h3>
        <button class="panel-close" onclick="closePanel()">×</button>
    </div>
    <div class="panel-body"   id="panelBody"></div>
    <div class="panel-footer" id="panelFooter"></div>
</div>

<!-- Toast -->
<div class="toast-wrap"><div class="toast" id="toast"></div></div>

{{-- Google Maps JS API --}}
<script>
    // Expose key to JS safely
    window.GOOGLE_MAPS_KEY = "{{ config('services.google_maps.key', '') }}";
</script>
<script>
// ── Config ──
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
const CEMETERY_LAT = 7.370585;   // Carmen Public Cemetery, Davao del Norte
const CEMETERY_LNG = 125.715360;

let map, addMode = false, pendingMarker = null;
const markersStore = {}; // id → google.maps.Marker

// ── Load Google Maps dynamically ──
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center:    { lat: CEMETERY_LAT, lng: CEMETERY_LNG },
        zoom:      19,
        mapTypeId: 'satellite',   // Start on satellite view
        tilt:      0,
        mapTypeControl: false,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl: true,
        gestureHandling: 'greedy',
    });

    // Click on map → place plot if in add mode
    map.addListener('click', (e) => {
        if (!addMode) return;
        placePendingMarker(e.latLng);
        openAddPanel(e.latLng.lat(), e.latLng.lng());
    });

    loadPlots();
}

function toggleMapType() {
    const btn = document.getElementById('btnSat');
    if (map.getMapTypeId() === 'satellite') {
        map.setMapTypeId('roadmap');
        btn.classList.remove('btn-active');
        btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg> Satellite`;
    } else {
        map.setMapTypeId('satellite');
        btn.classList.add('btn-active');
        btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg> Road Map`;
    }
}

function resetView() {
    map.setCenter({ lat: CEMETERY_LAT, lng: CEMETERY_LNG });
    map.setZoom(19);
}

// ── Marker icons ──
function markerIcon(status) {
    const colors = { available: '#10b981', occupied: '#ef4444', reserved: '#f59e0b' };
    const c = colors[status] || '#9ca3af';
    // SVG circle pin
    return {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: c,
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2.5,
        scale: 9,
    };
}

// ── Load all plots ──
function loadPlots() {
    fetch('{{ route("cemetery.plots") }}')
        .then(r => r.json())
        .then(geojson => {
            // Clear existing markers
            Object.values(markersStore).forEach(m => m.setMap(null));
            Object.keys(markersStore).forEach(k => delete markersStore[k]);

            let total = 0, av = 0, oc = 0, re = 0;

            geojson.features.forEach(f => {
                const p   = f.properties;
                const lat = f.geometry.coordinates[1];
                const lng = f.geometry.coordinates[0];
                if (!lat || !lng) return;

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                    map,
                    icon:  markerIcon(p.status),
                    title: p.plot_code,
                });

                marker.addListener('click', () => openViewPanel(p));
                markersStore[p.id] = marker;

                total++;
                if (p.status === 'available') av++;
                if (p.status === 'occupied')  oc++;
                if (p.status === 'reserved')  re++;
            });

            document.getElementById('stat-total').textContent     = total;
            document.getElementById('stat-available').textContent = av;
            document.getElementById('stat-occupied').textContent  = oc;
            document.getElementById('stat-reserved').textContent  = re;
        });
}

// ── Add-mode ──
function startAddMode() {
    addMode = true;
    document.getElementById('map').classList.add('adding');
    document.getElementById('addBanner').classList.add('show');
    document.getElementById('btnAddPlot').classList.add('btn-active');
    closePanel();
}
function cancelAddMode() {
    addMode = false;
    document.getElementById('map').classList.remove('adding');
    document.getElementById('addBanner').classList.remove('show');
    document.getElementById('btnAddPlot').classList.remove('btn-active');
    if (pendingMarker) { pendingMarker.setMap(null); pendingMarker = null; }
    closePanel();
}

function placePendingMarker(latLng) {
    if (pendingMarker) pendingMarker.setMap(null);
    pendingMarker = new google.maps.Marker({
        position: latLng,
        map,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: '#6366f1',
            fillOpacity: 1,
            strokeColor: '#fff',
            strokeWeight: 3,
            scale: 11,
        },
        animation: google.maps.Animation.BOUNCE,
        zIndex: 999,
    });
    // Stop bounce after 1 sec
    setTimeout(() => pendingMarker && pendingMarker.setAnimation(null), 1400);
}

// ── PANELS ──
function openViewPanel(p) {
    document.getElementById('panelTitle').textContent = `Plot ${p.plot_code}`;

    const statusPill = `<span class="status-pill pill-${p.status}">${cap(p.status)}</span>`;

    document.getElementById('panelBody').innerHTML = `
        <div class="section-div">Location</div>
        <div class="info-row"><span class="info-key">Plot Code</span><span class="info-val">${p.plot_code}</span></div>
        <div class="info-row"><span class="info-key">Section</span><span class="info-val">${p.section || '—'}</span></div>
        <div class="info-row"><span class="info-key">Row</span><span class="info-val">${p.row || '—'}</span></div>
        <div class="info-row"><span class="info-key">Column</span><span class="info-val">${p.column || '—'}</span></div>
        <div class="info-row"><span class="info-key">Status</span><span class="info-val">${statusPill}</span></div>
        ${p.deceased_name ? `
        <div class="section-div" style="margin-top:.5rem">Occupant</div>
        <div class="info-row"><span class="info-key">Name</span><span class="info-val">${p.deceased_name}</span></div>
        <div class="info-row"><span class="info-key">Date Died</span><span class="info-val">${p.date_of_death || '—'}</span></div>
        ` : ''}
        ${p.notes ? `
        <div class="section-div" style="margin-top:.5rem">Notes</div>
        <div style="font-size:13px;color:#374151;line-height:1.6;margin-top:.25rem">${p.notes}</div>
        ` : ''}
    `;

    const pJson = JSON.stringify(p).replace(/"/g, '&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn btn-danger" onclick="deletePlot(${p.id})">Delete</button>
        <button class="btn btn-primary" onclick="openEditPanel(JSON.parse(this.dataset.p))"
                data-p="${pJson}">Edit</button>
    `;

    document.getElementById('sidePanel').classList.add('open');
}

function openAddPanel(lat, lng) {
    document.getElementById('panelTitle').textContent = 'New Plot';

    document.getElementById('panelBody').innerHTML = `
        <div class="coord-box">
            📍 <strong>${lat.toFixed(6)}</strong>, <strong>${lng.toFixed(6)}</strong>
        </div>
        <div class="form-group">
            <label class="form-label">Plot Code <span style="color:#ef4444">*</span></label>
            <input id="f_code" class="form-control" placeholder="e.g. A-01" required autofocus>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group">
                <label class="form-label">Section</label>
                <input id="f_section" class="form-control" placeholder="e.g. A">
            </div>
            <div class="form-group">
                <label class="form-label">Row</label>
                <input id="f_row" class="form-control" placeholder="e.g. 1">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select id="f_status" class="form-control" onchange="toggleDeceasedField('f')">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="reserved">Reserved</option>
            </select>
        </div>
        <div class="form-group" id="f_deceased_group" style="display:none">
            <label class="form-label">Assign Deceased</label>
            <div class="search-wrap">
                <svg class="search-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="f_dsearch" class="form-control" placeholder="Type name to search…"
                       oninput="searchDeceased(this.value,'f')" autocomplete="off">
                <div class="search-results" id="f_dresults"></div>
            </div>
            <input type="hidden" id="f_deceased_id">
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea id="f_notes" class="form-control" rows="2" style="resize:vertical"
                      placeholder="Optional notes…"></textarea>
        </div>
    `;

    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" onclick="cancelAddMode()">Cancel</button>
        <button class="btn btn-primary" onclick="savePlot(${lat},${lng})">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Save Plot
        </button>
    `;

    document.getElementById('sidePanel').classList.add('open');
}

function openEditPanel(p) {
    document.getElementById('panelTitle').textContent = `Edit — ${p.plot_code}`;

    document.getElementById('panelBody').innerHTML = `
        <div class="form-group">
            <label class="form-label">Status</label>
            <select id="e_status" class="form-control" onchange="toggleDeceasedField('e')">
                <option value="available" ${p.status==='available'?'selected':''}>Available</option>
                <option value="occupied"  ${p.status==='occupied' ?'selected':''}>Occupied</option>
                <option value="reserved"  ${p.status==='reserved' ?'selected':''}>Reserved</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group">
                <label class="form-label">Section</label>
                <input id="e_section" class="form-control" value="${p.section||''}">
            </div>
            <div class="form-group">
                <label class="form-label">Row</label>
                <input id="e_row" class="form-control" value="${p.row||''}">
            </div>
        </div>
        <div class="form-group" id="e_deceased_group"
             style="${p.status==='occupied'?'':'display:none'}">
            <label class="form-label">Assign Deceased</label>
            <div class="search-wrap">
                <svg class="search-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="e_dsearch" class="form-control"
                       value="${p.deceased_name||''}" placeholder="Type name to search…"
                       oninput="searchDeceased(this.value,'e')" autocomplete="off">
                <div class="search-results" id="e_dresults"></div>
            </div>
            <input type="hidden" id="e_deceased_id" value="${p.deceased_id||''}">
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea id="e_notes" class="form-control" rows="2"
                      style="resize:vertical">${p.notes||''}</textarea>
        </div>
    `;

    const pJson = JSON.stringify(p).replace(/"/g, '&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" onclick="openViewPanel(JSON.parse(this.dataset.p))"
                data-p="${pJson}">Cancel</button>
        <button class="btn btn-primary" onclick="updatePlot(${p.id})">Save Changes</button>
    `;
}

function closePanel() {
    document.getElementById('sidePanel').classList.remove('open');
}

// ── CRUD ──
function savePlot(lat, lng) {
    const code = (document.getElementById('f_code')?.value || '').trim();
    if (!code) { showToast('Plot code is required.', 'red'); return; }

    fetch('{{ route("cemetery.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            plot_code:   code,
            section:     document.getElementById('f_section')?.value.trim() || null,
            row:         document.getElementById('f_row')?.value.trim()     || null,
            latitude:    lat,
            longitude:   lng,
            status:      document.getElementById('f_status')?.value || 'available',
            deceased_id: document.getElementById('f_deceased_id')?.value || null,
            notes:       document.getElementById('f_notes')?.value.trim() || null,
        }),
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) { showToast(d.message || 'Error saving.', 'red'); return; }
        cancelAddMode();
        loadPlots();
        showToast(`Plot "${code}" saved!`, 'green');
    })
    .catch(() => showToast('Network error. Try again.', 'red'));
}

function updatePlot(id) {
    fetch(`/cemetery/plots/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            status:      document.getElementById('e_status')?.value,
            section:     document.getElementById('e_section')?.value.trim() || null,
            row:         document.getElementById('e_row')?.value.trim()     || null,
            deceased_id: document.getElementById('e_deceased_id')?.value    || null,
            notes:       document.getElementById('e_notes')?.value.trim()   || null,
        }),
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) { showToast('Error updating.', 'red'); return; }
        closePanel();
        loadPlots();
        showToast('Plot updated!', 'green');
    })
    .catch(() => showToast('Network error.', 'red'));
}

function deletePlot(id) {
    if (!confirm('Delete this plot? This cannot be undone.')) return;
    fetch(`/cemetery/plots/${id}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) { showToast('Error deleting.', 'red'); return; }
        closePanel();
        loadPlots();
        showToast('Plot deleted.', 'green');
    });
}

// ── Deceased search ──
let decTimer;
function searchDeceased(q, prefix) {
    clearTimeout(decTimer);
    const resEl = document.getElementById(`${prefix}_dresults`);
    if (q.length < 2) { resEl.classList.remove('open'); return; }
    decTimer = setTimeout(() => {
        fetch(`{{ route("cemetery.search-deceased") }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(list => {
                if (!list.length) { resEl.classList.remove('open'); return; }
                resEl.innerHTML = list.map(d =>
                    `<div class="sr-item"
                          onclick="selectDeceased(${d.id},'${esc(d.name)}','${prefix}')">
                        <div>${d.name}</div>
                        <div class="sr-sub">Died: ${d.dod||'—'}</div>
                     </div>`
                ).join('');
                resEl.classList.add('open');
            });
    }, 280);
}
function selectDeceased(id, name, prefix) {
    document.getElementById(`${prefix}_dsearch`).value    = name;
    document.getElementById(`${prefix}_deceased_id`).value = id;
    document.getElementById(`${prefix}_dresults`).classList.remove('open');
}
function toggleDeceasedField(prefix) {
    const status = document.getElementById(`${prefix}_status`)?.value;
    const group  = document.getElementById(`${prefix}_deceased_group`);
    if (group) group.style.display = status === 'occupied' ? '' : 'none';
}

// ── Utils ──
function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
function esc(s) { return (s||'').replace(/'/g, "\\'"); }

let toastTimer;
function showToast(msg, type = 'green') {
    const el = document.getElementById('toast');
    el.innerHTML = `${msg}<div class="toast-bar"></div>`;
    el.className = `toast toast-${type} show`;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('show'), 3600);
}

// Keyboard & outside-click
document.addEventListener('keydown', e => { if (e.key === 'Escape') { cancelAddMode(); closePanel(); } });
document.addEventListener('click', e => {
    if (!e.target.closest('.search-wrap'))
        document.querySelectorAll('.search-results').forEach(el => el.classList.remove('open'));
});
</script>

{{-- Load Google Maps API with callback --}}
@if(config('services.google_maps.key'))
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&v=weekly">
</script>
@else
{{-- Fallback: load without key (works for localhost testing, shows watermark) --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?callback=initMap&v=weekly">
</script>
@endif

</body>
</html>