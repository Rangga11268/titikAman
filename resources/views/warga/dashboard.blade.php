@extends('layouts.app')

@section('title', 'Portal Warga - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo-bg">
                <img class="brand-logo-img" src="{{ asset('assets/logo-titikaman.png') }}" alt="Logo TitikAman" onerror="this.src='https://placehold.co/44x44/f3f3f3/006a60?text=TA'">
            </div>
            <div class="brand-text">
                <span class="brand-title">TitikAman</span>
                <span class="brand-subtitle">Portal Warga</span>
            </div>
        </div>

        <div class="user-profile-section">
            <div class="user-avatar">
                <i data-lucide="user" style="width: 20px; height: 20px; color: var(--color-brand-teal);"></i>
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->fullname }}</span>
                <span class="user-role-badge">Warga</span>
                <span class="user-domicile">
                    <i data-lucide="map-pin" style="width: 10px; height: 10px;"></i>
                    {{ auth()->user()->kelurahan }}, {{ auth()->user()->kecamatan }}
                </span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('warga.dashboard') }}" class="nav-item active">
                <i data-lucide="map"></i>
                <span>Peta Genangan & Posko</span>
            </a>
            <a href="{{ route('warga.lapor') }}" class="nav-item">
                <i data-lucide="file-plus"></i>
                <span>Lapor Genangan Banjir</span>
            </a>
            <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i data-lucide="log-out" class="text-red"></i>
                <span class="text-red">Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>

        <!-- Legend Card -->
        <div class="sidebar-card">
            <h4 class="card-title">Legenda Peta</h4>
            <div class="legend-list">
                <div class="legend-item">
                    <span class="legend-color marker-shelter"></span>
                    <span>Posko Pengungsian Aktif</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color marker-level-low"></span>
                    <span>Genangan Ringan (<50 cm)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color marker-level-medium"></span>
                    <span>Genangan Sedang (50 - 100 cm)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color marker-level-high"></span>
                    <span>Genangan Parah (>100 cm)</span>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="sidebar-card info-card-bg">
            <h4 class="card-title" style="color: var(--color-white); display: flex; align-items: center; gap: 8px;">
                <i data-lucide="info" style="width: 16px; height: 16px;"></i>
                Status Darurat
            </h4>
            <p style="font-size: 12px; color: var(--color-text-blue-muted); line-height: 1.5; margin-top: 8px;">
                Jika Anda terjebak banjir dan membutuhkan pertolongan evakuasi segera, klik tombol **SOS merah** di sudut kanan bawah peta.
            </p>
        </div>
    </div>

    <!-- Toggle Sidebar (Mobile) -->
    <button class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i data-lucide="menu" id="toggleIcon"></i>
    </button>

    <!-- Main Map Area -->
    <div class="map-area-container">
        <!-- Success Alert (Redirected Flash) -->
        @if (session('success'))
            <div class="dashboard-toast" id="successToast">
                <i data-lucide="check-circle" class="text-teal" style="width: 20px; height: 20px;"></i>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('successToast').style.display='none'" class="toast-close">
                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        @endif

        <!-- Map Container -->
        <div id="main-map"></div>

        <!-- Floating SOS Button -->
        <button type="button" class="btn-sos-floating" id="openSosBtn" title="Kirim Sinyal SOS Darurat">
            <span>SOS</span>
            <div class="sos-pulse-ring"></div>
        </button>
    </div>
</div>

