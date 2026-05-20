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

            <a href="#"
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
        <header class="topbar">
            <div class="topbar-search">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher des candidatures...">
            </div>
            <div class="topbar-actions">
                <button class="topbar-btn">🔔</button>
                <button class="topbar-btn">🌙</button>
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            </div>
        </header>

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

</body>
</html>