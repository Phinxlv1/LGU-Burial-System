import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { NuqsAdapter } from 'nuqs/adapters/react'
import App from './App'
import SuperApp from './SuperApp'

const container = document.getElementById('geomap-root')
const analyticsContainer = document.getElementById('geomap-analytics-root')

if (container) {
  createRoot(container).render(
    <StrictMode>
      <NuqsAdapter>
        <App />
      </NuqsAdapter>
    </StrictMode>
  )
}

if (analyticsContainer) {
    createRoot(analyticsContainer).render(
      <StrictMode>
        <NuqsAdapter>
          <SuperApp />
        </NuqsAdapter>
      </StrictMode>
    )
}
