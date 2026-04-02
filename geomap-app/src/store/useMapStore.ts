import { create } from 'zustand'
import type { Map } from 'maplibre-gl'

interface MapState {
  map: Map | null
  setMap: (map: Map | null) => void
  isLoaded: boolean
  setIsLoaded: (isLoaded: boolean) => void
  zoom: number
  setZoom: (zoom: number) => void
  center: { lng: number; lat: number }
  setCenter: (center: { lng: number; lat: number }) => void
}

export const useMapStore = create<MapState>((set) => ({
  map: null,
  setMap: (map) => set({ map }),
  isLoaded: false,
  setIsLoaded: (isLoaded) => set({ isLoaded }),
  zoom: 19,
  setZoom: (zoom) => set({ zoom }),
  center: { lng: 125.714882, lat: 7.370672 },
  setCenter: (center) => set({ center }),
}))
