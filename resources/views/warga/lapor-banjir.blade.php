@extends('layouts.dashboard')

@section('title', 'Lapor Genangan Banjir - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/warga-lapor-banjir.css') }}">
@endsection

@section('topbar-left')
    <h1 class="lapor-header-title">Form Laporan Banjir</h1>
@endsection

@section('dashboard-content')
    <!-- Main Content Area -->
    <div class="lapor-content-wrapper" style="padding-top: 0;">
        <!-- Top Navigation Header -->
        <div class="lapor-header" style="display: none;">
            <h1 class="lapor-header-title">Form Laporan Banjir</h1>
            <div class="lapor-header-status">
                <div class="status-badge-green">
                    <span class="status-dot-green"></span>
                    <span>Emergency Status: Normal</span>
                </div>
            </div>
        </div>

        <!-- 2 Columns Layout -->
        <div class="lapor-main-layout">
            <!-- Left Column (58%): The Form Card -->
            <div class="lapor-left-col">
                <div class="card-container" style="border-radius: 12px; margin-bottom: 0; max-width: 100%; width: 100%;">
                    <!-- Stepper Progress Bar -->
                    <div class="form-header" style="border-bottom: 1px solid rgba(196,198,207,0.2); padding-bottom: 16px; margin-bottom: 24px;">
                        <h2 class="form-title" style="font-family: var(--font-heading); font-size: 20px; font-weight: 700; color: var(--color-neutral-dark); margin-bottom: 16px;">
                            Laporkan Kondisi Banjir di Sekitarmu
                        </h2>
                        
                        <div class="stepper-list-horizontal">
                            <div class="step-item-horizontal active" id="step-tab-1">
                                <div class="step-circle">1</div>
                                <span class="step-title">Lokasi & Kondisi</span>
                            </div>
                            <div class="step-item-horizontal" id="step-tab-2">
                                <div class="step-circle">2</div>
                                <span class="step-title">Foto Bukti</span>
                            </div>
                            <div class="step-item-horizontal" id="step-tab-3">
                                <div class="step-circle">3</div>
                                <span class="step-title">Konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-body">
                        @if ($errors->any())
                            <div class="error-alert">
                                <ul style="list-style: none; margin: 0; padding: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="laporBanjirForm" action="{{ route('warga.lapor.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden Inputs for Coordinates -->
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', '-6.2383') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', '106.9922') }}">

                            <!-- ==========================================
                                 STEP 1: LOKASI & KONDISI
                                 ========================================== -->
                            <div class="step-content" id="step-content-1">
                                <!-- Kecamatan & Kelurahan -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="kecamatan" class="form-label">Kecamatan Kejadian *</label>
                                        <select id="kecamatan" name="kecamatan" class="form-select" required>
                                            <option value="" disabled selected>Pilih Kecamatan</option>
                                            <option value="Pondok Gede" {{ old('kecamatan') == 'Pondok Gede' ? 'selected' : '' }}>Pondok Gede</option>
                                            <option value="Jatiasih" {{ old('kecamatan') == 'Jatiasih' ? 'selected' : '' }}>Jatiasih</option>
                                            <option value="Bekasi Timur" {{ old('kecamatan') == 'Bekasi Timur' ? 'selected' : '' }}>Bekasi Timur</option>
                                            <option value="Bekasi Selatan" {{ old('kecamatan') == 'Bekasi Selatan' ? 'selected' : '' }}>Bekasi Selatan</option>
                                            <option value="Bekasi Barat" {{ old('kecamatan') == 'Bekasi Barat' ? 'selected' : '' }}>Bekasi Barat</option>
                                            <option value="Bekasi Utara" {{ old('kecamatan') == 'Bekasi Utara' ? 'selected' : '' }}>Bekasi Utara</option>
                                            <option value="Rawalumbu" {{ old('kecamatan') == 'Rawalumbu' ? 'selected' : '' }}>Rawalumbu</option>
                                            <option value="Mustikajaya" {{ old('kecamatan') == 'Mustikajaya' ? 'selected' : '' }}>Mustikajaya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kelurahan" class="form-label">Kelurahan Kejadian *</label>
                                        <select id="kelurahan" name="kelurahan" class="form-select" required>
                                            <option value="" disabled selected>Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Nama Jalan / Alamat Detail -->
                                <div class="form-group">
                                    <label for="street_name" class="form-label">Detail Nama Jalan / Lokasi *</label>
                                    <div class="input-wrapper">
                                        <i data-lucide="map-pin"></i>
                                        <input type="text" 
                                               id="street_name" 
                                               name="street_name" 
                                               class="form-input" 
                                               placeholder="Contoh: Jl. Kartini Raya RT 03/RW 04, Kel. Margahayu" 
                                               value="{{ old('street_name') }}" 
                                               required>
                                    </div>
                                </div>

                                <!-- Estimasi Tinggi Air (Slider) -->
                                <div class="slider-container">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <label class="form-label" style="margin: 0;">Estimasi Tinggi Air *</label>
                                        <span class="popup-tag tag-flood" id="heightBadge" style="background-color: rgba(46, 125, 50, 0.1); color: #2e7d32; font-weight: 700; border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Normal</span>
                                    </div>
                                    
                                    <div class="slider-value-display">
                                        <span class="slider-value-number" id="sliderVal">85</span>
                                        <span class="slider-value-unit">cm</span>
                                    </div>

                                    <input type="range" 
                                           id="water_height_cm" 
                                           name="water_height_cm" 
                                           class="slider-input" 
                                           min="5" 
                                           max="350" 
                                           value="{{ old('water_height_cm', 85) }}">
                                    
                                    <div class="slider-labels">
                                        <span>5 cm</span>
                                        <span>Lutut</span>
                                        <span>Pinggang</span>
                                        <span>Dada</span>
                                        <span>Kepala</span>
                                        <span>Atap</span>
                                        <span>350 cm</span>
                                    </div>
                                </div>

                                <!-- Status Akses Jalan (Selectable Cards) -->
                                <div class="form-group">
                                    <label class="form-label">Status Akses Jalan *</label>
                                    <input type="hidden" id="status_akses_jalan" name="status_akses_jalan" value="{{ old('status_akses_jalan', 'Masih Bisa Dilewati') }}">
                                    <div class="status-cards-grid">
                                        <div class="status-card selected" data-status="Masih Bisa Dilewati" id="status-card-1">
                                            <i data-lucide="check-circle"></i>
                                            <span class="status-card-title">Masih Bisa Dilewati</span>
                                        </div>
                                        <div class="status-card" data-status="Sulit Dilewati" id="status-card-2">
                                            <i data-lucide="alert-triangle"></i>
                                            <span class="status-card-title">Sulit Dilewati</span>
                                        </div>
                                        <div class="status-card" data-status="Tidak Bisa Dilewati" id="status-card-3">
                                            <i data-lucide="x-circle"></i>
                                            <span class="status-card-title">Tidak Bisa Dilewati</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kondisi Tambahan (Checkboxes 2x2) -->
                                <div class="form-group">
                                    <label class="form-label">Kondisi Tambahan</label>
                                    <div class="checkbox-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 4px;">
                                        <div class="checkbox-group" style="margin: 0;">
                                            <input type="checkbox" id="listrik_padam" name="listrik_padam" class="form-checkbox" value="1" {{ old('listrik_padam') ? 'checked' : '' }}>
                                            <label for="listrik_padam" class="checkbox-label">Listrik Padam</label>
                                        </div>
                                        <div class="checkbox-group" style="margin: 0;">
                                            <input type="checkbox" id="air_masih_naik" name="air_masih_naik" class="form-checkbox" value="1" {{ old('air_masih_naik') ? 'checked' : '' }}>
                                            <label for="air_masih_naik" class="checkbox-label">Air Masih Naik</label>
                                        </div>
                                        <div class="checkbox-group" style="margin: 0;">
                                            <input type="checkbox" id="butuh_evakuasi" name="butuh_evakuasi" class="form-checkbox" value="1" {{ old('butuh_evakuasi') ? 'checked' : '' }}>
                                            <label for="butuh_evakuasi" class="checkbox-label">Butuh Evakuasi</label>
                                        </div>
                                        <div class="checkbox-group" style="margin: 0;">
                                            <input type="checkbox" id="warga_terisolasi" name="warga_terisolasi" class="form-checkbox" value="1" {{ old('warga_terisolasi') ? 'checked' : '' }}>
                                            <label for="warga_terisolasi" class="checkbox-label">Warga Terisolasi</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keterangan Bebas -->
                                <div class="form-group">
                                    <label for="keterangan_bebas" class="form-label">Keterangan Tambahan / Detail</label>
                                    <textarea id="keterangan_bebas" 
                                              name="keterangan_bebas" 
                                              class="form-input" 
                                              placeholder="Tuliskan detail tambahan seperti jumlah rumah terdampak atau bantuan mendesak..." 
                                              rows="3" 
                                              style="height: auto; padding: 12px; font-family: var(--font-body); resize: none;">{{ old('keterangan_bebas') }}</textarea>
                                </div>

                                <div class="form-actions" style="margin-top: 32px;">
                                    <button type="button" class="btn btn-primary" id="btn-next-1" style="width: 100%;">
                                        <span>Lanjut ke Foto</span>
                                        <i data-lucide="arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ==========================================
                                 STEP 2: FOTO BUKTI
                                 ========================================== -->
                            <div class="step-content" id="step-content-2" style="display: none;">
                                <h3 class="form-subtitle" style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--color-neutral-dark); margin-bottom: 20px;">
                                    2. Unggah Foto Bukti Genangan
                                </h3>

                                <div class="form-group">
                                    <label class="form-label">Foto Bukti Genangan Banjir *</label>
                                    
                                    <!-- Custom drag and drop zone -->
                                    <div class="file-upload-dragdrop" id="uploadZone">
                                        <i data-lucide="camera" id="uploadIcon"></i>
                                        <span class="upload-text">Klik atau seret file gambar ke sini</span>
                                        <span class="upload-subtext">JPEG, PNG, JPG (Maksimal 5MB)</span>
                                        <input type="file" 
                                               id="photo_evidence" 
                                               name="photo_evidence" 
                                               accept="image/*" 
                                               style="display: none;" 
                                               required>
                                    </div>

                                    <!-- Preview container -->
                                    <div class="image-preview-container" id="previewContainer">
                                        <img src="" alt="Preview Bukti" class="image-preview-img" id="previewImg">
                                    </div>
                                </div>

                                <div class="form-actions" style="margin-top: 32px;">
                                    <button type="button" class="btn btn-secondary" id="btn-back-2">
                                        <i data-lucide="arrow-left"></i>
                                        <span>Kembali</span>
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btn-next-2" style="flex-grow: 1;">
                                        <span>Lanjut ke Konfirmasi</span>
                                        <i data-lucide="arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ==========================================
                                 STEP 3: KONFIRMASI
                                 ========================================== -->
                            <div class="step-content" id="step-content-3" style="display: none;">
                                <h3 class="form-subtitle" style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--color-neutral-dark); margin-bottom: 20px;">
                                    3. Tinjau & Kirim Laporan
                                </h3>

                                <!-- Summary card -->
                                <div class="review-summary-card">
                                    <div class="review-item">
                                        <span class="review-item-label">Lokasi Domisili</span>
                                        <span class="review-item-value" id="rev-lokasi">Pondok Gede, Jatiwaringin</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Alamat / Jalan</span>
                                        <span class="review-item-value" id="rev-jalan">Jl. Raya Jatiwaringin</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Koordinat Map</span>
                                        <span class="review-item-value" id="rev-koordinat">-6.23830, 106.99220</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Tinggi Genangan Air</span>
                                        <span class="review-item-value font-red" id="rev-tinggi">85 cm</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Akses Jalan</span>
                                        <span class="review-item-value" id="rev-akses">Masih Bisa Dilewati</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Kondisi Tambahan</span>
                                        <span class="review-item-value" id="rev-kondisi">-</span>
                                    </div>
                                    <div class="review-item">
                                        <span class="review-item-label">Catatan Tambahan</span>
                                        <span class="review-item-value" id="rev-catatan">-</span>
                                    </div>
                                </div>

                                <div class="form-actions" style="margin-top: 32px;">
                                    <button type="button" class="btn btn-secondary" id="btn-back-3">
                                        <i data-lucide="arrow-left"></i>
                                        <span>Kembali</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary" style="flex-grow: 1;">
                                        <i data-lucide="send"></i>
                                        <span>Kirim Laporan Resmi</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column (42%): Aside Panels -->
            <div class="lapor-right-col">
                <!-- Panel A: Preview Lokasi di Peta -->
                <div class="lapor-aside-card">
                    <div class="aside-card-header">
                        <span class="aside-card-title">Preview Lokasi di Peta</span>
                    </div>
                    <!-- Mini Map -->
                    <div id="mini-map" style="height: 192px; border-radius: 8px; border: 1px solid var(--color-border-muted); z-index: 10;"></div>
                    
                    <!-- Coords Info & Update GPS Button -->
                    <div class="location-status-box success" id="gpsStatusBox" style="margin: 0; border: 1px solid rgba(196,198,207,0.3); background-color: #e7f6ff; color: var(--color-neutral-dark); display: flex; justify-content: space-between; align-items: center; padding: 12px 16px;">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <i data-lucide="check-circle" class="text-teal" id="gpsStatusIcon" style="width: 18px; height: 18px;"></i>
                            <div style="display: flex; flex-direction: column;">
                                <span id="gpsStatusCoords" style="font-size: 13px; font-weight: 700; color: var(--color-neutral-dark);">-6.23830, 106.99220</span>
                                <span id="gpsStatusText" style="font-size: 11px; color: var(--color-text-muted);">Mendeteksi lokasi otomatis...</span>
                            </div>
                        </div>
                        <button type="button" id="btn-refresh-gps" style="background: none; border: none; font-size: 12px; font-weight: 700; color: var(--color-brand-teal); cursor: pointer; padding: 4px;">
                            Perbarui GPS
                        </button>
                    </div>
                </div>

                <!-- Panel B: Kondisi Terkini Area Sekitarmu -->
                <div class="lapor-aside-card">
                    <div class="aside-card-header">
                        <span class="aside-card-title">Kondisi Terkini Area Sekitarmu</span>
                    </div>
                    
                    <!-- Alert Peringatan -->
                    <div class="river-warning-alert">
                        <i data-lucide="alert-triangle"></i>
                        <div>
                            <strong>Peringatan: Air Kiriman</strong>
                            <p>Estimasi kenaikan debit air dari wilayah Bogor diperkirakan tiba dalam 2-3 jam ke depan.</p>
                        </div>
                    </div>

                    <!-- River List status -->
                    <div class="river-status-list">
                        <div class="river-status-item">
                            <span class="river-name">Sungai Cikeas</span>
                            <span class="river-badge badge-orange">SIAGA 2</span>
                        </div>
                        <div class="river-status-item">
                            <span class="river-name">Sungai Cileungsi</span>
                            <span class="river-badge badge-red">SIAGA 1</span>
                        </div>
                        <div class="river-status-item">
                            <span class="river-name">Kali Bekasi</span>
                            <span class="river-badge badge-green">NORMAL</span>
                        </div>
                    </div>
                </div>

                <!-- Panel C: Riwayat Laporanmu -->
                <div class="lapor-aside-card">
                    <div class="aside-card-header">
                        <span class="aside-card-title">Riwayat Laporanmu</span>
                    </div>
                    
                    <div class="history-list" style="max-height: 220px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; padding-right: 4px;">
                        @forelse($myReports as $rep)
                            <div class="history-item status-{{ $rep->verification_status }}">
                                <div class="history-item-content">
                                    <strong class="history-item-title">{{ $rep->street_name ? Str::limit($rep->street_name, 25) : 'Genangan Banjir' }}</strong>
                                    <span class="history-item-time">
                                        {{ $rep->water_height_cm }} cm • {{ $rep->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($rep->verification_status === 'verified')
                                        <span style="font-size: 10px; font-weight: 700; color: #2e7d32; background-color: rgba(46,125,50,0.1); padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">Terverifikasi</span>
                                        <i data-lucide="check-circle" class="text-green" style="color: #2e7d32; width: 16px; height: 16px;"></i>
                                    @elseif($rep->verification_status === 'rejected')
                                        <span style="font-size: 10px; font-weight: 700; color: #e63946; background-color: rgba(230,57,70,0.1); padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">Ditolak</span>
                                        <i data-lucide="x-circle" class="text-red" style="color: #e63946; width: 16px; height: 16px;"></i>
                                    @else
                                        <span style="font-size: 10px; font-weight: 700; color: #f4a261; background-color: rgba(244,162,97,0.1); padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">Pending</span>
                                        <i data-lucide="clock" class="text-orange" style="color: #f4a261; width: 16px; height: 16px;"></i>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-history">
                                Belum ada laporan yang Anda kirimkan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Panel D: Panduan Pelaporan -->
                <div class="lapor-aside-card dark-card">
                    <div class="aside-card-header" style="border-bottom-color: rgba(255,255,255,0.1);">
                        <span class="aside-card-title text-white">Panduan Pelaporan</span>
                    </div>
                    <ul class="aside-info-list">
                        <li class="aside-info-item">
                            <i data-lucide="info"></i>
                            <span>Gunakan patokan benda sekitar untuk estimasi tinggi air</span>
                        </li>
                        <li class="aside-info-item">
                            <i data-lucide="map-pin"></i>
                            <span>Pastikan GPS aktif sebelum mengirim laporan</span>
                        </li>
                        <li class="aside-info-item">
                            <i data-lucide="camera"></i>
                            <span>Foto yang jelas mempercepat respon tim evakuasi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('dashboard-scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 2. LOKASI DROPDOWN DYNAMIC MAPPING ---
        const kelurahanDb = {
            'Pondok Gede': ['Jatiwaringin', 'Jatibening', 'Jatibening Baru', 'Jaticempaka', 'Jatimakmur'],
            'Jatiasih': ['Jatiasih', 'Jatikramat', 'Jatiluhur', 'Jatirasa', 'Jatisari', 'Jati Mekar'],
            'Bekasi Timur': ['Aren Jaya', 'Bekasi Jaya', 'Duren Jaya', 'Margahayu'],
            'Bekasi Selatan': ['Jakamulya', 'Jakasetia', 'Kayuringin Jaya', 'Marga Jaya', 'Pekayon Jaya'],
            'Bekasi Barat': ['Bintara', 'Bintara Jaya', 'Jakasampurna', 'Kota Baru', 'Kranji'],
            'Bekasi Utara': ['Harapan Baru', 'Harapan Jaya', 'Kaliabang Tengah', 'Marga Mulya', 'Perwira', 'Teluk Pucung'],
            'Rawalumbu': ['Bojong Rawalumbu', 'Bojong Menteng', 'Pengasinan', 'Sepanjang Jaya'],
            'Mustikajaya': ['Mustikajaya', 'Mustikasari', 'Pedurenan', 'Cimuning']
        };

        const kecamatanSelect = document.getElementById('kecamatan');
        const kelurahanSelect = document.getElementById('kelurahan');

        function updateKelurahan(selectedKecamatan, selectedKelurahan = null) {
            kelurahanSelect.innerHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
            if (selectedKecamatan && kelurahanDb[selectedKecamatan]) {
                kelurahanDb[selectedKecamatan].forEach(kel => {
                    const option = document.createElement('option');
                    option.value = kel;
                    option.textContent = kel;
                    if (selectedKelurahan && kel === selectedKelurahan) {
                        option.selected = true;
                    }
                    kelurahanSelect.appendChild(option);
                });
            }
        }

        kecamatanSelect.addEventListener('change', function () {
            updateKelurahan(this.value);
        });

        // Initialize old dropdown values if any
        const oldKecamatan = "{{ old('kecamatan') }}";
        const oldKelurahan = "{{ old('kelurahan') }}";
        if (oldKecamatan) {
            updateKelurahan(oldKecamatan, oldKelurahan);
        }

        // --- 3. LEAFLET MINI MAP FALLBACK & GPS HANDLING ---
        const defaultLat = -6.2383;
        const defaultLng = 106.9922;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const gpsStatusBox = document.getElementById('gpsStatusBox');
        const gpsStatusIcon = document.getElementById('gpsStatusIcon');
        const gpsStatusText = document.getElementById('gpsStatusText');
        const gpsStatusCoords = document.getElementById('gpsStatusCoords');
        const btnRefreshGps = document.getElementById('btn-refresh-gps');

        const map = L.map('mini-map').setView([defaultLat, defaultLng], 14);
    // Batasi peta ke area Bekasi
    const bekasiBounds = L.latLngBounds([-6.5, 106.8], [-6.0, 107.3]);
    if(typeof map !== 'undefined') { map.setMaxBounds(bekasiBounds); map.setMinZoom(10); }
    if(typeof detailMap !== 'undefined') { detailMap.setMaxBounds(bekasiBounds); detailMap.setMinZoom(10); }
    if(typeof miniMap !== 'undefined') { miniMap.setMaxBounds(bekasiBounds); miniMap.setMinZoom(10); }


        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            maxZoom: 20
        }).addTo(map);

        const laporIcon = L.divIcon({
            html: `<div class="map-marker-custom marker-level-high" style="transform: scale(1.1); animation: none; background-color: #d32f2f;">
                     <i data-lucide="waves" style="width:14px; height:14px; color:#ffffff;"></i>
                   </div>`,
            className: '',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: laporIcon
        }).addTo(map);

        lucide.createIcons();

        function updateCoordinates(lat, lng) {
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
            gpsStatusCoords.innerText = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        }

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
            gpsStatusBox.className = 'location-status-box success';
            gpsStatusIcon.className = 'text-teal';
            gpsStatusIcon.setAttribute('data-lucide', 'check-circle');
            gpsStatusText.innerHTML = 'Posisi disesuaikan manual.';
            lucide.createIcons();
        });

        function requestGeolocation() {
            gpsStatusBox.className = 'location-status-box';
            gpsStatusIcon.className = 'text-teal';
            gpsStatusIcon.setAttribute('data-lucide', 'loader');
            gpsStatusText.innerHTML = 'Mendeteksi lokasi GPS...';
            lucide.createIcons();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 16);
                        updateCoordinates(lat, lng);
                        gpsStatusBox.className = 'location-status-box success';
                        gpsStatusIcon.className = 'text-teal';
                        gpsStatusIcon.setAttribute('data-lucide', 'check-circle');
                        gpsStatusText.innerHTML = 'GPS terkunci otomatis.';
                        lucide.createIcons();
                    },
                    function () {
                        gpsStatusBox.className = 'location-status-box error';
                        gpsStatusIcon.className = 'text-red';
                        gpsStatusIcon.setAttribute('data-lucide', 'alert-circle');
                        gpsStatusText.innerHTML = 'Gagal mengakses GPS. Geser pin.';
                        lucide.createIcons();
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            } else {
                gpsStatusBox.className = 'location-status-box error';
                gpsStatusIcon.className = 'text-red';
                gpsStatusIcon.setAttribute('data-lucide', 'alert-circle');
                gpsStatusText.innerHTML = 'Browser tidak mendukung GPS.';
                lucide.createIcons();
            }
        }

        // Trigger on load
        requestGeolocation();

        // Refresh button click
        if (btnRefreshGps) {
            btnRefreshGps.addEventListener('click', requestGeolocation);
        }

        // --- 4. ESTIMASI TINGGI AIR (SLIDER & BADGE) ---
        const sliderInput = document.getElementById('water_height_cm');
        const sliderValDisplay = document.getElementById('sliderVal');
        const heightBadge = document.getElementById('heightBadge');

        function updateHeightDisplay(val) {
            sliderValDisplay.innerText = val;
            let status = 'Normal';
            let bg = 'rgba(46, 125, 50, 0.1)';
            let fg = '#2e7d32';

            if (val > 150) {
                status = 'Siaga 1 — Berbahaya';
                bg = 'rgba(230, 57, 70, 0.15)';
                fg = '#e63946';
            } else if (val >= 100) {
                status = 'Siaga 2';
                bg = 'rgba(245, 124, 0, 0.15)';
                fg = '#f57c00';
            } else if (val >= 50) {
                status = 'Siaga 3';
                bg = 'rgba(251, 192, 45, 0.15)';
                fg = '#b79008';
            }

            heightBadge.innerText = status;
            heightBadge.style.backgroundColor = bg;
            heightBadge.style.color = fg;
        }

        sliderInput.addEventListener('input', function () {
            updateHeightDisplay(this.value);
        });

        // Initialize height
        updateHeightDisplay(sliderInput.value);

        // --- 5. STATUS AKSES JALAN (CARDS RADIO) ---
        const hiddenAksesInput = document.getElementById('status_akses_jalan');
        const cards = document.querySelectorAll('.status-card');

        cards.forEach(card => {
            card.addEventListener('click', function () {
                cards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                hiddenAksesInput.value = this.getAttribute('data-status');
            });
        });

        // --- 6. FILE UPLOAD & PREVIEW (STEP 2) ---
        const fileInput = document.getElementById('photo_evidence');
        const uploadZone = document.getElementById('uploadZone');
        const previewContainer = document.getElementById('previewContainer');
        const previewImg = document.getElementById('previewImg');
        const uploadIcon = document.getElementById('uploadIcon');

        uploadZone.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            handleFileSelect(this.files[0]);
        });

        // Drag & Drop
        uploadZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.style.borderColor = 'var(--color-brand-teal)';
            this.style.backgroundColor = 'rgba(0, 106, 96, 0.05)';
        });

        uploadZone.addEventListener('dragleave', function () {
            this.style.borderColor = 'var(--color-border-muted)';
            this.style.backgroundColor = 'var(--color-neutral-light)';
        });

        uploadZone.addEventListener('drop', function (e) {
            e.preventDefault();
            this.style.borderColor = 'var(--color-border-muted)';
            this.style.backgroundColor = 'var(--color-neutral-light)';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Format gambar tidak valid. Gunakan format JPEG, PNG, atau JPG.');
                fileInput.value = '';
                return;
            }

            if (file.size > 5120 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
                uploadZone.querySelector('.upload-text').innerText = 'Ganti foto bukti';
                uploadIcon.setAttribute('data-lucide', 'refresh-cw');
                lucide.createIcons();
            };
            reader.readAsDataURL(file);
        }

        // --- 7. MULTI-STEP WIZARD TRANSITIONS ---
        let currentStep = 1;

        const stepTab1 = document.getElementById('step-tab-1');
        const stepTab2 = document.getElementById('step-tab-2');
        const stepTab3 = document.getElementById('step-tab-3');

        const stepContent1 = document.getElementById('step-content-1');
        const stepContent2 = document.getElementById('step-content-2');
        const stepContent3 = document.getElementById('step-content-3');

        function showStep(step) {
            stepContent1.style.display = 'none';
            stepContent2.style.display = 'none';
            stepContent3.style.display = 'none';

            stepTab1.className = 'step-item-horizontal';
            stepTab2.className = 'step-item-horizontal';
            stepTab3.className = 'step-item-horizontal';

            if (step === 1) {
                stepContent1.style.display = 'block';
                stepTab1.classList.add('active');
            } else if (step === 2) {
                stepContent2.style.display = 'block';
                stepTab1.classList.add('completed');
                stepTab2.classList.add('active');
            } else if (step === 3) {
                stepContent3.style.display = 'block';
                stepTab1.classList.add('completed');
                stepTab2.classList.add('completed');
                stepTab3.classList.add('active');
                
                // Review summary
                document.getElementById('rev-lokasi').innerText = `${kecamatanSelect.value}, ${kelurahanSelect.value}`;
                document.getElementById('rev-jalan').innerText = document.getElementById('street_name').value;
                document.getElementById('rev-koordinat').innerText = `${parseFloat(latInput.value).toFixed(5)}, ${parseFloat(lngInput.value).toFixed(5)}`;
                document.getElementById('rev-tinggi').innerText = `${sliderInput.value} cm (${heightBadge.innerText})`;
                document.getElementById('rev-akses').innerText = hiddenAksesInput.value;

                let kondisi = [];
                if (document.getElementById('listrik_padam').checked) kondisi.push('Listrik Padam');
                if (document.getElementById('air_masih_naik').checked) kondisi.push('Air Masih Naik');
                if (document.getElementById('butuh_evakuasi').checked) kondisi.push('Butuh Evakuasi');
                if (document.getElementById('warga_terisolasi').checked) kondisi.push('Warga Terisolasi');
                
                document.getElementById('rev-kondisi').innerText = kondisi.length > 0 ? kondisi.join(', ') : 'Tidak ada kondisi tambahan';
                
                const catatan = document.getElementById('keterangan_bebas').value.trim();
                document.getElementById('rev-catatan').innerText = catatan ? catatan : 'Tidak ada catatan';
            }
            
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Validations
        function validateStep1() {
            if (!kecamatanSelect.value) {
                alert('Silakan pilih Kecamatan terlebih dahulu.');
                kecamatanSelect.focus();
                return false;
            }
            if (!kelurahanSelect.value) {
                alert('Silakan pilih Kelurahan terlebih dahulu.');
                kelurahanSelect.focus();
                return false;
            }
            if (!document.getElementById('street_name').value.trim()) {
                alert('Silakan isi detail nama jalan/lokasi.');
                document.getElementById('street_name').focus();
                return false;
            }
            return true;
        }

        function validateStep2() {
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Silakan unggah foto bukti genangan air terlebih dahulu.');
                return false;
            }
            return true;
        }

        // Buttons trigger
        document.getElementById('btn-next-1').addEventListener('click', function () {
            if (validateStep1()) {
                showStep(2);
            }
        });

        document.getElementById('btn-next-2').addEventListener('click', function () {
            if (validateStep2()) {
                showStep(3);
            }
        });

        document.getElementById('btn-back-2').addEventListener('click', function () {
            showStep(1);
        });

        document.getElementById('btn-back-3').addEventListener('click', function () {
            showStep(2);
        });
    });
</script>
@endsection
