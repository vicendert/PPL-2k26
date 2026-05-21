<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Booking lapangan padel lebih cepat, rapi, dan transparan. Pilih jadwal, lakukan booking, pantau status langsung dari dashboard.">
    <title>{{ config('app.name', 'Padel Booking') }} — Lapangan Padel Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-body">

    {{-- Animated background blobs --}}
    <div class="landing-blobs" aria-hidden="true">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    {{-- ── HERO SPLIT ── --}}
    <div class="landing-split">

        {{-- LEFT 60%: content --}}
        <div class="landing-left">

            {{-- Nav --}}
            <nav class="lp-nav">
                <div class="lp-brand">
                    <span class="lp-brand-icon">🎾</span>
                    <span class="lp-brand-text">PadelCourt</span>
                </div>
                <div class="lp-nav-actions">
                    @auth
                        <a class="lp-btn lp-btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
                        <form method="post" action="{{ route('logout') }}" style="display:inline">
                            @csrf
                            <button class="lp-btn lp-btn-outline" type="submit">Logout</button>
                        </form>
                    @else
                        <a class="lp-btn lp-btn-ghost" href="{{ route('login') }}" id="nav-login-btn">Masuk</a>
                        <a class="lp-btn lp-btn-primary" href="{{ route('register') }}" id="nav-register-btn">Daftar Gratis</a>
                    @endauth
                </div>
            </nav>

            {{-- Hero Content --}}
            <div class="lp-hero-content">
                <div class="lp-eyebrow">
                    <span class="lp-dot"></span>
                    Platform Booking Padel #1
                </div>

                <h1 class="lp-hero-title">
                    Booking Lapangan Padel
                    <span class="lp-gradient-text">Lebih Cepat &amp; Mudah</span>
                </h1>

                <p class="lp-hero-desc">
                    Pilih jadwal kosong, lakukan booking, dan pantau status secara real-time. Sistem terintegrasi untuk user, kasir, dan owner dalam satu platform.
                </p>

                {{-- CTA Buttons --}}
                <div class="lp-cta-group">
                    <a class="lp-btn lp-btn-primary lp-btn-lg" href="{{ route('register') }}" id="cta-mulai-btn">
                        <span>Mulai Booking</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a class="lp-btn lp-btn-glass lp-btn-lg" href="{{ route('login') }}" id="cta-login-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                        <span>Masuk Dashboard</span>
                    </a>
                </div>

                {{-- Stats --}}
                <div class="lp-stats">
                    <div class="lp-stat">
                        <span class="lp-stat-num">3+</span>
                        <span class="lp-stat-label">Lapangan</span>
                    </div>
                    <div class="lp-stat-divider"></div>
                    <div class="lp-stat">
                        <span class="lp-stat-num">24/7</span>
                        <span class="lp-stat-label">Booking Online</span>
                    </div>
                </div>
            </div>

            {{-- Google Maps Embed --}}
            <div style="margin-top: 40px; border-radius: 18px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                <iframe width="100%" height="220" style="border:0; display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=Lapangan+Sempur+Bogor&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>



        </div>

        {{-- RIGHT 40%: visual liquid glass panel --}}
        <div class="landing-right" id="landing-right-panel">
            <div class="lr-bg-image"></div>
            <div class="lr-overlay"></div>

            {{-- Floating glass cards --}}
            <div class="lr-glass-card lr-card-top" id="glass-card-top">
                <div class="lr-card-icon">⚡</div>
                <div>
                    <div class="lr-card-title">Konfirmasi Instan</div>
                    <div class="lr-card-sub">Booking dikonfirmasi real-time</div>
                </div>
            </div>

            <div class="lr-glass-card lr-card-mid" id="glass-card-mid">
                <div class="lr-mini-schedule">
                    <div class="lr-schedule-title">Jadwal Hari Ini</div>
                    <div class="lr-schedule-row">
                        <span class="lr-time">08:00</span>
                        <span class="lr-slot lr-slot-taken">Terisi</span>
                    </div>
                    <div class="lr-schedule-row">
                        <span class="lr-time">10:00</span>
                        <span class="lr-slot lr-slot-free">Tersedia</span>
                    </div>
                    <div class="lr-schedule-row">
                        <span class="lr-time">12:00</span>
                        <span class="lr-slot lr-slot-free">Tersedia</span>
                    </div>
                    <div class="lr-schedule-row">
                        <span class="lr-time">14:00</span>
                        <span class="lr-slot lr-slot-taken">Terisi</span>
                    </div>
                </div>
            </div>

            <div class="lr-glass-card lr-card-bottom" id="glass-card-bottom">
                <div class="lr-card-icon">✅</div>
                <div>
                    <div class="lr-card-title">Booking Berhasil!</div>
                    <div class="lr-card-sub">Lapangan A · Sabtu 10:00</div>
                </div>
            </div>

            {{-- Decorative orbs --}}
            <div class="lr-orb lr-orb-1" aria-hidden="true"></div>
            <div class="lr-orb lr-orb-2" aria-hidden="true"></div>
        </div>

    </div>{{-- /.landing-split --}}

</body>
</html>
