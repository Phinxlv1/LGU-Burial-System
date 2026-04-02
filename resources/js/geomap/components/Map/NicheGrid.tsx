import React, { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useMapStore, GridSection } from '../../store/useMapStore'
import { X, Settings2, Trash2 } from 'lucide-react'

interface NicheGridProps {
  section: GridSection
}

// Project a geographic coordinate to canvas pixels
function project(map: any, lat: number, lng: number) {
  return map.project([lng, lat])
}

export const NicheGrid: React.FC<NicheGridProps> = ({ section }) => {
  const {
    map,
    updateGridCell,
    removeGridSection,
    updateGridStatus,
    editMode,
    hoveredGridId,
    setHoveredGridId,
    selectedGridId,
    setSelectedGridId,
  } = useMapStore()

  const [midPixel,    setMidPixel]    = useState({ x: 0, y: 0 })
  const [editingCell, setEditingCell] = useState<string | null>(null)
  const [showConfig,  setShowConfig]  = useState(false)
  const [tempLabel,   setTempLabel]   = useState('')

  const isSelected  = selectedGridId === String(section.id)
  const isHovered   = hoveredGridId  === String(section.id)
  const isExpanded  = isSelected || isHovered
  const isManageMode = editMode !== 'view'

  // Track midpoint in canvas space
  useEffect(() => {
    if (!map) return
    const mid = section.lineStart && section.lineEnd
      ? { lat: (section.lineStart.lat + section.lineEnd.lat) / 2, lng: (section.lineStart.lng + section.lineEnd.lng) / 2 }
      : section.position

    if (!mid) return

    const update = () => {
      const px = project(map, mid.lat, mid.lng)
      setMidPixel({ x: px.x, y: px.y })
    }

    map.on('move', update)
    map.on('zoom', update)
    update()
    return () => { map.off('move', update); map.off('zoom', update) }
  }, [map, section.lineStart, section.lineEnd, section.position])

  if (!map) return null

  // ── Fixed cell size — ALWAYS readable regardless of zoom ──
  // 18px is a sweet spot that shows labels but isn't huge
  const CELL = 18
  const color = section.color || '#ef4444'

  const rows = Array.from({ length: section.rows }, (_, i) => i + 1)
  const cols = Array.from({ length: section.cols }, (_, i) => i + 1)

  const getLabel = (row: number, col: number, override?: string) => {
    if (override) return override
    return section.labelFormat
      .replace('{row}', String(row))
      .replace('{col}', String(col))
      .replace('{section}', section.name)
  }

  const STATUS_COLORS: Record<string, string> = {
    available: '#22c55e',
    occupied:  '#ef4444',
    reserved:  '#f59e0b',
    empty:     'rgba(255,255,255,0.05)',
  }

  return (
    <div
      style={{
        position:      'absolute',
        left:           midPixel.x,
        top:            midPixel.y,
        // Always render at the midpoint; shift up so the arrow tip sits on the line
        transform:      'translate(-50%, calc(-100% - 14px))',
        zIndex:         isSelected ? 2000 : isHovered ? 1500 : 5,
        // KEY FIX: popup is pointer-events NONE while hovering (transient) —
        // the MapLibre line layer drives hover state.
        // When SELECTED it becomes interactive.
        pointerEvents: isSelected ? 'auto' : 'none',
      }}
    >
      <AnimatePresence>
        {isExpanded && (
          <motion.div
            key="grid-popup"
            initial={{ scale: 0.88, opacity: 0, y: 10 }}
            animate={{ scale: 1, opacity: 1, y: 0 }}
            exit={{ scale: 0.88, opacity: 0, y: 10 }}
            transition={{ type: 'spring', stiffness: 400, damping: 30 }}
          >
            {/* Glass Panel */}
            <div
              style={{
                background:     'rgba(10, 16, 33, 0.97)',
                backdropFilter: 'blur(32px)',
                border:         `2px solid ${isSelected ? color : 'rgba(255,255,255,0.14)'}`,
                borderRadius:   '18px',
                padding:        '12px',
                boxShadow:      isSelected
                  ? `0 20px 60px rgba(0,0,0,0.8), 0 0 0 1px ${color}30`
                  : '0 12px 36px rgba(0,0,0,0.6)',
                cursor: isSelected ? 'default' : 'pointer',
              }}
              // When transient, clicking the popup selects it
              onClick={(e: React.MouseEvent) => {
                if (!isSelected) { e.stopPropagation(); setSelectedGridId(String(section.id)) }
              }}
            >
              {/* Header */}
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '8px', gap: '8px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <div style={{ width: '7px', height: '7px', borderRadius: '50%', background: color, flexShrink: 0, boxShadow: `0 0 5px ${color}` }} />
                  <span style={{ fontSize: '10px', fontWeight: 900, color: 'white', textTransform: 'uppercase', letterSpacing: '0.06em', whiteSpace: 'nowrap' }}>
                    {section.name}
                  </span>
                </div>
                <div style={{ display: 'flex', gap: '3px' }}>
                  {isManageMode && isSelected && (
                    <>
                      <button onClick={(e: React.MouseEvent) => { e.stopPropagation(); setShowConfig(!showConfig) }}
                              style={iconBtn} title="Settings"><Settings2 size={11} /></button>
                      <button onClick={(e: React.MouseEvent) => {
                                e.stopPropagation()
                                if (confirm(`Delete "${section.name}"?`)) removeGridSection(section.id)
                              }}
                              style={{ ...iconBtn, background: 'rgba(239,68,68,0.15)', color: '#ef4444' }} title="Delete">
                        <Trash2 size={11} />
                      </button>
                    </>
                  )}
                  {isSelected && (
                    <button onClick={(e: React.MouseEvent) => { e.stopPropagation(); setSelectedGridId(null) }}
                            style={iconBtn} title="Close"><X size={11} /></button>
                  )}
                </div>
              </div>

              {/* Niche Grid — fixed CELL size */}
              <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
                {rows.map(r => (
                  <div key={r} style={{ display: 'flex', gap: '2px' }}>
                    {cols.map(c => {
                      const key    = `${r}-${c}`
                      const cell   = section.cells?.[key] || {}
                      const status = cell.status || 'available'
                      const label  = getLabel(r, c, cell.label)
                      return (
                        <div key={c} style={{ position: 'relative' }}>
                          <motion.div
                            whileHover={isManageMode && isSelected ? { scale: 1.18, zIndex: 10 } : {}}
                            style={{
                              width:          CELL,
                              height:         CELL,
                              background:     STATUS_COLORS[status] || STATUS_COLORS.available,
                              border:         '1px solid rgba(0,0,0,0.25)',
                              borderRadius:   '3px',
                              cursor:         isManageMode && isSelected ? 'pointer' : 'default',
                              display:        'flex',
                              alignItems:     'center',
                              justifyContent: 'center',
                              fontSize:       '7px',
                              color:          'rgba(255,255,255,0.9)',
                              fontWeight:     900,
                              transition:     'all 0.12s',
                              boxShadow:      status !== 'empty' ? `0 1px 4px ${STATUS_COLORS[status]}50` : 'none',
                            }}
                            onClick={(e: React.MouseEvent) => {
                              if (isManageMode && isSelected) {
                                e.stopPropagation()
                                setEditingCell(key)
                                setTempLabel(label)
                              }
                            }}
                          >
                            {label.split('-').pop()}
                          </motion.div>

                          {/* Cell Editor */}
                          <AnimatePresence>
                            {editingCell === key && isSelected && (
                              <motion.div
                                initial={{ opacity: 0, y: -6 }}
                                animate={{ opacity: 1, y: 0 }}
                                exit={{ opacity: 0, y: -6 }}
                                onClick={(e: React.MouseEvent) => e.stopPropagation()}
                                style={{
                                  position: 'absolute', bottom: '100%', left: '50%', transform: 'translateX(-50%)',
                                  marginBottom: '10px', width: '190px',
                                  background: '#0f172a', borderRadius: '14px', padding: '14px',
                                  zIndex: 300, border: `1px solid ${color}60`,
                                  boxShadow: '0 20px 40px rgba(0,0,0,0.9)',
                                }}
                              >
                                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
                                  <span style={{ color, fontSize: '9px', fontWeight: 900, textTransform: 'uppercase' }}>Niche</span>
                                  <button onClick={() => setEditingCell(null)} style={{ background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}><X size={13} /></button>
                                </div>
                                <input
                                  autoFocus value={tempLabel}
                                  onChange={(e: React.ChangeEvent<HTMLInputElement>) => setTempLabel(e.target.value)}
                                  style={{ width: '100%', background: '#1e293b', border: '1px solid rgba(255,255,255,0.08)', borderRadius: '7px', padding: '7px 9px', color: 'white', fontSize: '12px', outline: 'none', marginBottom: '8px', boxSizing: 'border-box' }}
                                  onKeyDown={(e: React.KeyboardEvent) => {
                                    if (e.key === 'Enter') { updateGridCell(section.id, key, { label: tempLabel }); setEditingCell(null) }
                                  }}
                                />
                                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '5px' }}>
                                  {['available', 'occupied', 'reserved', 'empty'].map(s => (
                                    <button key={s}
                                      onClick={() => { updateGridStatus(section.id, key, s); setEditingCell(null) }}
                                      style={{ padding: '5px', fontSize: '8px', borderRadius: '5px', background: status === s ? color : 'rgba(255,255,255,0.05)', color: 'white', border: 'none', cursor: 'pointer', textTransform: 'capitalize', fontWeight: 800 }}>
                                      {s}
                                    </button>
                                  ))}
                                </div>
                              </motion.div>
                            )}
                          </AnimatePresence>
                        </div>
                      )
                    })}
                  </div>
                ))}
              </div>

              {/* Legend */}
              <div style={{ display: 'flex', gap: '6px', marginTop: '8px', paddingTop: '7px', borderTop: '1px solid rgba(255,255,255,0.06)' }}>
                {[['available','#22c55e'],['occupied','#ef4444'],['reserved','#f59e0b']].map(([s,c]) => (
                  <div key={s} style={{ display: 'flex', alignItems: 'center', gap: '3px' }}>
                    <div style={{ width: '5px', height: '5px', borderRadius: '2px', background: c }} />
                    <span style={{ fontSize: '7px', color: '#64748b', fontWeight: 700, textTransform: 'capitalize' }}>{s}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Arrow tip pointing at the line */}
            <div style={{
              width: 0, height: 0,
              borderLeft: '7px solid transparent',
              borderRight: '7px solid transparent',
              borderTop: `7px solid ${isSelected ? color : 'rgba(255,255,255,0.14)'}`,
              margin: '0 auto',
            }} />
          </motion.div>
        )}
      </AnimatePresence>

      {/* Settings flyout */}
      <AnimatePresence>
        {showConfig && isSelected && (
          <motion.div
            key="config"
            initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, y: 10 }}
            onClick={(e: React.MouseEvent) => e.stopPropagation()}
            style={{
              position: 'absolute', bottom: 'calc(100% + 10px)', left: '50%', transform: 'translateX(-50%)',
              width: '230px', background: '#1e293b', borderRadius: '16px', padding: '18px',
              zIndex: 400, border: '1px solid rgba(255,255,255,0.1)', boxShadow: '0 24px 50px rgba(0,0,0,0.8)',
              pointerEvents: 'auto',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '12px' }}>
              <span style={{ fontSize: '10px', fontWeight: 900, color, textTransform: 'uppercase', letterSpacing: '0.06em' }}>Block Settings</span>
              <button onClick={() => setShowConfig(false)} style={{ background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}><X size={14} /></button>
            </div>
            <p style={{ color: '#64748b', fontSize: '10px', lineHeight: 1.6, margin: 0 }}>
              Click each niche to change its status or label.<br />Re-draw the line to update dimensions.
            </p>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  )
}

const iconBtn: React.CSSProperties = {
  background: 'rgba(255,255,255,0.07)',
  border: 'none',
  color: 'rgba(255,255,255,0.65)',
  cursor: 'pointer',
  borderRadius: '5px',
  padding: '4px',
  display: 'flex',
  alignItems: 'center',
}
