<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Manual - LGU Burial System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600|dm-mono:400,500" rel="stylesheet" />

    @include('admin.partials.design-system')

    <style>
        html {
            scroll-behavior: smooth;
        }
        .manual-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: calc(100vh - 56px);
        }

        .manual-sidebar {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .manual-content {
            padding: 2.5rem;
            max-width: 900px;
        }

        .doc-section {
            margin-bottom: 4rem;
            scroll-margin-top: 80px;
        }

        .doc-section h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .doc-section h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 2rem 0 1rem;
            color: var(--navy);
        }

        .doc-section p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-2);
            margin-bottom: 1rem;
        }

        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: var(--accent-bg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            flex-shrink: 0;
        }

        .nav-link {
            display: block;
            padding: 0.6rem 1rem;
            color: var(--text-2);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: var(--surface-2);
            color: var(--navy);
        }

        .nav-link.active {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .nav-group-title {
            font-size: 10px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 1.5rem 0 0.5rem 1rem;
        }

        kbd {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 6px;
            font-family: var(--mono);
            font-size: 11px;
            color: var(--navy);
            box-shadow: 0 1px 0 rgba(0,0,0,0.1);
        }

        .status-badge-demo {
            display: inline-flex;
            margin-right: 8px;
        }
    </style>
</head>
<body class="dark:bg-[#0f1117]">
    @include('superadmin.partials.sidebar')

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <div class="topbar-title">Knowledge Center</div>
                <div class="topbar-date">System Documentation & User Manual</div>
            </div>
            <div class="topbar-right">
                <a href="{{ auth()->user()->role === 'super_admin' ? route('superadmin.dashboard') : route('dashboard') }}" class="btn-xs">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </header>

        <div class="manual-layout">
            <aside class="manual-sidebar">
                <div class="nav-group-title">GETTING STARTED</div>
                <a href="#overview" class="nav-link">System Overview</a>
                <a href="#navigation" class="nav-link">Navigation & Shortcuts</a>
                
                <div class="nav-group-title">CORE MODULES</div>
                <a href="#permits" class="nav-link">Burial Permits</a>
                <a href="#cemetery-map" class="nav-link">Cemetery GIS Map</a>
                
                <div class="nav-group-title">ADVANCED TOOLS</div>
                <a href="#reports" class="nav-link">Reports & Exports</a>
                @if(auth()->user()->role === 'super_admin')
                    <a href="#data-quality" class="nav-link">Data Quality Scanner</a>
                    <a href="#settings" class="nav-link">System Settings</a>
                @endif
            </aside>

            <main class="manual-content">
                <section id="overview" class="doc-section">
                    <h1>System Overview</h1>
                    <p>Welcome to the LGU Burial System. This platform digitizes the management of burial permits, deceased records, and cemetery plot allocations using a modern GIS-based approach.</p>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div>
                            <h2>Real-time GIS Mapping</h2>
                            <p>Manage and visualize cemetery occupancy through an interactive grid. Each niche can be assigned, searched, and monitored for renewal status.</p>
                        </div>
                    </div>
                </section>

                <section id="navigation" class="doc-section">
                    <h1>Navigation & Shortcuts</h1>
                    <p>The system is designed for efficiency. Use the sidebar to navigate between modules, or use global keyboard shortcuts to speed up your workflow.</p>
                    
                    <h2>Global Shortcuts</h2>
                    <table style="max-width: 500px;">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Shortcut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Quick Search (Everywhere)</td>
                                <td><kbd>Ctrl</kbd> + <kbd>F</kbd></td>
                            </tr>
                            <tr>
                                <td>Toggle Dark Mode</td>
                                <td><kbd>Alt</kbd> + <kbd>D</kbd></td>
                            </tr>
                            <tr>
                                <td>Toggle Sidebar</td>
                                <td><kbd>Alt</kbd> + <kbd>B</kbd></td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section id="permits" class="doc-section">
                    <h1>Burial Permits</h1>
                    <p>Burial permits are the core of the system. They track the legal and physical status of every deceased individual in the LGU Carmen cemetery.</p>
                    
                    <h2>Permit Lifecycle</h2>
                    <p>Permits automatically transition through three states based on their expiry date:</p>
                    <div style="margin-left: 1rem;">
                        <p><span class="badge badge-green status-badge-demo"><span class="badge-dot"></span>Active</span> - Valid permit. No action needed.</p>
                        <p><span class="badge badge-yellow status-badge-demo"><span class="badge-dot"></span>Expiring</span> - Requires renewal within the specified warning threshold.</p>
                        <p><span class="badge badge-red status-badge-demo"><span class="badge-dot"></span>Expired</span> - Permit is no longer valid. Immediate renewal or bone transfer is required.</p>
                    </div>
                </section>

                <section id="cemetery-map" class="doc-section">
                    <h1>Cemetery GIS Map</h1>
                    <p>The interactive map allows you to visualize the physical layout of the cemetery including Apartment-style niches and vertical structures.</p>
                    <h2>Assigning a Niche</h2>
                    <p>To assign a niche, navigate to the map, find an empty slot (greyed out), and click "Assign". Follow the prompts to link a Deceased Record or Burial Permit.</p>
                </section>

                <section id="reports" class="doc-section">
                    <h1>Reports & Exports</h1>
                    <p>The system generates premium, municipal-branded reports in both XLSX and PDF formats.</p>
                    <h2>Financial Reports</h2>
                    <p>Access consolidated revenue reports based on burial and renewal fees defined in your system settings.</p>
                </section>

                @if(auth()->user()->role === 'super_admin')
                <section id="data-quality" class="doc-section">
                    <h1>Data Quality Scanner</h1>
                    <p>To ensure database integrity, the system runs an 11-point scan identifying duplicates, missing links, and logical errors in the deceased records.</p>
                    <p>Check this module regularly to fix "Swapped Names" or "Duplicate Permits" flagged by the AI engine.</p>
                </section>
                @endif
            </main>
        </div>
    </div>

    <script>
        // Enhanced scrollspy for the sidebar
        const sections = document.querySelectorAll('.doc-section');
        const navLinks = document.querySelectorAll('.nav-link');

        function updateActiveLink() {
            let current = '';
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
            
            // Check if we're at the bottom of the page
            if (window.innerHeight + scrollPos >= document.documentElement.scrollHeight - 50) {
                current = sections[sections.length - 1].getAttribute('id');
            } else {
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    // Adjust trigger point for better feel
                    if (scrollPos >= sectionTop - 120) {
                        current = section.getAttribute('id');
                    }
                });
            }

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        }

        // Optimized scroll listener
        let isScrolling;
        window.addEventListener('scroll', () => {
            window.cancelAnimationFrame(isScrolling);
            isScrolling = window.requestAnimationFrame(updateActiveLink);
        }, false);

        // Initial call to set active state
        document.addEventListener('DOMContentLoaded', updateActiveLink);
        
        // Handle immediate highlight on click
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    </script>
</body>
</html>
