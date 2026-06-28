@extends('layouts.dashboard')

@section('title', 'Dashboard Utama - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/shared-dashboard.css') }}">
@endsection

@section('topbar-left')
    <div style="display: flex; flex-direction: column;">
        <span class="breadcrumb" style="font-size: 11px; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Ringkasan Data & Aktivitas</span>
        <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 18px; font-weight: 700; color: var(--navy-dark); margin: 0;">Pusat Informasi Kebencanaan</h1>
    </div>
@endsection

@section('dashboard-content')
        <!-- Warning Banner -->
        <div class="warning-banner">
            <i data-lucide="alert-triangle"></i>
            <span>PERINGATAN: Air kiriman dari Bogor terdeteksi — TMA Sungai Cileungsi naik ke 205cm (SIAGA 1)</span>
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

            <!-- Berita & Status Row -->
            <div class="news-dashboard-row">
                <!-- Left: Berita Banjir (News Feed) -->
                <div class="news-feed-panel map-card">
                    <div class="map-header-row" style="margin-bottom: 16px;">
                        <span class="map-title">Berita Terkini & Laporan Lapangan</span>
                        <a href="{{ route('laporan.warga') }}" class="btn-green-link">Lihat Semua Laporan</a>
                    </div>
                    <div class="news-list">
                        @forelse($verifiedReports as $report)
                            <div class="news-item">
                                <div class="news-thumbnail">
                                    @if($report->photo_path)
                                        <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan">
                                    @else
                                        <div class="news-thumbnail-placeholder">
                                            <i data-lucide="image" style="color: #a0aabf; width: 24px; height: 24px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="news-content">
                                    <h3 class="news-title">Banjir Setinggi {{ $report->water_level_cm ?? '?' }}cm Melanda {{ $report->lokasi ?? 'Kawasan Bekasi' }}</h3>
                                    <p class="news-excerpt">{{ Str::limit($report->description ?? 'Tidak ada deskripsi rinci dari pelapor.', 80) }}</p>
                                    <div class="news-meta">
                                        <span class="news-author"><i data-lucide="user" style="width: 12px; height: 12px;"></i> {{ $report->user->fullname ?? 'Warga Anonim' }}</span>
                                        <span class="news-date"><i data-lucide="clock" style="width: 12px; height: 12px;"></i> {{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray py-4">
                                <i data-lucide="newspaper" style="width: 32px; height: 32px; margin: 0 auto 8px; color: var(--brand-teal);"></i>
                                <p style="font-size: 13px;">Belum ada berita laporan banjir yang diverifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right: Pintu Air & Logistik -->
                <div class="right-stacked-panels">
                    <!-- TMA Panel -->
                    <div class="map-card" style="margin-bottom: 20px;">
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
                        <div class="prediction-box">
                            <div class="prediction-title">PREDIKSI KEDATANGAN DEBIT AIR</div>
                            <div class="prediction-text">
                                Aliran air kiriman hulu terdeteksi bergerak ke wilayah hilir Kali Bekasi. Prediksi kenaikan TMA sekitar 20-35cm di Pintu Air Jatiasih pada pukul 14:15 WIB.
                            </div>
                        </div>
                    </div>

                    <!-- Logistik Panel -->
                    <div class="map-card">
                        <div class="card-header-row">
                            <span class="card-header-title">
                                <i data-lucide="package" style="color: var(--brand-teal); width: 18px; height: 18px;"></i>
                                Persediaan Logistik Terkini
                            </span>
                            <span class="text-gray" style="font-size: 12px; font-weight: 500;">Gudang Utama: Bekasi Timur</span>
                        </div>
                        <div class="logistik-rows">
                            @forelse($logistikStats as $stat)
                                @php
                                    $percent = $stat->total_need > 0 ? min(100, round(($stat->total_fulfilled / $stat->total_need) * 100)) : 0;
                                    $colorClass = $percent < 30 ? 'red' : ($percent < 70 ? 'orange' : 'green');
                                    $redirectUrl = auth()->user()->role == 'Pengelola_Posko' ? route('pengelola.dashboard') : route('donasi.hub');
                                @endphp
                                <div class="logistik-row">
                                    <div class="logistik-label-row">
                                        <span class="logistik-name">{{ $stat->category }}</span>
                                        <span class="logistik-qty">{{ number_format($stat->total_fulfilled) }} / {{ number_format($stat->total_need) }} Unit</span>
                                    </div>
                                    <div class="progress-bar-wrapper">
                                        <div class="progress-track">
                                            <div class="progress-fill {{ $colorClass }}" style="width: {{ $percent }}%;"></div>
                                        </div>
                                        <button class="btn-add-mini" onclick="window.location.href='{{ $redirectUrl }}'"><i data-lucide="plus" style="width: 10px; height: 10px;"></i> Tambah</button>
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 24px; text-align: center; color: var(--color-text-muted); font-size: 13px;">
                                    Belum ada data kebutuhan logistik.
                                </div>
                            @endforelse
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
@endsection

@section('dashboard-scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();

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

        // Add Reports / Danger Zones Markers
        @php
            $reportMapData = isset($verifiedReports) ? $verifiedReports->map(function($report) {
                return [
                    'lat' => floatval($report->latitude),
                    'lng' => floatval($report->longitude),
                    'description' => $report->street_name,
                    'height' => $report->water_height_cm
                ];
            })->toArray() : [];
        @endphp
        const reports = @json($reportMapData);

        reports.forEach(function(r) {
            if (r.lat && r.lng) {
                L.circleMarker([r.lat, r.lng], {
                    radius: 6,
                    fillColor: '#ba1a1a',
                    color: '#fff',
                    weight: 1.5,
                    fillOpacity: 0.9
                }).addTo(map)
                .bindPopup(`<strong>Zona Genangan / Banjir</strong><br>${r.description}<br>Tinggi Air: ${r.height} cm`);
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