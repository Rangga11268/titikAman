@extends('layouts.app')

@section('title', 'Dashboard Utama - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
<style>
/* Filter Dropdown CSS inline karena spesifik untuk interaksi JS */
.filter-wrapper {
    position: relative;
    display: inline-block;
}
.filter-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 110%;
    background: white;
    border: 1px solid var(--card-border);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 1000;
    min-width: 180px;
    padding: 6px 0;
    animation: filterFadeIn 0.2s ease-out;
}
.filter-option {
    display: block;
    padding: 8px 16px;
    color: var(--navy-dark);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
    text-align: left;
}
.filter-option:hover {
    background-color: var(--bg-light);
    color: var(--brand-teal);
}
.filter-option.active {
    background-color: rgba(0, 106, 96, 0.08);
    color: var(--brand-teal);
    font-weight: 600;
}
@keyframes filterFadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Warning Banner -->
        <div class="warning-banner">
            <i data-lucide="alert-triangle"></i>
            <span>PERINGATAN: Air kiriman dari Bogor terdeteksi — TMA Sungai Cileungsi naik ke 205cm (SIAGA 1)</span>
        </div>

        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left">
                <h1>Dashboard Utama</h1>
            </div>
            <div class="topbar-right">
                <div class="notification-bell">
                    <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                    <div class="notification-dot"></div>
                </div>
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 2)) }}
                    </div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->user()->fullname }}</span>
                        <span class="user-widget-role">
                            @if(auth()->user()->role == 'Warga')
                                Warga Terdampak
                            @elseif(auth()->user()->role == 'Relawan')
                                Relawan Penyelamat
                            @elseif(auth()->user()->role == 'Pengelola_Posko')
                                Pengelola Posko
                            @else
                                Admin BPBD
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="dashboard-body">
            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card red-border">
                    <span class="stat-header">Titik Banjir</span>
                    <span class="stat-value text-red">{{ $titikBanjir }}</span>
                    <span class="stat-footer text-red"><i data-lucide="alert-circle" style="width: 12px; height: 12px;"></i> Terverifikasi</span>
                </div>
                <div class="stat-card orange-border">
                    <span class="stat-header">Warga Terdampak</span>
                    <span class="stat-value text-orange">{{ $wargaTerdampak }}</span>
                    <span class="stat-footer text-orange">Jiwa Terdaftar</span>
                </div>
                <div class="stat-card red-border">
                    <span class="stat-header">SOS Menunggu</span>
                    <span class="stat-value text-red">{{ $sosMenunggu }}</span>
                    <span class="stat-footer text-red"><i data-lucide="shield-alert" style="width: 12px; height: 12px;"></i> Segera Verifikasi</span>
                </div>
                <div class="stat-card teal-border">
                    <span class="stat-header">Posko Aktif</span>
                    <span class="stat-value text-teal">{{ $poskoAktif }}</span>
                    <span class="stat-footer text-teal"><i data-lucide="check" style="width: 12px; height: 12px;"></i> Tersedia Kapasitas</span>
                </div>
                <div class="stat-card teal-border">
                    <span class="stat-header">Logistik Utama</span>
                    <span class="stat-value text-teal">76%</span>
                    <span class="stat-footer text-teal"><i data-lucide="package" style="width: 12px; height: 12px;"></i> Stok Aman</span>
                </div>
            </div>

            <!-- Map & Pintu Air Row -->
            <div class="map-section-row">
                <!-- Left: Map -->
                <div class="map-card">
                    <div class="map-header-row">
                        <span class="map-title">Peta Genangan & Posko Pengungsian</span>
                        <div class="map-toggles">
                            <button class="toggle-btn siaga1">SIAGA 1</button>
                            <button class="toggle-btn siaga2">SIAGA 2</button>
                            <button class="toggle-btn siaga3">SIAGA 3</button>
                        </div>
                    </div>
                    <div id="dashboard-map"></div>
                </div>

                <!-- Right: Pintu Air -->
                <div class="pintu-air-panel">
                    <div>
                        <div class="map-header-row" style="margin-bottom: 12px;">
                            <span class="map-title">Tinggi Muka Air (TMA)</span>
                            <span class="text-teal" style="font-size: 11px; font-weight: 700;">● LIVE 15M</span>
                        </div>
                        <div class="pintu-list">
                            @forelse($waterGates as $gate)
                                <div class="pintu-row">
                                    <div class="pintu-name-col">
                                        <span class="pintu-name">{{ $gate->gate_name }}</span>
                                        <span class="pintu-river">{{ $gate->river_name }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span class="pintu-level">{{ $gate->water_level_cm }} cm</span>
                                        @if($gate->danger_status == 'Siaga_1')
                                            <span class="badge-pill badge-red">SIAGA 1</span>
                                        @elseif($gate->danger_status == 'Siaga_2')
                                            <span class="badge-pill badge-orange">SIAGA 2</span>
                                        @elseif($gate->danger_status == 'Siaga_3')
                                            <span class="badge-pill badge-yellow">SIAGA 3</span>
                                        @else
                                            <span class="badge-pill badge-green">NORMAL</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="pintu-row">
                                    <span class="pintu-river">Tidak ada data pintu air</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="prediction-box">
                        <div class="prediction-title">PREDIKSI KEDATANGAN DEBIT AIR</div>
                        <div class="prediction-text">
                            Aliran air kiriman hulu terdeteksi bergerak ke wilayah hilir Kali Bekasi. Prediksi kenaikan TMA sekitar 20-35cm di Pintu Air Jatiasih pada pukul 14:15 WIB.
                        </div>
                    </div>
                </div>
            </div>

            <!-- SOS & Logistik Row -->
            <div class="two-column-row">
                <!-- Left: Antrean SOS -->
                <div class="map-card">
                    <div class="card-header-row">
                        <span class="card-header-title">
                            <i data-lucide="alert-circle" style="color: var(--accent-orange); width: 18px; height: 18px;"></i>
                            Antrean SOS Aktif
                        </span>
                        <a href="#" class="btn-green-link">Lihat Semua</a>
                    </div>
                    <div class="sos-list">
                        @php $num = 1; @endphp
                        @forelse($latestSos as $sos)
                            <div class="sos-item">
                                <div class="sos-item-left">
                                    <div class="sos-number">{{ $num++ }}</div>
                                    <div class="sos-details">
                                        <span class="sos-title">{{ $sos->user->fullname }}</span>
                                        <span class="sos-meta">
                                            Kel. {{ $sos->user->kelurahan ?: 'Jatiasih' }}, Kec. {{ $sos->user->kecamatan ?: 'Jatiasih' }} • {{ $sos->created_at->diffForHumans() }}
                                        </span>
                                        <span class="sos-desc">Terjebak: {{ $sos->people_trapped }} Orang • Prioritas: {{ ucfirst($sos->priority_level) }}</span>
                                    </div>
                                </div>
                                <div>
                                    @if($sos->status == 'waiting')
                                        <button class="btn-action red">Verifikasi</button>
                                    @else
                                        <button class="btn-action gray" disabled>Diproses</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray py-4" style="width: 100%;">
                                <i data-lucide="check-circle-2" style="width: 32px; height: 32px; margin: 0 auto 8px; color: var(--brand-teal);"></i>
                                <p style="font-size: 13px;">Tidak ada antrean SOS darurat saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right: Persediaan Logistik -->
                <div class="map-card">
                    <div class="card-header-row">
                        <span class="card-header-title">
                            <i data-lucide="package" style="color: var(--brand-teal); width: 18px; height: 18px;"></i>
                            Persediaan Logistik Terkini
                        </span>
                        <span class="text-gray" style="font-size: 12px; font-weight: 500;">Gudang Utama: Bekasi Timur</span>
                    </div>
                    <div class="logistik-rows">
                        <div class="logistik-row">
                            <div class="logistik-label-row">
                                <span class="logistik-name">Makanan Siap Saji</span>
                                <span class="logistik-qty">469 / 2000 Porsi</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-track">
                                    <div class="progress-fill red" style="width: 23%;"></div>
                                </div>
                                <button class="btn-add-mini"><i data-lucide="plus" style="width: 10px; height: 10px;"></i> Tambah</button>
                            </div>
                        </div>

                        <div class="logistik-row">
                            <div class="logistik-label-row">
                                <span class="logistik-name">Susu Formula & Balita</span>
                                <span class="logistik-qty">819 / 1000 Unit</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-track">
                                    <div class="progress-fill orange" style="width: 82%;"></div>
                                </div>
                                <button class="btn-add-mini"><i data-lucide="plus" style="width: 10px; height: 10px;"></i> Tambah</button>
                            </div>
                        </div>

                        <div class="logistik-row">
                            <div class="logistik-label-row">
                                <span class="logistik-name">Obat-obatan Dasar</span>
                                <span class="logistik-qty">14,000 / 16,000 Pcs</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-track">
                                    <div class="progress-fill green" style="width: 87%;"></div>
                                </div>
                                <button class="btn-add-mini"><i data-lucide="plus" style="width: 10px; height: 10px;"></i> Tambah</button>
                            </div>
                        </div>

                        <div class="logistik-row">
                            <div class="logistik-label-row">
                                <span class="logistik-name">Selimut & Kasur Lipat</span>
                                <span class="logistik-qty">316 / 1000 Stel</span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-track">
                                    <div class="progress-fill red" style="width: 32%;"></div>
                                </div>
                                <button class="btn-add-mini"><i data-lucide="plus" style="width: 10px; height: 10px;"></i> Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Aktivitas Section -->
            <div class="table-section-card">
                <div class="map-header-row">
                    <span class="map-title">Log Aktivitas Kebencanaan Real-Time</span>
                    <div class="table-actions">
                        <div class="filter-wrapper">
                            <button class="btn-teal-outline" id="filter-btn">
                                <i data-lucide="filter" style="width: 14px; height: 14px;"></i> <span id="current-filter">Filter Data</span>
                            </button>
                            <div class="filter-dropdown" id="filter-menu">
                                <a href="#" class="filter-option active" data-filter="all">Semua Aktivitas</a>
                                <a href="#" class="filter-option" data-filter="laporan">Laporan Genangan</a>
                                <a href="#" class="filter-option" data-filter="sos">SOS Darurat</a>
                                <a href="#" class="filter-option" data-filter="pintu_air">Tinggi Muka Air</a>
                                <a href="#" class="filter-option" data-filter="donasi">Donasi Logistik</a>
                            </div>
                        </div>
                        <button class="btn-teal-filled" onclick="window.location.href='{{ route('laporan.export') }}'"><i data-lucide="download" style="width: 14px; height: 14px;"></i> Unduh Laporan PDF</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>WAKTU</th>
                                <th>JENIS KEJADIAN</th>
                                <th>LOKASI / DETAIL</th>
                                <th>PETUGAS</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLog as $log)
                                <tr class="activity-row" data-type="{{ $log['type'] }}">
                                    <td>{{ \Carbon\Carbon::parse($log['time'])->format('H:i') }} WIB</td>
                                    <td>
                                        <span class="badge-pill {{ $log['badge_class'] }}">{{ $log['badge'] }}</span>
                                    </td>
                                    <td>{{ $log['detail'] }}</td>
                                    <td>{{ $log['pic'] }}</td>
                                    <td>
                                        <span class="badge-pill {{ $log['status_class'] }}">{{ $log['status'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="activity-row" data-type="sos">
                                    <td>11:45 WIB</td>
                                    <td><span class="badge-pill badge-red">SOS EMERGENCY</span></td>
                                    <td>Penyelamatan 4 Jiwa terjebak di atap rumah Kavling Jati</td>
                                    <td>Relawan Budi</td>
                                    <td><span class="badge-pill badge-green">Selesai</span></td>
                                </tr>
                                <tr class="activity-row" data-type="pintu_air">
                                    <td>11:32 WIB</td>
                                    <td><span class="badge-pill badge-blue">PINTU AIR</span></td>
                                    <td>Kenaikan TMA Pintu Air Pondok Gede Hulu ke 185 cm</td>
                                    <td>Petugas Bambang</td>
                                    <td><span class="badge-pill badge-yellow">Masuk</span></td>
                                </tr>
                                <tr class="activity-row" data-type="laporan">
                                    <td>11:20 WIB</td>
                                    <td><span class="badge-pill badge-gray">LAPORAN</span></td>
                                    <td>Laporan Genangan Jalan Perjuangan validasi lapangan</td>
                                    <td>BPBD Bekasi</td>
                                    <td><span class="badge-pill badge-blue">Valid</span></td>
                                </tr>
                                <tr class="activity-row" data-type="donasi">
                                    <td>10:55 WIB</td>
                                    <td><span class="badge-pill badge-orange">DONASI</span></td>
                                    <td>Donasi 100 Box Selimut tiba di Posko Serbaguna Jatiasih</td>
                                    <td>Donatur Yayasan</td>
                                    <td><span class="badge-pill badge-green">Masuk</span></td>
                                </tr>
                            @endforelse
                            <tr id="no-activity-row" style="display: none;">
                                <td colspan="5" style="text-align: center; padding: 24px; color: var(--color-text-muted);">
                                    Tidak ada aktivitas untuk filter ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="dashboard-footer">
            <div class="footer-grid">
                <div class="footer-branding">
                    <div class="brand-logo-bg">
                        <img class="brand-logo-img" src="{{ asset('assets/logo-titikaman.png') }}" alt="Logo" onerror="this.src='https://placehold.co/44x44/f3f3f3/006a60?text=TA'">
                    </div>
                    <span class="brand-title">TitikAman</span>
                    <p class="footer-desc">
                        Sistem Informasi Manajemen Kebencanaan Kota Bekasi. Kolaborasi BPBD, Relawan, dan Warga untuk Bekasi Tangguh Bencana.
                    </p>
                </div>
                <div>
                    <h3 class="footer-col-title">AKSES CEPAT</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard Utama</a></li>
                        <li><a href="{{ route('peta.evakuasi') }}" class="footer-link">Peta Evakuasi</a></li>
                        <li><a href="{{ route('pintu.air') }}" class="footer-link">Data Tinggi Air</a></li>
                        <li><a href="{{ route('posko') }}" class="footer-link">Posko Aktif</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer-col-title">KERJA SAMA</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">BPBD Kota Bekasi</a></li>
                        <li><a href="#" class="footer-link">SAR Jawa Barat</a></li>
                        <li><a href="#" class="footer-link">BMKG</a></li>
                        <li><a href="#" class="footer-link">Pemerintah Kota</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer-col-title">BANTUAN</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">Kontak Darurat</a></li>
                        <li><a href="#" class="footer-link">Panduan Warga</a></li>
                        <li><a href="#" class="footer-link">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="footer-link">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2026 TitikAman Kota Bekasi. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Kerjasama BPBD Bekasi & Pengembang Relawan.</span>
            </div>
        </footer>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        const map = L.map('dashboard-map').setView([-6.241586, 106.992416], 12); // Bekasi center
        
        // CartoDB Dark matter tiles
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Precomputed shelter coordinates
        @php
            $shelterData = $shelters->map(function($shelter) {
                $pct = $shelter->max_capacity > 0 ? ($shelter->current_occupants / $shelter->max_capacity) : 0;
                $status = $shelter->status === 'active' ? ($pct >= 0.85 ? 'almost_full' : 'available') : $shelter->status;
                return [
                    'name' => $shelter->shelter_name,
                    'lat' => floatval($shelter->latitude),
                    'lng' => floatval($shelter->longitude),
                    'status' => $status,
                    'occupants' => $shelter->current_occupants,
                    'max' => $shelter->max_capacity
                ];
            })->toArray();
        @endphp
        const shelters = @json($shelterData);

        shelters.forEach(function(s) {
            if (s.lat && s.lng) {
                let color = s.status === 'full' ? '#ba1a1a' : (s.status === 'almost_full' ? '#f59e0b' : '#006a60');
                L.circleMarker([s.lat, s.lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    fillOpacity: 0.8
                }).addTo(map)
                .bindPopup(`<strong>${s.name}</strong><br>Kapasitas: ${s.occupants}/${s.max}`);
            }
        });

        // Activity log filtering
        const filterBtn = document.getElementById('filter-btn');
        const filterMenu = document.getElementById('filter-menu');
        const filterOptions = document.querySelectorAll('.filter-option');
        const activityRows = document.querySelectorAll('.activity-row');
        const noActivityRow = document.getElementById('no-activity-row');
        const currentFilterSpan = document.getElementById('current-filter');

        if (filterBtn && filterMenu) {
            filterBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = window.getComputedStyle(filterMenu).display === 'block';
                filterMenu.style.display = isVisible ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!filterBtn.contains(e.target) && !filterMenu.contains(e.target)) {
                    filterMenu.style.display = 'none';
                }
            });

            filterOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Remove active class from all options
                    filterOptions.forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    this.classList.add('active');

                    const selectedFilter = this.getAttribute('data-filter');
                    if (selectedFilter === 'all') {
                        currentFilterSpan.textContent = 'Filter Data';
                    } else {
                        currentFilterSpan.textContent = 'Filter: ' + this.textContent;
                    }
                    
                    filterMenu.style.display = 'none';

                    let visibleCount = 0;

                    activityRows.forEach(row => {
                        const rowType = row.getAttribute('data-type');
                        if (selectedFilter === 'all' || rowType === selectedFilter) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (visibleCount === 0) {
                        if (noActivityRow) noActivityRow.style.display = '';
                    } else {
                        if (noActivityRow) noActivityRow.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection