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
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Inter', sans-serif; color: #111827; -webkit-font-smoothing: antialiased; display: flex; }

        /* sidebar styles live in partials/sidebar.blade.php */

        /* ── MAIN ── */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; height: 100vh; }

        /* ── TOPBAR ── */
        .topbar { background: #fff; border-bottom: 1px solid #f0f0f0; height: 54px; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; z-index: 40; box-shadow: 0 1px 0 #f0f0f0; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-date  { font-size: 12px; color: #9ca3af; }
        .role-tag { background: #1a2744; color: #fff; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; letter-spacing: .04em; text-transform: uppercase; }

        /* ── MAP WRAPPER ── */
        .map-wrap { position: relative; flex: 1; overflow: hidden; }
        #map { width: 100%; height: 100%; }
        #map.adding { cursor: crosshair !important; }

        /* ── FLOATING TOOLBAR ── */
        .map-float-bar {
            position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
            z-index: 10; display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.98); border-radius: 16px;
            padding: 8px 16px; box-shadow: 0 4px 24px rgba(0,0,0,.14), 0 1px 4px rgba(0,0,0,.06);
            backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.9);
        }
        .float-bar-title { font-size: 12px; font-weight: 700; color: #1a2744; padding-right: 10px; border-right: 1px solid #e5e7eb; margin-right: 2px; letter-spacing: .01em; }

        /* ── FLOATING LEGEND ── */
        .float-legend {
            position: absolute; bottom: 28px; left: 14px; z-index: 10;
            background: rgba(12,24,52,.92); border-radius: 18px;
            padding: 14px 18px; box-shadow: 0 8px 32px rgba(0,0,0,.35), 0 2px 8px rgba(0,0,0,.15);
            backdrop-filter: blur(16px); min-width: 168px;
            border: 1px solid rgba(255,255,255,.08);
        }
        .legend-title { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 10px; }
        .legend-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
        .legend-row:last-child { margin-bottom: 0; }
        .legend-left { display: flex; align-items: center; gap: 7px; }
        .legend-dot { width: 11px; height: 11px; border-radius: 50%; border: 2px solid rgba(255,255,255,.3); flex-shrink: 0; }
        .legend-label { font-size: 12px; color: rgba(255,255,255,.85); font-weight: 500; }
        .legend-count { font-size: 16px; font-weight: 800; color: #fff; line-height: 1; }
        .legend-divider { height: 1px; background: rgba(255,255,255,.1); margin: 8px 0; }
        .legend-total-row { display: flex; align-items: center; justify-content: space-between; }
        .legend-total-label { font-size: 11px; color: rgba(255,255,255,.5); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
        .legend-total-val { font-size: 20px; font-weight: 800; color: #fff; }

        /* ── ADD MODE BANNER ── */
        .add-banner { position: absolute; top: 0; left: 0; right: 0; z-index: 20; background: #1a2744; color: #fff; font-size: 13px; font-weight: 500; padding: .6rem 1rem; display: none; align-items: center; justify-content: center; gap: .6rem; }
        .add-banner.show { display: flex; }

        /* ── BUTTONS ── */
        .btn { display: inline-flex; align-items: center; gap: 5px; padding: .4rem .9rem; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid #e5e7eb; background: #fff; color: #374151; transition: all .15s; white-space: nowrap; }
        .btn:hover { background: #f9fafb; border-color: #1a2744; color: #1a2744; }
        .btn-primary { background: #1a2744; color: #fff; border-color: #1a2744; }
        .btn-primary:hover { background: #243459; }
        .btn-danger  { color: #991b1b; border-color: #fca5a5; }
        .btn-danger:hover { background: #fef2f2; }
        .btn-active  { background: #dbeafe !important; color: #1e40af !important; border-color: #93c5fd !important; }
        .btn-sm { padding: .3rem .75rem; font-size: 11px; border-radius: 7px; }
        .style-pills { display: flex; gap: 3px; }
        .style-pill { padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 600; cursor: pointer; border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280; transition: all .15s; letter-spacing: .01em; }
        .style-pill:hover  { border-color: #1a2744; color: #1a2744; }
        .style-pill.active { background: #1a2744; color: #fff; border-color: #1a2744; }

        /* ── SIDE PANEL ── */
        .side-panel { position: absolute; top: 0; right: -360px; width: 348px; height: 100%; background: #fff; border-left: 1px solid #e5e7eb; z-index: 30; display: flex; flex-direction: column; transition: right .3s cubic-bezier(.4,0,.2,1); box-shadow: -8px 0 40px rgba(0,0,0,.14); }
        .side-panel.open { right: 0; }
        .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; background: #fff; }
        .panel-head h3 { font-size: 14px; font-weight: 700; color: #111827; }
        .panel-close { background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 20px; line-height: 1; transition: color .15s; padding: 0 4px; }
        .panel-close:hover { color: #374151; }
        .panel-body   { flex: 1; overflow-y: auto; padding: 1rem 1.1rem; display: flex; flex-direction: column; gap: .85rem; }
        .panel-footer { padding: .85rem 1.1rem; border-top: 1px solid #f3f4f6; display: flex; gap: .5rem; justify-content: flex-end; background: #fafafa; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .06em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .8rem; border: 1px solid #e5e7eb; border-radius: 8px; outline: none; width: 100%; transition: border-color .15s, box-shadow .15s; background: #fafafa; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); background: #fff; }
        .status-pill { display: inline-flex; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .pill-available { background: #d1fae5; color: #065f46; }
        .pill-occupied  { background: #fee2e2; color: #991b1b; }
        .pill-reserved  { background: #fef3c7; color: #92400e; }
        .info-row { display: flex; justify-content: space-between; align-items: flex-start; padding: .4rem 0; border-bottom: 1px solid #f9fafb; }
        .info-row:last-child { border: none; }
        .info-key { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; min-width: 80px; padding-top: 2px; }
        .info-val { font-size: 13px; font-weight: 500; color: #111827; text-align: right; }
        .section-div { font-size: 10px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .3rem; border-bottom: 1.5px solid #e5e7eb; margin-top: .25rem; }
        .coord-box { font-size: 12px; color: #374151; background: #f0f4ff; border: 1px solid #c7d2fe; border-radius: 10px; padding: .65rem .9rem; }
        .search-wrap { position: relative; }
        .search-icon { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
        .search-wrap .form-control { padding-left: 28px; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; z-index: 999; box-shadow: 0 8px 24px rgba(0,0,0,.12); display: none; max-height: 180px; overflow-y: auto; margin-top: 4px; overflow: hidden; }
        .search-results.open { display: block; }
        .sr-item { padding: .5rem .75rem; font-size: 13px; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
        .sr-item:last-child { border: none; }
        .sr-item:hover { background: #f0f4ff; }
        .sr-sub { font-size: 11px; color: #9ca3af; }

        /* Toast */
        .toast-wrap { position: absolute; bottom: 28px; right: 20px; z-index: 50; }
        .toast { padding: .75rem 1.1rem 1.1rem; border-radius: 12px; font-size: 13px; font-weight: 500; box-shadow: 0 4px 14px rgba(0,0,0,.18); transform: translateY(16px); opacity: 0; transition: all .3s; min-width: 200px; position: relative; overflow: hidden; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-green { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .toast-red   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .toast-bar { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; transform-origin: left; animation: drain 3.5s linear forwards; }
        .toast-green .toast-bar { background: #22c55e; }
        .toast-red   .toast-bar { background: #ef4444; }
        @keyframes drain { from{transform:scaleX(1)} to{transform:scaleX(0)} }
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
            📍 Click anywhere on the map to place a new plot
            <button class="btn btn-sm" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:#fff" onclick="cancelAddMode()">Cancel</button>
        </div>

        <div class="map-float-bar">
            <span class="float-bar-title">Carmen Public Cemetery</span>
            <button id="btnAdd" class="btn btn-primary btn-sm" onclick="startAddMode()">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Plot
            </button>
            <button class="btn btn-sm" onclick="resetView()">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                Reset
            </button>
            <div class="style-pills">
                <button class="style-pill active" id="pill-satellite" onclick="setMapType('satellite')">🛰 Satellite</button>
                <button class="style-pill" id="pill-hybrid" onclick="setMapType('hybrid')">🌍 Hybrid</button>
                <button class="style-pill" id="pill-roadmap" onclick="setMapType('roadmap')">🗺 Road</button>
            </div>
        </div>

        <div class="float-legend">
            <div class="legend-title">Plot Status</div>
            <div class="legend-row">
                <div class="legend-left"><div class="legend-dot" style="background:#10b981"></div><span class="legend-label">Available</span></div>
                <span class="legend-count" id="stat-available">{{ $stats['available'] }}</span>
            </div>
            <div class="legend-row">
                <div class="legend-left"><div class="legend-dot" style="background:#ef4444"></div><span class="legend-label">Occupied</span></div>
                <span class="legend-count" id="stat-occupied">{{ $stats['occupied'] }}</span>
            </div>
            <div class="legend-row">
                <div class="legend-left"><div class="legend-dot" style="background:#f59e0b"></div><span class="legend-label">Reserved</span></div>
                <span class="legend-count" id="stat-reserved">{{ $stats['reserved'] }}</span>
            </div>
            <div class="legend-divider"></div>
            <div class="legend-total-row">
                <span class="legend-total-label">Total</span>
                <span class="legend-total-val" id="stat-total">{{ $stats['total'] }}</span>
            </div>
        </div>

        <div id="map"></div>

        <div class="side-panel" id="sidePanel">
            <div class="panel-head">
                <h3 id="panelTitle">Plot Details</h3>
                <button class="panel-close" onclick="closePanel()">×</button>
            </div>
            <div class="panel-body"   id="panelBody"></div>
            <div class="panel-footer" id="panelFooter"></div>
        </div>

        <div class="toast-wrap"><div class="toast" id="toast"></div></div>

    </div>
</div>

<script>
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
const CEMETERY_LAT =  7.370672;
const CEMETERY_LNG = 125.714882;

let map, addMode = false, pendingMarker = null;
const markersStore = {};

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center:    { lat: CEMETERY_LAT, lng: CEMETERY_LNG },
        zoom:      18.2,
        mapTypeId: 'satellite',
        tilt: 0,
        mapTypeControl:    false,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl:       true,
        gestureHandling:   'greedy',
        zoomControlOptions:       { position: google.maps.ControlPosition.RIGHT_BOTTOM },
        streetViewControlOptions: { position: google.maps.ControlPosition.RIGHT_BOTTOM },
        fullscreenControlOptions: { position: google.maps.ControlPosition.RIGHT_TOP },
    });

    map.addListener('click', (e) => {
        if (!addMode) return;
        placePendingMarker(e.latLng);
        openAddPanel(e.latLng.lat(), e.latLng.lng());
    });

    loadPlots();
}

function setMapType(type) {
    map.setMapTypeId(type);
    document.querySelectorAll('.style-pill').forEach(p => p.classList.remove('active'));
    document.getElementById('pill-' + type).classList.add('active');
}

function resetView() {
    map.panTo({ lat: CEMETERY_LAT, lng: CEMETERY_LNG });
    map.setZoom(19);
}

function markerIcon(status) {
    const colors = { available: '#10b981', occupied: '#ef4444', reserved: '#f59e0b' };
    return {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: colors[status] || '#9ca3af',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2.5,
        scale: 9,
    };
}

function loadPlots() {
    fetch('{{ route("cemetery.plots") }}')
        .then(r => r.json())
        .then(geojson => {
            Object.values(markersStore).forEach(m => m.setMap(null));
            Object.keys(markersStore).forEach(k => delete markersStore[k]);

            let tot=0, av=0, oc=0, re=0;
            geojson.features.forEach(f => {
                const p   = f.properties;
                const lat = f.geometry.coordinates[1];
                const lng = f.geometry.coordinates[0];
                if (!lat || !lng) return;

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                    map, icon: markerIcon(p.status), title: p.plot_code,
                });
                marker.addListener('click', () => openViewPanel(p));
                markersStore[p.id] = marker;

                tot++;
                if (p.status==='available') av++;
                if (p.status==='occupied')  oc++;
                if (p.status==='reserved')  re++;
            });

            document.getElementById('stat-total').textContent     = tot;
            document.getElementById('stat-available').textContent = av;
            document.getElementById('stat-occupied').textContent  = oc;
            document.getElementById('stat-reserved').textContent  = re;
        });
}

function startAddMode() {
    addMode = true;
    document.getElementById('map').classList.add('adding');
    document.getElementById('addBanner').classList.add('show');
    document.getElementById('btnAdd').classList.add('btn-active');
    closePanel();
}
function cancelAddMode() {
    addMode = false;
    document.getElementById('map').classList.remove('adding');
    document.getElementById('addBanner').classList.remove('show');
    document.getElementById('btnAdd').classList.remove('btn-active');
    if (pendingMarker) { pendingMarker.setMap(null); pendingMarker = null; }
    closePanel();
}
function placePendingMarker(latLng) {
    if (pendingMarker) pendingMarker.setMap(null);
    pendingMarker = new google.maps.Marker({
        position: latLng, map,
        icon: { path: google.maps.SymbolPath.CIRCLE, fillColor: '#6366f1', fillOpacity: 1, strokeColor: '#fff', strokeWeight: 3, scale: 11 },
        animation: google.maps.Animation.BOUNCE, zIndex: 999,
    });
    setTimeout(() => pendingMarker && pendingMarker.setAnimation(null), 1400);
}

function openViewPanel(p) {
    document.getElementById('panelTitle').textContent = `Plot ${p.plot_code}`;
    document.getElementById('panelBody').innerHTML = `
        <div class="section-div">Location</div>
        <div class="info-row"><span class="info-key">Plot Code</span><span class="info-val">${p.plot_code}</span></div>
        <div class="info-row"><span class="info-key">Section</span><span class="info-val">${p.section||'—'}</span></div>
        <div class="info-row"><span class="info-key">Row</span><span class="info-val">${p.row||'—'}</span></div>
        <div class="info-row"><span class="info-key">Status</span>
            <span class="info-val"><span class="status-pill pill-${p.status}">${cap(p.status)}</span></span></div>
        ${p.deceased_name ? `
        <div class="section-div" style="margin-top:.5rem">Occupant</div>
        <div class="info-row"><span class="info-key">Name</span><span class="info-val">${p.deceased_name}</span></div>
        <div class="info-row"><span class="info-key">Date Died</span><span class="info-val">${p.date_of_death||'—'}</span></div>
        ` : ''}
        ${p.notes ? `
        <div class="section-div" style="margin-top:.5rem">Notes</div>
        <div style="font-size:13px;color:#374151;line-height:1.6;margin-top:.25rem">${p.notes}</div>
        ` : ''}
    `;
    const pj = JSON.stringify(p).replace(/"/g,'&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn btn-danger" onclick="deletePlot(${p.id})">Delete</button>
        <button class="btn btn-primary" data-p="${pj}" onclick="openEditPanel(JSON.parse(this.dataset.p))">Edit</button>
    `;
    document.getElementById('sidePanel').classList.add('open');
}

function openAddPanel(lat, lng) {
    document.getElementById('panelTitle').textContent = 'New Plot';
    document.getElementById('panelBody').innerHTML = `
        <div class="coord-box">📍 <strong>${lat.toFixed(6)}</strong>, <strong>${lng.toFixed(6)}</strong></div>
        <div class="form-group">
            <label class="form-label">Plot Code <span style="color:#ef4444">*</span></label>
            <input id="f_code" class="form-control" placeholder="e.g. A-01" autofocus>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group"><label class="form-label">Section</label><input id="f_section" class="form-control" placeholder="A"></div>
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
                <svg class="search-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="f_ds" class="form-control" placeholder="Type name…" oninput="searchDec(this.value,'f')" autocomplete="off">
                <div class="search-results" id="f_dr"></div>
            </div>
            <input type="hidden" id="f_did">
        </div>
        <div class="form-group"><label class="form-label">Notes</label>
            <textarea id="f_notes" class="form-control" rows="2" style="resize:vertical"></textarea>
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
        <div class="form-group"><label class="form-label">Status</label>
            <select id="e_status" class="form-control" onchange="toggleDecField('e')">
                <option value="available" ${p.status==='available'?'selected':''}>Available</option>
                <option value="occupied"  ${p.status==='occupied' ?'selected':''}>Occupied</option>
                <option value="reserved"  ${p.status==='reserved' ?'selected':''}>Reserved</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
            <div class="form-group"><label class="form-label">Section</label><input id="e_section" class="form-control" value="${p.section||''}"></div>
            <div class="form-group"><label class="form-label">Row</label><input id="e_row" class="form-control" value="${p.row||''}"></div>
        </div>
        <div class="form-group" id="e_dgroup" style="${p.status==='occupied'?'':'display:none'}">
            <label class="form-label">Assign Deceased</label>
            <div class="search-wrap">
                <svg class="search-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="e_ds" class="form-control" value="${p.deceased_name||''}" placeholder="Type name…" oninput="searchDec(this.value,'e')" autocomplete="off">
                <div class="search-results" id="e_dr"></div>
            </div>
            <input type="hidden" id="e_did" value="${p.deceased_id||''}">
        </div>
        <div class="form-group"><label class="form-label">Notes</label>
            <textarea id="e_notes" class="form-control" rows="2" style="resize:vertical">${p.notes||''}</textarea>
        </div>
    `;
    const pj = JSON.stringify(p).replace(/"/g,'&quot;');
    document.getElementById('panelFooter').innerHTML = `
        <button class="btn" data-p="${pj}" onclick="openViewPanel(JSON.parse(this.dataset.p))">Cancel</button>
        <button class="btn btn-primary" onclick="updatePlot(${p.id})">Save Changes</button>
    `;
}

function closePanel() { document.getElementById('sidePanel').classList.remove('open'); }

function savePlot(lat, lng) {
    const code = (document.getElementById('f_code')?.value||'').trim();
    if (!code) { showToast('Plot code is required.','red'); return; }
    fetch('{{ route("cemetery.store") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({
            plot_code: code,
            section:   document.getElementById('f_section')?.value.trim()||null,
            row:       document.getElementById('f_row')?.value.trim()||null,
            latitude: lat, longitude: lng,
            status:    document.getElementById('f_status')?.value||'available',
            deceased_id: document.getElementById('f_did')?.value||null,
            notes:     document.getElementById('f_notes')?.value.trim()||null,
        }),
    }).then(r=>r.json()).then(d=>{
        if (!d.success) { showToast(d.message||'Error saving.','red'); return; }
        cancelAddMode(); loadPlots(); showToast(`Plot "${code}" saved!`,'green');
    }).catch(()=>showToast('Network error.','red'));
}

function updatePlot(id) {
    fetch(`/cemetery/plots/${id}`, {
        method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({
            status:    document.getElementById('e_status')?.value,
            section:   document.getElementById('e_section')?.value.trim()||null,
            row:       document.getElementById('e_row')?.value.trim()||null,
            deceased_id: document.getElementById('e_did')?.value||null,
            notes:     document.getElementById('e_notes')?.value.trim()||null,
        }),
    }).then(r=>r.json()).then(d=>{
        if (!d.success) { showToast('Error updating.','red'); return; }
        closePanel(); loadPlots(); showToast('Plot updated!','green');
    }).catch(()=>showToast('Network error.','red'));
}

function deletePlot(id) {
    if (!confirm('Delete this plot?')) return;
    fetch(`/cemetery/plots/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF}})
        .then(r=>r.json()).then(d=>{
            if (!d.success) { showToast('Error.','red'); return; }
            closePanel(); loadPlots(); showToast('Plot deleted.','green');
        });
}

let dt;
function searchDec(q, px) {
    clearTimeout(dt);
    const res = document.getElementById(`${px}_dr`);
    if (q.length<2) { res.classList.remove('open'); return; }
    dt = setTimeout(()=>{
        fetch(`{{ route("cemetery.search-deceased") }}?q=${encodeURIComponent(q)}`)
            .then(r=>r.json()).then(list=>{
                if (!list.length) { res.classList.remove('open'); return; }
                res.innerHTML = list.map(d=>
                    `<div class="sr-item" onclick="selDec(${d.id},'${esc(d.name)}','${px}')">
                        <div>${d.name}</div><div class="sr-sub">Died: ${d.dod||'—'}</div>
                    </div>`).join('');
                res.classList.add('open');
            });
    },280);
}
function selDec(id,name,px) {
    document.getElementById(`${px}_ds`).value  = name;
    document.getElementById(`${px}_did`).value = id;
    document.getElementById(`${px}_dr`).classList.remove('open');
}
function toggleDecField(px) {
    const s = document.getElementById(`${px}_status`)?.value;
    const g = document.getElementById(`${px}_dgroup`);
    if (g) g.style.display = s==='occupied' ? '' : 'none';
}

function cap(s) { return s.charAt(0).toUpperCase()+s.slice(1); }
function esc(s) { return (s||'').replace(/'/g,"\\'"); }

let tt;
function showToast(msg, type='green') {
    const el = document.getElementById('toast');
    el.innerHTML = `${msg}<div class="toast-bar"></div>`;
    el.className = `toast toast-${type} show`;
    clearTimeout(tt);
    tt = setTimeout(()=>el.classList.remove('show'), 3600);
}

document.addEventListener('keydown', e => { if(e.key==='Escape'){ cancelAddMode(); closePanel(); } });
document.addEventListener('click', e => {
    if (!e.target.closest('.search-wrap'))
        document.querySelectorAll('.search-results').forEach(el=>el.classList.remove('open'));
});
</script>

@if(config('services.google_maps.key'))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&v=weekly"></script>
@else
<script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap&v=weekly"></script>
@endif

</body>
</html>