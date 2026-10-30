<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Order Pemesanan - PadelCourt</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 14mm 12mm 14mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            background-color: #ffffff;
            line-height: 1.4;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-left {
            vertical-align: top;
            width: 60%;
        }

        .header-right {
            vertical-align: top;
            text-align: right;
            width: 40%;
        }

        .brand-box {
            display: inline-block;
            margin-bottom: 4px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            display: inline-block;
            vertical-align: middle;
        }

        .brand-name span {
            color: #2563eb;
        }

        .brand-tagline {
            font-size: 10px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 2px;
        }

        .brand-address {
            font-size: 9px;
            color: #64748b;
            line-height: 1.3;
        }

        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .doc-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .doc-meta {
            font-size: 9px;
            color: #475569;
            line-height: 1.4;
        }

        .doc-meta strong {
            color: #0f172a;
        }

        .header-divider {
            height: 3px;
            background: #2563eb;
            margin-bottom: 12px;
            border-radius: 2px;
        }

        /* ── Statistik Bar ── */
        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 14px;
        }

        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            vertical-align: top;
            width: 25%;
        }

        .stat-label {
            font-size: 8px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-sub {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── Main Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }

        .data-table th {
            background: #1e293b;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
            border-right: 1px solid #334155;
        }

        .data-table th:last-child {
            border-right: none;
        }

        .data-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            font-size: 9.5px;
            color: #1e293b;
            vertical-align: middle;
        }

        .data-table td:last-child {
            border-right: none;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .booking-id {
            font-weight: 700;
            color: #2563eb;
        }

        .court-name {
            font-weight: 700;
            color: #0f172a;
        }

        .customer-name {
            font-weight: 600;
            color: #1e293b;
        }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-approved {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-pending {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-canceled {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-walkin {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            font-size: 7.5px;
        }

        .badge-online {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 7.5px;
        }

        /* ── Sign-off / Footer Section ── */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .footer-left {
            vertical-align: top;
            width: 60%;
            padding-right: 20px;
        }

        .footer-right {
            vertical-align: top;
            width: 40%;
            text-align: right;
        }

        .notes-card {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.4;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }

        .signature-date {
            font-size: 9px;
            color: #475569;
            margin-bottom: 2px;
        }

        .signature-role {
            font-size: 9.5px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 45px;
        }

        .signature-name {
            font-size: 9.5px;
            font-weight: 700;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
        }

        .bottom-bar {
            margin-top: 15px;
            padding-top: 6px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header Perusahaan & Dokumen --}}
    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="brand-box">
                    <span class="brand-name">Padel<span>Court</span></span>
                </div>
                <div class="brand-tagline">Sistem Manajemen &amp; Reservasi Lapangan Padel</div>
                <div class="brand-address">
                    Kompleks Olahraga Mandiri, Surabaya &bull; Telp: (031) 890-1234 &bull; Email: admin@padelcourt.com
                </div>
            </td>
            <td class="header-right">
                <div class="doc-title">Laporan Pemesanan</div>
                <div class="doc-badge">Dashboard Kasir</div>
                <div class="doc-meta">
                    Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong><br>
                    Petugas Kasir: <strong>{{ auth()->user()->name ?? 'Kasir Operasional' }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    {{-- Ringkasan Statistik Cepat --}}
    @php
        $totalBookings    = $bookings->count();
        $confirmedCount   = $bookings->where('status', 'confirmed')->count();
        $pendingCount     = $bookings->where('status', 'pending')->count();
        $walkinCount      = $bookings->whereNotNull('customer_name')->count();
        $onlineCount      = $totalBookings - $walkinCount;
    @endphp
    <table class="stats-table">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Total Pemesanan</div>
                <div class="stat-value" style="color:#2563eb;">{{ $totalBookings }}</div>
                <div class="stat-sub">Semua riwayat booking</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Approved (Selesai)</div>
                <div class="stat-value" style="color:#059669;">{{ $confirmedCount }}</div>
                <div class="stat-sub">Jadwal terkonfirmasi</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Pending (Menunggu)</div>
                <div class="stat-value" style="color:#d97706;">{{ $pendingCount }}</div>
                <div class="stat-sub">Perlu tindak lanjut</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Komposisi Pesanan</div>
                <div class="stat-value" style="font-size:14px; margin-top:2px;">
                    <span style="color:#b45309;">{{ $walkinCount }} Walk-in</span> &bull; 
                    <span style="color:#2563eb;">{{ $onlineCount }} Online</span>
                </div>
                <div class="stat-sub">Pencatatan kasir &amp; web</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Data --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 32px;" class="text-center">No</th>
                <th style="width: 65px;" class="text-center">ID</th>
                <th style="width: 75px;" class="text-center">Tanggal</th>
                <th style="width: 95px;" class="text-center">Jam Sewa</th>
                <th style="width: 110px;">Lapangan</th>
                <th>Nama Pelanggan</th>
                <th style="width: 70px;" class="text-center">Tipe</th>
                <th style="width: 85px;" class="text-center">Status</th>
                @if ($includeAmounts)
                    <th style="width: 95px;" class="text-right">Harga / Jam</th>
                    <th style="width: 100px;" class="text-right">Total Bayar</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $booking)
                @php
                    $isWalkin    = !empty($booking->customer_name);
                    $displayName = $booking->customer_name ?? $booking->user?->name ?? '-';

                    $badgeClass = match($booking->status) {
                        'confirmed' => 'badge-approved',
                        'cancelled' => 'badge-canceled',
                        default     => 'badge-pending',
                    };

                    $badgeText = match($booking->status) {
                        'confirmed' => 'Approved',
                        'cancelled' => 'Canceled',
                        default     => 'Pending',
                    };
                @endphp
                <tr>
                    <td class="text-center" style="color:#64748b;">{{ $index + 1 }}</td>
                    <td class="text-center"><span class="booking-id">#{{ $booking->id }}</span></td>
                    <td class="text-center">{{ $booking->date?->format('d/m/Y') }}</td>
                    <td class="text-center" style="color:#475569;">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} &ndash; {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </td>
                    <td><span class="court-name">{{ $booking->court->name ?? '-' }}</span></td>
                    <td>
                        <div class="customer-name">{{ $displayName }}</div>
                        @if ($isWalkin && $booking->user && $booking->user->name !== $displayName)
                            <div style="font-size:8px; color:#94a3b8;">Kasir: {{ $booking->user->name }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($isWalkin)
                            <span class="badge badge-walkin">WALK-IN</span>
                        @else
                            <span class="badge badge-online">ONLINE</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                    </td>
                    @if ($includeAmounts)
                        <td class="text-right" style="color:#64748b;">Rp {{ number_format($booking->price_per_hour, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight:700; color:#0f172a;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $includeAmounts ? 10 : 8 }}" class="text-center" style="padding: 24px; color:#64748b;">
                        Tidak ada data pemesanan yang tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($includeAmounts && $bookings->isNotEmpty())
            <tfoot>
                <tr style="background:#eff6ff;">
                    <td colspan="9" class="text-right" style="font-weight:700; color:#1e293b; padding:8px 10px;">TOTAL PENDAPATAN:</td>
                    <td class="text-right" style="font-weight:800; color:#2563eb; font-size:10.5px; padding:8px 10px;">
                        Rp {{ number_format($bookings->sum('total_price'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- Footer Tanda Tangan & Keterangan --}}
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                <div class="notes-card">
                    <strong>Catatan Resmi Kasir:</strong><br>
                    &bull; Laporan ini digenerate secara otomatis oleh sistem reservasi kasir PadelCourt.<br>
                    &bull; Pastikan bukti pembayaran transfer dan pesanan tunai walk-in telah diverifikasi fisik di kasir.<br>
                    &bull; Dokumen ini sah sebagai rekapitulasi operasional lapangan harian/periode.
                </div>
            </td>
            <td class="footer-right">
                <div class="signature-box">
                    <div class="signature-date">Surabaya, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="signature-role">Petugas Kasir Operasional,</div>
                    <div class="signature-name">( {{ auth()->user()->name ?? 'Petugas Kasir' }} )</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="bottom-bar">
        PadelCourt Reservation Management System &bull; Dokumen Resmi Dicetak Otomatis &bull; Halaman 1
    </div>

</body>
</html>
