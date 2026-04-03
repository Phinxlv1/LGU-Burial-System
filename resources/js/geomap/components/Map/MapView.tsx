import { useEffect, useRef } from 'react'
import maplibregl from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import { useMapStore } from '../../store/useMapStore'

export const MapView = () => {
  const mapContainer = useRef<HTMLDivElement>(null)
  const { setMap, setIsLoaded, setZoom, setCenter, mapStyle } = useMapStore()
  const mapRef = useRef<any>(null)

  useEffect(() => {
    if (!mapContainer.current) return

    const map = new (maplibregl as any).Map({
      container: mapContainer.current,
      style: 'https://tiles.openfreemap.org/styles/liberty', 
      center: [125.714882, 7.370672],
      zoom: 17, // Better default overview
      pitch: 0,
      bearing: 0,
      antialias: true
    })

    mapRef.current = map

    map.on('error', (e) => {
      console.error('Map Engine Error:', e.error)
    })

    map.on('load', () => {
      console.log('Map Engine Loaded Successfully')
      
      // 🛰️ Add Google Satellite Source (Modern & High-Res Hybrid)
      map.addSource('google-satellite', {
        type: 'raster',
        tiles: ['https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'], // 'y' is Hybrid (Satellite + Labels)
        tileSize: 256,
        attribution: '&copy; Google Maps'
      })

      // Add Satellite Layer
      map.addLayer({
        id: 'satellite-layer',
        type: 'raster',
        source: 'google-satellite',
        paint: { 'raster-opacity': mapStyle === 'satellite' ? 1 : 0 }
      }) 

      setIsLoaded(true)
      setMap(map)
    })

    map.on('move', () => {
      const { lng, lat } = map.getCenter()
      setZoom(map.getZoom())
      setCenter({ lng, lat })
    })

    map.addControl(new maplibregl.NavigationControl({
        showCompass: true,
        showZoom: true,
        visualizePitch: true
    }), 'top-right')

    map.addControl(new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true
    }), 'top-right')

    return () => map.remove()
  }, [setMap, setIsLoaded, setZoom, setCenter])

  // 🛰️ Sync Map Style
  useEffect(() => {
    const map = mapRef.current
    if (!map || !map.getLayer('satellite-layer')) return

    map.setPaintProperty('satellite-layer', 'raster-opacity', mapStyle === 'satellite' ? 1 : 0)
  }, [mapStyle])

  return (
    <div 
      ref={mapContainer} 
      className="w-full h-full absolute inset-0 z-0 bg-[#0f172a]" 
      style={{ width: '100vw', height: '100vh', minHeight: '500px' }}
      id="map" 
    />
  )
}
