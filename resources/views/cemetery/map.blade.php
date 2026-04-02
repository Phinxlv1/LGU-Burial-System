<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>High-Performance Cemetery Map — LGU Burial System</title>
    
    <!-- React & MapLibre Native Integration -->
    @viteReactRefresh
    @vite(['resources/js/geomap/main.tsx'])
    
    <style>
        body, html { 
            margin: 0; padding: 0; height: 100%; width: 100%; 
            overflow: hidden; background: #0f172a;
        }
    #geomap-root { width: 100%; height: 100vh; display: flex; flex-direction: column; }
        
        /* ── Sidebar Adjustment (Since we're inside the layout) ── */
        .main-wrapper { display: flex; height: 100vh; overflow: hidden; }
        .map-content { flex: 1; position: relative; margin-left: 220px; }
    </style>
</head>
<body>

<div class="main-wrapper">
    @include('admin.partials.sidebar')

    <div class="map-content">
        <div id="geomap-root">
            <!-- React mounts here -->
            <div style="height: 100vh; display: flex; align-items: center; justify-content: center; color: #64748b; font-family: sans-serif; gap: 1rem; flex-direction: column;">
                <div style="width: 40px; height: 40px; border: 3px solid #1e293b; border-top-color: #3b82f6; border-radius: 50%; animate: spin 1s linear infinite;"></div>
                <div style="font-weight: 700; letter-spacing: 0.1em; font-size: 11px; text-transform: uppercase;">Booting High-Performance Engine...</div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

</body>
</html>