import { useEffect } from 'react'
import { useMapStore } from '../../store/useMapStore'

interface DataSourceProps {
  id: string
  data: any
  cluster?: boolean
  clusterRadius?: number
}

export const DataSource = ({ id, data, cluster = false, clusterRadius = 50 }: DataSourceProps) => {
  const { map, isLoaded } = useMapStore()

  useEffect(() => {
    if (!map || !isLoaded) return

    if (map.getSource(id)) {
      (map.getSource(id) as any).setData(data)
    } else {
      map.addSource(id, {
        type: 'geojson',
        data,
        cluster,
        clusterMaxZoom: 14,
        clusterRadius
      })
    }

    return () => {
      if (map.getSource(id)) {
        map.removeSource(id)
      }
    }
  }, [map, isLoaded, id, data, cluster, clusterRadius])

  return null
}
