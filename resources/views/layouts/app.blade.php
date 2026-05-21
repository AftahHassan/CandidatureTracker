<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CandidatureTracker' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="app-layout">

        {{-- ─── Sidebar ───────────────────────────────────────────── --}}
        <aside class="sidebar">

            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">💼</div>
                <div>
                    <div class="sidebar-logo-text">CandidatureTracker</div>
                    <span class="sidebar-logo-sub">Management Platform</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}"
                class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('candidatures.index') }}"
                class="nav-item {{ request()->routeIs('candidatures.*') && !request()->routeIs('candidatures.archives') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/>
                    </svg>
                    Mes Candidatures
                </a>

                <a href="{{ route('entretiens.index') }}"
                    class="nav-item {{ request()->routeIs('entretiens.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Entretiens
                </a>

                <a href="{{ route('candidatures.archives') }}"
                class="nav-item {{ request()->routeIs('candidatures.archives') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 8v13H3V8M1 3h22v5H1zM10 12h4"/>
                    </svg>
                    Archives
                </a>
            </nav>

            <div class="sidebar-bottom">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item" style="width:100%;background:none;border:none;text-align:left;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;opacity:0.7">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>

        </aside>

        {{-- ─── Main wrapper ───────────────────────────────────────── --}}
        <div class="main-wrapper">

            {{-- Topbar --}}
            <div class="topbar-actions">

            {{-- Notifications --}}
            <div style="position:relative;">
                <button class="topbar-btn" id="notif-btn" onclick="toggleNotif()">
                    🔔
                    <span id="notif-badge" style="
                        position:absolute; top:-4px; right:-4px;
                        width:16px; height:16px;
                        background:var(--danger); color:#fff;
                        border-radius:50%; font-size:9px;
                        display:flex; align-items:center; justify-content:center;
                        font-weight:600;">3</span>
                </button>
                <div id="notif-panel" style="
                    display:none; position:absolute; right:0; top:44px;
                    width:280px; background:var(--bg-white);
                    border:1px solid var(--border); border-radius:var(--radius-md);
                    box-shadow:var(--shadow-lg); z-index:200; overflow:hidden;">
                    <div style="padding:14px 16px; border-bottom:1px solid var(--border); font-weight:600; font-size:14px;">
                        Notifications
                    </div>
                    <div style="padding:12px 16px; font-size:13px; color:var(--text-secondary); text-align:center; padding:24px;">
                        Aucune notification pour le moment.
                    </div>
                </div>
            </div>

            {{-- Dark Mode --}}
            <button class="topbar-btn" id="theme-btn" onclick="toggleTheme()" title="Changer le thème">
                <span id="theme-icon">🌙</span>
            </button>

            {{-- Avatar --}}
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>

        </div>

            {{-- Flash messages --}}
            <div style="padding: 0 28px; padding-top: 20px;">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">❌ {{ session('error') }}</div>
                @endif
            </div>

            {{-- Page slot --}}
            <main class="page-content">
                {{ $slot }}
            </main>

        </div>
    </div>
    <script>
            // ── Dark Mode ──────────────────────────────────────────────
            const root = document.documentElement;

            function applyTheme(dark) {
                if (dark) {
                    root.style.setProperty('--bg',           '#0F1117');
                    root.style.setProperty('--bg-white',     '#1A1D27');
                    root.style.setProperty('--text-primary',  '#F1F5F9');
                    root.style.setProperty('--text-secondary','#94A3B8');
                    root.style.setProperty('--text-muted',    '#64748B');
                    root.style.setProperty('--border',        '#2D3148');
                    document.getElementById('theme-icon').textContent = '☀️';
                } else {
                    root.style.setProperty('--bg',           '#F8F9FC');
                    root.style.setProperty('--bg-white',     '#FFFFFF');
                    root.style.setProperty('--text-primary',  '#111827');
                    root.style.setProperty('--text-secondary','#6B7280');
                    root.style.setProperty('--text-muted',    '#9CA3AF');
                    root.style.setProperty('--border',        '#E5E7EB');
                    document.getElementById('theme-icon').textContent = '🌙';
                }
            }

            function toggleTheme() {
                const isDark = localStorage.getItem('theme') === 'dark';
                localStorage.setItem('theme', isDark ? 'light' : 'dark');
                applyTheme(!isDark);
            }

            // Appliquer au chargement
            applyTheme(localStorage.getItem('theme') === 'dark');

            // ── Notifications ──────────────────────────────────────────
            function toggleNotif() {
                const panel = document.getElementById('notif-panel');
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            }

            // Fermer en cliquant ailleurs
            document.addEventListener('click', function(e) {
                const btn   = document.getElementById('notif-btn');
                const panel = document.getElementById('notif-panel');
                if (panel && !panel.contains(e.target) && !btn.contains(e.target)) {
                    panel.style.display = 'none';
                }
            });
    </script>

</body>
</html>