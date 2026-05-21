<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Order</title>
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

            .badge {
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 9px;
                font-weight: 600;
                text-transform: uppercase;
            }
            .badge-confirmed { background: rgba(16, 185, 129, 0.1); color: #34d399; }
            .badge-pending { background: rgba(245, 158, 11, 0.1); color: #fbbf24; }
            .badge-cancelled { background: rgba(239, 68, 68, 0.1); color: #f87171; }
        </style>
    </head>
    <body>
        <div class="header-container">
            <h1>Laporan Order Penyewaan</h1>
            <div class="meta">Generated: {{ now()->format('Y-m-d H:i') }} | Padel Court Manager</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Lapangan</th>
                    <th>Customer</th>
                    <th>Status</th>
                    @if ($includeAmounts)
                        <th>Harga / Jam</th>
                        <th>Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>{{ $booking->date?->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                        <td>{{ $booking->court->name ?? '-' }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>
                            @php
                                $badgeClass = match($booking->status) {
                                    'confirmed' => 'badge-confirmed',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'badge-pending'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $booking->status }}</span>
                        </td>
                        @if ($includeAmounts)
                            <td>Rp {{ number_format($booking->price_per_hour, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
