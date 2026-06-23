@extends('layouts.app')

@section('title', 'Peta Evakuasi - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    :root {
        --brand-teal: #006a60;
        --brand-teal-hover: #004d46;
        --navy-dark: #031f41;
        --accent-orange: #f59e0b;
        --accent-red: #ba1a1a;
        --accent-green: #006a60;
        --white-semi: rgba(255, 255, 255, 0.85);
        --blur-val: blur(12px);
    }

    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    }

    #evacuation-map {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        z-index: 0;
    }

    /* Floating Topbar */
    .floating-topbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 70px;
        background-color: var(--white-semi);
        backdrop-filter: var(--blur-val);
        -webkit-backdrop-filter: var(--blur-val);
        border-bottom: 1px solid rgba(196, 198, 207, 0.4);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--navy-dark);
    }

    .brand-logo {
        background-color: #031f41;
        color: white;
        padding: 6px;
        border-radius: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 16px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 18px;
    }

    .status-badge {
        background-color: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-left: 12px;
    }

    .search-container {
        flex: 0 1 360px;
        position: relative;
    }

    .search-input {
        width: 100%;
        height: 40px;
        background-color: white;
        border: 1px solid rgba(196, 198, 207, 0.6);
        border-radius: 8px;
        padding: 8px 16px 8px 40px;
        font-size: 13px;
        outline: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .search-input:focus {
        border-color: var(--brand-teal);
        box-shadow: 0 0 0 3px rgba(0, 106, 96, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .topbar-btn {
        background: none;
        border: none;
        color: var(--navy-dark);
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .topbar-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .user-avatar-drop {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
    }

    .user-avatar-drop:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .avatar-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--brand-teal);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    /* Floating Panels Left */
    .floating-left-panel {
        position: fixed;
        left: 16px;
        width: 290px;
        background-color: white;
        border-radius: 12px;
        border: 1px solid rgba(196, 198, 207, 0.4);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        overflow: hidden;
    }

    .panel-top {
        top: 86px;
        max-height: calc(100vh - 310px);
        display: flex;
        flex-direction: column;
    }

    .panel-bottom {
        bottom: 16px;
        height: auto;
    }

    .panel-header {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(196, 198, 207, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fcfcfd;
    }

    .panel-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .active-badge {
        background-color: rgba(16, 185, 129, 0.15);
        color: #10b981;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .shelter-list {
        padding: 10px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .shelter-card {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid rgba(196, 198, 207, 0.3);
        background-color: var(--bg-light);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .shelter-card:hover {
        border-color: var(--brand-teal);
        background-color: rgba(0, 106, 96, 0.02);
    }

    .shelter-card.highlighted {
        background-color: rgba(0, 106, 96, 0.05);
        border-color: var(--brand-teal);
    }

    .shelter-card-left {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .shelter-icon {
        background-color: rgba(0, 106, 96, 0.1);
        color: var(--brand-teal);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .shelter-card.highlighted .shelter-icon {
        background-color: var(--brand-teal);
        color: white;
    }

    .shelter-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .shelter-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--navy-dark);
        max-width: 170px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .shelter-meta {
        font-size: 10px;
        color: var(--color-text-muted);
    }

    .shelter-arrow-btn {
        background: none;
        border: none;
        color: var(--brand-teal);
        cursor: pointer;
        padding: 4px;
    }

    .panel-footer-link {
        padding: 10px;
        text-align: center;
        border-top: 1px solid rgba(196, 198, 207, 0.3);
        font-size: 12px;
        font-weight: 600;
        color: var(--brand-teal);
        text-decoration: none;
        display: block;
    }

    .panel-footer-link:hover {
        background-color: rgba(0, 106, 96, 0.02);
        text-decoration: underline;
    }

    /* Bottom Info Card */
    .summary-body {
        padding: 12px 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .alert-header {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-orange);
        font-size: 12px;
        font-weight: 700;
    }

    .summary-text {
        font-size: 11px;
        color: #4b5563;
        line-height: 1.4;
        margin: 0;
    }

    .stat-chips {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 4px;
    }

    .stat-chip {
        background-color: #f3f4f6;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 9px;
        font-weight: 700;
        color: var(--navy-dark);
        border-left: 3px solid var(--brand-teal);
        text-transform: uppercase;
    }

    .stat-chip.orange { border-left-color: var(--accent-orange); }

    /* Floating Control Buttons Right */
    .floating-right-actions {
        position: fixed;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 1000;
    }

    .control-btn {
        width: 40px;
        height: 40px;
        background-color: white;
        border-radius: 8px;
        border: 1px solid rgba(196, 198, 207, 0.4);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--navy-dark);
        cursor: pointer;
        transition: all 0.2s;
    }

    .control-btn:hover {
        background-color: #f9fafb;
        color: var(--brand-teal);
    }

    /* Custom Modal Petunjuk */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(3, 31, 65, 0.4);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .custom-modal-card {
        background-color: white;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 1px solid rgba(196, 198, 207, 0.4);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .custom-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(196, 198, 207, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fcfcfd;
    }

    .custom-modal-header h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
    }

    .close-modal-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--color-text-muted);
        cursor: pointer;
        transition: color 0.2s;
    }

    .close-modal-btn:hover {
        color: var(--navy-dark);
    }

    .custom-modal-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .legend-section h4,
    .instructions-section h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--navy-dark);
        margin-top: 0;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .legend-item-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-top: 3px;
        flex-shrink: 0;
    }

    .legend-dot.green {
        background-color: #2e7d32;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #2e7d32;
    }

    .legend-dot.red-poly {
        background-color: rgba(230, 57, 70, 0.2);
        border: 2px solid #e63946;
        border-radius: 4px;
    }

    .legend-dot.blue-drop {
        background-color: #3b82f6;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #3b82f6;
    }

    .legend-item-row div strong {
        font-size: 12px;
        color: var(--navy-dark);
        display: block;
    }

    .legend-item-row div p {
        font-size: 11px;
        color: var(--color-text-muted);
        margin: 2px 0 0 0;
        line-height: 1.4;
    }

    .instructions-section ol {
        margin: 0;
        padding-left: 16px;
        font-size: 12px;
        color: #4b5563;
    }

    .instructions-section li {
        margin-bottom: 8px;
        line-height: 1.5;
    }
</style>
@endsection

@section('content')
<!-- Topbar -->
<div class="floating-topbar">
    <a href="{{ route('dashboard') }}" class="topbar-brand">
        <div class="brand-logo">TA</div>
        <div class="brand-text">
            <span class="brand-name">TitikAman</span>
            <span class="brand-subtitle" style="display: block; font-size: 10px;">Peta Kebencanaan</span>
        </div>
        <span class="status-badge">EMERGENCY STATUS: NORMAL</span>
    </a>

    <div class="search-container">
        <i data-lucide="search" class="search-icon" style="width: 16px; height: 16px;"></i>
        <input type="text" class="search-input" placeholder="Cari Lokasi Aman / Posko...">
    </div>

    <div class="topbar-right">
        <a href="{{ route('dashboard') }}" class="btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; font-size: 12px; height: 36px;">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Kembali</span>
        </a>
        <button class="topbar-btn" title="Petunjuk">
            <i data-lucide="help-circle" style="width: 20px; height: 20px;"></i>
        </button>
        <button class="topbar-btn" title="Notifikasi">
            <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
        </button>
        <div class="user-avatar-drop">
            <div class="avatar-img">{{ strtoupper(substr(auth()->user()->fullname, 0, 2)) }}</div>
            <span style="font-size: 12px; font-weight: 600; color: var(--navy-dark);">{{ auth()->user()->fullname }}</span>
            <i data-lucide="chevron-down" style="width: 14px; height: 14px; color: var(--color-text-muted);"></i>
        </div>
    </div>
</div>

<!-- Map Container -->
<div id="evacuation-map"></div>

<!-- Top Left Panel: Shelter Terdekat -->
<div class="floating-left-panel panel-top">
    <div class="panel-header">
        <span class="panel-title">Shelter Terdekat</span>
        <span class="active-badge">3 AKTIF</span>
    </div>
    <div class="shelter-list">
        @php $first = true; @endphp
        @forelse($shelters->take(5) as $shelter)
            <div class="shelter-card {{ $first ? 'highlighted' : '' }}" onclick="focusShelter({{ $shelter->latitude }}, {{ $shelter->longitude }}, '{{ $shelter->shelter_name }}')">
                <div class="shelter-card-left">
                    <div class="shelter-icon">
                        <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                    </div>
                    <div class="shelter-details">
                        <span class="shelter-name">{{ $shelter->shelter_name }}</span>
                        <span class="shelter-meta">Kapasitas: {{ $shelter->current_occupants }}/{{ $shelter->max_capacity }} Jiwa</span>
                    </div>
                </div>
                <button class="shelter-arrow-btn">
                    <i data-lucide="navigation" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
            @php $first = false; @endphp
        @empty
            <div class="text-center py-4" style="font-size: 11px; color: var(--color-text-muted);">
                Tidak ada data posko pengungsian aktif.
            </div>
        @endforelse
    </div>
    <a href="{{ route('posko') }}" class="panel-footer-link">Lihat Semua Shelter</a>
</div>

<!-- Bottom Left Panel: Ringkasan Laporan -->
<div class="floating-left-panel panel-bottom">
    <div class="panel-header">
        <span class="panel-title">Ringkasan Laporan</span>
    </div>
    <div class="summary-body">
        <div class="alert-header">
            <i data-lucide="alert-triangle" style="width: 14px; height: 14px;"></i>
            <span>Peringatan: Air Kiriman Hulu</span>
        </div>
        <p class="summary-text">
            TMA Sungai Cileungsi dilaporkan naik ke tingkat SIAGA 2. Warga di bantaran sungai diimbau waspada dan bersiap evakuasi mandiri.
        </p>
        <div class="stat-chips">
            <div class="stat-chip orange">STATUS SUNGAI: Cileungsi [SIAGA 2]</div>
            <div class="stat-chip">LAPORAN BARU 24 JAM: {{ $totalLaporanBaru }} Laporan</div>
        </div>
    </div>
</div>

<!-- Right Action Buttons -->
<div class="floating-right-actions">
    <button class="control-btn" id="locate-btn" title="Lokasi Saya">
        <i data-lucide="crosshair" style="width: 18px; height: 18px;"></i>
    </button>
    <button class="control-btn" title="Layers">
        <i data-lucide="layers" style="width: 18px; height: 18px;"></i>
    </button>
    <button class="control-btn" id="zoom-in-btn" title="Perbesar">
        <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
    </button>
    <button class="control-btn" id="zoom-out-btn" title="Perkecil">
        <i data-lucide="minus" style="width: 18px; height: 18px;"></i>
    </button>
</div>

<!-- Modal Petunjuk / Legenda -->
<div class="custom-modal-overlay" id="petunjukModal" style="display: none;">
    <div class="custom-modal-card">
        <div class="custom-modal-header">
            <h3>Petunjuk & Legenda Peta</h3>
            <button class="close-modal-btn" onclick="closePetunjuk()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <div class="legend-section">
                <h4>Legenda Peta</h4>
                <div class="legend-item-row">
                    <span class="legend-dot green"></span>
                    <div>
                        <strong>Posko Pengungsian</strong>
                        <p>Lokasi evakuasi aktif yang aman dari banjir, dikelola oleh BPBD/Relawan.</p>
                    </div>
                </div>
                <div class="legend-item-row">
                    <span class="legend-dot red-poly"></span>
                    <div>
                        <strong>Zona Genangan / Banjir</strong>
                        <p>Area pemukiman atau jalan raya yang tergenang banjir aktif (hindari area ini).</p>
                    </div>
                </div>
                <div class="legend-item-row">
                    <span class="legend-dot blue-drop"></span>
                    <div>
                        <strong>Pintu Air (TMA)</strong>
                        <p>Pos pantau Tinggi Muka Air sungai untuk mendeteksi banjir kiriman hulu.</p>
                    </div>
                </div>
            </div>
            <div class="instructions-section">
                <h4>Panduan Evakuasi</h4>
                <ol>
                    <li>Pilih salah satu <strong>Shelter Terdekat</strong> di panel kiri.</li>
                    <li>Perhatikan status kapasitas shelter sebelum bergerak menuju lokasi.</li>
                    <li>Ikuti jalur jalan yang aman dan hindari melintasi area berwarna merah (zona genangan).</li>
                    <li>Jika membutuhkan bantuan evakuasi segera, gunakan tombol <strong>SOS Darurat</strong> di dashboard.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map;

    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        map = L.map('evacuation-map', {
            zoomControl: false // Disable standard zoom controls to use our custom buttons
        }).setView([-6.241586, 106.992416], 13); // Bekasi center

        // CartoDB Voyager tiles (light theme suited for maps)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Bind custom zoom controls
        document.getElementById('zoom-in-btn').addEventListener('click', () => map.zoomIn());
        document.getElementById('zoom-out-btn').addEventListener('click', () => map.zoomOut());

        // Geolocation locate me button
        document.getElementById('locate-btn').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    
                    // Add user location marker
                    L.circleMarker([lat, lng], {
                        radius: 7,
                        fillColor: '#3b82f6',
                        color: 'white',
                        weight: 2.5,
                        fillOpacity: 1
                    }).addTo(map).bindPopup("Lokasi Anda").openPopup();
                });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
            }
        });

        // Add Shelter Markers (Teal circles)
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
            
            $reportMapData = $reports->map(function($report) {
                return [
                    'lat' => floatval($report->latitude),
                    'lng' => floatval($report->longitude),
                    'description' => $report->street_name,
                    'height' => $report->water_height_cm
                ];
            })->toArray();
        @endphp
        const shelters = @json($shelterMapData);

        shelters.forEach(function(s) {
            if (s.lat && s.lng) {
                let color = s.status === 'full' ? '#ba1a1a' : (s.status === 'almost_full' ? '#f59e0b' : '#006a60');
                L.circle([s.lat, s.lng], {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.6,
                    radius: 120
                }).addTo(map)
                .bindPopup(`<strong>${s.name}</strong><br>Kapasitas: ${s.occupants}/${s.max} Jiwa`);
            }
        });

        // Add Reports / Danger Zones Markers (Red drops)
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
                .bindPopup(`<strong>Zona Bahaya / Genangan</strong><br>${r.description}<br>Tinggi Air: ${r.height} cm`);
            }
        });

        // --- Petunjuk Modal Toggle ---
        const petunjukBtn = document.querySelector('button[title="Petunjuk"]');
        const petunjukModal = document.getElementById('petunjukModal');
        
        if (petunjukBtn && petunjukModal) {
            petunjukBtn.addEventListener('click', function() {
                petunjukModal.style.display = 'flex';
            });
        }

        window.closePetunjuk = function() {
            if (petunjukModal) {
                petunjukModal.style.display = 'none';
            }
        };

        if (petunjukModal) {
            petunjukModal.addEventListener('click', function(e) {
                if (e.target === petunjukModal) {
                    closePetunjuk();
                }
            });
        }
    });

    function focusShelter(lat, lng, name) {
        if (map && lat && lng) {
            map.setView([lat, lng], 15);
            L.popup()
                .setLatLng([lat, lng])
                .setContent(`<strong>${name}</strong><br>Shelter Fokus`)
                .openOn(map);
        }
    }
</script>
@endsection
