<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cemetery Map — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Inter', sans-serif; color: #111827; -webkit-font-smoothing: antialiased; display: flex; }

        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #f0f0f0; height: 54px; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date  { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        .map-wrap { position: relative; flex: 1; overflow: hidden; }
        #map { width: 100%; height: 100%; }
        #map.adding { cursor: crosshair !important; }

        /* ── FLOAT BAR ── */
        .map-float-bar {
            position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
            z-index: 10; display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.98); border-radius: 16px;
            padding: 8px 16px; box-shadow: 0 4px 24px rgba(0,0,0,.14);
            backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.9);
            flex-wrap: wrap; max-width: calc(100% - 2rem);
        }
        .float-bar-title { font-size: 12px; font-weight: 700; color: #1a2744; padding-right: 10px; border-right: 1px solid #e5e7eb; margin-right: 2px; }
        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .4rem .9rem; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid #e5e7eb; background: #fff; color: #374151; transition: all .15s; white-space: nowrap; text-decoration: none; }
        .btn:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-primary { background: #1a2744; color: #fff; border-color: #1a2744; }
        .btn-primary:hover { background: #243459; }
        .btn-active { background: #dbeafe !important; color: #1e40af !important; border-color: #93c5fd !important; }
        .style-pills { display: flex; gap: 3px; }
        .style-pill { padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; cursor: pointer; border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280; transition: all .15s; }
        .style-pill:hover { border-color: #1a2744; color: #1a2744; }
        .style-pill.active { background: #1a2744; color: #fff; border-color: #1a2744; }

        /* ── LEGEND ── */
        .float-legend {
            position: absolute; bottom: 28px; left: 14px; z-index: 10;
            background: rgba(12,24,52,.92); border-radius: 18px;
            padding: 14px 18px; box-shadow: 0 8px 32px rgba(0,0,0,.35);
            backdrop-filter: blur(16px); min-width: 168px;
            border: 1px solid rgba(255,255,255,.08);
        }
        .legend-title { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 10px; }
        .legend-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
        .legend-row:last-child { margin-bottom: 0; }
        .legend-dot { width: 11px; height: 11px; border-radius: 50%; border: 2px solid rgba(255,255,255,.3); flex-shrink: 0; }
        .legend-label { font-size: 12px; color: rgba(255,255,255,.85); font-weight: 500; flex: 1; margin-left: 7px; }
        .legend-count { font-size: 16px; font-weight: 800; color: #fff; }
        .legend-divider { height: 1px; background: rgba(255,255,255,.1); margin: 8px 0; }
        .legend-total-row { display: flex; align-items: center; justify-content: space-between; }
        .legend-total-label { font-size: 11px; color: rgba(255,255,255,.5); font-weight: 600; text-transform: uppercase; }
        .legend-total-val { font-size: 20px; font-weight: 800; color: #fff; }

        /* ── ADD BANNER ── */
        .add-banner { position: absolute; top: 0; left: 0; right: 0; z-index: 20; background: #1a2744; color: #fff; font-size: 13px; font-weight: 500; padding: .6rem 1rem; display: none; align-items: center; justify-content: center; gap: .6rem; }
        .add-banner.show { display: flex; }

        /* ── SIDE PANEL ── */
        .side-panel { position: absolute; top: 0; right: -380px; width: 360px; height: 100%; background: #fff; border-left: 1px solid #e5e7eb; z-index: 30; display: flex; flex-direction: column; transition: right .3s cubic-bezier(.4,0,.2,1); box-shadow: -8px 0 40px rgba(0,0,0,.14); }
        .side-panel.open { right: 0; }
        .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .panel-head h3 { font-size: 14px; font-weight: 700; color: #111827; }
        .panel-close { background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 20px; line-height: 1; }
        .panel-close:hover { color: #374151; }
        .panel-body { flex: 1; overflow-y: auto; padding: 1rem 1.1rem; display: flex; flex-direction: column; gap: .85rem; }
        .panel-footer { padding: .85rem 1.1rem; border-top: 1px solid #f3f4f6; display: flex; gap: .5rem; justify-content: flex-end; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .06em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .8rem; border: 1px solid #e5e7eb; border-radius: 8px; outline: none; width: 100%; transition: border-color .15s; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .status-pill { display: inline-flex; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .pill-available { background: #d1fae5; color: #065f46; }
        .pill-occupied  { background: #fee2e2; color: #991b1b; }
        .pill-reserved  { background: #fef3c7; color: #92400e; }

        /* ── GRID OVERLAY PANEL (inside side panel) ── */
        .grid-section-list { display: flex; flex-direction: column; gap: .5rem; }
        .grid-sec-item { border: 1px solid #e5e7eb; border-radius: 8px; padding: .65rem .85rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all .15s; }
        .grid-sec-item:hover { border-color: #1a2744; background: #f0f4ff; }
        .grid-sec-item.active { border-color: #1a2744; background: #eff6ff; }
        .grid-sec-name  { font-size: 13px; font-weight: 600; color: #111827; }
        .grid-sec-meta  { font-size: 11px; color: #9ca3af; margin-top: 2px; }
        .grid-sec-stats { display: flex; gap: .4rem; }
        .gss { font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 3px; }
        .gss-g { background: #d1fae5; color: #065f46; }
        .gss-r { background: #fee2e2; color: #991b1b; }
        .gss-y { background: #fef3c7; color: #92400e; }

        /* ── NICHE MINI GRID in panel ── */
        .mini-grid-wrap { overflow-x: auto; padding: .5rem 0; }
        .mini-grid { display: flex; flex-direction: column; gap: 3px; width: max-content; }
        .mini-row   { display: flex; gap: 3px; }
        .mini-cell  {
            width: 22px; height: 17px; border-radius: 3px; border: 1px solid #e5e7eb;
            background: #f9fafb; cursor: pointer; transition: all .1s; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 7px;
        }
        .mini-cell:hover { transform: scale(1.2); z-index: 2; position: relative; }
        .mini-cell.available { background: #d1fae5; border-color: #6ee7b7; }
        .mini-cell.occupied  { background: #fee2e2; border-color: #fca5a5; }
        .mini-cell.occupied::after { content: '✕'; color: #ef4444; font-size: 8px; font-weight: 900; }
        .mini-cell.reserved  { background: #fef3c7; border-color: #fde68a; }

        /* Paint pills in panel */
        .paint-bar { display: flex; gap: .4rem; flex-wrap: wrap; }
        .ppill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; cursor: pointer; border: 2px solid transparent; transition: all .15s; }
        .ppill.available { background: #d1fae5; color: #065f46; }
        .ppill.available.on { border-color: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.2); }
        .ppill.occupied  { background: #fee2e2; color: #991b1b; }
        .ppill.occupied.on  { border-color: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,.2); }
        .ppill.reserved  { background: #fef3c7; color: #92400e; }
        .ppill.reserved.on  { border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,.2); }
        .ppill.erase     { background: #f3f4f6; color: #6b7280; }
        .ppill.erase.on  { border-color: #9ca3af; }

        /* Section divider */
        .sdiv { font-size: 10px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .4rem 0 .2rem; border-bottom: 1.5px solid #e5e7eb; }

        /* Toast */
        .toast-wrap { position: absolute; bottom: 28px; right: 20px; z-index: 50; }
        .toast { padding: .7rem 1.1rem; border-radius: 10px; font-size: 13px; font-weight: 500; box-shadow: 0 4px 14px rgba(0,0,0,.18); transform: translateY(16px); opacity: 0; transition: all .3s; min-width: 200px; position: relative; overflow: hidden; background: #fff; border: 1px solid #e5e7eb; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-green { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .toast-red   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

        /* Search */
        .search-wrap { position: relative; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; z-index: 999; box-shadow: 0 8px 24px rgba(0,0,0,.12); display: none; max-height: 180px; overflow-y: auto; margin-top: 4px; }
        .search-results.open { display: block; }
        .sr-item { padding: .5rem .75rem; font-size: 13px; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
        .sr-item:last-child { border: none; }
        .sr-item:hover { background: #f0f4ff; }
        .sr-sub { font-size: 11px; color: #9ca3af; }

        /* Info row */
        .info-row { display: flex; justify-content: space-between; padding: .4rem 0; border-bottom: 1px solid #f9fafb; }
        .info-row:last-child { border: none; }
        .info-key { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; min-width: 80px; }
        .info-val { font-size: 13px; font-weight: 500; color: #111827; text-align: right; }

        /* Dark mode */
        html.dark body { background: #0f1117 !important; }
        html.dark .topbar { background: #1a1d27 !important; border-color: #2d3148 !important; }
        html.dark .topbar-title { color: #e2e8f0 !important; }
        html.dark .topbar-date  { color: #64748b !important; }
        html.dark .map-float-bar { background: rgba(30,33,48,.97) !important; border-color: rgba(255,255,255,.08) !important; }
        html.dark .float-bar-title { color: #e2e8f0 !important; border-right-color: #2d3148 !important; }
        html.dark .btn { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
        html.dark .btn:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
        html.dark .btn-primary { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
        html.dark .style-pill { background: #252840 !important; border-color: #374151 !important; color: #94a3b8 !important; }
        html.dark .style-pill.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
        html.dark .side-panel { background: #1e2130 !important; border-color: #2d3148 !important; }
        html.dark .panel-head { border-bottom-color: #2d3148 !important; }
        html.dark .panel-head h3 { color: #e2e8f0 !important; }
        html.dark .panel-close { color: #64748b !important; }
        html.dark .panel-footer { border-top-color: #2d3148 !important; background: #181b29 !important; }
        html.dark .form-label { color: #64748b !important; }
        html.dark .form-control { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
        html.dark .grid-sec-item { border-color: #2d3148 !important; }
        html.dark .grid-sec-item:hover { background: #1e2d6b !important; border-color: #6366f1 !important; }
        html.dark .grid-sec-name { color: #e2e8f0 !important; }
        html.dark .mini-cell { background: #252840 !important; border-color: #374151 !important; }
        html.dark .sdiv { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
        html.dark .info-key { color: #64748b !important; }
        html.dark .info-val { color: #e2e8f0 !important; }
        html.dark .info-row { border-color: #2d3148 !important; }
        html.dark .search-results { background: #1e2130 !important; border-color: #2d3148 !important; }
        html.dark .sr-item { border-color: #2d3148 !important; color: #cbd5e1 !important; }
        html.dark .sr-item:hover { background: #252840 !important; }
        html.dark .sr-sub { color: #64748b !important; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Cemetery Map</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <span class="role-tag">Admin</span>
    </div>

    <div class="map-wrap">

        <div class="add-banner" id="addBanner">
            📍 Click on the map to place a new plot marker
            <button class="btn" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:#fff" onclick="cancelAddMode()">Cancel</button>
        </div>

        <div class="map-float-bar">
            <span class="float-bar-title">Carmen Public Cemetery</span>
            <button id="btnAdd" class="btn btn-primary" onclick="startAddMode()">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Plot
            </button>
            <button class="btn" onclick="openGridPanel()">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Niche Grid
            </button>
            <button class="btn" onclick="resetView()">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                Reset
            </button>
            <div class="style-pills">
                <button class="style-pill active" id="pill-satellite" onclick="setMapType('satellite')">🛰 Satellite</button>
                <button class="style-pill" id="pill-hybrid"    onclick="setMapType('hybrid')">🌍 Hybrid</button>
                <button class="style-pill" id="pill-roadmap"   onclick="setMapType('roadmap')">🗺 Road</button>
            </div>
        </div>

        <div class="float-legend">
            <div class="legend-title">Plot Status</div>
            <div class="legend-row"><div style="display:flex;align-items:center"><div class="legend-dot" style="background:#10b981"></div><span class="legend-label">Available</span></div><span class="legend-count" id="stat-available">0</span></div>
            <div class="legend-row"><div style="display:flex;align-items:center"><div class="legend-dot" style="background:#ef4444"></div><span class="legend-label">Occupied</span></div><span class="legend-count" id="stat-occupied">0</span></div>
            <div class="legend-row"><div style="display:flex;align-items:center"><div class="legend-dot" style="background:#f59e0b"></div><span class="legend-label">Reserved</span></div><span class="legend-count" id="stat-reserved">0</span></div>
            <div class="legend-divider"></div>
            <div class="legend-total-row"><span class="legend-total-label">Total</span><span class="legend-total-val" id="stat-total">0</span></div>
        </div>

        <div id="map"></div>

        {{-- SIDE PANEL --}}
        <div class="side-panel" id="sidePanel">
            <div class="panel-head">
                <h3 id="panelTitle">Details</h3>
                <button class="panel-close" onclick="closePanel()">×</button>
            </div>
            <div class="panel-body" id="panelBody"></div>
            <div class="panel-footer" id="panelFooter"></div>
        </div>

        <div class="toast-wrap"><div class="toast" id="toast"></div></div>

    </div>
</div>

<script>
const CSRF         = document.querySelector('meta[name="csrf-token"]').content;
const CEMETERY_LAT = 7.370672;
const CEMETERY_LNG = 125.714882;
const GRID_KEY     = 'lgu_cemetery_grid_{{ auth()->id() }}';

let map, streetView, addMode = false, pendingMarker = null;
const markersStore = {};

// ── Load grid data from localStorage ──
function loadGridData() {
    try { return JSON.parse(localStorage.getItem(GRID_KEY) || '[]'); } catch(e) { return []; }
}

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: CEMETERY_LAT, lng: CEMETERY_LNG },
        zoom: 19,
        mapTypeId: 'satellite',
        tilt: 0,
        mapTypeControl: false,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl: true,
        gestureHandling: 'greedy',
        zoomControlOptions:       { position: google.maps.ControlPosition.RIGHT_BOTTOM },
        streetViewControlOptions: { position: google.maps.ControlPosition.RIGHT_BOTTOM },
        fullscreenControlOptions: { position: google.maps.ControlPosition.RIGHT_TOP },
    });

    streetView = map.getStreetView();
    streetView.addListener('visible_changed', () => {
        if (streetView.getVisible() && addMode) streetView.setVisible(false);
    });

    map.addListener('click', (e) => {
        if (!addMode) return;
        if (streetView.getVisible()) return;
        placePendingMarker(e.latLng);
        openAddPanel(e.latLng.lat(), e.latLng.lng());
    });

    loadPlots();

    // ── Define NicheOverlay HERE so google.maps.OverlayView is guaranteed to exist ──
    window.NicheOverlay = class extends google.maps.OverlayView {
        constructor(section, position) {
            super();
            this.section  = section;
            this.position = position;
            this.div      = null;
        }

        onAdd() {
            const div = document.createElement('div');
            div.style.cssText = `
                position: absolute;
                cursor: move;
                user-select: none;
                background: rgba(26,39,68,0.92);
                border: 2px solid rgba(255,255,255,0.5);
                border-radius: 8px;
                padding: 6px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.6);
                min-width: 80px;
                z-index: 999;
            `;

            // Header (drag handle)
            const header = document.createElement('div');
            header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;cursor:move;';
            header.innerHTML = `
                <span style="font-size:10px;font-weight:700;color:#fff;letter-spacing:.05em;white-space:nowrap">${esc(this.section.name)}</span>
                <button onclick="removeGridOverlay('${this.section.id}')" style="background:rgba(239,68,68,.5);border:none;color:#fff;width:16px;height:16px;border-radius:3px;cursor:pointer;font-size:11px;line-height:1;margin-left:6px;flex-shrink:0">×</button>
            `;
            div.appendChild(header);

            // Niche grid
            const grid = document.createElement('div');
            grid.id = 'overlay_grid_' + this.section.id;
            grid.style.cssText = 'display:flex;flex-direction:column;gap:2px;';
            this._renderGrid(grid);
            div.appendChild(grid);

            // Drag logic
            let dragging=false, startX, startY, startLat, startLng;
            header.addEventListener('mousedown', (e)=>{
                dragging=true; startX=e.clientX; startY=e.clientY;
                startLat=this.position.lat; startLng=this.position.lng;
                e.preventDefault(); e.stopPropagation();
            });
            const onMove = (e)=>{
                if(!dragging) return;
                const proj = this.getProjection();
                if(!proj) return;
                const startPx = proj.fromLatLngToDivPixel(new google.maps.LatLng(startLat, startLng));
                const newPx   = new google.maps.Point(startPx.x+(e.clientX-startX), startPx.y+(e.clientY-startY));
                const newLatLng = proj.fromDivPixelToLatLng(newPx);
                this.position = { lat: newLatLng.lat(), lng: newLatLng.lng() };
                this.draw();
                saveOverlayPosition(this.section.id, this.position);
            };
            const onUp = ()=>{ dragging=false; };
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);

            this.div = div;
            this.getPanes().overlayMouseTarget.appendChild(div);
        }

        _renderGrid(container) {
            const sec = this.section;
            container.innerHTML = '';
            const size = Math.max(8, Math.min(16, Math.floor(120/Math.max(sec.cols,1))));
            for(let r=1;r<=sec.rows;r++){
                const row = document.createElement('div');
                row.style.cssText = 'display:flex;gap:2px;';
                for(let c=1;c<=sec.cols;c++){
                    const key  = `${r}-${c}`;
                    const cell = (sec.cells||{})[key]||{};
                    const niche= document.createElement('div');
                    niche.style.cssText = `width:${size}px;height:${Math.round(size*.65)}px;border-radius:1px;border:1px solid rgba(255,255,255,.15);cursor:pointer;transition:transform .1s;flex-shrink:0;`;
                    const colors = { available:'rgba(16,185,129,.75)', occupied:'rgba(239,68,68,.75)', reserved:'rgba(245,158,11,.75)' };
                    niche.style.background = colors[cell.status] || 'rgba(255,255,255,.12)';
                    niche.title = cell.label ? `${cell.label} (${cell.status||'empty'})` : `${sec.name} R${r}-C${c}`;
                    niche.addEventListener('click', (e)=>{ e.stopPropagation(); openNicheDetail(sec.id, key, cell, sec.name); });
                    niche.addEventListener('mouseover',()=>{ niche.style.transform='scale(1.4)'; niche.style.zIndex='2'; niche.style.position='relative'; });
                    niche.addEventListener('mouseout', ()=>{ niche.style.transform='scale(1)'; });
                    row.appendChild(niche);
                }
                container.appendChild(row);
            }
        }

        draw() {
            if(!this.div) return;
            const proj = this.getProjection();
            if(!proj) return;
            const px = proj.fromLatLngToDivPixel(new google.maps.LatLng(this.position.lat, this.position.lng));
            if(!px) return;
            this.div.style.left = px.x + 'px';
            this.div.style.top  = px.y + 'px';
        }

        onRemove() {
            if(this.div && this.div.parentNode) this.div.parentNode.removeChild(this.div);
            this.div = null;
        }

        refresh() {
            this.section = loadGridData().find(s=>s.id===this.section.id) || this.section;
            const grid = document.getElementById('overlay_grid_' + this.section.id);
            if(grid) this._renderGrid(grid);
        }
    };

    renderGridOverlays();
}

// ── Map controls ──
function setMapType(type) {
    map.setMapTypeId(type);
    document.querySelectorAll('.style-pill').forEach(p => p.classList.remove('active'));
    document.getElementById('pill-' + type).classList.add('active');
}
function resetView() {
    if (streetView && streetView.getVisible()) streetView.setVisible(false);
    map.panTo({ lat: CEMETERY_LAT, lng: CEMETERY_LNG });
    map.setZoom(19);
}

// ── Markers ──
function markerIcon(status) {
    const colors = { available:'#10b981', occupied:'#ef4444', reserved:'#f59e0b' };
    return { path: google.maps.SymbolPath.CIRCLE, fillColor: colors[status]||'#9ca3af', fillOpacity:1, strokeColor:'#fff', strokeWeight:2.5, scale:9 };
}

function loadPlots() {
    fetch('{{ route("cemetery.plots") }}')
        .then(r=>r.json())
        .then(geo=>{
            Object.values(markersStore).forEach(m=>m.setMap(null));
            Object.keys(markersStore).forEach(k=>delete markersStore[k]);
            let tot=0,av=0,oc=0,re=0;
            geo.features.forEach(f=>{
                const p=f.properties, lat=f.geometry.coordinates[1], lng=f.geometry.coordinates[0];
                if(!lat||!lng) return;
                const marker = new google.maps.Marker({ position:{lat:parseFloat(lat),lng:parseFloat(lng)}, map, icon:markerIcon(p.status), title:p.plot_code });
                marker.addListener('click',()=>openViewPanel(p));
                markersStore[p.id]=marker;
                tot++; if(p.status==='available')av++; if(p.status==='occupied')oc++; if(p.status==='reserved')re++;
            });
            document.getElementById('stat-total').textContent=tot;
            document.getElementById('stat-available').textContent=av;
            document.getElementById('stat-occupied').textContent=oc;
            document.getElementById('stat-reserved').textContent=re;
        });
}

// ══════════════════════════════════════════
//  GRID OVERLAYS (NicheOverlay defined inside initMap above)
// ══════════════════════════════════════════
const overlayWindows = {};

function renderGridOverlays() {
    const sections  = loadGridData();
    const positions = loadOverlayPositions();

    // Remove old overlays
    Object.values(overlayWindows).forEach(o=>o.setMap(null));
    Object.keys(overlayWindows).forEach(k=>delete overlayWindows[k]);

    sections.forEach((sec, i) => {
        const pos = positions[sec.id] || {
            lat: CEMETERY_LAT + 0.0003 + (i * 0.0002),
            lng: CEMETERY_LNG + 0.0001 + (i * 0.0003)
        };
        const overlay = new window.NicheOverlay(sec, pos);
        overlay.setMap(map);
        overlayWindows[sec.id] = overlay;
    });
}

function saveOverlayPosition(secId, pos) {
    const positions = loadOverlayPositions();
    positions[secId] = pos;
    localStorage.setItem('lgu_overlay_pos_{{ auth()->id() }}', JSON.stringify(positions));
}
function loadOverlayPositions() {
    try { return JSON.parse(localStorage.getItem('lgu_overlay_pos_{{ auth()->id() }}') || '{}'); } catch(e){ return {}; }
}
function removeGridOverlay(secId) {
    if(!confirm('Remove this grid section from the map?')) return;
    const sections = loadGridData().filter(s=>s.id!==secId);
    localStorage.setItem(GRID_KEY, JSON.stringify(sections));
    renderGridOverlays();
    showToast('Section removed.','green');
}

// ── Niche detail popup ──
function openNicheDetail(secId, key, cell, secName) {
    openGridPanel();
    document.getElementById('panelTitle').textContent = `${secName} — ${key}`;
    const [r,c] = key.split('-');
    document.getElementById('panelBody').innerHTML = `
        <div class="sdiv">Niche Info</div>
        <div class="info-row"><span class="info-key">Location</span><span class="info-val">Row ${r}, Col ${c}</span></div>
        <div class="info-row"><span class="info-key">Status</span><span class="info-val"><span class="status-pill pill-${cell.status||'empty'}">${cell.status||'Empty'}</span></span></div>
        ${cell.label ? `<div class="info-row"><span class="info-key">Name</span><span class="info-val">${esc(cell.label)}</span></div>` : ''}
        ${cell.notes ? `<div class="info-row"><span class="info-key">Notes</span><span class="info-val">${esc(cell.notes)}</span></div>` : ''}
        <div class="sdiv" style="margin-top:.75rem">Change Status</div>
        <div class="paint-bar" style="margin-top:.5rem">
            <span class="ppill available ${cell.status==='available'?'on':''}" onclick="setNicheStatus('${secId}','${key}','available')">● Available</span>
            <span class="ppill occupied  ${cell.status==='occupied' ?'on':''}" onclick="setNicheStatus('${secId}','${key}','occupied')">✕ Occupied</span>
            <span class="ppill reserved  ${cell.status==='reserved' ?'on':''}" onclick="setNicheStatus('${secId}','${key}','reserved')">◐ Reserved</span>
            <span class="ppill erase"                                            onclick="setNicheStatus('${secId}','${key}','empty')">⌫ Clear</span>
        </div>
        <div class="sdiv" style="margin-top:.75rem">Assign Name</div>
        <div class="form-group">
            <label class="form-label">Deceased / Label</label>
            <input type="text" id="nicheLabel" class="form-control" value="${cell.label||''}" placeholder="e.g. Santos, Juan">
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <input type="text" id="nicheNotes" class="form-control" value="${cell.notes||''}" placeholder="Optional notes">
        </div>
    `;
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" onclick="closePanel()">Cancel</button>
        <button class="btn btn-primary" onclick="saveNicheDetail('${secId}','${key}')">Save</button>
    `;
}

function setNicheStatus(secId, key, status) {
    const sections = loadGridData();
    const sec = sections.find(s=>s.id===secId);
    if(!sec) return;
    if(status==='empty') { delete sec.cells[key]; }
    else { sec.cells[key] = { ...sec.cells[key], status }; }
    localStorage.setItem(GRID_KEY, JSON.stringify(sections));
    if(overlayWindows[secId]) { overlayWindows[secId].section=sec; overlayWindows[secId].refresh(); }
    // Refresh panel
    openNicheDetail(secId, key, sec.cells[key]||{}, sec.name);
}

function saveNicheDetail(secId, key) {
    const sections = loadGridData();
    const sec = sections.find(s=>s.id===secId);
    if(!sec) return;
    const label = document.getElementById('nicheLabel')?.value.trim()||null;
    const notes = document.getElementById('nicheNotes')?.value.trim()||null;
    if(!sec.cells[key]) sec.cells[key]={};
    sec.cells[key].label = label;
    sec.cells[key].notes = notes;
    localStorage.setItem(GRID_KEY, JSON.stringify(sections));
    if(overlayWindows[secId]) { overlayWindows[secId].section=sec; overlayWindows[secId].refresh(); }
    closePanel();
    showToast(label ? `"${label}" saved!` : 'Niche updated.','green');
}

// ── Grid panel (list all sections) ──
function openGridPanel() {
    const sections = loadGridData();
    document.getElementById('panelTitle').textContent = 'Niche Grid';
    if(!sections.length) {
        document.getElementById('panelBody').innerHTML = `
            <div style="text-align:center;padding:2rem;color:#9ca3af;font-size:13px">
                No grid sections yet.<br>
                <a href="/cemetery/grid" style="color:#1a2744;font-weight:600;margin-top:.5rem;display:inline-block">Open Grid Manager →</a>
            </div>`;
        document.getElementById('panelFooter').innerHTML = '';
        document.getElementById('sidePanel').classList.add('open');
        return;
    }
    let html = `<div style="font-size:12px;color:#9ca3af;margin-bottom:.75rem">Click any section to view it on the map. Click niches to mark them.</div>`;
    sections.forEach(sec => {
        let av=0,oc=0,re=0;
        for(let r=1;r<=sec.rows;r++) for(let c=1;c<=sec.cols;c++){
            const st=(sec.cells[`${r}-${c}`]||{}).status||'empty';
            if(st==='available')av++; else if(st==='occupied')oc++; else if(st==='reserved')re++;
        }
        html += `
        <div class="grid-sec-item" onclick="flyToSection('${sec.id}')">
            <div>
                <div class="grid-sec-name">${esc(sec.name)}</div>
                <div class="grid-sec-meta">${sec.rows}×${sec.cols} · ${sec.rows*sec.cols} niches</div>
            </div>
            <div class="grid-sec-stats">
                <span class="gss gss-g">${av}</span>
                <span class="gss gss-r">${oc}</span>
                <span class="gss gss-y">${re}</span>
            </div>
        </div>`;
    });
    html += `<div style="margin-top:.75rem;text-align:center"><a href="/cemetery/grid" class="btn btn-primary" style="display:inline-flex;text-decoration:none">Open Grid Manager</a></div>`;
    document.getElementById('panelBody').innerHTML = html;
    document.getElementById('panelFooter').innerHTML = `<button class="btn" onclick="renderGridOverlays();showToast('Overlays refreshed','green')">Refresh Overlays</button>`;
    document.getElementById('sidePanel').classList.add('open');
}

function flyToSection(secId) {
    const pos = loadOverlayPositions()[secId];
    if(pos) { map.panTo({ lat: pos.lat, lng: pos.lng }); map.setZoom(21); }
    showToast('Zoomed to section','green');
}

// ── Regular plot marker panel ──
function openViewPanel(p) {
    document.getElementById('panelTitle').textContent = `Plot ${p.plot_code}`;
    document.getElementById('panelBody').innerHTML = `
        <div class="sdiv">Location</div>
        <div class="info-row"><span class="info-key">Plot Code</span><span class="info-val">${p.plot_code}</span></div>
        <div class="info-row"><span class="info-key">Section</span><span class="info-val">${p.section||'—'}</span></div>
        <div class="info-row"><span class="info-key">Row</span><span class="info-val">${p.row||'—'}</span></div>
        <div class="info-row"><span class="info-key">Status</span><span class="info-val"><span class="status-pill pill-${p.status}">${cap(p.status)}</span></span></div>
        ${p.deceased_name ? `<div class="sdiv" style="margin-top:.5rem">Occupant</div>
        <div class="info-row"><span class="info-key">Name</span><span class="info-val">${esc(p.deceased_name)}</span></div>
        <div class="info-row"><span class="info-key">Date Died</span><span class="info-val">${p.date_of_death||'—'}</span></div>` : ''}
        ${p.notes ? `<div class="sdiv" style="margin-top:.5rem">Notes</div><p style="font-size:13px;color:#374151;margin-top:.25rem">${esc(p.notes)}</p>` : ''}
    `;
    const pj = JSON.stringify(p).replace(/"/g,'&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" style="color:#991b1b;border-color:#fca5a5" onclick="deletePlot(${p.id})">Delete</button>
        <button class="btn btn-primary" data-p="${pj}" onclick="openEditPanel(JSON.parse(this.dataset.p))">Edit</button>
    `;
    document.getElementById('sidePanel').classList.add('open');
}

function openAddPanel(lat, lng) {
    document.getElementById('panelTitle').textContent = 'New Plot';
    document.getElementById('panelBody').innerHTML = `
        <div style="background:#f0f4ff;border:1px solid #c7d2fe;border-radius:8px;padding:.65rem .9rem;font-size:12px;color:#374151;margin-bottom:.25rem">
            📍 <strong>${lat.toFixed(6)}</strong>, <strong>${lng.toFixed(6)}</strong>
        </div>
        <div class="form-group"><label class="form-label">Plot Code *</label><input id="f_code" class="form-control" placeholder="e.g. NW-A-01" autofocus></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group"><label class="form-label">Section</label><input id="f_section" class="form-control" placeholder="e.g. Niche Wall A"></div>
            <div class="form-group"><label class="form-label">Row</label><input id="f_row" class="form-control" placeholder="1"></div>
        </div>
        <div class="form-group"><label class="form-label">Status</label>
            <select id="f_status" class="form-control" onchange="toggleDecField('f')">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="reserved">Reserved</option>
            </select>
        </div>
        <div class="form-group" id="f_dgroup" style="display:none">
            <label class="form-label">Assign Deceased</label>
            <div class="search-wrap">
                <input id="f_ds" class="form-control" placeholder="Type name…" oninput="searchDec(this.value,'f')" autocomplete="off">
                <div class="search-results" id="f_dr"></div>
            </div>
            <input type="hidden" id="f_did">
        </div>
        <div class="form-group"><label class="form-label">Notes</label><textarea id="f_notes" class="form-control" rows="2" style="resize:vertical"></textarea></div>
    `;
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" onclick="cancelAddMode()">Cancel</button>
        <button class="btn btn-primary" onclick="savePlot(${lat},${lng})">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Save Plot
        </button>`;
    document.getElementById('sidePanel').classList.add('open');
}

function openEditPanel(p) {
    document.getElementById('panelTitle').textContent = `Edit — ${p.plot_code}`;
    document.getElementById('panelBody').innerHTML = `
        <div class="form-group"><label class="form-label">Status</label>
            <select id="e_status" class="form-control" onchange="toggleDecField('e')">
                <option value="available" ${p.status==='available'?'selected':''}>Available</option>
                <option value="occupied"  ${p.status==='occupied'?'selected':''}>Occupied</option>
                <option value="reserved"  ${p.status==='reserved'?'selected':''}>Reserved</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group"><label class="form-label">Section</label><input id="e_section" class="form-control" value="${p.section||''}"></div>
            <div class="form-group"><label class="form-label">Row</label><input id="e_row" class="form-control" value="${p.row||''}"></div>
        </div>
        <div class="form-group" id="e_dgroup" style="${p.status==='occupied'?'':'display:none'}">
            <label class="form-label">Assign Deceased</label>
            <div class="search-wrap">
                <input id="e_ds" class="form-control" value="${p.deceased_name||''}" placeholder="Type name…" oninput="searchDec(this.value,'e')" autocomplete="off">
                <div class="search-results" id="e_dr"></div>
            </div>
            <input type="hidden" id="e_did" value="${p.deceased_id||''}">
        </div>
        <div class="form-group"><label class="form-label">Notes</label><textarea id="e_notes" class="form-control" rows="2" style="resize:vertical">${p.notes||''}</textarea></div>
    `;
    const pj = JSON.stringify(p).replace(/"/g,'&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" data-p="${pj}" onclick="openViewPanel(JSON.parse(this.dataset.p))">Cancel</button>
        <button class="btn btn-primary" onclick="updatePlot(${p.id})">Save Changes</button>`;
}

function closePanel() { document.getElementById('sidePanel').classList.remove('open'); }

// ── Add mode ──
function startAddMode() {
    if(streetView && streetView.getVisible()) streetView.setVisible(false);
    addMode=true;
    document.getElementById('map').classList.add('adding');
    document.getElementById('addBanner').classList.add('show');
    document.getElementById('btnAdd').classList.add('btn-active');
    closePanel();
}
function cancelAddMode() {
    addMode=false;
    document.getElementById('map').classList.remove('adding');
    document.getElementById('addBanner').classList.remove('show');
    document.getElementById('btnAdd').classList.remove('btn-active');
    if(pendingMarker){pendingMarker.setMap(null);pendingMarker=null;}
    closePanel();
}
function placePendingMarker(latLng) {
    if(pendingMarker) pendingMarker.setMap(null);
    pendingMarker = new google.maps.Marker({ position:latLng, map, icon:{path:google.maps.SymbolPath.CIRCLE,fillColor:'#6366f1',fillOpacity:1,strokeColor:'#fff',strokeWeight:3,scale:11}, animation:google.maps.Animation.BOUNCE, zIndex:999 });
    setTimeout(()=>pendingMarker&&pendingMarker.setAnimation(null),1400);
}

// ── CRUD ──
function savePlot(lat,lng){
    const code=(document.getElementById('f_code')?.value||'').trim();
    if(!code){showToast('Plot code required.','red');return;}
    fetch('{{ route("cemetery.store") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({plot_code:code,section:document.getElementById('f_section')?.value.trim()||null,row:document.getElementById('f_row')?.value.trim()||null,latitude:lat,longitude:lng,status:document.getElementById('f_status')?.value||'available',deceased_id:document.getElementById('f_did')?.value||null,notes:document.getElementById('f_notes')?.value.trim()||null})
    }).then(r=>r.json()).then(d=>{if(!d.success){showToast(d.message||'Error.','red');return;} cancelAddMode();loadPlots();showToast(`Plot "${code}" saved!`,'green');}).catch(()=>showToast('Network error.','red'));
}
function updatePlot(id){
    fetch(`/cemetery/plots/${id}`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({status:document.getElementById('e_status')?.value,section:document.getElementById('e_section')?.value.trim()||null,row:document.getElementById('e_row')?.value.trim()||null,deceased_id:document.getElementById('e_did')?.value||null,notes:document.getElementById('e_notes')?.value.trim()||null})
    }).then(r=>r.json()).then(d=>{if(!d.success){showToast('Error.','red');return;} closePanel();loadPlots();showToast('Plot updated!','green');}).catch(()=>showToast('Network error.','red'));
}
function deletePlot(id){
    if(!confirm('Delete this plot?')) return;
    fetch(`/cemetery/plots/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF}}).then(r=>r.json()).then(d=>{if(!d.success){showToast('Error.','red');return;} closePanel();loadPlots();showToast('Plot deleted.','green');});
}

// ── Search deceased ──
let dt;
function searchDec(q,px){
    clearTimeout(dt); const res=document.getElementById(`${px}_dr`);
    if(q.length<2){res.classList.remove('open');return;}
    dt=setTimeout(()=>{
        fetch(`{{ route("cemetery.search-deceased") }}?q=${encodeURIComponent(q)}`).then(r=>r.json()).then(list=>{
            if(!list.length){res.classList.remove('open');return;}
            res.innerHTML=list.map(d=>`<div class="sr-item" onclick="selDec(${d.id},'${esc2(d.name)}','${px}')"><div>${d.name}</div><div class="sr-sub">Died: ${d.dod||'—'}</div></div>`).join('');
            res.classList.add('open');
        });
    },280);
}
function selDec(id,name,px){document.getElementById(`${px}_ds`).value=name;document.getElementById(`${px}_did`).value=id;document.getElementById(`${px}_dr`).classList.remove('open');}
function toggleDecField(px){const s=document.getElementById(`${px}_status`)?.value;const g=document.getElementById(`${px}_dgroup`);if(g)g.style.display=s==='occupied'?'':'none';}

function cap(s){return s.charAt(0).toUpperCase()+s.slice(1);}
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
function esc2(s){return (s||'').replace(/'/g,"\\'");}

let tt;
function showToast(msg,type='green'){
    const el=document.getElementById('toast');
    el.textContent=msg; el.className=`toast toast-${type} show`;
    clearTimeout(tt); tt=setTimeout(()=>el.classList.remove('show'),3200);
}

document.addEventListener('keydown',e=>{if(e.key==='Escape'){cancelAddMode();closePanel();}});
document.addEventListener('click',e=>{if(!e.target.closest('.search-wrap'))document.querySelectorAll('.search-results').forEach(el=>el.classList.remove('open'));});
</script>

@if(config('services.google_maps.key'))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&v=weekly"></script>
@else
<script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap&v=weekly"></script>
@endif

</body>
</html>