import React, { useEffect, useState, useRef, useMemo, useCallback, memo } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useMapStore, GridSection } from '../../store/useMapStore'
import { X, Settings2, Trash2, User, Calendar, FileText, ChevronDown } from 'lucide-react'

interface NicheGridProps {
  section: GridSection
}

function project(map: any, lat: number, lng: number) {
  return map.project([lng, lat])
}

const CELL = 18
const COLOR = '#ef4444'

function getLabel(r: number, c: number, custom?: string) {
  return custom || `${r}-${c}`
}

const STATUS_BG: Record<string, string> = {
  available: '#22c55e',
  occupied:  '#ef4444',
  reserved:  '#f59e0b',
  empty:     'rgba(255,255,255,0.07)',
}

// Fast easing — no spring physics
const FAST = { duration: 0.14, ease: [0.25, 0.46, 0.45, 0.94] as any }
const POP  = { duration: 0.18, ease: [0.34, 1.26, 0.64, 1.0]  as any }

// ─── Niche Info Modal (Universal System Design) ──────────────────────────────
const NicheInfoModal = memo(({ nicheModal, blockName, onClose }: { nicheModal: any; blockName: string; onClose: () => void }) => {
  const [data, setData]       = useState<any>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError]     = useState(false)

  useEffect(() => {
    if (!nicheModal?.deceased_id) { setLoading(false); setError(true); return }
    let cancelled = false
    setLoading(true); setError(false); setData(null)
    fetch(`/cemetery/deceased/${nicheModal.deceased_id}/info`)
      .then(r => r.ok ? r.json() : Promise.reject())
      .then(json => { if (!cancelled) { setData(json); setLoading(false) } })
      .catch(() => { if (!cancelled) { setError(true); setLoading(false) } })
    return () => { cancelled = true }
  }, [nicheModal?.deceased_id])

  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={FAST}
      onMouseDown={(e: any) => e.stopPropagation()}
      onClick={(e: any) => { e.stopPropagation(); onClose() }}
      style={{
        position: 'fixed', inset: 0,
        backgroundColor: 'rgba(0,0,0,0.6)',
        backdropFilter: 'blur(16px)',
        zIndex: 99999,
        display: 'flex', 
        alignItems: 'center',       // Vertical center
        justifyContent: 'center',    // Horizontal center
        padding: '20px',
        paddingTop: '80px',          // Force push down to avoid search bar collision
        pointerEvents: 'all',
      }}
    >
      <motion.div
        initial={{ scale: 0.9, opacity: 0, y: 20 }}
        animate={{ scale: 1, opacity: 1, y: 0 }}
        exit={{ scale: 0.9, opacity: 0, y: 20 }}
        transition={POP}
        onMouseDown={(e: any) => e.stopPropagation()}
        onClick={(e: any) => e.stopPropagation()}
        style={{
          width: '380px',
          maxHeight: '90vh',
          backgroundColor: '#0f172a',
          borderRadius: '32px',
          border: '1px solid rgba(255, 255, 255, 0.1)',
          padding: '40px',
          boxShadow: '0 50px 100px -20px rgba(0, 0, 0, 1)',
          position: 'relative',
          display: 'flex',
          flexDirection: 'column',
          overflowY: 'auto',
          scrollbarWidth: 'none',
        }}
      >
        <button 
           onClick={onClose}
           style={{ position: 'absolute', top: '24px', right: '24px', background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}
        >
          <X size={20} />
        </button>

        <div style={{ marginBottom: '24px' }}>
          <div style={{ fontSize: '10px', color: '#3b82f6', fontWeight: '900', letterSpacing: '0.2em', textTransform: 'uppercase', marginBottom: '8px' }}>
            Niche Occupant · {blockName}
          </div>
          <h2 style={{ fontSize: '32px', fontWeight: '900', color: 'white', margin: 0, letterSpacing: '-0.04em', lineHeight: 1.1 }}>
            {loading ? '...' : (data?.full_name || nicheModal.deceased_name)}
          </h2>
        </div>

        {loading ? (
          <div style={{ padding: '24px', color: '#64748b', textAlign: 'center' }}>Loading dossier...</div>
        ) : data ? (
          <>
            <div style={{ padding: '24px', backgroundColor: 'rgba(255,255,255,0.03)', borderRadius: '24px', border: '1px solid rgba(255,255,255,0.05)', marginBottom: '16px' }}>
               <div style={{ fontSize: '10px', color: '#64748b', fontWeight: 'bold', textTransform: 'uppercase', marginBottom: '8px' }}>Permit Status</div>
               <div style={{ fontSize: '18px', color: 'white', fontWeight: 'bold', display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: data.permit?.status === 'active' ? '#22c55e' : '#ef4444' }} />
                  {data.permit ? `${data.permit.permit_number} — ${data.permit.status.toUpperCase()}` : 'No Active Permit'}
               </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', marginBottom: '32px' }}>
               <div style={{ padding: '16px', backgroundColor: 'rgba(255,255,255,0.02)', borderRadius: '18px', border: '1px solid rgba(255,255,255,0.04)' }}>
                  <div style={{ fontSize: '8px', color: '#64748b', fontWeight: 'bold', textTransform: 'uppercase', marginBottom: '4px' }}>Date of Death</div>
                  <div style={{ fontSize: '12px', color: 'white', fontWeight: '600' }}>{data.date_of_death || '—'}</div>
               </div>
               <div style={{ padding: '16px', backgroundColor: 'rgba(255,255,255,0.02)', borderRadius: '18px', border: '1px solid rgba(255,255,255,0.04)' }}>
                  <div style={{ fontSize: '8px', color: '#64748b', fontWeight: 'bold', textTransform: 'uppercase', marginBottom: '4px' }}>Kind of Burial</div>
                  <div style={{ fontSize: '12px', color: 'white', fontWeight: '600' }}>{data.kind_of_burial || '—'}</div>
               </div>
            </div>

            <button 
              onClick={onClose}
              style={{ width: '100%', backgroundColor: 'white', color: '#0f172a', border: 'none', padding: '18px', borderRadius: '18px', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '0.1em', cursor: 'pointer', transition: 'transform 0.1s' }}
              onMouseDown={(e: any) => e.currentTarget.style.transform = 'scale(0.98)'}
              onMouseUp={(e: any) => e.currentTarget.style.transform = 'scale(1)'}
            >
              Close Record
            </button>
          </>
        ) : (
          <div style={{ padding: '24px', color: '#ef4444', textAlign: 'center' }}>Error loading data.</div>
        )}
      </motion.div>
    </motion.div>
  )
})

