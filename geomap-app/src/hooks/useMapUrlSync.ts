import { useEffect } from 'react'
import { useQueryState, parseAsFloat } from 'nuqs'
import { useMapStore } from '../store/useMapStore'

export const useMapUrlSync = () => {
  const { map, isLoaded } = useMapStore()

  const [, setLat] = useQueryState('lat', parseAsFloat.withDefault(14.58))
  const [, setLng] = useQueryState('lng', parseAsFloat.withDefault(121.05))
  const [, setZoom] = useQueryState('zoom', parseAsFloat.withDefault(10))
  const [, setPitch] = useQueryState('pitch', parseAsFloat.withDefault(0))
  const [, setBearing] = useQueryState('bearing', parseAsFloat.withDefault(0))

  // Update URL when map moves
  useEffect(() => {
    if (!map || !isLoaded) return

    const handleMove = () => {
      const center = map.getCenter()
      setLat(center.lat)
      setLng(center.lng)
      setZoom(map.getZoom())
      setPitch(map.getPitch())
      setBearing(map.getBearing())
    }

    map.on('moveend', handleMove)
    return () => {
        map.off('moveend', handleMove)
    }
  }, [map, isLoaded, setLat, setLng, setZoom, setPitch, setBearing])

  return null
}
