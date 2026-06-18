@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="db-page-header">
        <div>
            <div class="db-page-eyebrow">Admin Kasir</div>
            <h1 class="db-page-title">Dashboard</h1>
            <p class="db-page-desc">Monitor user, jadwal booking, dan bukti pembayaran.</p>
        </div>
        <div class="db-page-badge db-badge-admin">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Admin Kasir
        </div>
    </div>

    {{-- Stats --}}
    <div class="db-stats-grid">
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Total User</div>
                <div class="db-stat-value">{{ $users->count() }}</div>
                <div class="db-stat-sub">Pengguna terdaftar</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-teal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Total Booking</div>
                <div class="db-stat-value">{{ $bookings->count() }}</div>
                <div class="db-stat-sub">Semua booking</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-amber">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Pending</div>
                <div class="db-stat-value">{{ $bookings->where('status','pending')->count() }}</div>
                <div class="db-stat-sub">Menunggu konfirmasi</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Sudah Bayar</div>
                <div class="db-stat-value">{{ $bookings->whereNotNull('payment_proof')->count() }}</div>
                <div class="db-stat-sub">Ada bukti pembayaran</div>
            </div>
        </div>
    </div>

    <div class="db-grid-30-70">
        {{-- User Table --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    Daftar User
                </div>
            </div>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="db-table-user">
                                        <div class="db-mini-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="db-muted">{{ $user->username }}</td>
                                <td class="db-muted">{{ $user->email }}</td>
                                <td class="db-muted" style="font-family: monospace;">{{ $user->password_plain ?? 'N/A' }}</td>
                                <td><span class="db-role-badge db-role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Riwayat Pemesanan
                </div>
                <span class="db-page-badge db-badge-admin">{{ $bookings->whereNotNull('payment_proof')->count() }} Bukti Masuk</span>
            </div>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Bukti Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            @php
                                $statusLabel = match ($booking->status) {
                                    'confirmed' => 'Approved',
                                    'cancelled' => 'Canceled',
                                    default     => 'Pending',
                                };
                            @endphp
                            <tr>
                                <td><span class="db-court-name">{{ $booking->court->name ?? '-' }}</span></td>
                                <td class="db-muted db-nowrap">{{ $booking->date?->format('d/m/Y') }}</td>
                                <td class="db-muted db-nowrap">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                <td>
                                    <div class="db-table-user">
                                        <div class="db-mini-avatar">{{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}</div>
                                        {{ $booking->user->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="db-price-cell db-nowrap">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($booking->payment_proof)
                                        <div class="db-proof-preview">
                                            <a href="{{ Storage::url($booking->payment_proof) }}" target="_blank" class="db-proof-thumb-link">
                                                <img src="{{ Storage::url($booking->payment_proof) }}"
                                                     alt="Bukti Bayar"
                                                     class="db-proof-thumb"
                                                     onerror="this.style.display='none'">
                                                <span class="db-proof-link-text">Lihat</span>
                                            </a>
                                        </div>
                                    @else
                                        <span class="db-no-proof">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Belum upload
                                        </span>
                                    @endif
                                </td>
                                <td><span class="db-status-badge db-status-{{ $booking->status }}">{{ $statusLabel }}</span></td>
                                <td>
                                    <form method="post" action="{{ route('admin.bookings.status', $booking) }}" class="db-inline-form">
                                        @csrf
                                        <select class="db-select-sm" name="status">
                                            <option value="pending"    @selected($booking->status === 'pending')>Pending</option>
                                            <option value="confirmed"  @selected($booking->status === 'confirmed')>Approved</option>
                                            <option value="cancelled"  @selected($booking->status === 'cancelled')>Canceled</option>
                                        </select>
                                        <button class="db-btn-sm db-btn-teal" type="submit">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reports --}}
    <div class="db-card">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Laporan Order
            </div>
        </div>
        <div class="db-report-actions">
            <button class="db-btn db-btn-red" type="button" onclick="openExportPreview('Laporan Order (PDF)', '{{ route('admin.reports.orders', ['format' => 'pdf', 'preview' => true]) }}', '{{ route('admin.reports.orders', ['format' => 'pdf']) }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Export PDF
            </button>
            <button class="db-btn db-btn-green" type="button" onclick="openExportPreview('Laporan Order (Excel)', '{{ route('admin.reports.orders', ['format' => 'excel', 'preview' => true]) }}', '{{ route('admin.reports.orders', ['format' => 'excel']) }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export Excel
            </button>
        </div>
    </div>

    @include('partials.export_modal')
@endsection
