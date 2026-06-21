@extends('layouts.app')

@section('title', 'Lapor Genangan Banjir - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('content')
<div class="split-container">
    <!-- Left Panel (Sidebar Info) -->
    <div class="left-panel">
        <div class="brand-section">
            <div class="brand-container">
                <div class="brand-logo-bg">
                    <img class="brand-logo-img" src="{{ asset('assets/logo-titikaman.png') }}" alt="Logo TitikAman" onerror="this.src='https://placehold.co/44x44/f3f3f3/006a60?text=TA'">
                </div>
                <div class="brand-text">
                    <span class="brand-title">TitikAman</span>
                    <span class="brand-subtitle">Lapor Genangan</span>
                </div>
            </div>
            <div class="badge-official">
                <i data-lucide="shield-check" class="text-teal"></i>
                <span>Official BPBD Indonesia</span>
            </div>
        </div>

        <div class="stepper-section">
            <h2 class="left-panel-title">Kontribusi Anda<br>Sangat Berharga.</h2>
            <p class="left-panel-desc">Setiap laporan warga membantu memetakan bencana secara akurat dan mempercepat respons penyelamatan di wilayah Bekasi.</p>
        </div>

        <div class="info-box">
            <h4 class="info-box-title">Aturan Melapor Genangan</h4>
            <ul class="info-list">
                <li class="info-item">
                    <i data-lucide="info"></i>
                    <span>Tinggi air diukur dari aspal jalan/tanah (bukan di dalam rumah)</span>
                </li>
                <li class="info-item">
                    <i data-lucide="camera"></i>
                    <span>Foto harus jelas memperlihatkan genangan air & objek sekitar</span>
                </li>
                <li class="info-item">
                    <i data-lucide="map-pin"></i>
                    <span>Pastikan koordinat lokasi di peta mini sudah tepat</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel (Report Form) -->
    <div class="right-panel">
        <div class="card-container">
            <div class="form-header">
                <div class="back-link-container">
                    <a href="{{ route('warga.dashboard') }}" class="btn-back">
                        <i data-lucide="arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
                <h1 class="form-title" style="margin-top: 16px;">Laporkan Kondisi Banjir</h1>
                <p class="form-subtitle">Unggah informasi tinggi genangan air di lokasi sekitar Anda saat ini untuk membantu warga lain.</p>
            </div>

            <div class="form-body">
                @if ($errors->any())
                    <div class="error-alert">
                        <ul style="list-style: none;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('warga.lapor.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Tinggi Air -->
                    <div class="form-group">
                        <label for="water_height_cm" class="form-label">Tinggi Genangan Air (cm) *</label>
                        <div class="input-wrapper">
                            <i data-lucide="ruler"></i>
                            <input type="number" 
                                   id="water_height_cm" 
                                   name="water_height_cm" 
                                   class="form-input @error('water_height_cm') error @enderror" 
                                   placeholder="Contoh: 50 (dalam sentimeter)" 
                                   value="{{ old('water_height_cm') }}" 
                                   min="1" 
                                   required>
                        </div>
                        @error('water_height_cm')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nama Jalan / Alamat -->
                    <div class="form-group">
                        <label for="street_name" class="form-label">Detail Nama Jalan / Lokasi *</label>
                        <div class="input-wrapper">
                            <i data-lucide="map-pin"></i>
                            <input type="text" 
                                   id="street_name" 
                                   name="street_name" 
                                   class="form-input @error('street_name') error @enderror" 
                                   placeholder="Contoh: Jl. Kartini Raya RT 03/RW 04, Kel. Margahayu" 
                                   value="{{ old('street_name') }}" 
                                   required>
                        </div>
                        @error('street_name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Geolocation Coordinates -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude" class="form-label">Latitude *</label>
                            <input type="text" id="latitude" name="latitude" class="form-input" readonly required value="{{ old('latitude') }}">
                        </div>
                        <div class="form-group">
                            <label for="longitude" class="form-label">Longitude *</label>
                            <input type="text" id="longitude" name="longitude" class="form-input" readonly required value="{{ old('longitude') }}">
                        </div>
                    </div>

                    <!-- Mini Map Fallback Selection -->
                    <div class="form-group">
                        <label class="form-label">Lokasi Kejadian di Peta (Geser pin jika kurang akurat) *</label>
                        <div class="location-status-box" id="gpsStatusBox">
                            <i data-lucide="loader-2" class="animate-spin text-teal" id="gpsStatusIcon"></i>
                            <span id="gpsStatusText">Mendeteksi lokasi otomatis...</span>
                        </div>
                        <div id="mini-map" style="height: 250px; border-radius: 8px; border: 1px solid var(--color-border-muted); margin-top: 8px;"></div>
                    </div>

                    <!-- Foto Bukti -->
                    <div class="form-group">
                        <label for="photo_evidence" class="form-label">Foto Bukti Genangan Banjir *</label>
                        <div class="input-wrapper">
                            <input type="file" 
                                   id="photo_evidence" 
                                   name="photo_evidence" 
                                   class="form-input @error('photo_evidence') error @enderror" 
                                   style="padding-left: 16px; padding-top: 10px;" 
                                   accept="image/*" 
                                   required>
                        </div>
                        <span style="font-size: 11px; color: var(--color-text-muted);">
                            Format gambar: JPEG, PNG, JPG (Maksimal 5MB).
                        </span>
                        @error('photo_evidence')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i data-lucide="send"></i>
                            <span>Kirim Laporan Genangan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Default center: Bekasi City (-6.2383, 106.9922)
        const defaultLat = -6.2383;
        const defaultLng = 106.9922;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const gpsStatusBox = document.getElementById('gpsStatusBox');
        const gpsStatusIcon = document.getElementById('gpsStatusIcon');
        const gpsStatusText = document.getElementById('gpsStatusText');

        // Initialize Mini Map
        const map = L.map('mini-map').setView([defaultLat, defaultLng], 14);

        // Add CartoDB Voyager Basemap
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            maxZoom: 20
        }).addTo(map);

        // Custom Marker Icon
        const laporIcon = L.divIcon({
            html: `<div class="map-marker-custom marker-level-high" style="transform: scale(1.1); animation: none;">
                     <i data-lucide="waves" style="width:14px; height:14px; color:#ffffff;"></i>
                   </div>`,
            className: '',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        // Add Draggable Marker
        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: laporIcon
        }).addTo(map);

        // Recreate lucide icons in marker
        lucide.createIcons();

        // Update inputs on marker drag end
        function updateCoordinates(lat, lng) {
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
        }

        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
            gpsStatusBox.className = 'location-status-box success';
            gpsStatusIcon.className = 'text-teal';
            gpsStatusIcon.setAttribute('data-lucide', 'check-circle');
            gpsStatusText.innerHTML = 'Posisi disesuaikan secara manual di peta.';
            lucide.createIcons();
        });

        // Set default coordinates in input
        updateCoordinates(defaultLat, defaultLng);

        // Geolocation Capture
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update marker position
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 16);
                    updateCoordinates(lat, lng);

                    // Update Status UI
                    gpsStatusBox.className = 'location-status-box success';
                    gpsStatusIcon.className = 'text-teal';
                    gpsStatusIcon.setAttribute('data-lucide', 'check-circle');
                    gpsStatusText.innerHTML = 'GPS berhasil dikunci otomatis.';
                    lucide.createIcons();
                },
                function (error) {
                    // Update Status UI to warn user they can still use map
                    gpsStatusBox.className = 'location-status-box error';
                    gpsStatusIcon.className = 'text-red';
                    gpsStatusIcon.setAttribute('data-lucide', 'alert-circle');
                    gpsStatusText.innerHTML = 'GPS tidak aktif. Geser pin merah di peta untuk menentukan lokasi.';
                    lucide.createIcons();
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        } else {
            gpsStatusBox.className = 'location-status-box error';
            gpsStatusIcon.className = 'text-red';
            gpsStatusIcon.setAttribute('data-lucide', 'alert-circle');
            gpsStatusText.innerHTML = 'Browser tidak mendukung GPS. Geser pin merah di peta untuk menentukan lokasi.';
            lucide.createIcons();
        }
    });
</script>
@endsection
