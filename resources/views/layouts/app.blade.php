<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Padel Booking') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="db-body">

    {{-- Sidebar --}}
    <aside class="db-sidebar" id="db-sidebar">
        <div class="db-sidebar-brand">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-color);"><circle cx="12" cy="12" r="10"/><path d="M6 12a6 6 0 0 1 12 0"/></svg>
            <span class="db-sidebar-name">PadelCourt</span>
        </div>

        <nav class="db-nav">
            <div class="db-nav-label">Menu</div>
            <a class="db-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a class="db-nav-item" href="{{ route('home') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Beranda
            </a>
        </nav>

        <div class="db-sidebar-user">
            @auth
            <div class="db-user-info">
                <div class="db-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="db-user-meta">
                    <div class="db-user-name">{{ auth()->user()->name }}</div>
                    <div class="db-user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>

            @endauth
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="db-main">
        <header class="db-topbar">
            <div class="db-topbar-left">
                <button class="db-menu-toggle" id="db-menu-toggle" aria-label="Toggle menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="db-topbar-title">{{ $title ?? config('app.name', 'Dashboard') }}</div>
            </div>
            <div class="db-topbar-right">
                @auth
                <div class="db-topbar-user">
                    <div class="db-user-avatar db-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="db-topbar-username">{{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}" style="margin-left: 12px;">
                        @csrf
                        <button class="db-logout-btn" type="submit" style="padding: 4px 10px; background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <main class="db-content">
            @yield('content')
        </main>
    </div>

    <script>
        const toggle = document.getElementById('db-menu-toggle');
        const sidebar = document.getElementById('db-sidebar');
        toggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('db-sidebar--open');
        });
    </script>
</body>
</html>
