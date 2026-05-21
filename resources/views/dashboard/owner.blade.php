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

    {{-- Modern Confirm Modal --}}
    <div id="confirmModal" class="qr-modal-backdrop" style="display: none; align-items: center; justify-content: center; z-index: 100000;">
        <div class="qr-modal-card" style="max-width: 400px; width: 90%;">
            <div class="qr-modal-header">
                <div class="qr-modal-title" style="color: #f87171;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Konfirmasi Hapus
                </div>
                <button type="button" class="qr-close-btn" onclick="closeConfirmModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="qr-modal-body" style="padding: 20px;">
                <p style="color: var(--db-text); font-size: 14px; margin-bottom: 24px;">Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" class="db-btn db-btn-outline" onclick="closeConfirmModal()">Batal</button>
                    <button type="button" class="db-btn db-btn-red" id="confirmDeleteBtn">Hapus Akun</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let formToSubmit = null;

        document.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                formToSubmit = this;
                document.getElementById('confirmModal').style.display = 'flex';
            });
        });

        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
            formToSubmit = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    </script>
@endsection
