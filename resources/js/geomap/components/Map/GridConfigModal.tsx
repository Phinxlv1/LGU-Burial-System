import React, { useState } from 'react'
import { motion } from 'framer-motion'
import { X, LayoutGrid, Palette } from 'lucide-react'
import { useMapStore, PendingGrid, GridSection } from '../../store/useMapStore'

interface Props {
  pending: PendingGrid
}

const PRESET_COLORS = [
  '#ef4444', // red
  '#f97316', // orange
  '#eab308', // yellow
  '#22c55e', // green
  '#3b82f6', // blue
  '#8b5cf6', // purple
  '#ec4899', // pink
  '#14b8a6', // teal
]

export const GridConfigModal: React.FC<Props> = ({ pending }) => {
  const { addGridSection, setPendingGrid, setEditMode } = useMapStore()

  const [name,       setName]       = useState('Niche Block A')
  const [rows,       setRows]       = useState(5)
  const [cols,       setCols]       = useState(4)
  const [color,      setColor]      = useState('#ef4444')
  const [saving,     setSaving]     = useState(false)

  const distLabel = pending.distanceM < 1000
    ? `${Math.round(pending.distanceM)} m`
    : `${(pending.distanceM / 1000).toFixed(2)} km`

  const handleConfirm = async () => {
    setSaving(true)
    const mid = {
      lat: (pending.lineStart.lat + pending.lineEnd.lat) / 2,
      lng: (pending.lineStart.lng + pending.lineEnd.lng) / 2,
    }
    const section: GridSection = {
      id:          'temp-' + Date.now(),
      name,
      rows,
      cols,
      labelFormat: 'R{row}-C{col}',
      color,
      cells:       {},
      lineStart:   pending.lineStart,
      lineEnd:     pending.lineEnd,
      position:    mid,
      rotation:    pending.bearing,
      widthScale:  pending.distanceM / 20,
    }
    await addGridSection(section)
    setSaving(false)
    setPendingGrid(null)
    setEditMode('view')
  }

  const handleCancel = () => {
    setPendingGrid(null)
    setEditMode('view')
  }

  // Outer wrapper — plain div owns the fixed centering
  const wrapperStyle: React.CSSProperties = {
    position: 'fixed',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    zIndex: 9000,
    width: '340px',
    maxHeight: '92vh',
    overflowY: 'auto',
  }

  // Inner panel — motion.div owns only the animation
  const panelStyle: React.CSSProperties = {
    background: 'rgba(10, 16, 33, 0.98)',
    backdropFilter: 'blur(40px)',
    border: '1px solid rgba(255,255,255,0.12)',
    borderRadius: '24px',
    padding: '22px',
    boxShadow: '0 60px 120px rgba(0,0,0,0.9)',
  }

  const inputStyle: React.CSSProperties = {
    width: '100%',
    background: 'rgba(255,255,255,0.05)',
    border: '1px solid rgba(255,255,255,0.1)',
    borderRadius: '12px',
    padding: '12px 14px',
    color: 'white',
    fontSize: '14px',
    outline: 'none',
    boxSizing: 'border-box',
  }

  const labelStyle: React.CSSProperties = {
    fontSize: '10px',
    color: '#64748b',
    fontWeight: 800,
    textTransform: 'uppercase',
    letterSpacing: '0.08em',
    display: 'block',
    marginBottom: '8px',
  }

  return (
    <>
      {/* Backdrop */}
      <div
        onClick={handleCancel}
        style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: 8999, backdropFilter: 'blur(4px)' }}
      />
      <div style={wrapperStyle}>
        <motion.div
          initial={{ scale: 0.92, opacity: 0, y: 20 }}
          animate={{ scale: 1, opacity: 1, y: 0 }}
          exit={{ scale: 0.92, opacity: 0, y: 20 }}
          style={panelStyle}
        >
        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '16px' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
            <div style={{ width: '32px', height: '32px', background: `${color}20`, borderRadius: '9px', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <LayoutGrid size={16} color={color} />
            </div>
            <div>
              <h3 style={{ color: 'white', fontWeight: 900, fontSize: '15px', margin: 0 }}>Configure Niche Block</h3>
              <p style={{ color: '#64748b', fontSize: '10px', margin: 0 }}>{distLabel} · {Math.round(pending.bearing)}° bearing</p>
            </div>
          </div>
          <button onClick={handleCancel} style={{ background: 'none', border: 'none', color: '#64748b', cursor: 'pointer', padding: '4px' }}>
            <X size={18} />
          </button>
        </div>

        <div style={{ height: '1px', background: 'rgba(255,255,255,0.06)', marginBottom: '14px' }} />

        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
          {/* Block Name */}
          <div>
            <label style={labelStyle}>Block Name</label>
            <input
              style={inputStyle}
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder="e.g. Niche Block A"
            />
          </div>

          {/* Rows & Cols */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
            <div>
              <label style={labelStyle}>Rows</label>
              <input
                type="number"
                min={1}
                max={50}
                style={inputStyle}
                value={rows}
                onChange={(e) => setRows(Math.max(1, parseInt(e.target.value) || 1))}
              />
            </div>
            <div>
              <label style={labelStyle}>Columns</label>
              <input
                type="number"
                min={1}
                max={50}
                style={inputStyle}
                value={cols}
                onChange={(e) => setCols(Math.max(1, parseInt(e.target.value) || 1))}
              />
            </div>
          </div>

          {/* Preview count */}
          <div style={{ background: 'rgba(255,255,255,0.03)', borderRadius: '12px', padding: '12px 14px', display: 'flex', justifyContent: 'space-between' }}>
            <span style={{ color: '#64748b', fontSize: '12px' }}>Total niches</span>
            <span style={{ color: 'white', fontSize: '13px', fontWeight: 900 }}>{rows * cols}</span>
          </div>

          {/* Color Picker */}
          <div>
            <label style={{ ...labelStyle, display: 'flex', alignItems: 'center', gap: '6px' }}>
              <Palette size={11} /> Line Color
            </label>
            <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
              {PRESET_COLORS.map(c => (
                <button
                  key={c}
                  onClick={() => setColor(c)}
                  style={{
                    width: '32px', height: '32px', borderRadius: '8px',
                    background: c, border: color === c ? '3px solid white' : '2px solid transparent',
                    cursor: 'pointer', transition: 'all 0.15s',
                    boxShadow: color === c ? `0 0 12px ${c}80` : 'none',
                  }}
                />
              ))}
            </div>
          </div>
        </div>

        <div style={{ height: '1px', background: 'rgba(255,255,255,0.06)', margin: '14px 0' }} />

        {/* Actions */}
        <div style={{ display: 'flex', gap: '12px' }}>
          <button
            onClick={handleCancel}
            style={{
              flex: 1, padding: '14px', borderRadius: '14px',
              background: 'rgba(255,255,255,0.06)', color: 'rgba(255,255,255,0.6)',
              border: '1px solid rgba(255,255,255,0.08)', cursor: 'pointer',
              fontWeight: 700, fontSize: '13px',
            }}
          >
            Cancel
          </button>
          <button
            onClick={handleConfirm}
            disabled={saving}
            style={{
              flex: 2, padding: '14px', borderRadius: '14px',
              background: saving ? '#1e3a5f' : color,
              color: 'white', border: 'none', cursor: saving ? 'wait' : 'pointer',
              fontWeight: 900, fontSize: '13px',
              boxShadow: saving ? 'none' : `0 8px 24px ${color}60`,
              transition: 'all 0.2s',
            }}
          >
            {saving ? 'Saving…' : `Place ${rows * cols} Niches`}
          </button>
        </div>
      </motion.div>
      </div>
    </>
  )
}
