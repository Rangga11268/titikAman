@extends('layouts.dashboard')

@section('title', 'Posko Pengungsian - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/shared-posko.css') }}">
<style>
#shelter-mini-map {
    height: 340px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(196, 198, 207, 0.4);
}
@media (max-width: 640px) {
    .search-input {
        width: 100%;
        max-width: 200px;
    }
}
</style>
@endsection

@section('topbar-left')
    <div class="search-wrapper">
        <i data-lucide="search" class="search-icon" style="width: 14px; height: 14px;"></i>
        <input type="text" class="search-input" placeholder="Cari nama posko...">
    </div>
@endsection

@section('topbar-right')
    <button class="btn-emergency-header">🚨 Emergency Alert</button>
@endsection

@section('dashboard-content')
    <!-- Main Content -->
    <div class="dashboard-body">
        <!-- Alert success & error -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error" style="background-color: rgba(186, 26, 26, 0.1); border: 1px solid rgba(186, 26, 26, 0.2); color: var(--accent-red); padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-error" style="background-color: rgba(186, 26, 26, 0.1); border: 1px solid rgba(186, 26, 26, 0.2); color: var(--accent-red); padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header -->
            <div class="header-row">
                <div class="page-title-section">
                    <h2 class="live-heading">
                        <span style="color: var(--brand-teal); font-size: 26px; line-height: 1;">●</span>
                        Posko Pengungsian Aktif
                    </h2>
                    <span class="page-subtitle">Data diperbarui real-time oleh relawan & pengelola posko di lapangan</span>
                </div>
                <button class="btn-outline">Pilih Kecamatan ▼</button>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card orange-border">
                    <span class="stat-header">Posko Aktif</span>
                    <span class="stat-value text-orange">{{ $stats['poskoAktif'] }} Posko</span>
                    <span class="stat-footer"><i data-lucide="building" style="width: 12px; height: 12px;"></i> Terverifikasi BPBD</span>
                </div>
                <div class="stat-card blue-border">
                    <span class="stat-header">Total Pengungsi</span>
                    <span class="stat-value text-blue">{{ number_format($stats['totalPengungsi']) }} Jiwa</span>
                    <span class="stat-footer"><i data-lucide="users" style="width: 12px; height: 12px;"></i> Terdata Lapangan</span>
                </div>
                <div class="stat-card teal-border">
                    <span class="stat-header">Posko Tersedia</span>
                    <span class="stat-value text-teal">{{ $stats['poskoTersedia'] }} Posko</span>
                    <span class="stat-footer text-teal"><i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Menampung Warga</span>
                </div>
                <div class="stat-card red-border">
                    <span class="stat-header">Status Kritis</span>
                    <span class="stat-value text-red">{{ $stats['statusKritis'] }} Posko</span>
                    <span class="stat-footer text-red"><i data-lucide="alert-triangle" style="width: 12px; height: 12px;"></i> Terisi Penuh</span>
                </div>
            </div>

            <!-- Filter Chips -->
            <div class="filter-chips">
                <a href="{{ route('posko', ['filter' => 'all']) }}" class="chip {{ $filter == 'all' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('posko', ['filter' => 'available']) }}" class="chip {{ $filter == 'available' ? 'active' : '' }}">Tersedia</a>
                <a href="{{ route('posko', ['filter' => 'mck']) }}" class="chip {{ $filter == 'mck' ? 'active' : '' }}">Ada MCK</a>
                <a href="#" class="chip">Ramah Lansia</a>
                <a href="#" class="chip">Dapur Umum</a>
            </div>

            <!-- Main Layout Split -->
            <div class="split-layout">
                <!-- Left: List -->
                <div class="shelter-cards-list">
                    @forelse($shelters as $shelter)
                        @php
                            $pct = min(100, intval($shelter->max_capacity > 0 ? ($shelter->current_occupants / $shelter->max_capacity * 100) : 0));
                            
                            $statusColor = 'green';
                            $statusText = 'TERSEDIA';
                            
                            if ($shelter->status == 'full') {
                                $statusColor = 'red';
                                $statusText = 'PENUH';
                            } elseif ($shelter->status == 'almost_full') {
                                $statusColor = 'orange';
                                $statusText = 'HAMPIR PENUH';
                            } elseif ($shelter->status == 'closed') {
                                $statusColor = 'gray';
                                $statusText = 'POSKO DITUTUP';
                            }
                        @endphp
                        <div class="shelter-horizontal-card">
                            <div class="shelter-image-col">
                                <img src="{{ $shelter->photo ? asset('storage/' . $shelter->photo) : asset('assets/landing/hero-map.png') }}" class="shelter-img" alt="{{ $shelter->shelter_name }}" style="object-fit: cover; width: 100%; height: 100%;">
                                <div class="status-overlay {{ $statusColor }}">{{ $statusText }}</div>
                            </div>
                            <div class="shelter-body-col">
                                <div class="shelter-title-row">
                                    <h3 class="shelter-h3">{{ $shelter->shelter_name }}</h3>
                                    <span class="shelter-address">{{ $shelter->address }} • 1.2km</span>
                                </div>

                                <div class="capacity-bar-section">
                                    <div class="capacity-labels">
                                        <span>Kapasitas Hunian</span>
                                        <span>{{ $shelter->current_occupants }} / {{ $shelter->max_capacity }} Jiwa ({{ $pct }}%)</span>
                                    </div>
                                    <div class="capacity-bar-track">
                                        <div class="capacity-bar-fill {{ $statusColor }}" style="width: {{ $pct }}%;"></div>
                                    </div>
                                </div>

                                <div class="facility-pills">
                                    @if($shelter->has_toilet_facilities)
                                        <span class="facility-pill">MCK Layak</span>
                                    @endif
                                    <span class="facility-pill">Dapur Umum</span>
                                    <span class="facility-pill">Pos Kesehatan</span>
                                </div>

                                <div class="shelter-buttons">
                                    @if($shelter->status == 'closed')
                                        <button class="btn-card disabled" disabled style="background-color: #9ca3af !important; color: white !important; cursor: not-allowed;">Posko Sudah Ditutup</button>
                                    @elseif($shelter->status == 'full')
                                        <button class="btn-card disabled" disabled>Posko Terisi Penuh</button>
                                    @else
                                        <button class="btn-card outline" onclick="window.open('https://www.google.com/maps/dir/?api=1&destination={{ $shelter->latitude }},{{ $shelter->longitude }}', '_blank')">Lihat Rute</button>
                                        <button class="btn-card filled" onclick="focusShelterMap({{ $shelter->latitude }}, {{ $shelter->longitude }}, '{{ $shelter->shelter_name }}')">Fokus Peta</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert-success" style="background-color: var(--bg-light); text-align: center; padding: 40px; color: var(--color-text-muted);">
                            Tidak ada posko pengungsian yang memenuhi kriteria filter.
                        </div>
                    @endforelse
                </div>

                <!-- Right: Map -->
                <div class="map-card-right">
                    <span class="table-title">Sebaran Posko Terdekat</span>
                    <div id="shelter-mini-map"></div>
                    <div class="map-legend">
                        <div><span class="legend-dot green"></span> Tersedia</div>
                        <div><span class="legend-dot orange"></span> Hampir Penuh</div>
                        <div><span class="legend-dot red"></span> Penuh</div>
                    </div>
                    <span class="page-subtitle" style="font-size: 11px;">Terakhir diperbarui: 14:22 WIB</span>
                </div>
            </div>

            <!-- Kirim Donasi Section -->
            <div class="donasi-section">
                <div class="donasi-header">
                    <div class="donasi-icon-bg">
                        <i data-lucide="truck"></i>
                    </div>
                    <div>
                        <h3 class="donasi-title">Kirim Bantuan Logistik ke Posko</h3>
                        <span class="page-subtitle">Formulir komitmen penyaluran donasi logistik warga</span>
                    </div>
                </div>

                <form action="{{ route('donasi.submit') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    <div class="donasi-form-grid">
                        <div class="span-2">
                            <label class="donasi-label" for="need_id">PILIH POSKO &amp; BARANG KEBUTUHAN *</label>
                            <select name="need_id" id="need_id" class="donasi-select" required>
                                <option value="">-- Pilih Posko & Barang Kebutuhan --</option>
                                @foreach($shelters as $s)
                                    @if($s->shelterNeeds->isNotEmpty())
                                        <optgroup label="{{ $s->shelter_name }}">
                                            @foreach($s->shelterNeeds as $need)
                                                @if($need->quantity_fulfilled < $need->quantity_need)
                                                    <option value="{{ $need->need_id }}">
                                                        {{ $need->item_name }} (Sisa Kebutuhan: {{ $need->quantity_need - $need->quantity_fulfilled }} Unit/Box)
                                                    </option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="donasi-label" for="quantity_donated">JUMLAH YANG DIDONASIKAN *</label>
                            <input type="number" name="quantity_donated" id="quantity_donated" class="donasi-input" min="1" placeholder="Contoh: 50" required>
                        </div>
                        <div>
                            <label class="donasi-label" for="shipping_receipt_no">NOMOR RESI PENGIRIMAN (OPSIONAL)</label>
                            <input type="text" name="shipping_receipt_no" id="shipping_receipt_no" class="donasi-input" placeholder="Contoh: TA-DONASI-123">
                        </div>
                        <div class="span-2">
                            <label class="donasi-label" for="proof_photo">FOTO BUKTI PENGIRIMAN / BARANG *</label>
                            <div class="donasi-file-upload">
                                <i data-lucide="upload-cloud"></i>
                                <span>Klik atau seret foto bukti pengiriman ke sini</span>
                                <input type="file" name="proof_photo" id="proof_photo" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="donasi-info">
                        <i data-lucide="info"></i>
                        <span class="donasi-info-text">
                            <strong>Penting:</strong> Harap pastikan jumlah dan foto bukti barang sesuai untuk memudahkan verifikasi oleh pengelola posko.
                        </span>
                    </div>

                    <button type="submit" class="donasi-submit">
                        <span>Konfirmasi Pengiriman Bantuan</span>
                        <i data-lucide="chevron-right"></i>
                    </button>
                </form>
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
@endsection