// ─── NicheGrid ───────────────────────────────────────────────────────────────
export const NicheGrid = memo(({ section }: NicheGridProps) => {
  const {
    map, removeGridSection, updateGridCell, updateGridStatus,
    editMode, hoveredGridId, selectedGridId, setSelectedGridId,
    setSelectedFeature
  } = useMapStore()
  const selectedFeature = useMapStore(s => s.selectedFeature)

  const gridRef  = useRef<HTMLDivElement>(null)
  const [editingCell, setEditingCell]           = useState<string | null>(null)
  const [hoveredCell, setHoveredCell]           = useState<string | null>(null)
  const [showConfig, setShowConfig]             = useState(false)
  const [isMouseOverPanel, setIsMouseOverPanel] = useState(false)
  const [nicheModal, setNicheModal]             = useState<any | null>(null)

  const isSelected   = selectedGridId === String(section.id)
  const isHovered    = hoveredGridId  === String(section.id)
  const isExpanded   = isSelected || isHovered || isMouseOverPanel
  const isManageMode = editMode !== 'view'

  // Position update via direct DOM — no React state, no re-render on every frame
  useEffect(() => {
    if (!map) return
    const mid = section.lineStart && section.lineEnd
      ? { lat: (section.lineStart.lat + section.lineEnd.lat) / 2, lng: (section.lineStart.lng + section.lineEnd.lng) / 2 }
      : section.position

    const update = () => {
      const el = gridRef.current
      if (!el || !mid) return
      const { x, y } = project(map, mid.lat, mid.lng)
      // Use translate3d for GPU acceleration
      el.style.transform = `translate3d(calc(${x}px - 50%), calc(${y}px - 100% - 14px), 0)`
    }
    update()
    map.on('move', update)
    map.on('zoom', update)
    return () => { map.off('move', update); map.off('zoom', update) }
  }, [map, section.lineStart, section.lineEnd, section.position])

  const rows = useMemo(() => Array.from({ length: section.rows || 4  }, (_, i) => i + 1), [section.rows])
  const cols = useMemo(() => Array.from({ length: section.cols || 10 }, (_, i) => i + 1), [section.cols])

  const openModal  = useCallback((cell: any, key: string, label: string) => setNicheModal({ ...cell, key, label }), [])
  const closeModal = useCallback(() => setNicheModal(null), [])

  return (
    <>
      <div
        ref={gridRef}
        style={{
          position: 'absolute', left: 0, top: 0,
          zIndex: isSelected ? 2000 : isHovered ? 1500 : 5,
          pointerEvents: 'auto',
          willChange: 'transform',
          backfaceVisibility: 'hidden',
        }}
        onMouseEnter={() => setIsMouseOverPanel(true)}
        onMouseLeave={() => setIsMouseOverPanel(false)}
      >
        <AnimatePresence>
          {isExpanded && (
            <motion.div
              key="grid-popup"
              initial={{ opacity: 0, scale: 0.98 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, pointerEvents: 'none' }}
              transition={{ duration: 0.1, ease: 'linear' }}
              style={{ willChange: 'opacity, transform' }}
            >
              {/* Glass panel — lighter blur for performance */}
              <div style={{
                background: 'rgba(10,16,33,0.96)',
                border: `2px solid ${isSelected ? COLOR : 'rgba(255,255,255,0.13)'}`,
                borderRadius: '18px', padding: '12px',
                boxShadow: isSelected ? `0 16px 48px rgba(0,0,0,0.75), 0 0 0 1px ${COLOR}28` : '0 10px 30px rgba(0,0,0,0.55)',
                cursor: isSelected ? 'default' : 'pointer',
              }}
                onClick={(e: any) => { if (!isSelected) { e.stopPropagation(); setSelectedGridId(String(section.id)) } }}
              >
                {/* Header */}
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '8px', gap: '8px' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                    <div style={{ width: '7px', height: '7px', borderRadius: '50%', background: COLOR, boxShadow: `0 0 5px ${COLOR}` }} />
                    <span style={{ fontSize: '10px', fontWeight: 900, color: 'white', textTransform: 'uppercase', letterSpacing: '0.06em', whiteSpace: 'nowrap' }}>{section.name}</span>
                  </div>
                  <div style={{ display: 'flex', gap: '3px' }}>
                    {isManageMode && isSelected && (
                      <>
                        <button onClick={(e: any) => { e.stopPropagation(); setShowConfig((v: any) => !v) }} style={iconBtn}><Settings2 size={11} /></button>
                        <button onClick={(e: any) => { e.stopPropagation(); if (confirm(`Delete "${section.name}"?`)) removeGridSection(section.id) }} style={{ ...iconBtn, background: 'rgba(239,68,68,0.15)', color: '#ef4444' }}><Trash2 size={11} /></button>
                      </>
                    )}
                    {isSelected && <button onClick={(e: any) => { e.stopPropagation(); setIsMouseOverPanel(false); setSelectedGridId(null) }} style={iconBtn}><X size={11} /></button>}
                  </div>
                </div>

                {/* Grid — plain divs, CSS transitions only (no per-cell motion.div) */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px' }}>
                  {rows.map(r => (
                    <div key={r} style={{ display: 'flex', gap: '3px', minWidth: 'max-content' }}>
                      {cols.map((c: any) => {
                        const key    = `${r}-${c}`
                        const cell: any = section.cells?.[key] || {}
                        const status = cell.status || 'available'
                        const label  = getLabel(r, c, cell.label)
                        const isHov  = hoveredCell === key

                        return (
                          <div key={c} style={{ position: 'relative' }}
                            onMouseEnter={() => setHoveredCell(key)}
                            onMouseLeave={() => setHoveredCell(null)}
                          >
                            {/* Pure CSS transform on hover — much lighter than motion.div */}
                            <div
                              style={{
                                width: CELL, height: CELL, flexShrink: 0,
                                background: STATUS_BG[status] || STATUS_BG.available,
                                border: '1px solid rgba(0,0,0,0.28)',
                                borderRadius: '4px',
                                cursor: (isManageMode && isSelected) || (status === 'occupied' && !!cell.deceased_id) ? 'pointer' : 'default',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: '6px', color: 'rgba(255,255,255,0.93)', fontWeight: 900,
                                boxShadow: status !== 'empty' ? 'inset 0 1px 2px rgba(255,255,255,0.18), 0 2px 4px rgba(0,0,0,0.28)' : 'none',
                                overflow: 'hidden',
                                // CSS transition instead of Framer Motion
                                transition: 'transform 0.1s ease, box-shadow 0.1s ease',
                                transform: isHov ? 'scale(1.18)' : 'scale(1)',
                                zIndex: isHov ? 10 : 'auto',
                                // SEARCH HEARTBEAT: Only pulse during active search (identifiable by fromSearch flag)
                                animation: (selectedFeature?.fromSearch && selectedFeature?.deceased_id && cell?.deceased_id && String(cell.deceased_id) === String(selectedFeature.deceased_id))
                                  ? 'heartbeat 0.8s ease-in-out infinite' 
                                  : 'none',
                              }}
                              onClick={(e: any) => {
                                e.stopPropagation()
                                setSelectedFeature(null) // Stop pulsing when clicked
                                if (!isSelected) setSelectedGridId(String(section.id))
                                setHoveredCell(null)
                                if (isManageMode) {
                                  setEditingCell(key)
                                } else if (status === 'occupied' && cell.deceased_id) {
                                  openModal(cell, key, label)
                                }
                              }}
                            >
                              {cell.deceased_id
                                ? <User size={9} strokeWidth={3} />
                                : (label.length > 4 ? `c${c}` : label.split('-').pop())}
                            </div>

                            {/* Tooltip */}
                            {isHov && editingCell !== key && (
                              <div style={{
                                position: 'absolute', bottom: 'calc(100% + 6px)', left: '50%',
                                transform: 'translateX(-50%)',
                                background: '#0f172a',
                                border: `1px solid ${COLOR}45`,
                                padding: '4px 8px', borderRadius: '6px',
                                boxShadow: '0 8px 20px rgba(0,0,0,0.4)',
                                zIndex: 1000, pointerEvents: 'none', minWidth: 'max-content',
                                display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '2px',
                                // CSS animation — much cheaper
                                animation: 'fadeUp 0.1s ease forwards',
                              }}>
                                <span style={{ color: 'white', fontSize: '9px', fontWeight: 800 }}>
                                  {status === 'occupied' && cell.deceased_name ? cell.deceased_name : `${label} – ${status.toUpperCase()}`}
                                </span>
                                {cell.permit_number && (
                                  <span style={{ color: '#22c55e', fontSize: '7px', fontWeight: 700 }}>BP: {cell.permit_number}</span>
                                )}
                                <div style={{ position: 'absolute', top: '100%', left: '50%', transform: 'translateX(-50%)', borderLeft: '4px solid transparent', borderRight: '4px solid transparent', borderTop: `4px solid ${COLOR}45` }} />
                              </div>
                            )}

                            {/* Cell Editor */}
                            <AnimatePresence>
                              {editingCell === key && isSelected && (
                                <CellEditor
                                  section={section} cellKey={key} color={COLOR}
                                  status={status} cellData={cell}
                                  isManageMode={isManageMode}
                                  onClose={() => setEditingCell(null)}
                                />
                              )}
                            </AnimatePresence>
                          </div>
                        )
                      })}
                    </div>
                  ))}
                </div>

                {/* Legend */}
                <div style={{ display: 'flex', gap: '6px', marginTop: '8px', paddingTop: '7px', borderTop: '1px solid rgba(255,255,255,0.055)' }}>
                  {[['available', '#22c55e'], ['occupied', '#ef4444'], ['reserved', '#f59e0b']].map(([s, c]: any[]) => (
                    <div key={s} style={{ display: 'flex', alignItems: 'center', gap: '3px' }}>
                      <div style={{ width: '5px', height: '5px', borderRadius: '2px', background: c }} />
                      <span style={{ fontSize: '7px', color: '#64748b', fontWeight: 700, textTransform: 'capitalize' }}>{s}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Arrow */}
              <div style={{ width: 0, height: 0, borderLeft: '7px solid transparent', borderRight: '7px solid transparent', borderTop: `7px solid ${isSelected ? COLOR : 'rgba(255,255,255,0.13)'}`, margin: '0 auto' }} />
            </motion.div>
          )}
        </AnimatePresence>

        {/* Settings flyout */}
        <AnimatePresence>
          {showConfig && isSelected && (
            <motion.div key="cfg"
              initial={{ opacity: 0, y: 6 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, y: 6 }}
              transition={FAST}
              onClick={(e: any) => e.stopPropagation()}
              style={{ position: 'absolute', bottom: 'calc(100% + 10px)', left: '50%', transform: 'translateX(-50%)', width: '220px', background: '#1e293b', borderRadius: '14px', padding: '16px', zIndex: 400, border: '1px solid rgba(255,255,255,0.09)', boxShadow: '0 20px 40px rgba(0,0,0,0.75)', pointerEvents: 'auto' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                <span style={{ fontSize: '10px', fontWeight: 900, color: COLOR, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Block Settings</span>
                <button onClick={() => setShowConfig(false)} style={{ background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}><X size={13} /></button>
              </div>
              <p style={{ color: '#64748b', fontSize: '10px', lineHeight: 1.6, margin: 0 }}>Click each niche to change status.<br />Re-draw the line to resize.</p>
            </motion.div>
          )}
        </AnimatePresence>
      </div>

      {/* Keyframes for animations */}
      <style>{`
        @keyframes fadeUp {
          from { opacity: 0; transform: translateX(-50%) translateY(4px); }
          to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        @keyframes heartbeat {
          0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.9); border: 2px solid #3b82f6; }
          50% { transform: scale(1.5); box-shadow: 0 0 40px 25px rgba(59, 130, 246, 0); border: 3px solid #fff; }
          100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); border: 2px solid #3b82f6; }
        }
      `}</style>

      {/* Full-screen info modal */}
      <AnimatePresence>
        {nicheModal && (
          <NicheInfoModal nicheModal={nicheModal} blockName={section.name} onClose={closeModal} />
        )}
      </AnimatePresence>
    </>
  )
})

// ─── Styles ──────────────────────────────────────────────────────────────────
const iconBtn: React.CSSProperties = {
  background: 'rgba(255,255,255,0.07)', border: 'none', color: 'rgba(255,255,255,0.6)',
  cursor: 'pointer', borderRadius: '5px', padding: '4px', display: 'flex', alignItems: 'center',
}

// ─── Cell Editor ─────────────────────────────────────────────────────────────
const CellEditor = memo(({ section, cellKey, color, status, cellData, onClose, isManageMode }: any) => {
  const { updateGridCell, updateGridStatus } = useMapStore()
  const [search, setSearch]   = useState('')
  const [results, setResults] = useState<any[]>([])
  const [loading, setLoading] = useState(false)

  // Fetch results (starts at 1 char, or empty for initial list)
  const fetchResults = useCallback(async (q: string) => {
    setLoading(true)
    try {
      const res = await fetch(`/cemetery/search-permits?unassigned=1&q=${encodeURIComponent(q)}`)
      const data = await res.json()
      setResults(data)
    } catch (err) {
      console.error(err)
    } finally {
      setLoading(false)
    }
  }, [])

  // Initial load
  useEffect(() => {
    fetchResults('')
  }, [fetchResults])

  // Search debouncing
  useEffect(() => {
    if (search.length === 0) {
      fetchResults('') // Show initial list if search cleared
      return
    }
    const t = setTimeout(() => fetchResults(search), 300)
    return () => clearTimeout(t)
  }, [search, fetchResults])

  const assign = (p: any) => {
    updateGridCell(section.id, cellKey, { 
      label: p.deceased_name, 
      deceased_id: p.deceased_id, 
      deceased_name: p.deceased_name, 
      permit_number: p.permit_number, 
      permit_status: p.permit_status, 
      status: 'occupied' 
    })
    onClose()
  }

  return (
    <motion.div initial={{ opacity: 0, y: -5 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, y: -5 }} transition={FAST}
      onClick={(e: any) => e.stopPropagation()}
      style={{ 
        position: 'absolute', bottom: '100%', left: '50%', transform: 'translateX(-50%)', 
        marginBottom: '10px', width: '240px', background: '#0f172a', borderRadius: '16px', 
        padding: '16px', zIndex: 300, border: `1px solid ${color}80`, 
        boxShadow: '0 20px 50px rgba(0,0,0,0.9)',
        fontFamily: '"DM Sans", sans-serif'
      }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '14px', alignItems: 'center' }}>
        <span style={{ color, fontSize: '10px', fontWeight: 900, textTransform: 'uppercase', letterSpacing: '0.08em' }}>Niche Editor</span>
        <button onClick={onClose} style={{ background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}><X size={14} /></button>
      </div>

      <div style={{ marginBottom: '14px' }}>
        {cellData?.deceased_id ? (
          <div style={{ background: 'rgba(34,197,94,0.08)', padding: '10px 12px', borderRadius: '12px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', border: '1px solid rgba(34,197,94,0.2)' }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
              <span style={{ fontSize: '8px', color: '#22c55e', fontWeight: 800, textTransform: 'uppercase' }}>Current Occupant</span>
              <span style={{ fontSize: '12px', color: 'white', fontWeight: 700 }}>{cellData.deceased_name}</span>
              <span style={{ fontSize: '9px', color: '#64748b', fontFamily: '"DM Mono", monospace' }}>{cellData.permit_number}</span>
            </div>
            <button onClick={() => updateGridCell(section.id, cellKey, { deceased_id: null, deceased_name: null })}
              style={{ background: 'rgba(239,68,68,0.15)', border: 'none', color: '#ef4444', cursor: 'pointer', padding: '6px', borderRadius: '8px' }}><X size={12} /></button>
          </div>
        ) : (
          <div style={{ position: 'relative' }}>
            <div style={{ position: 'relative', display: 'flex', alignItems: 'center' }}>
              <input autoFocus value={search} onChange={e => setSearch(e.target.value)} placeholder="Search or select person…"
                style={{ width: '100%', background: '#1e293b', border: '1px solid rgba(255,255,255,0.1)', borderRadius: '10px', padding: '10px 32px 10px 12px', color: 'white', fontSize: '12px', outline: 'none', boxSizing: 'border-box' }} />
              <div style={{ position: 'absolute', right: '10px', pointerEvents: 'none', color: '#64748b' }}>
                <ChevronDown size={14} />
              </div>
            </div>
            
            {/* Results List */}
            <div style={{ 
              marginTop: '6px', maxHeight: '180px', overflowY: 'auto', background: '#1e293b', 
              borderRadius: '10px', border: '1px solid rgba(255,255,255,0.08)', 
              boxShadow: '0 10px 25px rgba(0,0,0,0.5)', scrollbarWidth: 'none'
            }}>
              {loading && <div style={{ padding: '12px', fontSize: '11px', color: '#64748b', textAlign: 'center' }}>Searching...</div>}
              {!loading && results.length === 0 && <div style={{ padding: '12px', fontSize: '11px', color: '#64748b', textAlign: 'center' }}>No unassigned people found.</div>}
              {results.map((r: any, i: number) => (
                <button key={i} onClick={() => assign(r)} 
                  style={{ 
                    width: '100%', textAlign: 'left', padding: '10px 12px', background: 'transparent', 
                    border: 'none', borderBottom: '1px solid rgba(255,255,255,0.03)', color: 'white', 
                    fontSize: '12px', cursor: 'pointer', transition: 'background 0.2s',
                    display: 'flex', flexDirection: 'column', gap: '2px'
                  }}
                  onMouseEnter={(e: any) => e.currentTarget.style.background = 'rgba(255,255,255,0.05)'}
                  onMouseLeave={(e: any) => e.currentTarget.style.background = 'transparent'}
                >
                  <div style={{ fontWeight: 600 }}>{r.deceased_name}</div>
                  <div style={{ fontSize: '9px', color: '#64748b', display: 'flex', justifyContent: 'space-between' }}>
                    <span>{r.permit_number}</span>
                    <span style={{ color: r.permit_status === 'active' ? '#22c55e' : '#f59e0b', fontWeight: 700 }}>{r.permit_status?.toUpperCase()}</span>
                  </div>
                </button>
              ))}
            </div>
          </div>
        )}
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '6px' }}>
        {['available', 'occupied', 'reserved', 'empty'].map((s: string) => (
          <button key={s} onClick={() => { updateGridStatus(section.id, cellKey, s); onClose() }}
            style={{ 
              padding: '8px', fontSize: '10px', borderRadius: '8px', 
              background: status === s ? color : 'rgba(255,255,255,0.05)', 
              color: 'white', border: '1px solid rgba(255,255,255,0.05)', 
              cursor: 'pointer', textTransform: 'capitalize', fontWeight: 800,
              transition: 'all 0.2s'
            }}
            onMouseEnter={(e: any) => { if(status !== s) e.currentTarget.style.background = 'rgba(255,255,255,0.1)' }}
            onMouseLeave={(e: any) => { if(status !== s) e.currentTarget.style.background = 'rgba(255,255,255,0.05)' }}
          >{s}</button>
        ))}
      </div>
    </motion.div>
  )
})
