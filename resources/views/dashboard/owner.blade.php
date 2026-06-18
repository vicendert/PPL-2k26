@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="db-page-header">
        <div>
            <div class="db-page-eyebrow">Pemilik</div>
            <h1 class="db-page-title">Dashboard Owner</h1>
            <p class="db-page-desc">Pantau pendapatan, user, dan admin aktif secara real-time.</p>
        </div>
        <div class="db-page-badge db-badge-owner">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Owner
        </div>
    </div>

    {{-- Stats --}}
    <div class="db-stats-grid db-stats-3">
        <div class="db-stat-card db-stat-card--highlight">
            <div class="db-stat-icon db-icon-amber">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Total Pendapatan</div>
                <div class="db-stat-value db-stat-revenue">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                <div class="db-stat-sub">Dari booking confirmed</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">User Terdaftar</div>
                <div class="db-stat-value">{{ $users->count() }}</div>
                <div class="db-stat-sub">Semua pengguna</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon db-icon-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="db-stat-body">
                <div class="db-stat-label">Admin Online</div>
                <div class="db-stat-value">{{ $activeAdmins->count() }}</div>
                <div class="db-stat-sub">Admin kasir aktif</div>
            </div>
        </div>
    </div>

    <div class="db-grid-30-70">
        {{-- Active Admins --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Admin Kasir Online
                </div>
                <span class="db-online-dot-badge">
                    <span class="db-online-dot"></span>
                    {{ $activeAdmins->count() }} aktif
                </span>
            </div>
            <div class="db-admin-list">
                @forelse ($activeAdmins as $admin)
                    <div class="db-admin-item">
                        <div class="db-mini-avatar db-avatar-green">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                        <div class="db-admin-meta">
                            <div class="db-admin-name">{{ $admin->name }}</div>
                            <div class="db-admin-time">Aktif {{ $admin->last_active_at?->diffForHumans() }}</div>
                        </div>
                        <span class="db-status-badge db-status-confirmed">Online</span>
                    </div>
                @empty
                    <div class="db-empty-state">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        <div>Belum ada admin aktif saat ini.</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- User Table --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    Kelola User
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
                            <th>Aksi</th>
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
                                <td>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('owner.users.destroy', $user) }}" class="delete-user-form" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="db-btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); padding: 6px 8px; border-radius: 6px; cursor: pointer;" title="Hapus Akun">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Booking Table / Riwayat Pemesanan --}}
    <div class="db-card" style="margin-bottom: 16px;">
        <div class="db-card-header">
            <div class="db-card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Riwayat Pemesanan
            </div>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kelola Kode Promo --}}
    <div class="db-grid-30-70" style="margin-bottom: 16px;">
        {{-- Form Tambah Promo --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Buat Promo Baru
                </div>
            </div>
            <div style="padding: 16px;">
                <form method="post" action="{{ route('owner.promos.store') }}">
                    @csrf
                    <div class="db-form-group">
                        <label class="db-label" for="code">Kode Promo</label>
                        <input class="db-input" type="text" name="code" id="code" required placeholder="Cth: DISKON10" style="text-transform: uppercase;">
                    </div>
                    <div class="db-form-group">
                        <label class="db-label" for="discount_percentage">Diskon (%)</label>
                        <input class="db-input" type="number" name="discount_percentage" id="discount_percentage" min="1" max="100" required placeholder="Cth: 10">
                    </div>
                    <div class="db-form-group" style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_active" id="is_active" checked>
                        <label class="db-label" for="is_active" style="margin-bottom: 0;">Aktifkan Promo</label>
                    </div>
                    <button type="submit" class="db-btn db-btn-primary" style="width: 100%;">Simpan Promo</button>
                </form>
            </div>
        </div>

        {{-- Tabel Promo --}}
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Daftar Kode Promo
                </div>
            </div>
            <div class="db-table-wrap">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Diskon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promos as $promo)
                            <tr>
                                <td><strong>{{ $promo->code }}</strong></td>
                                <td>{{ $promo->discount_percentage }}%</td>
                                <td>
                                    @if($promo->is_active)
                                        <span class="db-status-badge db-status-confirmed">Aktif</span>
                                    @else
                                        <span class="db-status-badge db-status-cancelled">Non-aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('owner.promos.destroy', $promo) }}" style="display:inline-block;" onsubmit="return confirm('Hapus promo ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="db-btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); padding: 6px 8px; border-radius: 6px; cursor: pointer;" title="Hapus Promo">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('owner.promos.update', $promo) }}" style="display:inline-block; margin-left: 4px;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="code" value="{{ $promo->code }}">
                                        <input type="hidden" name="discount_percentage" value="{{ $promo->discount_percentage }}">
                                        @if($promo->is_active)
                                            <button type="submit" class="db-btn-sm" style="background: rgba(107, 114, 128, 0.1); color: #6b7280; border: 1px solid rgba(107, 114, 128, 0.2); padding: 6px 8px; border-radius: 6px; cursor: pointer;" title="Non-aktifkan">
                                                Non-aktifkan
                                            </button>
                                        @else
                                            <input type="hidden" name="is_active" value="1">
                                            <button type="submit" class="db-btn-sm" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); padding: 6px 8px; border-radius: 6px; cursor: pointer;" title="Aktifkan">
                                                Aktifkan
                                            </button>
                                        @endif
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
                Laporan Penyewaan &amp; Keuangan
            </div>
        </div>
        <div class="db-report-section">
            <div class="db-report-group">
                <div class="db-report-group-label">Laporan Penyewaan</div>
                <div class="db-report-actions">
                    <button class="db-btn db-btn-red" type="button" onclick="openExportPreview('Laporan Penyewaan (PDF)', '{{ route('owner.reports.orders', ['format' => 'pdf', 'preview' => true]) }}', '{{ route('owner.reports.orders', ['format' => 'pdf']) }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Export PDF
                    </button>
                    <button class="db-btn db-btn-green" type="button" onclick="openExportPreview('Laporan Penyewaan (Excel)', '{{ route('owner.reports.orders', ['format' => 'excel', 'preview' => true]) }}', '{{ route('owner.reports.orders', ['format' => 'excel']) }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>
            <div class="db-report-divider"></div>
            <div class="db-report-group">
                <div class="db-report-group-label">Laporan Keuangan</div>
                <div class="db-report-actions">
                    <button class="db-btn db-btn-red" type="button" onclick="openExportPreview('Laporan Keuangan (PDF)', '{{ route('owner.reports.revenue', ['format' => 'pdf', 'preview' => true]) }}', '{{ route('owner.reports.revenue', ['format' => 'pdf']) }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Export PDF
                    </button>
                    <button class="db-btn db-btn-green" type="button" onclick="openExportPreview('Laporan Keuangan (Excel)', '{{ route('owner.reports.revenue', ['format' => 'excel', 'preview' => true]) }}', '{{ route('owner.reports.revenue', ['format' => 'excel']) }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.export_modal')

    <script>
        document.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Hapus Akun',
                    cancelButtonText: 'Batal',
                    animation: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
