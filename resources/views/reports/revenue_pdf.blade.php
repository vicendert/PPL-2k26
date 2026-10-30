<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan Keuangan - PadelCourt</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
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

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-left {
            vertical-align: top;
            width: 55%;
        }

        .header-right {
            vertical-align: top;
            text-align: right;
            width: 45%;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: #0d9488;
        }

        .brand-tagline {
            font-size: 10px;
            font-weight: 600;
            color: #0d9488;
            margin-bottom: 2px;
        }

        .brand-address {
            font-size: 9px;
            color: #64748b;
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
            background: #ccfbf1;
            color: #0f766e;
            border: 1px solid #99f6e4;
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
        }

        .header-divider {
            height: 3px;
            background: #0d9488;
            margin-bottom: 15px;
            border-radius: 2px;
        }

        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 16px;
        }

        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            vertical-align: top;
            width: 50%;
        }

        .stat-label {
            font-size: 8.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .stat-sub {
            font-size: 8.5px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
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

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
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
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="brand-name">Padel<span>Court</span></div>
                <div class="brand-tagline">Laporan Finansial &amp; Pendapatan Sewa</div>
                <div class="brand-address">Surabaya &bull; Email: admin@padelcourt.com</div>
            </td>
            <td class="header-right">
                <div class="doc-title">Laporan Keuangan</div>
                <div class="doc-badge">Arsip Finansial</div>
                <div class="doc-meta">
                    Tanggal: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    <table class="stats-table">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Total Transaksi Sukses</div>
                <div class="stat-value" style="color: #0d9488;">{{ $bookings->count() }} Booking</div>
                <div class="stat-sub">Semua status approved</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Total Pendapatan Bersih</div>
                <div class="stat-value" style="color: #059669;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="stat-sub">Akumulasi penerimaan</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px;" class="text-center">No</th>
                <th style="width: 70px;" class="text-center">ID</th>
                <th style="width: 85px;" class="text-center">Tanggal</th>
                <th style="width: 140px;">Lapangan</th>
                <th>Nama Pelanggan</th>
                <th style="width: 120px;" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $index => $booking)
                <tr>
                    <td class="text-center" style="color:#64748b;">{{ $index + 1 }}</td>
                    <td class="text-center" style="font-weight:700; color:#0d9488;">#{{ $booking->id }}</td>
                    <td class="text-center">{{ $booking->date?->format('d/m/Y') }}</td>
                    <td><strong>{{ $booking->court->name ?? '-' }}</strong></td>
                    <td>{{ $booking->customer_name ?? $booking->user?->name ?? '-' }}</td>
                    <td class="text-right" style="font-weight:700;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#ccfbf1;">
                <td colspan="5" class="text-right" style="font-weight:700; color:#0f766e; padding:10px;">TOTAL KESELURUHAN:</td>
                <td class="text-right" style="font-weight:800; color:#065f46; font-size:11px; padding:10px;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 60%; vertical-align: top; font-size: 8.5px; color:#64748b;">
                Catatan: Laporan ini adalah ringkasan mutasi pendapatan yang telah dikonfirmasi sah dalam sistem PadelCourt.
            </td>
            <td style="width: 40%; text-align: right; vertical-align: top;">
                <div class="signature-box">
                    <div style="font-size:9px; color:#64748b; margin-bottom:2px;">Surabaya, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="signature-role">Manajemen Keuangan,</div>
                    <div class="signature-name">( {{ auth()->user()->name ?? 'Manager' }} )</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