<!-- SOS Emergency Modal -->
<div class="modal-overlay" id="sosModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-title">
                <div class="alert-icon-circle">
                    <i data-lucide="alert-triangle" class="text-red"></i>
                </div>
                <div>
                    <h3 class="modal-title">Kirim Sinyal Darurat SOS</h3>
                    <p class="modal-subtitle">Data lokasi GPS Anda akan otomatis dikirim ke Tim Relawan SAR.</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" id="closeSosBtn">&times;</button>
        </div>

        <div class="modal-body">
            <!-- Location Detection Status -->
            <div class="location-status-box" id="gpsStatusBox">
                <i data-lucide="loader-2" class="animate-spin text-teal" id="gpsStatusIcon"></i>
                <span id="gpsStatusText">Mendeteksi lokasi koordinat GPS Anda...</span>
            </div>

            <!-- Form -->
            <form id="sosForm" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="sos_latitude">
                <input type="hidden" name="longitude" id="sos_longitude">

                <!-- People Trapped -->
                <div class="form-group">
                    <label for="people_trapped" class="form-label">Banyak Orang Terjebak di Lokasi *</label>
                    <div class="input-wrapper">
                        <i data-lucide="users"></i>
                        <input type="number" 
                               id="people_trapped" 
                               name="people_trapped" 
                               class="form-input" 
                               placeholder="Jumlah orang (balita, dewasa, lansia)" 
                               min="1" 
                               value="1" 
                               required>
                    </div>
                </div>

                <!-- Vulnerable Groups -->
                <div class="form-group">
                    <label for="vulnerable_groups_count" class="form-label">Banyak Kelompok Rentan *</label>
                    <div class="input-wrapper">
                        <i data-lucide="heart"></i>
                        <input type="number" 
                               id="vulnerable_groups_count" 
                               name="vulnerable_groups_count" 
                               class="form-input" 
                               placeholder="Jumlah Lansia / Balita / Ibu Hamil" 
                               min="0" 
                               value="0" 
                               required>
                    </div>
                    <span style="font-size: 11px; color: var(--color-text-muted);">
                        *Kelompok rentan meliputi Lansia (>60 thn), Balita (<5 thn), Ibu Hamil, dan Difabel.
                    </span>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi Keadaan / Kebutuhan Darurat</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-input" 
                              placeholder="Contoh: Butuh perahu karet evakuasi, ada lansia sakit stroke di lantai 2" 
                              rows="3" 
                              style="height: auto; padding: 12px; font-family: var(--font-body); resize: none;"></textarea>
                </div>

                <!-- Action Button -->
                <button type="submit" class="btn btn-primary btn-sos-submit" id="submitSosBtn" disabled>
                    <i data-lucide="alert-circle"></i>
                    <span>Kirim Sinyal SOS Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. SIDEBAR TOGGLE FOR MOBILE ---
        const sidebarToggle = document.getElementById('sidebarToggle');
        const dashboardSidebar = document.querySelector('.dashboard-sidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                dashboardSidebar.classList.toggle('active');
                if (dashboardSidebar.classList.contains('active')) {
                    toggleIcon.setAttribute('data-lucide', 'x');
                } else {
                    toggleIcon.setAttribute('data-lucide', 'menu');
                }
                lucide.createIcons();
            });
        }

        // --- 2. LEAFLET MAP INITIALIZATION ---
        // Default center: Bekasi City (-6.2383, 106.9922)
        const defaultLat = -6.2383;
        const defaultLng = 106.9922;
        const map = L.map('main-map').setView([defaultLat, defaultLng], 13);

        // Add CartoDB Voyager Basemap
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // --- 3. CUSTOM MARKERS CREATION ---
        // Custom SVG icons to make the interface premium
        const shelterIcon = L.divIcon({
            html: `<div class="map-marker-custom marker-shelter-pulse">
                     <i data-lucide="home" style="width:16px; height:16px; color:#ffffff;"></i>
                   </div>`,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        function getFloodIcon(height) {
            let colorClass = 'marker-level-low';
            if (height > 100) {
                colorClass = 'marker-level-high';
            } else if (height >= 50) {
                colorClass = 'marker-level-medium';
            }

            return L.divIcon({
                html: `<div class="map-marker-custom ${colorClass}">
                         <i data-lucide="waves" style="width:14px; height:14px; color:#ffffff;"></i>
                       </div>`,
                className: '',
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
        }

        // --- 4. RENDER DATA MARKERS ---
        // Active Shelters Data
        const shelters = @json($shelters);
        shelters.forEach(function (shelter) {
            const popupContent = `
                <div class="map-popup-card">
                    <span class="popup-tag tag-shelter">POSKO PENGUNGSIAN</span>
                    <h4 class="popup-title">${shelter.shelter_name}</h4>
                    <p class="popup-desc"><i data-lucide="map-pin" style="width: 12px; height: 12px; display:inline-block; margin-right:4px;"></i>${shelter.address}</p>
                    <div class="popup-info-grid">
                        <div>
                            <span class="info-label">Kapasitas Maks</span>
                            <span class="info-value">${shelter.max_capacity} Jiwa</span>
                        </div>
                        <div>
                            <span class="info-label">Pengungsi Aktif</span>
                            <span class="info-value">${shelter.current_occupants} Jiwa</span>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <span class="popup-pill pill-${shelter.status === 'active' ? 'green' : 'red'}">${shelter.status === 'active' ? 'Aktif' : 'Penuh'}</span>
                        <span class="popup-pill pill-gray">${shelter.has_toilet_facilities === 'Yes' ? 'Toilet: Tersedia' : 'Toilet: Tidak Ada'}</span>
                    </div>
                </div>
            `;
            L.marker([shelter.latitude, shelter.longitude], { icon: shelterIcon })
                .addTo(map)
                .bindPopup(popupContent);
        });

        // Verified Flood Reports Data
        const reports = @json($reports);
        reports.forEach(function (report) {
            const date = new Date(report.created_at).toLocaleDateString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const popupContent = `
                <div class="map-popup-card">
                    <span class="popup-tag tag-flood">LAPORAN GENANGAN</span>
                    <h4 class="popup-title">${report.street_name}</h4>
                    <p class="popup-desc">Tinggi air terpantau oleh warga sekitar.</p>
                    <div class="popup-info-grid">
                        <div>
                            <span class="info-label">Tinggi Air</span>
                            <span class="info-value font-red">${report.water_height_cm} cm</span>
                        </div>
                        <div>
                            <span class="info-label">Dilaporkan</span>
                            <span class="info-value">${date}</span>
                        </div>
                    </div>
                    ${report.photo_evidence ? `
                        <div class="popup-image-container">
                            <img src="/storage/${report.photo_evidence}" alt="Bukti Genangan" class="popup-image">
                        </div>
                    ` : ''}
                </div>
            `;

            L.marker([report.latitude, report.longitude], { icon: getFloodIcon(report.water_height_cm) })
                .addTo(map)
                .bindPopup(popupContent);
        });

        // Add Lucide Icons inside popups when they open
        map.on('popupopen', function () {
            lucide.createIcons();
        });

        // --- 5. SOS MODAL & GEOLOCATION ---
        const sosModal = document.getElementById('sosModal');
        const openSosBtn = document.getElementById('openSosBtn');
        const closeSosBtn = document.getElementById('closeSosBtn');
        const gpsStatusBox = document.getElementById('gpsStatusBox');
        const gpsStatusIcon = document.getElementById('gpsStatusIcon');
        const gpsStatusText = document.getElementById('gpsStatusText');
        const submitSosBtn = document.getElementById('submitSosBtn');
        
        const latInput = document.getElementById('sos_latitude');
        const lngInput = document.getElementById('sos_longitude');

        let userCoordinates = null;

        // Open Modal & Start GPS
        openSosBtn.addEventListener('click', function () {
            sosModal.classList.add('active');
            detectUserLocation();
        });

        // Close Modal
        closeSosBtn.addEventListener('click', function () {
            sosModal.classList.remove('active');
        });

        // Detect Location Function
        function detectUserLocation() {
            gpsStatusBox.className = 'location-status-box';
            gpsStatusIcon.className = 'animate-spin text-teal';
            gpsStatusIcon.setAttribute('data-lucide', 'loader-2');
            gpsStatusText.innerText = 'Mendeteksi lokasi koordinat GPS Anda...';
            submitSosBtn.disabled = true;
            lucide.createIcons();

            if (!navigator.geolocation) {
                gpsStatusBox.classList.add('error');
                gpsStatusIcon.className = 'text-red';
                gpsStatusIcon.setAttribute('data-lucide', 'x-circle');
                gpsStatusText.innerText = 'Browser Anda tidak mendukung deteksi lokasi (Geolocation).';
                lucide.createIcons();
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    userCoordinates = { lat, lng };

                    // Save coordinates to hidden inputs
                    latInput.value = lat;
                    lngInput.value = lng;

                    // Update Status UI
                    gpsStatusBox.classList.add('success');
                    gpsStatusIcon.className = 'text-teal';
                    gpsStatusIcon.setAttribute('data-lucide', 'check-circle');
                    gpsStatusText.innerHTML = `Lokasi diperoleh: <strong>${lat.toFixed(5)}, ${lng.toFixed(5)}</strong>`;
                    submitSosBtn.disabled = false;
                    lucide.createIcons();

                    // Pan Map to User's location (optional visual helper)
                    map.panTo([lat, lng]);
                },
                function (error) {
                    gpsStatusBox.classList.add('error');
                    gpsStatusIcon.className = 'text-red';
                    gpsStatusIcon.setAttribute('data-lucide', 'x-circle');
                    lucide.createIcons();

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            gpsStatusText.innerText = 'Izin akses GPS ditolak. Silakan izinkan lokasi pada browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            gpsStatusText.innerText = 'Informasi lokasi tidak tersedia. Coba hidupkan GPS Anda.';
                            break;
                        case error.TIMEOUT:
                            gpsStatusText.innerText = 'Waktu deteksi GPS habis. Coba ulangi kembali.';
                            break;
                        default:
                            gpsStatusText.innerText = 'Terjadi kesalahan saat mendeteksi lokasi.';
                    }
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        // Handle Form Submission via AJAX/fetch
        const sosForm = document.getElementById('sosForm');
        sosForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Disable submit button and show loading state
            submitSosBtn.disabled = true;
            const originalBtnContent = submitSosBtn.innerHTML;
            submitSosBtn.innerHTML = `<i data-lucide="loader-2" class="animate-spin"></i> <span>Mengirim SOS...</span>`;
            lucide.createIcons();

            const formData = new FormData(sosForm);

            fetch('{{ route("warga.sos.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.status === 'success') {
                    // Show success state in modal
                    gpsStatusBox.className = 'location-status-box success';
                    gpsStatusIcon.className = 'text-teal';
                    gpsStatusIcon.setAttribute('data-lucide', 'check');
                    gpsStatusText.innerText = res.body.message;
                    lucide.createIcons();

                    // Clear and close after 3 seconds
                    setTimeout(function () {
                        sosModal.classList.remove('active');
                        sosForm.reset();
                    }, 3500);
                } else {
                    // Show error
                    gpsStatusBox.className = 'location-status-box error';
                    gpsStatusIcon.className = 'text-red';
                    gpsStatusIcon.setAttribute('data-lucide', 'x-circle');
                    gpsStatusText.innerText = res.body.message || 'Gagal mengirim sinyal SOS.';
                    lucide.createIcons();
                    submitSosBtn.disabled = false;
                    submitSosBtn.innerHTML = originalBtnContent;
                }
            })
            .catch(error => {
                console.error('Error sending SOS:', error);
                gpsStatusBox.className = 'location-status-box error';
                gpsStatusIcon.className = 'text-red';
                gpsStatusIcon.setAttribute('data-lucide', 'x-circle');
                gpsStatusText.innerText = 'Koneksi internet bermasalah. Gagal menghubungi server.';
                lucide.createIcons();
                submitSosBtn.disabled = false;
                submitSosBtn.innerHTML = originalBtnContent;
            });
        });
    });
</script>
@endsection
