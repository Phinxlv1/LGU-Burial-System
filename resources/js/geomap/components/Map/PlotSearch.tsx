import React, { useState, useEffect, useRef } from 'react'
import { Search, MapPin, X, User } from 'lucide-react'

interface SearchResult {
  id: number
  plot_code: string
  deceased_name: string
  permit_status: string
  latitude: number
  longitude: number
}

interface PlotSearchProps {
  onSelect: (result: SearchResult) => void
  pointsData: any
}

export const PlotSearch: React.FC<PlotSearchProps> = ({ onSelect, pointsData }) => {
  const [query, setQuery] = useState('')
  const [results, setResults] = useState<SearchResult[]>([])
  const [isOpen, setIsOpen] = useState(false)
  const inputRef = useRef<HTMLInputElement>(null)

  useEffect(() => {
    if (query.trim().length < 1) {
      setResults([])
      return
    }

    const q = query.trim()
    const timer = setTimeout(async () => {
      try {
        const res = await fetch(`/cemetery/search-permits?q=${encodeURIComponent(q)}`)
        const data = await res.json()
        setResults(data)
      } catch (err) {
        console.error('Failed to search permits', err)
      }
    }, 300)

    return () => clearTimeout(timer)
  }, [query])

  useEffect(() => {
    // Auto-focus on mount
    inputRef.current?.focus();

    const handleKeyDown = (e: KeyboardEvent) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'f') {
        const activeEl = document.activeElement;
        const isInput = activeEl instanceof HTMLInputElement || activeEl instanceof HTMLTextAreaElement;
        
        // Only override if not already in an input
        if (!isInput && !e.shiftKey && !e.altKey) {
          e.preventDefault();
          inputRef.current?.focus();
          inputRef.current?.select();
        }
      }
    }
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, []);

  const handleSelect = (res: SearchResult) => {
    onSelect(res)
    setQuery('')
    setIsOpen(false)
  }

  return (
    <div style={{ position: 'relative', width: '300px' }}>
      <div style={{
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        background: 'rgba(15, 23, 42, 0.8)',
        backdropFilter: 'blur(12px)',
        border: '1px solid rgba(255, 255, 255, 0.1)',
        padding: '10px 16px',
        borderRadius: '14px',
        color: 'white'
      }}>
        <Search size={16} color="#3b82f6" />
        <input
          ref={inputRef}
          type="text"
          value={query}
          onChange={(e) => {
            setQuery(e.target.value)
            setIsOpen(true)
          }}
          onFocus={() => setIsOpen(true)}
          placeholder="Search by Deceased Name..."
          style={{
            background: 'none',
            border: 'none',
            outline: 'none',
            color: 'white',
            fontSize: '13px',
            width: '100%',
            fontWeight: 500
          }}
        />
        {query && <X size={14} style={{ cursor: 'pointer', opacity: 0.5 }} onClick={() => setQuery('')} />}
      </div>

      {isOpen && results.length > 0 && (
        <div style={{
          position: 'absolute',
          top: 'calc(100% + 10px)',
          left: 0,
          right: 0,
          background: 'rgba(15, 23, 42, 0.95)',
          backdropFilter: 'blur(20px)',
          border: '1px solid rgba(255, 255, 255, 0.1)',
          borderRadius: '16px',
          overflow: 'hidden',
          zIndex: 2000,
          boxShadow: '0 20px 40px rgba(0,0,0,0.4)'
        }}>
          {results.map((res) => (
            <div
              key={res.id}
              onClick={() => handleSelect(res)}
              style={{
                padding: '12px 16px',
                borderBottom: '1px solid rgba(255, 255, 255, 0.05)',
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                gap: '12px',
                transition: 'background 0.2s'
              }}
              onMouseEnter={(e) => (e.currentTarget.style.background = 'rgba(59, 130, 246, 0.1)')}
              onMouseLeave={(e) => (e.currentTarget.style.background = 'none')}
            >
              <div style={{
                width: '32px',
                height: '32px',
                borderRadius: '8px',
                background: 'rgba(59, 130, 246, 0.2)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center'
              }}>
                {res.deceased_name ? <User size={14} color="#3b82f6" /> : <MapPin size={14} color="#3b82f6" />}
              </div>
              <div>
                <div style={{ fontSize: '13px', fontWeight: 600, color: 'white' }}>
                  {res.deceased_name || 'Empty Plot'}
                </div>
                <div style={{ fontSize: '10px', color: '#64748b', fontFamily: 'monospace' }}>
                  {res.plot_code} • {res.permit_status.toUpperCase()}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
