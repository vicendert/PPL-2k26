@extends('layouts.app')

@section('content')
    @php
        $totalSlots    = $slots ? count($slots) * max($courts->count(), 1) : 0;
        $bookedSlots   = 0;
        foreach ($bookingMap as $courtSlots) {
            $bookedSlots += count($courtSlots);
        }
        $availableSlots = max($totalSlots - $bookedSlots, 0);
    @endphp

    {{-- ════════ MODAL QR QRIS ════════ --}}
    <div class="qr-modal-backdrop" id="qr-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="qr-modal-title">
        <div class="qr-modal">
            <div class="qr-modal-header">
                <div class="qr-modal-title" id="qr-modal-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Scan QRIS untuk Membayar
                </div>
                <button class="qr-modal-close" id="qr-modal-close" aria-label="Tutup modal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="qr-modal-body">
                <div class="qr-summary" id="qr-summary">
                    {{-- Diisi oleh JS --}}
                </div>
                <div class="qr-image-wrap">
                    <img src="/images/qris.jpg" alt="QR Code QRIS Pembayaran" class="qr-image">
                </div>
                <div class="qr-info">
                    <div class="qr-info-row">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Scan QR lalu bayar sesuai nominal, kemudian upload bukti bayar di bawah.
                    </div>
                    <div class="qr-info-row">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        a.n. <strong>EFRAIM DARRELL F.N</strong> &mdash; NMID: ID1026469021541
                    </div>
                </div>
            </div>
            <div class="qr-modal-footer">
                <button class="db-btn db-btn-primary qr-confirm-btn" id="qr-confirm-btn" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Sudah Bayar — Booking Sekarang
                </button>
                <button class="db-btn qr-cancel-btn" id="qr-cancel-btn" type="button">Batal</button>
            </div>
        </div>
    </div>

    {{-- ════════ HEADER ════════ --}}
    <div class="db-page-header">
        <div>
            <div class="db-page-eyebrow">Member</div>
            <h1 class="db-page-title">Booking Lapangan</h1>
            <p class="db-page-desc">Pilih jadwal kosong dan lakukan booking dengan mudah.</p>
        </div>
        <div class="db-page-badge db-badge-user">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            User
        </div>
    </div>

    @include('partials.flash')

    {{-- ════════ STATS ════════ --}}
    <div class="db-stats-grid db-stats-3">
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Tanggal Dipilih</div>
                <div class="db-stat-value db-stat-date">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
                <div class="db-stat-sub">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd') }}</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-teal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Jumlah Lapangan</div>
                <div class="db-stat-value">{{ $courts->count() }}</div>
                <div class="db-stat-sub">Lapangan aktif</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Slot Tersedia</div>
                <div class="db-stat-value">{{ $availableSlots }}</div>
                <div class="db-stat-sub">Dari {{ $totalSlots }} total slot</div>
            </div>
        </div>
    </div>

    {{-- ════════ DATE PICKER ════════ --}}
    <div class="db-card db-date-picker-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Pilih Tanggal
            </div>
        </div>
        <div class="db-date-form">
            <input class="db-input" type="date" id="main-date-picker" name="date" value="{{ $date }}">
        </div>
        <p class="db-hint">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Slot berwarna hijau = tersedia. Merah/abu = terisi.
        </p>
    </div>

    {{-- ════════ SLOT GRID PER COURT ════════ --}}
    @foreach ($courts as $court)
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    {{ $court->name }}
                </div>
                <div class="db-slot-legend">
                    <span class="db-legend-dot db-legend-available"></span> Tersedia
                    <span class="db-legend-dot db-legend-booked" style="margin-left:12px"></span> Terisi
                </div>
            </div>
            <div class="db-slot-grid" data-court-slot-grid="{{ $court->id }}">
                @foreach ($slots as $slot)
                    @php $status = $bookingMap[$court->id][$slot] ?? null; @endphp
                    <div class="db-slot {{ $status ? 'db-slot-booked' : 'db-slot-free' }}">
                        <div class="db-slot-time">{{ $slot }}</div>
                        <div class="db-slot-label">{{ $status ? ucfirst($status) : 'Tersedia' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach


    {{-- ════════ HARGA LAPANGAN ════════ --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Daftar Harga Sewa
            </div>
            <span class="db-page-badge db-badge-user">Rp 300.000 / jam</span>
        </div>
        <div class="db-price-grid">
            @for ($i = 1; $i <= 8; $i++)
                <div class="db-price-item {{ old('duration_hours', 1) == $i ? 'db-price-item--highlight' : '' }}" data-hours="{{ $i }}">
                    <div class="db-price-duration">{{ $i }} Jam</div>
                    <div class="db-price-amount">Rp {{ number_format($i * 300000, 0, ',', '.') }}</div>
                    @if($i === 1)
                        <div class="db-price-tag">Populer</div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- ════════ FORM BOOKING ════════ --}}
    <div class="db-card" id="booking-form-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Pesan Lapangan
            </div>
        </div>
        <form method="post" action="{{ route('bookings.store') }}" class="db-booking-form" id="booking-form">
            @csrf
            <div class="db-form-grid">
                <div class="db-form-group">
                    <label class="db-label" for="court_id">Lapangan</label>
                    <select class="db-input" id="court_id" name="court_id" required>
                        <option value="">Pilih lapangan…</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}" @selected(old('court_id') == $court->id)>{{ $court->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Tanggal disinkronkan dari date picker atas (hidden) --}}
                <input type="hidden" id="booking_date" name="date" value="{{ old('date', $date) }}">
                <div class="db-form-group">
                    <label class="db-label" for="start_time">Jam Mulai</label>
                    <select class="db-input" id="start_time" name="start_time" required>
                        @php $oldTime = old('start_time', '00:00'); @endphp
                        @for ($h = 0; $h <= 23; $h++)
                            @php $val = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                            <option value="{{ $val }}" @selected($oldTime === $val)>{{ $val }}</option>
                        @endfor
                    </select>
                </div>
                <div class="db-form-group">
                    <label class="db-label" for="duration_hours">Durasi</label>
                    <select class="db-input" id="duration_hours" name="duration_hours" required>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" @selected(old('duration_hours', 1) == $i)>{{ $i }} jam — Rp {{ number_format($i * 300000, 0, ',', '.') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="db-form-group">
                    <label class="db-label" for="promo_code">Kode Promo (Opsional)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" class="db-input" id="promo_code" name="promo_code" placeholder="Masukkan kode promo" value="{{ old('promo_code') }}" style="flex: 1; text-transform: uppercase;">
                        <button type="button" class="db-btn db-btn-teal" id="check-promo-btn" style="padding: 0 16px;">Gunakan</button>
                    </div>
                    <span id="promo-message" style="font-size: 12px; margin-top: 4px; display: block;"></span>
                </div>
            </div>

            {{-- Ringkasan harga real-time --}}
            <div class="db-price-summary" id="price-summary">
                <div class="db-price-summary-row">
                    <span>Lapangan</span>
                    <span id="ps-court">—</span>
                </div>
                <div class="db-price-summary-row">
                    <span>Tanggal</span>
                    <span id="ps-date">—</span>
                </div>
                <div class="db-price-summary-row">
                    <span>Jam</span>
                    <span id="ps-time">—</span>
                </div>
                <div class="db-price-summary-row db-price-summary-total">
                    <span>Total Bayar</span>
                    <span id="ps-total">Rp 300.000</span>
                </div>
            </div>

            <div style="padding: 0 20px 20px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                <button class="db-btn db-btn-primary" type="button" id="open-qr-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Lihat QR &amp; Booking
                </button>
                <span class="db-hint" style="padding:0; margin:0;">Kamu akan diminta scan QRIS sebelum booking dikonfirmasi.</span>
            </div>
        </form>
    </div>

    {{-- ════════ RIWAYAT BOOKING ════════ --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                Riwayat Booking Saya
            </div>
            <span class="db-page-badge db-badge-admin">{{ $myBookings->count() }} Booking</span>
        </div>

        @if ($myBookings->isEmpty())
            <div class="db-empty-state">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <div>Belum ada riwayat booking.</div>
                <div style="font-size:12px;color:var(--db-muted)">Lakukan booking pertama kamu di atas!</div>
            </div>
        @else
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Bukti Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myBookings as $booking)
                            @php
                                $statusLabel = match($booking->status) {
                                    'confirmed' => 'Dikonfirmasi',
                                    'cancelled' => 'Dibatalkan',
                                    default     => 'Menunggu',
                                };
                            @endphp
                            <tr>
                                <td><span class="db-court-name">{{ $booking->court->name ?? '-' }}</span></td>
                                <td class="db-muted db-nowrap">{{ $booking->date?->format('d M Y') }}</td>
                                <td class="db-muted db-nowrap">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                <td class="db-muted">{{ $booking->duration_hours ?? '-' }} jam</td>
                                <td class="db-price-cell">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td><span class="db-status-badge db-status-{{ $booking->status }}">{{ $statusLabel }}</span></td>
                                <td>
                                    @if ($booking->status === 'cancelled')
                                        <span class="db-muted" style="font-size:12px">—</span>
                                    @elseif ($booking->payment_proof)
                                        <div class="db-proof-uploaded">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            <a href="{{ Storage::url($booking->payment_proof) }}" target="_blank" class="db-proof-link">Lihat Bukti</a>
                                        </div>
                                    @else
                                        <form method="post" action="{{ route('bookings.payment', $booking) }}" enctype="multipart/form-data" class="db-upload-form">
                                            @csrf
                                            <label class="db-upload-label" for="proof_{{ $booking->id }}">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                <span class="db-upload-text" id="upload-text-{{ $booking->id }}">Upload Bukti</span>
                                            </label>
                                            <input type="file" id="proof_{{ $booking->id }}" name="payment_proof"
                                                accept="image/*" class="db-upload-input"
                                                onchange="updateFileName(this, 'upload-text-{{ $booking->id }}', 'upload-submit-{{ $booking->id }}')">
                                            <button type="submit" class="db-btn-sm db-btn-teal db-upload-submit" id="upload-submit-{{ $booking->id }}" style="display:none;">Kirim</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ════════ JS ════════ --}}
    <script>
    (function () {
        const PRICE_PER_HOUR = 300000;

        // ── All slots (00:00 – 23:00) ──
        const ALL_SLOTS = Array.from({length: 24}, (_, i) => String(i).padStart(2,'0') + ':00');

        // ── Elements ──
        const courtSel      = document.getElementById('court_id');
        const mainDatePicker= document.getElementById('main-date-picker');  // date picker atas
        const bookingDate   = document.getElementById('booking_date');       // hidden input di form
        const timeSel       = document.getElementById('start_time');
        const durSel        = document.getElementById('duration_hours');
        const promoInput    = document.getElementById('promo_code');
        const checkPromoBtn = document.getElementById('check-promo-btn');
        const promoMsg      = document.getElementById('promo-message');
        const openQrBtn     = document.getElementById('open-qr-btn');
        const backdrop      = document.getElementById('qr-modal-backdrop');
        const closeBtn      = document.getElementById('qr-modal-close');
        const cancelBtn     = document.getElementById('qr-cancel-btn');
        const confirmBtn    = document.getElementById('qr-confirm-btn');
        const form          = document.getElementById('booking-form');

        let currentDiscountPercentage = 0;

        // ── Ringkasan harga real-time ──
        function formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function updateSummary() {
            const dur      = parseInt(durSel.value) || 1;
            let total      = dur * PRICE_PER_HOUR;
            const courtTxt = courtSel.options[courtSel.selectedIndex]?.text ?? '—';
            const dateTxt  = mainDatePicker?.value || '—';
            const timeTxt  = timeSel.value || '—';

            let discount = 0;
            if (currentDiscountPercentage > 0) {
                discount = total * (currentDiscountPercentage / 100);
                total = total - discount;
            }

            document.getElementById('ps-court').textContent = courtSel.value ? courtTxt : '—';
            document.getElementById('ps-date').textContent  = dateTxt;
            document.getElementById('ps-time').textContent  = timeTxt + (timeSel.value ? ` (${dur} jam)` : '');
            
            const totalEl = document.getElementById('ps-total');
            if (discount > 0) {
                totalEl.innerHTML = `<del style="color: #94a3b8; font-size: 13px; margin-right: 8px;">${formatRupiah(dur * PRICE_PER_HOUR)}</del>${formatRupiah(total)}`;
            } else {
                totalEl.textContent = formatRupiah(total);
            }
        }

        [courtSel, timeSel, durSel].forEach(el => el?.addEventListener('change', updateSummary));
        promoInput?.addEventListener('input', () => {
            currentDiscountPercentage = 0;
            promoMsg.textContent = '';
            updateSummary();
        });

        checkPromoBtn?.addEventListener('click', async () => {
            const code = promoInput.value.trim();
            if (!code) return;

            checkPromoBtn.disabled = true;
            promoMsg.textContent = 'Mengecek...';
            promoMsg.style.color = 'inherit';

            try {
                const res = await fetch(`/api/promos/check?code=${encodeURIComponent(code)}`);
                const data = await res.json();

                if (data.valid) {
                    currentDiscountPercentage = data.discount_percentage;
                    promoMsg.textContent = `Promo diterapkan! Diskon ${data.discount_percentage}%`;
                    promoMsg.style.color = '#10b981';
                } else {
                    currentDiscountPercentage = 0;
                    promoMsg.textContent = data.message || 'Promo tidak valid.';
                    promoMsg.style.color = '#ef4444';
                }
            } catch (err) {
                currentDiscountPercentage = 0;
                promoMsg.textContent = 'Gagal mengecek promo.';
                promoMsg.style.color = '#ef4444';
            } finally {
                checkPromoBtn.disabled = false;
                updateSummary();
            }
        });

        updateSummary();

        // ── Price Grid Selection Sync ──
        const priceItems = document.querySelectorAll('.db-price-item');
        priceItems.forEach(item => {
            item.addEventListener('click', () => {
                const hours = item.getAttribute('data-hours');
                if (durSel && hours) {
                    durSel.value = hours;
                    durSel.dispatchEvent(new Event('change'));
                }
            });
        });

        function updatePriceGridHighlight() {
            const currentHours = durSel?.value;
            priceItems.forEach(item => {
                const hours = item.getAttribute('data-hours');
                if (hours === currentHours) {
                    item.classList.add('db-price-item--highlight');
                } else {
                    item.classList.remove('db-price-item--highlight');
                }
            });
        }
        durSel?.addEventListener('change', updatePriceGridHighlight);
        updatePriceGridHighlight();

        // ── Dynamic Slot Grid (AJAX) ──
        // bookingMap dari server (initial load)
        let currentBookingMap = @json($bookingMap);

        function renderSlotGrids(bookingMap) {
            // Setiap .db-slot-grid di-render ulang berdasarkan bookingMap baru
            document.querySelectorAll('[data-court-slot-grid]').forEach(grid => {
                const courtId = grid.getAttribute('data-court-slot-grid');
                grid.innerHTML = ALL_SLOTS.map(slot => {
                    const status = bookingMap[courtId]?.[slot] ?? null;
                    const label  = status ? (status === 'confirmed' ? 'Dikonfirmasi' : 'Pending') : 'Tersedia';
                    const cls    = status ? 'db-slot-booked' : 'db-slot-free';
                    return `<div class="db-slot ${cls}">
                        <div class="db-slot-time">${slot}</div>
                        <div class="db-slot-label">${label}</div>
                    </div>`;
                }).join('');
            });

            // Update stat "Slot Tersedia"
            updateSlotStats(bookingMap);
        }

        function updateSlotStats(bookingMap) {
            const courtCount = document.querySelectorAll('[data-court-slot-grid]').length;
            const totalSlots = ALL_SLOTS.length * courtCount;
            let bookedCount  = 0;
            Object.values(bookingMap).forEach(slots => { bookedCount += Object.keys(slots).length; });
            const available  = Math.max(totalSlots - bookedCount, 0);

            const valEl = document.querySelector('.db-stat-value:not(.db-stat-date)');
            if (valEl) {
                // Cari stat card "Slot Tersedia" secara spesifik
                document.querySelectorAll('.db-stat-card').forEach(card => {
                    if (card.querySelector('.db-stat-label')?.textContent.trim() === 'Slot Tersedia') {
                        card.querySelector('.db-stat-value').textContent = available;
                        card.querySelector('.db-stat-sub').textContent   = `Dari ${totalSlots} total slot`;
                    }
                });
            }
        }

        function setSlotGridLoading(loading) {
            document.querySelectorAll('[data-court-slot-grid]').forEach(grid => {
                if (loading) {
                    grid.style.opacity = '0.4';
                    grid.style.pointerEvents = 'none';
                } else {
                    grid.style.opacity = '';
                    grid.style.pointerEvents = '';
                }
            });
        }

        async function fetchSlotsForDate(date) {
            if (!date) return;
            setSlotGridLoading(true);

            // Update tanggal yang tampil di stat card
            const dateStatEl = document.querySelector('.db-stat-date');
            if (dateStatEl && date) {
                try {
                    const d = new Date(date);
                    dateStatEl.textContent = d.toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
                    const dayNames = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    const subEl = dateStatEl.closest('.db-stat-body')?.querySelector('.db-stat-sub');
                    if (subEl) subEl.textContent = dayNames[d.getDay()];
                } catch(e) {}
            }

            try {
                const res = await fetch(`/api/slots?date=${encodeURIComponent(date)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) throw new Error('Network error');
                const bookingMap = await res.json();
                currentBookingMap = bookingMap;
                renderSlotGrids(bookingMap);
            } catch (err) {
                console.error('Gagal memuat slot:', err);
            } finally {
                setSlotGridLoading(false);
            }
        }

        // Saat tanggal di date picker atas berubah:
        // 1) Sync ke hidden input form booking
        // 2) Fetch slot grid baru via AJAX
        // 3) Update ringkasan harga
        mainDatePicker?.addEventListener('change', function () {
            const date = this.value;
            if (bookingDate) bookingDate.value = date;
            fetchSlotsForDate(date);
            updateSummary();
        });

        // ── QR Modal ──
        function openModal() {
            if (!courtSel.value || !mainDatePicker.value || !timeSel.value || !durSel.value) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Lengkapi form booking terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#2563eb',
                    animation: false
                });
                return;
            }
            const dur     = parseInt(durSel.value) || 1;
            let total     = dur * PRICE_PER_HOUR;
            let discountHtml = '';

            if (currentDiscountPercentage > 0) {
                const discount = total * (currentDiscountPercentage / 100);
                total = total - discount;
                discountHtml = `<div class="qr-summary-row" style="color: #10b981;"><span>Diskon Promo (${currentDiscountPercentage}%)</span><strong>-${formatRupiah(discount)}</strong></div>`;
            }

            const court   = courtSel.options[courtSel.selectedIndex]?.text ?? '-';
            const dateVal = mainDatePicker?.value ?? '-';
            document.getElementById('qr-summary').innerHTML = `
                <div class="qr-summary-row"><span>Lapangan</span><strong>${court}</strong></div>
                <div class="qr-summary-row"><span>Tanggal</span><strong>${dateVal}</strong></div>
                <div class="qr-summary-row"><span>Jam</span><strong>${timeSel.value} (${dur} jam)</strong></div>
                ${discountHtml}
                <div class="qr-summary-row qr-summary-total"><span>Total Bayar</span><strong>${formatRupiah(total)}</strong></div>
            `;
            backdrop.classList.add('qr-modal-open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            backdrop.classList.remove('qr-modal-open');
            document.body.style.overflow = '';
        }

        openQrBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        backdrop?.addEventListener('click', function(e) {
            if (e.target === backdrop) closeModal();
        });

        // Confirm → submit form
        confirmBtn?.addEventListener('click', function () {
            closeModal();
            form.submit();
        });
    })();

    // ── Upload preview ──
    function updateFileName(input, textId, btnId) {
        const textEl = document.getElementById(textId);
        const btnEl  = document.getElementById(btnId);
        if (input.files && input.files[0]) {
            textEl.textContent = input.files[0].name.substring(0, 18) + (input.files[0].name.length > 18 ? '…' : '');
            if (btnEl) btnEl.style.display = 'inline-flex';
        }
    }
    </script>
@endsection

