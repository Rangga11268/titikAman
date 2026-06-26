@extends('layouts.app')

@section('title', 'Verifikasi Pengguna BPBD - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/admin-verification.css') }}">
<style>
    /* User Profile Widget Topbar Styles */
    .dashboard-topbar {
        padding: 16px 24px;
        background-color: white;
        border-bottom: 1px solid rgba(196, 198, 207, 0.4);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .user-profile-widget {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-widget-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #006a60;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .user-widget-info {
        display: flex;
        flex-direction: column;
    }

    .user-widget-name {
        font-size: 14px;
        font-weight: 600;
        color: #031f41;
        line-height: 1.2;
    }

    .user-widget-role {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Moderation Main -->
    <div class="moderation-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left">
                <h1>Verifikasi Pengguna</h1>
            </div>

            <div class="topbar-right">
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 2)) }}
                    </div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->user()->fullname }}</span>
                        <span class="user-widget-role">Admin BPBD</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 24px; border-bottom: 1px solid #badbcc; font-size: 14px; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        <div class="moderation-grid">
            <!-- Left Queue Column -->
            <div class="queue-column">
                <div class="column-header">
                    <span>Antrean Pengajuan</span>
                    <span class="queue-badge">{{ $pendingUsers->count() }} Pending</span>
                </div>

                <!-- Tabs to filter Relawan / Pengelola -->
                <div class="tab-container">
                    <button class="tab-btn active" onclick="filterQueue('all')">Semua</button>
                    <button class="tab-btn" onclick="filterQueue('relawan')">Relawan</button>
                    <button class="tab-btn" onclick="filterQueue('pengelola')">Posko</button>
                </div>

                <div class="queue-list" id="queueList">
                    @forelse($pendingUsers as $user)
                        <a href="?user_id={{ $user->user_id }}" class="queue-item {{ $selectedUser && $selectedUser->user_id == $user->user_id ? 'active' : '' }}" data-role="{{ strtolower($user->role) }}">
                            <div class="item-meta">
                                <span class="item-role-tag {{ $user->role == 'Relawan' ? 'tag-relawan' : 'tag-pengelola' }}">
                                    {{ $user->role == 'Relawan' ? 'Relawan' : 'Pengelola' }}
                                </span>
                                <span class="item-time">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="item-title">{{ $user->fullname }}</h4>
                            <p class="item-subtitle">{{ $user->email }}</p>
                        </a>
                    @empty
                        <div style="text-align: center; padding: 24px; color: #8c8d99; font-size: 13px;">
                            Tidak ada pengajuan akun tertunda.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Detail Column -->
            <div class="detail-column">
                @if($selectedUser)
                    <div class="detail-card">
                        <div>
                            <span class="item-role-tag {{ $selectedUser->role == 'Relawan' ? 'tag-relawan' : 'tag-pengelola' }}" style="font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                {{ $selectedUser->role == 'Relawan' ? 'Relawan / SAR' : 'Pengelola Posko' }}
                            </span>
                            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; color: var(--navy-dark); margin: 12px 0 4px 0;">{{ $selectedUser->fullname }}</h2>
                            <p style="font-size: 14px; color: #5d5e66; margin: 0;">Mendaftar sejak {{ $selectedUser->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        <!-- Section 1: Profil Pengguna -->
                        <div>
                            <h3 class="detail-section-title">
                                <i data-lucide="user"></i>
                                <span>Profil Pengaju</span>
                            </h3>
                            <div class="detail-grid">
                                <div class="detail-group">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value">{{ $selectedUser->email }}</span>
                                </div>
                                <div class="detail-group">
                                    <span class="detail-label">Nomor Telepon</span>
                                    <span class="detail-value">{{ $selectedUser->phone }}</span>
                                </div>
                            </div>
                        </div>

                        @if($selectedUser->role == 'Relawan')
                            <!-- Relawan Details Section -->
                            <div>
                                <h3 class="detail-section-title">
                                    <i data-lucide="award"></i>
                                    <span>Informasi Kualifikasi Relawan</span>
                                </h3>
                                <div class="detail-grid" style="margin-bottom: 16px;">
                                    <div class="detail-group">
                                        <span class="detail-label">Nomor Induk Kependudukan (NIK)</span>
                                        <span class="detail-value">{{ $selectedUser->nik }}</span>
                                    </div>
                                    <div class="detail-group">
                                        <span class="detail-label">Organisasi / Komunitas</span>
                                        <span class="detail-value">{{ $selectedUser->organisasi ?? 'Mandiri / Tanpa Organisasi' }}</span>
                                    </div>
                                </div>
                                <div class="detail-group" style="margin-bottom: 16px;">
                                    <span class="detail-label">Spesialisasi Keahlian</span>
                                    <span class="detail-value" style="color: var(--brand-teal);">{{ $selectedUser->keahlian }}</span>
                                </div>
                                <div class="detail-group">
                                    <span class="detail-label">Dokumen Identitas (KTP / Sertifikat)</span>
                                    <div style="margin-top: 8px;">
                                        @if($selectedUser->document_path)
                                            <a href="{{ asset('storage/' . $selectedUser->document_path) }}" target="_blank" class="btn-view-document">
                                                <i data-lucide="file-text"></i>
                                                <span>Lihat Dokumen Verifikasi</span>
                                            </a>
                                        @else
                                            <span style="color: #ba1a1a; font-size: 13px; font-weight: 600;">Dokumen tidak tersedia</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($selectedUser->role == 'Pengelola_Posko' && $selectedUser->shelter_id)
                            @php
                                $shelter = \App\Models\Shelter::find($selectedUser->shelter_id);
                            @endphp
                            @if($shelter)
                                <!-- Posko/Shelter Details Section -->
                                <div>
                                    <h3 class="detail-section-title">
                                        <i data-lucide="home"></i>
                                        <span>Detail Posko Yang Didaftarkan</span>
                                    </h3>
                                    <div class="detail-grid" style="margin-bottom: 16px;">
                                        <div class="detail-group">
                                            <span class="detail-label">Nama Posko</span>
                                            <span class="detail-value">{{ $shelter->shelter_name }}</span>
                                        </div>
                                        <div class="detail-group">
                                            <span class="detail-label">Kapasitas Maksimum</span>
                                            <span class="detail-value">{{ $shelter->max_capacity }} Jiwa</span>
                                        </div>
                                    </div>
                                    <div class="detail-group" style="margin-bottom: 16px;">
                                        <span class="detail-label">Alamat Lengkap</span>
                                        <span class="detail-value">{{ $shelter->address }}</span>
                                    </div>
                                    <div class="detail-group" style="margin-bottom: 16px;">
                                        <span class="detail-label">Fasilitas Tersedia</span>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px;">
                                            @if(is_array($shelter->facilities))
                                                @foreach($shelter->facilities as $fac)
                                                    <span style="font-size: 12px; background-color: rgba(0,106,96,0.06); color: var(--brand-teal); padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $fac }}</span>
                                                @endforeach
                                            @else
                                                <span style="color: #6c757d; font-size: 13px;">Tidak mencantumkan fasilitas</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="detail-group">
                                        <span class="detail-label">Lokasi Koordinat Posko</span>
                                        <span class="detail-value">Latitude: {{ $shelter->latitude }}, Longitude: {{ $shelter->longitude }}</span>
                                        <div id="detail-map"></div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Action Approval / Rejection buttons -->
                        <div class="detail-actions-row">
                            <form action="{{ route('admin.user.reject', $selectedUser->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan akun ini?')">
                                @csrf
                                <button type="submit" class="btn-action btn-reject">
                                    <i data-lucide="user-x"></i>
                                    <span>Tolak Pendaftaran</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.user.approve', $selectedUser->user_id) }}" method="POST" onsubmit="return confirm('Setujui pendaftaran akun ini?')">
                                @csrf
                                <button type="submit" class="btn-action btn-approve">
                                    <i data-lucide="user-check"></i>
                                    <span>Setujui Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <i data-lucide="users"></i>
                        <h2>Pilih Pengaju dari Antrean</h2>
                        <p>Silakan klik salah satu akun di antrean sebelah kiri untuk melihat informasi lengkap pengajuan verifikasi mereka.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function filterQueue(role) {
        // Update tab styling
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Find clicked button
        event.target.classList.add('active');

        // Show/hide queue items
        const items = document.querySelectorAll('#queueList .queue-item');
        items.forEach(item => {
            if (role === 'all') {
                item.style.display = 'block';
            } else if (role === 'relawan' && item.getAttribute('data-role') === 'relawan') {
                item.style.display = 'block';
            } else if (role === 'pengelola' && item.getAttribute('data-role') === 'pengelola_posko') {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    @if($selectedUser && $selectedUser->role == 'Pengelola_Posko')
        document.addEventListener('DOMContentLoaded', function () {
            @php
                $shelter = \App\Models\Shelter::find($selectedUser->shelter_id);
            @endphp
            @if($shelter)
                const map = L.map('detail-map').setView([{{ $shelter->latitude }}, {{ $shelter->longitude }}], 14);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '© OpenStreetMap contributors © CARTO',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);

                const customIcon = L.divIcon({
                    html: `<div style="background-color: var(--brand-teal); width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                           </div>`,
                    className: 'custom-map-marker',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                L.marker([{{ $shelter->latitude }}, {{ $shelter->longitude }}], {icon: customIcon}).addTo(map);
            @endif
        });
    @endif
</script>
@endsection
