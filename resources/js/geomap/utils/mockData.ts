export const generateRandomPoints = (count: number, bbox: [number, number, number, number]) => {
  const [minLng, minLat, maxLng, maxLat] = bbox
  return {
    type: 'FeatureCollection',
    features: Array.from({ length: count }).map((_, i) => ({
      type: 'Feature',
      id: i,
      geometry: {
        type: 'Point',
        coordinates: [
          minLng + Math.random() * (maxLng - minLng),
          minLat + Math.random() * (maxLat - minLat),
        ],
      },
      properties: {
        id: `point-${i}`,
        title: `Asset #${i + 1}`,
        description: `High-performance spatial asset detected at this location.`,
        value: Math.floor(Math.random() * 100),
      },
    })),
  }
}

export const MANILA_BBOX: [number, number, number, number] = [120.9, 14.4, 121.2, 14.7]
