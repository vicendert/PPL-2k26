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
        <form method="get" action="{{ route('dashboard') }}" class="db-date-form">
            <input class="db-input" type="date" id="date" name="date" value="{{ $date }}">
            <button class="db-btn db-btn-teal-outline" type="submit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Tampilkan
            </button>
        </form>
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
            <div class="db-slot-grid">
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
                <div class="db-price-item {{ $i === 1 ? 'db-price-item--highlight' : '' }}">
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
                <div class="db-form-group">
                    <label class="db-label" for="booking_date">Tanggal</label>
                    <input class="db-input" type="date" id="booking_date" name="date" value="{{ old('date', $date) }}" required>
                </div>
                <div class="db-form-group">
                    <label class="db-label" for="start_time">Jam Mulai</label>
                    <input class="db-input" type="time" id="start_time" name="start_time" value="{{ old('start_time', '00:00') }}" min="00:00" max="23:00" required>
                </div>
                <div class="db-form-group">
                    <label class="db-label" for="duration_hours">Durasi</label>
                    <select class="db-input" id="duration_hours" name="duration_hours" required>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" @selected(old('duration_hours', 1) == $i)>{{ $i }} jam — Rp {{ number_format($i * 300000, 0, ',', '.') }}</option>
                        @endfor
                    </select>
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

        // ── Elements ──
        const courtSel   = document.getElementById('court_id');
        const dateSel    = document.getElementById('booking_date');
        const timeSel    = document.getElementById('start_time');
        const durSel     = document.getElementById('duration_hours');
        const openQrBtn  = document.getElementById('open-qr-btn');
        const backdrop   = document.getElementById('qr-modal-backdrop');
        const closeBtn   = document.getElementById('qr-modal-close');
        const cancelBtn  = document.getElementById('qr-cancel-btn');
        const confirmBtn = document.getElementById('qr-confirm-btn');
        const form       = document.getElementById('booking-form');

        // ── Ringkasan harga real-time ──
        function formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function updateSummary() {
            const dur      = parseInt(durSel.value) || 1;
            const total    = dur * PRICE_PER_HOUR;
            const courtTxt = courtSel.options[courtSel.selectedIndex]?.text ?? '—';
            const dateTxt  = dateSel.value || '—';
            const timeTxt  = timeSel.value || '—';

            document.getElementById('ps-court').textContent = courtSel.value ? courtTxt : '—';
            document.getElementById('ps-date').textContent  = dateTxt;
            document.getElementById('ps-time').textContent  = timeTxt + (timeSel.value ? ` (${dur} jam)` : '');
            document.getElementById('ps-total').textContent = formatRupiah(total);
        }

        [courtSel, dateSel, timeSel, durSel].forEach(el => el?.addEventListener('change', updateSummary));
        updateSummary();

        // ── QR Modal ──
        function openModal() {
            if (!courtSel.value || !dateSel.value || !timeSel.value || !durSel.value) {
                alert('Lengkapi form booking terlebih dahulu.');
                return;
            }
            const dur     = parseInt(durSel.value) || 1;
            const total   = dur * PRICE_PER_HOUR;
            const court   = courtSel.options[courtSel.selectedIndex]?.text ?? '-';
            document.getElementById('qr-summary').innerHTML = `
                <div class="qr-summary-row"><span>Lapangan</span><strong>${court}</strong></div>
                <div class="qr-summary-row"><span>Tanggal</span><strong>${dateSel.value}</strong></div>
                <div class="qr-summary-row"><span>Jam</span><strong>${timeSel.value} (${dur} jam)</strong></div>
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