@section('dashboard-scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let miniMap;

    document.addEventListener("DOMContentLoaded", function() {

        lucide.createIcons();

        // Initialize Map
        miniMap = L.map('shelter-mini-map').setView([-6.241586, 106.992416], 12); // Bekasi center
        
        // Fix map not rendering tiles properly when initialized in hidden or resizing containers on mobile
        setTimeout(() => { if(miniMap) miniMap.invalidateSize(); }, 500);
        
        // CartoDB Voyager tiles (light theme suited for maps)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(miniMap);

        // Precomputed shelter coordinates
        @php
            $shelterMapData = $shelters->map(function($shelter) {
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
        const shelters = @json($shelterMapData);

        shelters.forEach(function(s) {
            if (s.lat && s.lng) {
                let color = s.status === 'full' ? '#ba1a1a' : (s.status === 'almost_full' ? '#f59e0b' : '#006a60');
                L.circleMarker([s.lat, s.lng], {
                    radius: 7,
                    fillColor: color,
                    color: '#fff',
                    weight: 1.5,
                    fillOpacity: 0.85
                }).addTo(miniMap)
                .bindPopup(`<strong>${s.name}</strong><br>Kapasitas: ${s.occupants}/${s.max}`);
            }
        });
    });

    function focusShelterMap(lat, lng, name) {
        if (miniMap && lat && lng) {
            miniMap.setView([lat, lng], 15);
            L.popup()
                .setLatLng([lat, lng])
                .setContent(`<strong>${name}</strong><br>Lokasi Posko`)
                .openOn(miniMap);
        }
    }
</script>
@endsection
