import { useEffect, useRef } from 'react'
import maplibregl from 'maplibre-gl'
import 'maplibre-gl/dist/maplibre-gl.css'
import { useMapStore } from '../../store/useMapStore'

export const MapView = () => {
  const mapContainer = useRef<HTMLDivElement>(null)
  const { setMap, setIsLoaded, setZoom, setCenter } = useMapStore()

  useEffect(() => {
    if (!mapContainer.current) return

    const map = new maplibregl.Map({
      container: mapContainer.current,
      style: 'https://tiles.openfreemap.org/styles/liberty', // Switching to more reliable style
      center: [125.714882, 7.370672], // Carmen Cemetery default
      zoom: 19,
      pitch: 0,
      bearing: 0,
       // @ts-ignore - antialias is supported but type may be strict
       antialias: true
    })

    map.on('error', (e) => {
      console.error('Map Engine Error:', e.error)
    })

    map.on('load', () => {
      console.log('Map Engine Loaded Successfully')
      setIsLoaded(true)
      setMap(map)
    })

    map.on('move', () => {
      const { lng, lat } = map.getCenter()
      setZoom(map.getZoom())
      setCenter({ lng, lat })
    })

    // Navigation controls
    map.addControl(new maplibregl.NavigationControl({
        showCompass: true,
        showZoom: true,
        visualizePitch: true
    }), 'top-right')

    // Geolocation control
    map.addControl(new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true
    }), 'top-right')

    return () => map.remove()
  }, [setMap, setIsLoaded, setZoom, setCenter])

  return (
    <div 
      ref={mapContainer} 
      className="w-full h-full absolute inset-0 z-0 bg-[#0f172a]" 
      style={{ width: '100vw', height: '100vh', minHeight: '500px' }}
      id="map" 
    />
  )
}
