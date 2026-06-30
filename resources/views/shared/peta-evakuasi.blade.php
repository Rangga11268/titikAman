@extends('layouts.app')

@section('title', 'Peta Evakuasi - TitikAman')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="{{ asset('css/shared-peta-evakuasi.css') }}">
@endsection

@section('content')
    <!-- Topbar -->
    <div class="floating-topbar">
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <div class="brand-logo">
                <img src="{{ asset('assets/logo-titikaman.png') }}" alt="TitikAman Logo" style="width: 32px; height: 32px;">
            </div>
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
            <a href="{{ route('dashboard') }}" class="btn-outline"
                style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; font-size: 12px; height: 36px;">
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
                <span
                    style="font-size: 12px; font-weight: 600; color: var(--navy-dark);">{{ auth()->user()->fullname }}</span>
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
                <div class="shelter-card {{ $first ? 'highlighted' : '' }}"
                    onclick="focusShelter({{ $shelter->latitude }}, {{ $shelter->longitude }}, '{{ $shelter->shelter_name }}')">
                    <div class="shelter-card-left">
                        <div class="shelter-icon">
                            <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div class="shelter-details">
                            <span class="shelter-name">{{ $shelter->shelter_name }}</span>
                            <span class="shelter-meta">Kapasitas:
                                {{ $shelter->current_occupants }}/{{ $shelter->max_capacity }} Jiwa</span>
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
            @php
                $highestGate = $waterGates->firstWhere('danger_status', '!=', 'Normal');
                $latestReport = $reports->first();
            @endphp

            @if ($highestGate)
                <div class="alert-header">
                    <i data-lucide="alert-triangle" style="width: 14px; height: 14px; color: var(--color-accent-red);"></i>
                    <span style="color: var(--color-accent-red); font-weight: 600;">Peringatan: Air Kiriman Hulu</span>
                </div>
                <p class="summary-text">
                    TMA {{ $highestGate->river_name }} ({{ $highestGate->gate_name }}) berada di tingkat
                    <strong>{{ str_replace('_', ' ', $highestGate->danger_status) }}</strong> dengan tinggi air
                    {{ $highestGate->water_level_cm }} cm.
                </p>
            @else
                <div class="alert-header">
                    <i data-lucide="check-circle" style="width: 14px; height: 14px; color: var(--color-brand-teal);"></i>
                    <span style="color: var(--color-brand-teal); font-weight: 600;">Status Pintu Air Normal</span>
                </div>
                <p class="summary-text">
                    Seluruh pintu air saat ini terpantau normal. Belum ada peringatan air kiriman dari hulu.
                </p>
            @endif

            @if ($latestReport)
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.05);">
                    <div style="font-weight: 600; font-size: 12px; margin-bottom: 4px; color: var(--color-text-muted);">INFO
                        GENANGAN TERBARU:</div>
                    <div style="font-size: 13px;">
                        <strong>{{ $latestReport->street_name }}</strong><br>
                        Tinggi Air: {{ $latestReport->water_height_cm }} cm (Dilaporkan
                        {{ $latestReport->created_at->diffForHumans() }})
                    </div>
                </div>
            @endif

            <div class="stat-chips" style="margin-top: 16px;">
                @if ($highestGate)
                    <div class="stat-chip orange">STATUS: {{ str_replace('_', ' ', $highestGate->danger_status) }}</div>
                @endif
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
                        <li>Jika membutuhkan bantuan evakuasi segera, gunakan tombol <strong>SOS Darurat</strong> di
                            dashboard.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map;

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Map
            map = L.map('evacuation-map', {
                zoomControl: false // Disable standard zoom controls to use our custom buttons
            }).setView([-6.241586, 106.992416], 13);
    // Batasi peta ke area Bekasi
    const bekasiBounds = L.latLngBounds([-6.5, 106.8], [-6.0, 107.3]);
    if(typeof map !== 'undefined') { map.setMaxBounds(bekasiBounds); map.setMinZoom(10); }
    if(typeof detailMap !== 'undefined') { detailMap.setMaxBounds(bekasiBounds); detailMap.setMinZoom(10); }
    if(typeof miniMap !== 'undefined') { miniMap.setMaxBounds(bekasiBounds); miniMap.setMinZoom(10); }
 // Bekasi center

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

            // Add Shelter Markers (House icons)
            @php
                $shelterMapData = $shelters
                    ->map(function ($shelter) {
                        $pct = $shelter->max_capacity > 0 ? $shelter->current_occupants / $shelter->max_capacity : 0;
                        $status = $shelter->status === 'active' ? ($pct >= 0.85 ? 'almost_full' : 'available') : $shelter->status;
                        return [
                            'name' => $shelter->shelter_name,
                            'lat' => floatval($shelter->latitude),
                            'lng' => floatval($shelter->longitude),
                            'status' => $status,
                            'occupants' => $shelter->current_occupants,
                            'max' => $shelter->max_capacity,
                        ];
                    })
                    ->toArray();

                $reportMapData = $reports
                    ->map(function ($report) {
                        return [
                            'lat' => floatval($report->latitude),
                            'lng' => floatval($report->longitude),
                            'description' => $report->street_name,
                            'height' => $report->water_height_cm,
                        ];
                    })
                    ->toArray();
            @endphp
            const shelters = @json($shelterMapData);

            // Custom shelter SVG icon
            const shelterSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';

            shelters.forEach(function(s) {
                if (s.lat && s.lng) {
                    let bgColor = s.status === 'full' ? '#6b7280' : (s.status === 'almost_full' ? '#f59e0b' : '#006a60');
                    let statusLabel = s.status === 'full' ? 'Penuh' : (s.status === 'almost_full' ? 'Hampir Penuh' : 'Tersedia');
                    let labelColor = s.status === 'full' ? '#6b7280' : (s.status === 'almost_full' ? '#f59e0b' : '#006a60');

                    L.marker([s.lat, s.lng], {
                        icon: L.divIcon({
                            html: `<div style="width:32px;height:32px;border-radius:6px;background:${bgColor};border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">${shelterSvg}</div>`,
                            className: '',
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                        })
                    }).addTo(map).bindPopup(
                        `<strong>${s.name}</strong><br>Status: <strong style="color:${labelColor}">${statusLabel}</strong><br>Kapasitas: ${s.occupants}/${s.max} Jiwa`
                    );

                    // Add radius circle for context
                    L.circle([s.lat, s.lng], {
                        color: bgColor,
                        fillColor: bgColor,
                        fillOpacity: 0.1,
                        weight: 2,
                        radius: 100
                    }).addTo(map);
                }
            });

            // Add Reports / Danger Zones Markers (Water drop icons)
            const reports = @json($reportMapData);
            const floodSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>';

            reports.forEach(function(r) {
                if (r.lat && r.lng) {
                    L.marker([r.lat, r.lng], {
                        icon: L.divIcon({
                            html: `<div style="width:28px;height:28px;border-radius:50%;background:#ba1a1a;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">${floodSvg}</div>`,
                            className: '',
                            iconSize: [28, 28],
                            iconAnchor: [14, 14],
                        })
                    }).addTo(map).bindPopup(
                        `<strong>Zona Banjir / Genangan</strong><br>${r.description}<br>Tinggi Air: ${r.height} cm`
                    );
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
