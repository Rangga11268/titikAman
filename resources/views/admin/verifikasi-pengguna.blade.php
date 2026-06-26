@extends('layouts.app')

@section('title', 'Verifikasi Pengguna BPBD - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/admin-verification.css') }}">
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
            <div class="success-alert">
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
                        <div class="empty-state">
                            <div class="empty-state-text">Tidak ada pengajuan akun tertunda.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Detail Column -->
            <div class="detail-column">
                @if($selectedUser)
                    <div class="detail-card">
                        <div>
                            <span class="item-role-tag {{ $selectedUser->role == 'Relawan' ? 'tag-relawan' : 'tag-pengelola' }}">
                                {{ $selectedUser->role == 'Relawan' ? 'Relawan / SAR' : 'Pengelola Posko' }}
                            </span>
                            <h2 class="detail-user-name">{{ $selectedUser->fullname }}</h2>
                            <p class="detail-user-date">Mendaftar sejak {{ $selectedUser->created_at->format('d M Y, H:i') }}</p>
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
                                <div class="detail-grid">
                                    <div class="detail-group">
                                        <span class="detail-label">Nomor Induk Kependudukan (NIK)</span>
                                        <span class="detail-value">{{ $selectedUser->nik }}</span>
                                    </div>
                                    <div class="detail-group">
                                        <span class="detail-label">Organisasi / Komunitas</span>
                                        <span class="detail-value">{{ $selectedUser->organisasi ?? 'Mandiri / Tanpa Organisasi' }}</span>
                                    </div>
                                </div>
                                <div class="detail-group">
                                    <span class="detail-label">Spesialisasi Keahlian</span>
                                    <span class="detail-value detail-highlight">{{ $selectedUser->keahlian }}</span>
                                </div>
                                <div class="detail-group">
                                    <span class="detail-label">Dokumen Identitas (KTP / Sertifikat)</span>
                                    <div class="detail-document">
                                        @if($selectedUser->document_path)
                                            <a href="{{ asset('storage/' . $selectedUser->document_path) }}" target="_blank" class="btn-view-document">
                                                <i data-lucide="file-text"></i>
                                                <span>Lihat Dokumen Verifikasi</span>
                                            </a>
                                        @else
                                            <span class="detail-error">Dokumen tidak tersedia</span>
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
                                    <div class="detail-grid">
                                        <div class="detail-group">
                                            <span class="detail-label">Nama Posko</span>
                                            <span class="detail-value">{{ $shelter->shelter_name }}</span>
                                        </div>
                                        <div class="detail-group">
                                            <span class="detail-label">Kapasitas Maksimum</span>
                                            <span class="detail-value">{{ $shelter->max_capacity }} Jiwa</span>
                                        </div>
                                    </div>
                                    <div class="detail-group">
                                        <span class="detail-label">Alamat Lengkap</span>
                                        <span class="detail-value">{{ $shelter->address }}</span>
                                    </div>
                                    <div class="detail-group">
                                        <span class="detail-label">Fasilitas Tersedia</span>
                                        <div class="facilities-list">
                                            @if(is_array($shelter->facilities))
                                                @foreach($shelter->facilities as $fac)
                                                    <span class="facility-tag">{{ $fac }}</span>
                                                @endforeach
                                            @else
                                                <span class="detail-muted">Tidak mencantumkan fasilitas</span>
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
                                    <span>Setujui Pendaftaran</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i data-lucide="user-check" style="width: 64px; height: 64px;"></i>
                        </div>
                        <p class="empty-state-text">Pilih salah satu pengajuan dari antrean untuk melihat detail dan melakukan verifikasi.</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();

        // --- Queue Filtering ---
        window.filterQueue = function(role) {
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            const items = document.querySelectorAll('.queue-item');
            items.forEach(item => {
                if (role === 'all' || item.getAttribute('data-role') === role) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        };

        // --- Detail Map for Shelter ---
        @if($selectedUser && $selectedUser->role == 'Pengelola_Posko' && $selectedUser->shelter_id)
            @php
                $shelter = \App\Models\Shelter::find($selectedUser->shelter_id);
            @endphp
            @if($shelter && $shelter->latitude && $shelter->longitude)
                const detailMap = L.map('detail-map').setView([{{ $shelter->latitude }}, {{ $shelter->longitude }}], 15);
                
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                    maxZoom: 20
                }).addTo(detailMap);

                L.marker([{{ $shelter->latitude }}, {{ $shelter->longitude }}]).addTo(detailMap)
                    .bindPopup('{{ $shelter->shelter_name }}')
                    .openPopup();
            @endif
        @endif
    });
</script>
@endsection