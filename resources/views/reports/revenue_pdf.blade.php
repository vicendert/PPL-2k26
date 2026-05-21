<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Keuangan</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
            
            body {
                font-family: 'Inter', Helvetica, Arial, sans-serif;
                font-size: 11px;
                color: #e2e8f0;
                background-color: #0f1117;
                margin: 0;
                padding: 20px;
            }

            h1 {
                font-size: 20px;
                font-weight: 700;
                color: #fff;
                margin: 0 0 4px 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .header-container {
                border-bottom: 2px solid #0d9488;
                padding-bottom: 15px;
                margin-bottom: 25px;
            }

            .meta {
                color: #94a3b8;
                font-size: 11px;
            }

            table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                margin-bottom: 20px;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #1e293b;
            }

            th, td {
                padding: 10px 12px;
                text-align: left;
                border-bottom: 1px solid #1e293b;
            }

            th {
                background: #161b22;
                color: #0d9488;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 10px;
                letter-spacing: 0.5px;
            }

            tbody tr:nth-child(even) {
                background-color: rgba(255, 255, 255, 0.02);
            }

            tbody tr:last-child td {
                border-bottom: none;
            }

            .total td {
                font-weight: 700;
                background: #0d9488 !important;
                color: #fff;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        </style>
    </head>
    <body>
        <div class="header-container">
            <h1>Laporan Keuangan</h1>
            <div class="meta">Generated: {{ now()->format('Y-m-d H:i') }} | Padel Court Manager</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Lapangan</th>
                    <th>Customer</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>{{ $booking->date?->format('d/m/Y') }}</td>
                        <td>{{ $booking->court->name ?? '-' }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="4" style="text-align: right;">Total Pendapatan :</td>
                    <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
