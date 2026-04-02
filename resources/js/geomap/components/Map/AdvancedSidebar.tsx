import React, { useState } from 'react'
import { Activity, Shield, AlertTriangle, Skull, Map as MapIcon, XCircle, ChevronRight, ChevronLeft, BarChart3, TrendingUp } from 'lucide-react'
import { motion, AnimatePresence } from 'framer-motion'

interface AdvancedSidebarProps {
  stats: {
    total: number
    active: number
    expiring: number
    expired: number
    occupied: number
  }
}

export const AdvancedSidebar: React.FC<AdvancedSidebarProps> = ({ stats }) => {
  const [isOpen, setIsOpen] = useState(false)

  const StatItem = ({ label, value, icon: Icon, color }: { label: string, value: number, icon: any, color: string }) => (
    <div style={{
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
      padding: '12px 0',
      borderBottom: '1px solid rgba(255, 255, 255, 0.05)'
    }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
        <div style={{ width: '28px', height: '28px', background: `${color}15`, borderRadius: '8px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <Icon size={14} color={color} />
        </div>
        <span style={{ fontSize: '12px', color: '#94a3b8', fontWeight: 600 }}>{label}</span>
      </div>
      <span style={{ fontSize: '15px', fontWeight: 900, color: 'white', letterSpacing: '-0.02em' }}>{value}</span>
    </div>
  )

  return (
    <div style={{
      position: 'absolute',
      right: 0,
      top: '100px',
      bottom: '12px',
      zIndex: 1000,
      display: 'flex',
      alignItems: 'flex-start'
    }}>
      <button 
        onClick={() => setIsOpen(!isOpen)}
        style={{
          background: 'rgba(15, 23, 42, 0.95)',
          backdropFilter: 'blur(30px)',
          border: '1px solid rgba(255, 255, 255, 0.14)',
          borderRadius: '16px 0 0 16px',
          padding: '40px 10px',
          color: 'white',
          cursor: 'pointer',
          borderRight: 'none',
          boxShadow: '-10px 0 30px rgba(0,0,0,0.3)',
          marginTop: '20px'
        }}
      >
        {isOpen ? <ChevronRight size={20} /> : <ChevronLeft size={20} />}
      </button>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ width: 0, opacity: 0, x: 20 }}
            animate={{ width: 280, opacity: 1, x: 0 }}
            exit={{ width: 0, opacity: 0, x: 20 }}
            style={{
              height: '100%',
              background: 'rgba(15, 23, 42, 0.95)',
              backdropFilter: 'blur(40px)',
              border: '1px solid rgba(255, 255, 255, 0.14)',
              borderRight: 'none',
              borderRadius: '24px 0 0 24px',
              padding: '28px',
              boxShadow: '0 40px 100px rgba(0,0,0,0.6)',
              overflow: 'hidden',
              display: 'flex',
              flexDirection: 'column'
            }}
          >
            <div style={{ marginBottom: '24px', display: 'flex', alignItems: 'center', gap: '10px' }}>
              <div style={{ width: '32px', height: '32px', background: '#3b82f620', borderRadius: '10px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <TrendingUp size={18} color="#3b82f6" />
              </div>
              <div>
                  <span style={{ fontSize: '10px', fontWeight: 900, color: '#3b82f6', textTransform: 'uppercase', letterSpacing: '0.12em', display: 'block' }}>Real-time</span>
                  <span style={{ fontSize: '14px', fontWeight: 900, color: 'white', letterSpacing: '-0.02em' }}>Spatial Dashboard</span>
              </div>
            </div>

            <div style={{ display: 'flex', flexDirection: 'column', flex: 1 }}>
                <StatItem label="Map Registry" value={stats.total} icon={MapIcon} color="#3b82f6" />
                <StatItem label="Occupied Units" value={stats.occupied} icon={Skull} color="#8b5cf6" />
                <StatItem label="Active Permits" value={stats.active} icon={Shield} color="#10b981" />
                <StatItem label="Expiring Soon" value={stats.expiring} icon={AlertTriangle} color="#f59e0b" />
                <StatItem label="Terminated" value={stats.expired} icon={XCircle} color="#ef4444" />
            </div>

            <div style={{ marginTop: 'auto', padding: '16px', background: 'rgba(59, 130, 246, 0.05)', borderRadius: '16px', border: '1px solid rgba(59, 130, 246, 0.1)' }}>
                <p style={{ fontSize: '10px', color: '#94a3b8', margin: 0, lineHeight: 1.6, fontWeight: 500 }}>
                    Analytical data is dynamically updated based on the current map viewport.
                </p>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  )
}

