import { useEffect } from 'react'
import { useMapStore } from '../../store/useMapStore'

interface MapLayerProps {
  id: string
  type: 'fill' | 'line' | 'circle' | 'symbol'
  source: string
  paint?: any
  layout?: any
  filter?: any[]
  beforeId?: string
}

export const MapLayer = ({ id, type, source, paint, layout, filter, beforeId }: MapLayerProps) => {
  const { map, isLoaded } = useMapStore()

  useEffect(() => {
    if (!map || !isLoaded) return

    if (map.getLayer(id)) {
      map.removeLayer(id)
    }

    map.addLayer({
      id,
      type,
      source,
      paint,
      layout,
      filter
    }, beforeId)

    return () => {
      if (map.getLayer(id)) {
        map.removeLayer(id)
      }
    }
  }, [map, isLoaded, id, type, source, paint, layout, filter, beforeId])

  return null
}
