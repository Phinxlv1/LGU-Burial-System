import { useState, useEffect } from 'react'
import { MapView } from './components/Map/MapView'
import { DataSource } from './components/Map/DataSource'
import { MapLayer } from './components/Map/MapLayer'
import { motion, AnimatePresence } from 'framer-motion'
import { Search, Layers, Navigation2, Info, Map as MapIcon, X } from 'lucide-react'
import { useMapStore } from './store/useMapStore'

import { useMapUrlSync } from './hooks/useMapUrlSync'

function App() {
  const { isLoaded, zoom, center, map } = useMapStore()
  const [selectedFeature, setSelectedFeature] = useState<any>(null)
  const [pointsData, setPointsData] = useState<any>(null)
  
  // URL Synchronization
  useMapUrlSync()
  
  // Fetch Real Data from Laravel Backend
  useEffect(() => {
    if (!isLoaded) return
    
    const fetchPlots = async () => {
       try {
         const res = await fetch('/api/cemetery/plots')
         const data = await res.json()
         
         // Fix: Ensure we only set data if it's a valid GeoJSON FeatureCollection
         if (data && data.type === 'FeatureCollection') {
           console.log('✅ Fetched Plots from Laravel:', data.features?.length || 0, 'features found.')
           setPointsData(data)
         } else {
           console.warn('⚠️ Received non-GeoJSON data from API (check if you are logged in). Setting empty collection.')
           setPointsData({ type: 'FeatureCollection', features: [] })
         }
       } catch (err) {
         console.error('❌ Failed to fetch cemetery plots:', err)
         setPointsData({ type: 'FeatureCollection', features: [] })
       }
    }

    fetchPlots()
    
    // Refresh data every 30s to keep real-time sync with database
    const interval = setInterval(fetchPlots, 30000)
    return () => clearInterval(interval)
  }, [isLoaded])

  // Hook into map click for feature selection
  useEffect(() => {
    if (!map || !isLoaded) return

    const handleMapClick = (e: any) => {
      const features = map.queryRenderedFeatures(e.point, { layers: ['unclustered-points'] })
      if (features.length > 0) {
        const feature = features[0]
        setSelectedFeature(feature.properties)
        
        // Contextual Interaction: FlyTo camera animation
        map.flyTo({
          center: (feature.geometry as any).coordinates,
          zoom: 14,
          pitch: 45,
          bearing: -20,
          duration: 2000,
          essential: true
        })
      } else {
        setSelectedFeature(null)
      }
    }

    map.on('click', 'unclustered-points', handleMapClick)
    
    // Pointer cursor on hover
    map.on('mouseenter', 'unclustered-points', () => {
       map.getCanvas().style.cursor = 'pointer'
    })
    map.on('mouseleave', 'unclustered-points', () => {
       map.getCanvas().style.cursor = ''
    })

    return () => {
      map.off('click', 'unclustered-points', handleMapClick)
    }
  }, [map, isLoaded])

  return (
    <main className="relative w-screen h-screen overflow-hidden bg-slate-950 font-sans text-slate-100 antialiased">
      {/* 1. Core Map Engine */}
      <MapView />

      {/* Declarative Data Layers */}
      {isLoaded && (
        <>
          <DataSource id="assets" data={pointsData} cluster={true} clusterRadius={40} />
          
          {/* Clusters (Circle Layer) */}
          <MapLayer 
            id="clusters"
            type="circle"
            source="assets"
            filter={['has', 'point_count']}
            paint={{
              'circle-color': [
                'step',
                ['get', 'point_count'],
                '#3b82f6', 100, '#8b5cf6', 750, '#f59e0b'
              ],
              'circle-radius': [
                'step',
                ['get', 'point_count'],
                20, 100, 30, 750, 40
              ],
              'circle-stroke-width': 2,
              'circle-stroke-color': 'rgba(255, 255, 255, 0.2)',
              'circle-blur': 0.1
            }}
          />

          {/* Cluster Count (Symbol Layer) */}
          <MapLayer 
            id="cluster-count"
            type="symbol"
            source="assets"
            filter={['has', 'point_count']}
            layout={{
              'text-field': '{point_count_abbreviated}',
              'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
              'text-size': 14
            }}
            paint={{
              'text-color': '#ffffff'
            }}
          />

          {/* Unclustered individual points */}
          <MapLayer 
            id="unclustered-points"
            type="circle"
            source="assets"
            filter={['!', ['has', 'point_count']]}
            paint={{
              'circle-color': '#60a5fa',
              'circle-radius': 6,
              'circle-stroke-width': 2,
              'circle-stroke-color': '#ffffff'
            }}
          />
        </>
      )}

      {/* 2. Floating UI Components (Glassmorphism) */}
      <div className="pointer-events-none absolute inset-0 flex flex-col p-6 z-10">
        
        {/* Top Bar: Search & Status */}
        <header className="flex w-full items-start justify-between">
          <motion.div 
            initial={{ y: -20, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            className="pointer-events-auto flex items-center gap-3 rounded-2xl border border-white/10 bg-black/40 p-2 backdrop-blur-2xl shadow-2xl ring-1 ring-white/5"
          >
            <div className="flex h-10 items-center gap-2 pl-3 pr-2">
              <Search className="h-4 w-4 text-slate-400" />
              <input 
                type="text" 
                placeholder="Search Metro Manila..." 
                className="w-64 bg-transparent text-sm font-medium focus:outline-none placeholder:text-slate-500"
              />
            </div>
            <div className="h-5 w-px bg-white/10" />
            <button className="flex h-10 w-10 items-center justify-center rounded-xl hover:bg-white/10 transition-colors">
              <Layers className="h-4 w-4 text-slate-300" />
            </button>
          </motion.div>

          <div className="flex flex-col items-end gap-3 text-right">
             <motion.div 
               initial={{ x: 20, opacity: 0 }}
               animate={{ x: 0, opacity: 1 }}
               className="pointer-events-auto rounded-xl border border-white/10 bg-black/40 p-3 backdrop-blur-2xl"
             >
                <div className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1 leading-none">Map Health</div>
                <div className="flex items-center gap-2">
                  <div className={`h-1.5 w-1.5 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.8)] ${isLoaded ? 'bg-blue-500 animate-pulse' : 'bg-red-500'}`} />
                  <span className="text-[10px] font-mono font-bold">{isLoaded ? 'WebGL 2.0 ONLINE' : 'BOOTING...'}</span>
                </div>
             </motion.div>
          </div>
        </header>

        {/* Selected Feature Drawer (Glassmorphism) */}
        <AnimatePresence>
          {selectedFeature && (
            <motion.div 
              initial={{ x: -100, opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: -100, opacity: 0 }}
              className="pointer-events-auto mt-6 w-80 rounded-3xl border border-white/10 bg-black/40 p-6 backdrop-blur-3xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.5)]"
            >
               <div className="flex items-start justify-between mb-6">
                 <div className="bg-blue-500/20 text-blue-400 text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-wider">Asset Identified</div>
                 <button onClick={() => setSelectedFeature(null)} className="p-1 hover:bg-white/10 rounded-full transition-colors text-slate-500 hover:text-white">
                    <X className="h-4 w-4" />
                 </button>
               </div>
               <h2 className="text-2xl font-bold mb-2 tracking-tight text-white">{selectedFeature.title}</h2>
               <p className="text-sm text-slate-400 mb-6 leading-relaxed italic">"{selectedFeature.description}"</p>
               
               <div className="grid grid-cols-2 gap-3 pb-6 border-b border-white/5 mb-6">
                  <div className="bg-white/5 rounded-xl p-3">
                    <div className="text-[10px] text-slate-500 uppercase font-black mb-1">Stability Score</div>
                    <div className="text-xl font-bold text-blue-400">{selectedFeature.value}%</div>
                  </div>
                  <div className="bg-white/5 rounded-xl p-3">
                    <div className="text-[10px] text-slate-500 uppercase font-black mb-1">Status</div>
                    <div className="text-xl font-bold text-green-400 italic font-serif italic">Nominal</div>
                  </div>
               </div>

               <button className="w-full h-12 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-900/20 active:scale-95">Perform Spatial Analysis</button>
            </motion.div>
          )}
        </AnimatePresence>

        {/* Dynamic Legend / Statistics */}
        <div className="mt-auto flex w-full items-end justify-between">
           <motion.aside 
             initial={{ x: -20, opacity: 0 }}
             animate={{ x: 0, opacity: 1 }}
             className="pointer-events-auto flex flex-col gap-2"
           >
              <div className="rounded-2xl border border-white/10 bg-black/40 p-5 backdrop-blur-2xl w-64 shadow-2xl">
                 <h3 className="mb-3 text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                    <Navigation2 className="h-3 w-3 text-blue-400 rotate-45" />
                    Viewport Telemetry
                 </h3>
                 <div className="space-y-4">
                    <div className="flex justify-between items-center text-[11px]">
                       <span className="text-slate-500 font-medium">Zoom Level</span>
                       <span className="font-mono text-blue-400 font-black">{zoom.toFixed(2)}</span>
                    </div>
                    <div className="relative h-1 w-full bg-white/5 rounded-full overflow-hidden">
                       <motion.div 
                         className="absolute top-0 left-0 h-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]" 
                         animate={{ width: `${(zoom / 20) * 100}%` }}
                       />
                    </div>
                    <div className="flex justify-between items-center text-[10px] font-mono font-bold text-slate-400">
                       <span>{center.lat.toFixed(4)}°N</span>
                       <span>{center.lng.toFixed(4)}°E</span>
                    </div>
                 </div>
              </div>
           </motion.aside>

           {/* Custom Controls */}
           <motion.div 
             initial={{ y: 20, opacity: 0 }}
             animate={{ y: 0, opacity: 1 }}
             className="pointer-events-auto flex flex-col gap-3"
           >
              <div className="flex flex-col gap-1 rounded-2xl border border-white/10 bg-black/40 p-1.5 backdrop-blur-2xl shadow-2xl">
                 <button className="flex h-11 w-11 items-center justify-center rounded-xl text-slate-400 hover:bg-white/10 hover:text-white transition-all active:scale-90 group relative">
                    <MapIcon className="h-5 w-5" />
                    <span className="absolute left-[-100px] bg-black/80 px-2 py-1 rounded text-[10px] font-black opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Basemaps</span>
                 </button>
                 <button className="flex h-11 w-11 items-center justify-center rounded-xl text-slate-400 hover:bg-white/10 hover:text-white transition-all active:scale-90 group relative">
                    <Info className="h-5 w-5" />
                    <span className="absolute left-[-100px] bg-black/80 px-2 py-1 rounded text-[10px] font-black opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">About</span>
                 </button>
              </div>
           </motion.div>
        </div>
      </div>

      {/* 3. Loading Overlay */}
      <AnimatePresence mode="wait">
        {!isLoaded && (
          <motion.div 
            key="loader"
            initial={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="absolute inset-0 z-50 flex items-center justify-center bg-slate-950 px-10"
          >
             <div className="flex flex-col items-center gap-10">
                <div className="relative">
                   <div className="h-32 w-32 rounded-full border border-blue-500/10 animate-pulse scale-150" />
                   <div className="absolute inset-0 h-32 w-32 rounded-full border-t-2 border-l-2 border-blue-500 animate-spin" />
                   <div className="absolute inset-4 h-24 w-24 rounded-full border-b-2 border-blue-400/30 animate-[spin_3s_linear_infinite]" />
                   <div className="absolute inset-0 flex items-center justify-center">
                      <div className="h-2 w-2 bg-blue-500 rounded-full animate-ping shadow-[0_0_20px_#3b82f6]" />
                   </div>
                </div>
                <div className="text-center group">
                   <h2 className="text-4xl font-black italic tracking-tighter text-white mb-3">
                     PHINXLV <span className="text-blue-500">ENGINE v5</span>
                   </h2>
                   <div className="relative h-1 w-64 bg-white/5 rounded-full overflow-hidden mx-auto">
                      <motion.div 
                        className="absolute top-0 left-0 h-full bg-blue-500" 
                        initial={{ width: 0 }}
                        animate={{ width: '100%' }}
                        transition={{ duration: 3, repeat: Infinity }}
                      />
                   </div>
                   <p className="mt-4 text-[10px] text-slate-500 font-mono font-black uppercase tracking-[0.3em] group-hover:text-blue-400/50 transition-colors">Synchronizing High Fidelity Clusters...</p>
                </div>
             </div>
          </motion.div>
        )}
      </AnimatePresence>
    </main>
  )
}

export default App
