@extends('layouts.app')

@php
    $title = 'Catatan Pesanan Walk-in - PadelCourt';
@endphp

@section('content')
    {{-- Page Header --}}
    <div class="db-page-header">
        <div>
            <div class="db-page-eyebrow">Admin Kasir</div>
            <h1 class="db-page-title">Catatan Pesanan Walk-in</h1>
            <p class="db-page-desc">Pencatatan booking lapangan langsung di tempat untuk pelanggan walk-in dan riwayat transaksi tunai.</p>
        </div>
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ route('dashboard') }}" class="db-btn" style="background:var(--panel-bg); border:1px solid var(--border-color); color:var(--text-color); display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Ke Dashboard
            </a>
            <div class="db-page-badge db-badge-admin">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Admin Kasir
            </div>
        </div>
    </div>

    @include('partials.flash')

    {{-- ════════ FORM PEMESANAN KASIR (WALK-IN) ════════ --}}
    <div class="db-card" id="kasir-order-card" style="margin-bottom: 24px;">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Form Pesanan Walk-in Baru
            </div>
            <span class="db-page-badge db-badge-admin" style="background:rgba(16,185,129,.15);color:#10b981;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Langsung Dikonfirmasi
            </span>
        </div>

        {{-- Info banner --}}
        <div style="margin: 0 20px 4px; padding: 10px 14px; background: rgba(59,130,246,.08); border-left: 3px solid #3b82f6; border-radius: 4px; font-size: 13px; color: var(--db-text);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:6px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Booking yang dibuat kasir <strong>langsung berstatus Confirmed</strong>. Cocok untuk pelanggan yang datang langsung ke tempat dan membayar tunai.
        </div>

        <form method="post" action="{{ route('admin.bookings.store') }}" class="db-booking-form" id="kasir-booking-form">
            @csrf
            <div class="db-form-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; padding: 16px 20px 4px;">

                {{-- Nama Pelanggan --}}
                <div class="db-form-group">
                    <label class="db-label" for="kasir_customer_name">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Nama Pelanggan <span style="color:#ef4444">*</span>
                    </label>
                    <input type="text" class="db-input" id="kasir_customer_name" name="customer_name"
                        placeholder="Masukkan nama pelanggan…"
                        value="{{ old('customer_name') }}"
                        required autocomplete="off">
                    <span style="font-size:11px; color: var(--db-muted); margin-top:3px; display:block;">
                        Jika nama terdaftar di sistem, booking akan dikaitkan ke akun tersebut.
                    </span>
                </div>

                {{-- Lapangan --}}
                <div class="db-form-group">
                    <label class="db-label" for="kasir_court_id">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Lapangan <span style="color:#ef4444">*</span>
                    </label>
                    <select class="db-input" id="kasir_court_id" name="court_id" required>
                        <option value="">Pilih lapangan…</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}" @selected(old('court_id') == $court->id)>
                                {{ $court->name }} — {{ $court->location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div class="db-form-group">
                    <label class="db-label" for="kasir_date">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Tanggal Booking <span style="color:#ef4444">*</span>
                    </label>
                    <input type="date" class="db-input" id="kasir_date" name="date"
                        value="{{ old('date', now()->toDateString()) }}"
                        min="{{ now()->toDateString() }}" required>
                </div>

                {{-- Jam Mulai --}}
                <div class="db-form-group">
                    <label class="db-label" for="kasir_start_time">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Jam Mulai <span style="color:#ef4444">*</span>
                    </label>
                    <select class="db-input" id="kasir_start_time" name="start_time" required>
                        @php $oldTime = old('start_time', '08:00'); @endphp
                        @for ($h = 0; $h <= 23; $h++)
                            @php $val = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                            <option value="{{ $val }}" @selected($oldTime === $val)>{{ $val }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Durasi --}}
                <div class="db-form-group">
                    <label class="db-label" for="kasir_duration">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Durasi <span style="color:#ef4444">*</span>
                    </label>
                    <select class="db-input" id="kasir_duration" name="duration_hours" required>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" @selected(old('duration_hours', 1) == $i)>
                                {{ $i }} jam — Rp {{ number_format($i * 300000, 0, ',', '.') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Ringkasan real-time --}}
            <div class="db-price-summary" id="kasir-price-summary" style="margin: 8px 20px 0;">
                <div class="db-price-summary-row">
                    <span>Lapangan</span>
                    <span id="kps-court">—</span>
                </div>
                <div class="db-price-summary-row">
                    <span>Tanggal</span>
                    <span id="kps-date">—</span>
                </div>
                <div class="db-price-summary-row">
                    <span>Jam</span>
                    <span id="kps-time">—</span>
                </div>
                <div class="db-price-summary-row db-price-summary-total">
                    <span>Total Bayar</span>
                    <span id="kps-total">Rp 300.000</span>
                </div>
            </div>

            <div style="padding: 16px 20px 20px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                <button class="db-btn db-btn-primary" type="submit" id="kasir-submit-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Konfirmasi &amp; Simpan Booking
                </button>
                <button class="db-btn" type="reset" onclick="kasirResetSummary()" style="background:var(--db-border);color:var(--db-text);">Reset Form</button>
                <span class="db-hint" style="padding:0; margin:0;">Booking langsung <strong>confirmed</strong> tanpa perlu upload bukti bayar.</span>
            </div>
        </form>
    </div>

    {{-- ════════ RIWAYAT PESANAN WALK-IN ════════ --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Riwayat Catatan Pesanan Walk-in
            </div>
            <span class="db-page-badge db-badge-admin">{{ $walkInBookings->count() }} Pesanan Tercatat</span>
        </div>
        <div class="db-table-wrap">
            <table class="db-table">
                <thead>
                    <tr>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Waktu Dicatat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($walkInBookings as $booking)
                        @php
                            $displayName = $booking->customer_name ?? $booking->user?->name ?? '-';
                        @endphp
                        <tr>
                            <td><span class="db-court-name">{{ $booking->court->name ?? '-' }}</span></td>
                            <td class="db-muted db-nowrap">{{ $booking->date?->format('d/m/Y') }}</td>
                            <td class="db-muted db-nowrap">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                            <td>
                                <div class="db-table-user">
                                    <div class="db-mini-avatar" style="background:rgba(245,158,11,.2);color:#f59e0b;">
                                        {{ strtoupper(substr($displayName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div>{{ $displayName }}</div>
                                        <div style="font-size:10px; color:#f59e0b; font-weight:600; letter-spacing:.4px;">WALK-IN</div>
                                    </div>
                                </div>
                            </td>
                            <td class="db-price-cell db-nowrap">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="db-no-proof" style="color:#10b981; font-size:12px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Bayar tunai di tempat
                                </span>
                            </td>
                            <td>
                                <span class="db-status-badge db-status-{{ $booking->status }}">
                                    {{ $booking->status === 'confirmed' ? 'Approved' : ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="db-muted db-nowrap" style="font-size:12px;">
                                {{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 32px 16px; color: var(--db-muted);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:8px; opacity:0.5;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                                <div>Belum ada catatan pesanan walk-in. Gunakan formulir di atas untuk mencatat pesanan baru.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ════════ SCRIPT: Ringkasan real-time form kasir ════════ --}}
    <script>
    (function () {
        const PRICE_PER_HOUR = 300000;
        const TODAY = '{{ now()->toDateString() }}';

        const courtSel = document.getElementById('kasir_court_id');
        const datePick = document.getElementById('kasir_date');
        const timeSel  = document.getElementById('kasir_start_time');
        const durSel   = document.getElementById('kasir_duration');

        function fmt(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

        function updateKasirSummary() {
            const dur      = parseInt(durSel?.value) || 1;
            const courtTxt = courtSel?.options[courtSel.selectedIndex]?.text ?? '—';
            const dateTxt  = datePick?.value || '—';
            const timeTxt  = timeSel?.value || '—';

            document.getElementById('kps-court').textContent = courtSel?.value ? courtTxt : '—';
            document.getElementById('kps-date').textContent  = dateTxt;
            document.getElementById('kps-time').textContent  = timeTxt !== '—' ? `${timeTxt} (${dur} jam)` : '—';
            document.getElementById('kps-total').textContent = fmt(dur * PRICE_PER_HOUR);
        }

        [courtSel, timeSel, durSel].forEach(el => el?.addEventListener('change', updateKasirSummary));

        // Cegah tanggal lampau
        datePick?.addEventListener('change', function () {
            if (this.value && this.value < TODAY) {
                this.value = TODAY;
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Tidak dapat memilih tanggal yang sudah lewat.',
                    icon: 'warning',
                    confirmButtonColor: '#2563eb',
                    animation: false
                });
            }
            updateKasirSummary();
        });

        // Validasi submit
        document.getElementById('kasir-booking-form')?.addEventListener('submit', function (e) {
            const d = datePick?.value;
            if (d && d < TODAY) {
                e.preventDefault();
                datePick.value = TODAY;
                updateKasirSummary();
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
                    icon: 'warning',
                    confirmButtonColor: '#2563eb',
                    animation: false
                });
            }
        });

        updateKasirSummary();
    })();

    function kasirResetSummary() {
        setTimeout(() => {
            ['kps-court','kps-date','kps-time'].forEach(id => {
                document.getElementById(id).textContent = '—';
            });
            document.getElementById('kps-total').textContent = 'Rp 300.000';
        }, 10);
    }
    </script>
@endsection
