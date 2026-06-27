@extends('layouts.dashboard')

@section('title', 'SOS Darurat - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/warga-sos.css') }}">
@endsection

@section('topbar-left')
    <h1 class="sos-header-title">Darurat SOS</h1>
@endsection

@section('dashboard-content')
    <!-- Content Wrapper -->
    <div class="sos-content-wrapper" style="padding-top: 0;">
        <div class="sos-header" style="display: none;">
            <h1 class="sos-header-title">Darurat SOS</h1>
            <div class="sos-header-status">
                <div class="status-badge-red" style="background-color: #fff2f2; border: 1px solid #ffdad7; padding: 6px 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px;">
                    <span class="status-dot-red" style="width: 8px; height: 8px; border-radius: 50%; background-color: #e63946; display: inline-block;"></span>
                    <span style="color: #b61722; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">Mode Darurat Aktif — Bekasi SIAGA 2</span>
                </div>
            </div>
        </div>

        <div class="sos-main-layout">
            <!-- Left Column: GPS Map & Warnings -->
            <div class="sos-left-col">
                <div class="sos-aside-card">
                    <div class="sos-aside-card-header">
                        <i data-lucide="map-pin" style="width: 16px; height: 16px; color: var(--color-brand-teal);"></i>
                        <span>LOKASI KAMU TERDETEKSI</span>
                    </div>
                    <div class="sos-aside-card-body">
                        <div id="mini-map-sos"></div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 11px; font-weight: 600; color: var(--color-text-muted); display: block; margin-bottom: 4px; text-transform: uppercase;">Alamat:</span>
                            <span id="sos-address-text" style="font-size: 14px; font-weight: 700; color: var(--color-neutral-dark); line-height: 1.4; display: block;">
                                RT {{ auth()->user()->kelurahan ? '03/RW 07, ' . auth()->user()->kelurahan : 'Deteksi Lokasi...' }}, {{ auth()->user()->kecamatan }}, Kota Bekasi
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <i data-lucide="target" style="width: 14px; height: 14px; color: #00a472;"></i>
                            <span id="sos-accuracy-text" style="font-size: 12px; font-weight: 600; color: #00a472;">Akurasi GPS: ±8 meter</span>
                        </div>
                        <button type="button" class="btn" id="btnUpdateGps" style="width: 100%; border: 1.5px solid var(--color-border); background: var(--color-white); color: var(--color-neutral-dark); font-weight: 700; padding: 10px; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; cursor: pointer;">
                            <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                            <span>Perbarui Lokasi GPS</span>
                        </button>
                    </div>
                </div>

                <div class="sos-warning-box">
                    <i data-lucide="alert-octagon" style="width: 20px; height: 20px; color: #e63946; flex-shrink: 0;"></i>
                    <span class="sos-warning-text">Pastikan lokasi di atas adalah posisimu saat ini sebelum mengirim SOS.</span>
                </div>
            </div>

            <!-- Center Column: SOS Request Form -->
            <div class="sos-center-col">
                <div class="sos-form-card">
                    <div class="sos-form-banner">
                        <i data-lucide="shield-alert" style="width: 18px; height: 18px;"></i>
                        <span>KIRIM PERMINTAAN EVAKUASI DARURAT</span>
                    </div>
                    
                    <div class="sos-form-body">
                        <!-- SOS Circular Button Trigger -->
                        <div class="sos-trigger-container">
                            <svg class="progress-ring-svg">
                                <circle class="progress-ring-circle-bg" cx="70" cy="70" r="60"></circle>
                                <circle class="progress-ring-circle" id="progressRing" cx="70" cy="70" r="60"></circle>
                            </svg>
                            <button type="button" class="sos-circular-btn {{ $activeSos ? 'active' : '' }}" id="sosTriggerBtn" {{ $activeSos ? 'disabled' : '' }}>
                                @if ($activeSos)
                                    <span style="font-size: 20px; font-weight: 800; line-height: 1;">SOS</span>
                                    <span style="font-size: 11px; font-weight: 700; margin-top: 4px; opacity: 0.9; letter-spacing: 1px; line-height: 1;">AKTIF</span>
                                @else
                                    <span>SOS</span>
                                @endif
                            </button>
                            <div class="sos-pulse-ring-active" id="pulseRing" style="{{ $activeSos ? '' : 'display: none;' }}"></div>
                        </div>
                        
                        <div class="sos-hold-instruction" id="holdInstruction">
                            @if ($activeSos)
                                <span style="color: #b61722; font-weight: 700;">Sinyal SOS Sedang Aktif</span>
                            @else
                                Tekan & tahan 2 detik untuk mengaktifkan
                            @endif
                        </div>

                        <!-- Actual Form -->
                        <form id="formSosRequest" style="width: 100%; margin-top: 12px;">
                            @csrf
                            <input type="hidden" name="latitude" id="input_lat">
                            <input type="hidden" name="longitude" id="input_lng">
                            <input type="hidden" name="people_trapped" id="input_people" value="1">
                            <input type="hidden" name="vulnerable_groups_count" id="input_vulnerable" value="0">

                            <!-- Trapped Count Selector -->
                            <div class="sos-form-section">
                                <div class="sos-trapped-box">
                                    <div class="sos-trapped-label">
                                        Berapa orang yang butuh<br>bantuan?
                                    </div>
                                    <div class="counter-control-group">
                                        <button type="button" class="btn-counter" id="btnDecPeople" {{ $activeSos ? 'disabled' : '' }}>-</button>
                                        <span class="counter-val" id="displayPeople">1</span>
                                        <button type="button" class="btn-counter" id="btnIncPeople" {{ $activeSos ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Vulnerable Groups Grid Selection -->
                            <div class="sos-form-section">
                                <div class="sos-section-title">Ada kelompok rentan?</div>
                                <div class="vulnerable-grid">
                                    <button type="button" class="vulnerable-btn" data-vulnerable="lansia" {{ $activeSos ? 'disabled' : '' }}>
                                        <span class="vulnerable-emoji">👴</span>
                                        <span class="vulnerable-name">Lansia</span>
                                    </button>
                                    <button type="button" class="vulnerable-btn" data-vulnerable="bumil" {{ $activeSos ? 'disabled' : '' }}>
                                        <span class="vulnerable-emoji">🤰</span>
                                        <span class="vulnerable-name">Ibu Hamil</span>
                                    </button>
                                    <button type="button" class="vulnerable-btn" data-vulnerable="balita" {{ $activeSos ? 'disabled' : '' }}>
                                        <span class="vulnerable-emoji">👶</span>
                                        <span class="vulnerable-name">Balita</span>
                                    </button>
                                    <button type="button" class="vulnerable-btn" data-vulnerable="disabilitas" {{ $activeSos ? 'disabled' : '' }}>
                                        <span class="vulnerable-emoji">♿</span>
                                        <span class="vulnerable-name">Disabilitas</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Description Textarea -->
                            <div class="sos-form-section">
                                <div class="sos-section-title">Keterangan tambahan (opsional)</div>
                                <textarea name="description" class="sos-textarea" id="input_description" placeholder="Contoh: Terjebak di lantai 2, ada lansia sakit stroke..." {{ $activeSos ? 'disabled' : '' }}></textarea>
                            </div>

                            <!-- Submit Backup Button -->
                            <button type="submit" class="btn btn-primary" id="btnSosSubmitBackup" style="width: 100%; display: none;" {{ $activeSos ? 'disabled' : '' }}>
                                Kirim SOS Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status Tracker & Emergency Contacts -->
            <div class="sos-right-col">
                <!-- Status Tracking Card -->
                <div class="sos-aside-card">
                    <div class="sos-aside-card-header">
                        <i data-lucide="radio" style="width: 16px; height: 16px; color: var(--color-brand-teal);"></i>
                        <span>STATUS PERMINTAAN SOS</span>
                    </div>
                    <div class="sos-aside-card-body">
                        <div class="sos-timeline">
                            <div class="sos-timeline-line"></div>
                            
                            <!-- Step 1: Terkirim -->
                            <div class="sos-timeline-item {{ $activeSos ? 'completed' : 'inactive' }}" id="timeline-step-1">
                                <div class="sos-timeline-node">
                                    <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                                </div>
                                <div class="sos-timeline-title">Terkirim</div>
                                <div class="sos-timeline-desc" id="step-1-desc">
                                    @if ($activeSos)
                                        SOS dikirim pada {{ $activeSos->created_at->format('H:i') }} WIB.<br>
                                        {{ $activeSos->people_trapped }} orang | {{ $activeSos->vulnerable_groups_count }} rentan.<br>
                                        <strong>Prioritas: <span style="color: #e63946;">{{ strtoupper($activeSos->priority_level) }}</span></strong>
                                    @else
                                        Sinyal darurat belum dikirim
                                    @endif
                                </div>
                            </div>

                            <!-- Step 2: Mencari Relawan -->
                            <div class="sos-timeline-item {{ $activeSos && $activeSos->status == 'waiting' ? 'active' : ($activeSos && $activeSos->status != 'waiting' ? 'completed' : 'inactive') }}" id="timeline-step-2">
                                <div class="sos-timeline-node">
                                    <i data-lucide="search" style="width: 12px; height: 12px;"></i>
                                </div>
                                <div class="sos-timeline-title">Mencari Relawan</div>
                                <div class="sos-timeline-desc" id="step-2-desc">
                                    @if ($activeSos && $activeSos->status == 'waiting')
                                        Sistem sedang mencocokkan relawan terdekat...
                                    @elseif ($activeSos && $activeSos->status != 'waiting')
                                        Relawan terdekat ditemukan!
                                    @else
                                        Menunggu pengaktifan SOS
                                    @endif
                                </div>
                            </div>

                            <!-- Step 3: Relawan Ditugaskan -->
                            <div class="sos-timeline-item {{ $activeSos && $activeSos->status == 'assigned' ? 'active' : 'inactive' }}" id="timeline-step-3">
                                <div class="sos-timeline-node">
                                    <i data-lucide="user-check" style="width: 12px; height: 12px;"></i>
                                </div>
                                <div class="sos-timeline-title">Relawan Ditugaskan</div>
                                <div class="sos-timeline-desc" id="step-3-desc">
                                    @if ($activeSos && $activeSos->status == 'assigned' && $activeSos->rescueMission && $activeSos->rescueMission->volunteer)
                                        Relawan: <strong>{{ $activeSos->rescueMission->volunteer->fullname }}</strong> telah ditugaskan ke lokasi Anda.
                                    @elseif ($activeSos && $activeSos->status == 'assigned')
                                        Relawan SAR telah ditugaskan ke lokasi Anda.
                                    @else
                                        Belum ada penugasan relawan
                                    @endif
                                </div>
                            </div>

                            <!-- Step 4: Dalam Perjalanan -->
                            <div class="sos-timeline-item {{ $activeSos && $activeSos->status == 'assigned' ? 'active' : 'inactive' }}" id="timeline-step-4">
                                <div class="sos-timeline-node">
                                    <i data-lucide="navigation" style="width: 12px; height: 12px;"></i>
                                </div>
                                <div class="sos-timeline-title">Dalam Perjalanan</div>
                                <div class="sos-timeline-desc">
                                    @if ($activeSos && $activeSos->status == 'assigned')
                                        Relawan sedang membawa perlengkapan evakuasi menuju titik Anda.
                                    @else
                                        Menunggu konfirmasi perjalanan
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="sos-est-box">
                            <i data-lucide="hourglass" style="width: 18px; height: 18px; color: var(--color-brand-teal); flex-shrink: 0; margin-top: 2px;"></i>
                            <div>
                                <div class="sos-est-title">Estimasi respons: 8-15 mnt</div>
                                <div class="sos-est-desc">Tetap tenang. Naik ke lantai tertinggi jika air terus naik di dalam rumah Anda.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contacts Card -->
                <div class="sos-aside-card">
                    <div class="sos-aside-card-header">
                        <i data-lucide="phone-call" style="width: 16px; height: 16px; color: var(--color-brand-teal);"></i>
                        <span>KONTAK DARURAT</span>
                    </div>
                    <div class="sos-aside-card-body" style="padding-top: 8px; padding-bottom: 16px;">
                        <div class="contact-item">
                            <span class="contact-name">BPBD Bekasi</span>
                            <span class="contact-phone">112</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-name">Basarnas</span>
                            <span class="contact-phone">115</span>
                        </div>
                        <div class="contact-item">
                            <span class="contact-name">PMI Bekasi</span>
                            <span class="contact-phone">021-8895000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast for feedback -->
<div id="toastSuccess" class="sos-toast" style="display: none;">
    <i data-lucide="check-circle" style="color: #00a472; width: 24px; height: 24px;"></i>
    <div style="font-size: 13px; font-weight: 500; color: var(--color-neutral-dark);" id="toastMessage">
        Sinyal SOS Berhasil Dikirim!
    </div>
</div>
@endsection

@section('dashboard-scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {        // --- 2. Leaflet GPS Mini Map ---
        const defaultLat = -6.2383;
        const defaultLng = 106.9922;
        let currentLat = defaultLat;
        let currentLng = defaultLng;
        
        const map = L.map('mini-map-sos', {
            zoomControl: false,
            dragging: false,
            tap: false,
            doubleClickZoom: false,
            scrollWheelZoom: false
        }).setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 20
        }).addTo(map);

        // Marker with warning pulse style
        const markerIcon = L.divIcon({
            html: `<div class="map-marker-custom marker-level-high marker-shelter-pulse" style="background-color: #e63946; width: 16px; height: 16px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.4);"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });

        let marker = L.marker([defaultLat, defaultLng], { icon: markerIcon }).addTo(map);

        // Geolocation Retrieval
        function fetchLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLat = position.coords.latitude;
                        currentLng = position.coords.longitude;
                        const accuracy = Math.round(position.coords.accuracy);

                        // Set values to form
                        document.getElementById('input_lat').value = currentLat;
                        document.getElementById('input_lng').value = currentLng;

                        // Move map and marker
                        map.setView([currentLat, currentLng], 15);
                        marker.setLatLng([currentLat, currentLng]);

                        // Update text
                        document.getElementById('sos-accuracy-text').innerText = `Akurasi GPS: ±${accuracy} meter`;

                        @if ($activeSos)
                        // Automatically update SOS location on the server
                        fetch("{{ route('warga.sos.update-location') }}", {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                latitude: currentLat,
                                longitude: currentLng
                            })
                        }).then(res => res.json()).then(data => {
                            if(data.status === 'success') {
                                console.log('Lokasi SOS berhasil diperbarui ke server SAR.');
                            }
                        }).catch(err => console.error('Gagal update lokasi', err));
                        @endif

                        // Reverse Geocode using OSM Nominatim API
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${currentLat}&lon=${currentLng}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    const addressParts = data.address;
                                    let formattedAddress = '';
                                    if (addressParts.road) formattedAddress += addressParts.road + ', ';
                                    if (addressParts.neighbourhood) formattedAddress += addressParts.neighbourhood + ', ';
                                    if (addressParts.suburb) formattedAddress += addressParts.suburb + ', ';
                                    if (addressParts.city || addressParts.town) formattedAddress += (addressParts.city || addressParts.town);
                                    
                                    document.getElementById('sos-address-text').innerText = formattedAddress || data.display_name;
                                }
                            })
                            .catch(err => {
                                console.log('Reverse geocoding error: ', err);
                            });
                    },
                    function(error) {
                        console.warn('Geolocation failed: ', error.message);
                        // Fall back to default center
                        document.getElementById('input_lat').value = defaultLat;
                        document.getElementById('input_lng').value = defaultLng;
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                // Browser doesn't support geolocation
                document.getElementById('input_lat').value = defaultLat;
                document.getElementById('input_lng').value = defaultLng;
            }
        }

        // Initialize location
        fetchLocation();

        // Bind GPS Refresh button
        document.getElementById('btnUpdateGps').addEventListener('click', function() {
            const btnIcon = this.querySelector('i');
            btnIcon.classList.add('animate-spin');
            fetchLocation();
            setTimeout(() => {
                btnIcon.classList.remove('animate-spin');
            }, 1000);
        });

        // --- 3. People Trapped Counter ---
        let trappedCount = 1;
        const displayPeople = document.getElementById('displayPeople');
        const inputPeople = document.getElementById('input_people');

        document.getElementById('btnIncPeople').addEventListener('click', function() {
            trappedCount++;
            displayPeople.innerText = trappedCount;
            inputPeople.value = trappedCount;
        });

        document.getElementById('btnDecPeople').addEventListener('click', function() {
            if (trappedCount > 1) {
                trappedCount--;
                displayPeople.innerText = trappedCount;
                inputPeople.value = trappedCount;
            }
        });

        // --- 4. Vulnerable Groups Toggles ---
        const vulnerableButtons = document.querySelectorAll('.vulnerable-btn');
        const inputVulnerable = document.getElementById('input_vulnerable');
        let activeVulnerables = new Set();

        vulnerableButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.getAttribute('data-vulnerable');
                this.classList.toggle('active');
                
                if (this.classList.contains('active')) {
                    activeVulnerables.add(group);
                } else {
                    activeVulnerables.delete(group);
                }
                
                // Update hidden vulnerable count
                inputVulnerable.value = activeVulnerables.size;
            });
        });

        // --- 5. Press and Hold 2-Second Trigger for SOS Button ---
        const sosTriggerBtn = document.getElementById('sosTriggerBtn');
        const progressRing = document.getElementById('progressRing');
        const holdInstruction = document.getElementById('holdInstruction');
        
        let holdTimer = null;
        let holdDuration = 2000; // 2 seconds
        let startHoldTime = 0;
        let animationFrame = null;
        
        // Dash array is 377 (circumference of radius 60 circle)
        const circumference = 377;
        progressRing.style.strokeDashoffset = circumference;

        function updateProgress() {
            if (startHoldTime === 0) return;
            const elapsed = Date.now() - startHoldTime;
            const progress = Math.min(elapsed / holdDuration, 1);
            
            // Calculate new stroke dash offset
            progressRing.style.strokeDashoffset = circumference - (progress * circumference);
            
            if (progress < 1) {
                animationFrame = requestAnimationFrame(updateProgress);
            } else {
                triggerSosSubmission();
            }
        }

        function startHold(e) {
            if (sosTriggerBtn.disabled) return;
            e.preventDefault();
            
            startHoldTime = Date.now();
            holdInstruction.innerText = "Tahan tombol...";
            holdInstruction.style.color = "#e63946";
            holdInstruction.style.fontWeight = "700";
            
            animationFrame = requestAnimationFrame(updateProgress);
            
            // Mouseup / touchend events to cancel
            window.addEventListener('mouseup', cancelHold);
            window.addEventListener('touchend', cancelHold);
        }

        function cancelHold() {
            startHoldTime = 0;
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
                animationFrame = null;
            }
            
            // Reset circular progress ring
            progressRing.style.strokeDashoffset = circumference;
            
            if (!sosTriggerBtn.disabled) {
                holdInstruction.innerText = "Tekan & tahan 2 detik untuk mengaktifkan";
                holdInstruction.style.color = "var(--color-text-muted)";
                holdInstruction.style.fontWeight = "500";
            }
            
            window.removeEventListener('mouseup', cancelHold);
            window.removeEventListener('touchend', cancelHold);
        }

        sosTriggerBtn.addEventListener('mousedown', startHold);
        sosTriggerBtn.addEventListener('touchstart', startHold);

        // --- 6. Form Submission (SOS Action) ---
        function triggerSosSubmission() {
            cancelHold();
            
            // Form validation of coordinates
            const lat = document.getElementById('input_lat').value;
            const lng = document.getElementById('input_lng').value;
            
            if (!lat || !lng) {
                alert("Koordinat GPS belum terdeteksi. Silakan coba perbarui GPS Anda.");
                return;
            }

            // Submit via AJAX Fetch
            submitSosData();
        }

        function submitSosData() {
            // Disable trigger and selectors
            sosTriggerBtn.disabled = true;
            document.getElementById('pulseRing').style.display = 'none';
            document.getElementById('btnDecPeople').disabled = true;
            document.getElementById('btnIncPeople').disabled = true;
            document.getElementById('input_description').disabled = true;
            vulnerableButtons.forEach(b => b.disabled = true);
            holdInstruction.innerHTML = `<span style="color: #e63946; font-weight: 700;">Mengirim sinyal...</span>`;

            const formData = new FormData(document.getElementById('formSosRequest'));
            
            fetch("{{ route('warga.sos.submit') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success toast
                    const toast = document.getElementById('toastSuccess');
                    document.getElementById('toastMessage').innerText = data.message;
                    toast.style.display = 'flex';
                    
                    // Update button styling and contents to show active SOS state
                    sosTriggerBtn.classList.add('active');
                    sosTriggerBtn.innerHTML = `
                        <span style="font-size: 20px; font-weight: 800; line-height: 1;">SOS</span>
                        <span style="font-size: 11px; font-weight: 700; margin-top: 4px; opacity: 0.9; letter-spacing: 1px; line-height: 1;">AKTIF</span>
                    `;
                    
                    // Show pulse ring indicating transmitting state
                    document.getElementById('pulseRing').style.display = 'block';
                    
                    // Update Timeline status
                    holdInstruction.innerHTML = `<span style="color: #00a472; font-weight: 700;">Sinyal SOS Aktif</span>`;
                    
                    // Dynamically set Timeline
                    const now = new Date();
                    const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
                    
                    // Step 1: Terkirim
                    const step1 = document.getElementById('timeline-step-1');
                    step1.classList.remove('inactive');
                    step1.classList.add('completed');
                    document.getElementById('step-1-desc').innerHTML = `
                        SOS dikirim pada ${timeStr} WIB.<br>
                        ${trappedCount} orang | ${activeVulnerables.size} rentan.<br>
                        <strong>Prioritas: <span style="color: #e63946;">${activeVulnerables.size > 0 || trappedCount >= 5 ? 'TINGGI' : (trappedCount >= 3 ? 'SEDANG' : 'RENDAH')}</span></strong>
                    `;

                    // Step 2: Mencari Relawan
                    const step2 = document.getElementById('timeline-step-2');
                    step2.classList.remove('inactive');
                    step2.classList.add('active');
                    document.getElementById('step-2-desc').innerText = "Sistem sedang mencocokkan relawan terdekat...";

                    // Automatically hide toast after 5s
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 5000);
                } else {
                    alert("Gagal mengirim SOS. Silakan coba lagi.");
                    sosTriggerBtn.disabled = false;
                    document.getElementById('pulseRing').style.display = 'none';
                    holdInstruction.innerText = "Tekan & tahan 2 detik untuk mengaktifkan";
                }
            })
            .catch(err => {
                console.error("SOS Submit Error:", err);
                alert("Terjadi kesalahan koneksi saat mengirim SOS.");
                sosTriggerBtn.disabled = false;
                document.getElementById('pulseRing').style.display = 'none';
                holdInstruction.innerText = "Tekan & tahan 2 detik untuk mengaktifkan";
            });
        }
    });
</script>
@endsection
