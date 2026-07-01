<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TitikAman - Sistem Mitigasi & Peringatan Dini Banjir</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-titikaman.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Landing Page Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="lp-body">

    <!-- NAVBAR -->
    <nav class="lp-navbar">
        <div class="lp-navbar-container lp-container">
            <div class="lp-brand-group">
                <a href="{{ url('/') }}" class="lp-logo-container">
                    <div class="lp-logo-wrapper">
                        <img src="{{ asset('assets/landing/logo-titikaman.png') }}" alt="Logo TitikAman" class="lp-logo">
                    </div>
                    <span class="lp-brand-name">TitikAman</span>
                </a>
                <div class="lp-divider"></div>
                <div class="lp-badge-bpbd">
                    <img src="{{ asset('assets/landing/bpbd-badge.png') }}" alt="Badge BPBD" class="lp-badge-icon">
                    <span class="lp-badge-text">MITRA BPBD</span>
                </div>
            </div>
            
            <ul class="lp-nav-links">
                <li><a href="#peta-banjir" class="lp-nav-link">Peta Banjir</a></li>
                <li><a href="#layanan-darurat" class="lp-nav-link">Layanan Darurat</a></li>
                <li><a href="#cara-kerja" class="lp-nav-link">Panduan</a></li>
                <li><a href="#statistik" class="lp-nav-link">Statistik</a></li>
            </ul>

            <div class="lp-navbar-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="lp-cta-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="lp-cta-btn">Masuk / Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="lp-hero">
        <div class="lp-hero-container lp-container">
            <div class="lp-hero-content">
                <div class="lp-hero-badge">SISTEM PERINGATAN DINI NASIONAL</div>
                <h1 class="lp-hero-title">
                    Pantau Banjir, Lindungi<br>
                    Keluargamu — <span>Real-Time.</span>
                </h1>
                <p class="lp-hero-description">
                    Integrasi data tinggi muka air pintu air, laporan warga, dan koordinasi SAR dalam satu platform untuk meminimalisir risiko bencana.
                </p>
                <div class="lp-hero-actions">
                    <a href="{{ route('peta.evakuasi') }}" class="lp-btn lp-btn-primary">
                        <img src="{{ asset('assets/landing/btn-map-icon.svg') }}" alt="Map Icon" class="lp-btn-icon">
                        Buka Peta Live
                    </a>
                    <a href="{{ route('warga.sos') }}" class="lp-btn lp-btn-danger">
                        Kirim SOS
                    </a>
                </div>
                <div class="lp-hero-stats">
                    <div class="lp-stat-item">
                        <span class="lp-stat-number">142</span>
                        <span class="lp-stat-label">Posko Aktif</span>
                    </div>
                    <div class="lp-stat-divider"></div>
                    <div class="lp-stat-item">
                        <span class="lp-stat-number">99.8%</span>
                        <span class="lp-stat-label">Uptime Sistem</span>
                    </div>
                    <div class="lp-stat-divider"></div>
                    <div class="lp-stat-item">
                        <span class="lp-stat-number">2.4jt+</span>
                        <span class="lp-stat-label">Warga Terlindungi</span>
                    </div>
                </div>
            </div>
            
            <div class="lp-hero-visual">
                <div class="lp-mockup-wrapper">
                    <img src="{{ asset('assets/landing/hero-map.png') }}" alt="Peta Pemantauan Banjir" class="lp-mockup-image">
                    <div class="lp-mockup-overlay"></div>
                    <div class="lp-status-pill">
                        <div class="lp-status-dot" style="background-color: {{ $statusColor === 'red' ? '#e63946' : '#2ecc71' }};"></div>
                        <span class="lp-status-text">Update Terakhir: {{ ucfirst($lastUpdated) }} - {{ $statusText }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- FITUR UTAMA SECTION -->
    <section id="peta-banjir" class="lp-features">
        <div class="lp-container">
            <div class="lp-section-header">
                <h2 class="lp-section-title">Fitur Utama TitikAman</h2>
                <p class="lp-section-desc">Teknologi mutakhir untuk deteksi dini dan koordinasi penanganan bencana yang lebih efisien.</p>
            </div>
            
            <div class="lp-features-grid">
                <!-- Card 1 -->
                <div class="lp-feature-card">
                    <div class="lp-feature-icon-wrapper">
                        <img src="{{ asset('assets/landing/feature-iot.svg') }}" alt="Pintu Air" class="lp-feature-icon">
                    </div>
                    <h3 class="lp-feature-card-title">TMA Pintu Air Real-time</h3>
                    <p class="lp-feature-card-desc">Data tinggi muka air dari pintu air utama (Cikeas, Bekasi, Cakung) yang diinput oleh petugas untuk menentukan status siaga.</p>
                    <div class="lp-feature-bar"></div>
                </div>
                
                <!-- Card 2 -->
                <div class="lp-feature-card">
                    <div class="lp-feature-icon-wrapper">
                        <img src="{{ asset('assets/landing/feature-geofencing.svg') }}" alt="Notifikasi Geo-Fencing" class="lp-feature-icon">
                    </div>
                    <h3 class="lp-feature-card-title">Notifikasi WhatsApp</h3>
                    <p class="lp-feature-card-desc">Admin Relawan dapat mengirim instruksi evakuasi dan koordinasi tim langsung melalui WhatsApp ke relawan di lapangan.</p>
                    <div class="lp-feature-bar"></div>
                </div>
                
                <!-- Card 3 -->
                <div class="lp-feature-card">
                    <div class="lp-feature-icon-wrapper">
                        <img src="{{ asset('assets/landing/feature-crowdsourcing.svg') }}" alt="Crowdsourcing Data" class="lp-feature-icon">
                    </div>
                    <h3 class="lp-feature-card-title">Crowdsourcing Data</h3>
                    <p class="lp-feature-card-desc">Laporkan kondisi genangan di sekitar Anda. Verifikasi tim BPBD memastikan data yang masuk akurat dan dapat dipercaya.</p>
                    <div class="lp-feature-bar"></div>
                </div>
                
                <!-- Card 4 -->
                <div class="lp-feature-card">
                    <div class="lp-feature-icon-wrapper">
                        <img src="{{ asset('assets/landing/feature-ai.svg') }}" alt="Analisis Prediktif AI" class="lp-feature-icon">
                    </div>
                    <h3 class="lp-feature-card-title">Dashboard Terpadu BPBD</h3>
                    <p class="lp-feature-card-desc">Visualisasi data banjir, statistik posko, dan log aktivitas dalam satu dashboard yang memudahkan pengambilan keputusan.</p>
                    <div class="lp-feature-bar"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA SECTION -->
    <section id="cara-kerja" class="lp-how-it-works">
        <div class="lp-container">
            <div class="lp-section-header">
                <h2 class="lp-section-title">Sederhana & Terstruktur</h2>
                <p class="lp-section-desc">Langkah-langkah sistematis TitikAman dalam menjaga keselamatan Anda.</p>
            </div>
            
            <div class="lp-steps-container">
                <div class="lp-steps-decor-line"></div>
                
                <!-- Step 1 -->
                <div class="lp-step-card">
                    <div class="lp-step-badge">1</div>
                    <div class="lp-step-icon-wrapper">
                        <img src="{{ asset('assets/landing/step-1.svg') }}" alt="Deteksi & Monitor" class="lp-step-icon">
                    </div>
                    <h3 class="lp-step-title">Deteksi & Monitor</h3>
                    <p class="lp-step-desc">Data real-time dikumpulkan dari laporan warga, pintu air, dan koordinasi tim SAR secara terus menerus.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="lp-step-card">
                    <div class="lp-step-badge">2</div>
                    <div class="lp-step-icon-wrapper">
                        <img src="{{ asset('assets/landing/step-2.svg') }}" alt="Analisis Cepat" class="lp-step-icon">
                    </div>
                    <h3 class="lp-step-title">Analisis Cepat</h3>
                    <p class="lp-step-desc">Sistem TitikAman memproses data dan menentukan level status keamanan di setiap wilayah.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="lp-step-card">
                    <div class="lp-step-badge">3</div>
                    <div class="lp-step-icon-wrapper">
                        <img src="{{ asset('assets/landing/step-3.svg') }}" alt="Aksi & Evakuasi" class="lp-step-icon">
                    </div>
                    <h3 class="lp-step-title">Aksi & Evakuasi</h3>
                    <p class="lp-step-desc">Notifikasi dikirimkan seketika kepada warga dan tim darurat untuk langkah penyelamatan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PERSONA / BENEFIT CARDS SECTION -->
    <section id="layanan-darurat" class="lp-benefits">
        <div class="lp-benefits-container lp-container">
            <!-- Citizen Card -->
            <div class="lp-benefit-card citizen">
                <span class="lp-benefit-tag">UNTUK MASYARAKAT</span>
                <h2 class="lp-benefit-title">Ketenangan Pikiran untuk Keluarga Anda</h2>
                <p class="lp-benefit-desc">
                    Jangan biarkan ketidakpastian menghantui. Dengan TitikAman, Anda tahu persis kapan harus waspada dan kapan harus bertindak.
                </p>
                <ul class="lp-benefit-list">
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-citizen.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Rute evakuasi aman terdekat</span>
                    </li>
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-citizen.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Peringatan banjir via WhatsApp & SMS</span>
                    </li>
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-citizen.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Akses ke posko bantuan terverifikasi</span>
                    </li>
                </ul>
            </div>
            
            <!-- Authority Card -->
            <div class="lp-benefit-card authority">
                <span class="lp-benefit-tag">UNTUK PEMERINTAH & RELAWAN</span>
                <h2 class="lp-benefit-title">Manajemen Krisis yang Terukur</h2>
                <p class="lp-benefit-desc">
                    Optimalkan alokasi sumber daya dan percepat waktu respon dengan Dashboard Command Center yang komprehensif.
                </p>
                <ul class="lp-benefit-list">
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-authority.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Visualisasi data GIS yang presisi</span>
                    </li>
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-authority.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Logistik & Manajemen Inventori</span>
                    </li>
                    <li class="lp-benefit-item">
                        <img src="{{ asset('assets/landing/checkmark-authority.svg') }}" alt="Checkmark" class="lp-benefit-item-icon">
                        <span>Pelaporan Terpusat (One Data)</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="statistik" class="lp-footer">
        <div class="lp-footer-container lp-container">
            <div class="lp-footer-brand">
                <div class="lp-footer-logo-group">
                    <div class="lp-footer-logo-wrapper">
                        <img src="{{ asset('assets/landing/logo-titikaman.png') }}" alt="Logo TitikAman" class="lp-footer-logo">
                    </div>
                    <span class="lp-footer-brand-name">TitikAman</span>
                </div>
                <p class="lp-footer-desc">
                    Platform manajemen risiko banjir terpadu yang didedikasikan untuk keselamatan warga melalui data dan teknologi.
                </p>
            </div>
            
            <div class="lp-footer-column">
                <h4 class="lp-footer-column-title">PLATFORM</h4>
                <ul class="lp-footer-links">
                    <li><a href="{{ route('peta.evakuasi') }}" class="lp-footer-link">Peta Banjir Live</a></li>
                    <li><a href="{{ route('warga.sos') }}" class="lp-footer-link">Layanan Darurat</a></li>
                    <li><a href="{{ route('pintu.air') }}" class="lp-footer-link">Statistik Pintu Air</a></li>
                    <li><a href="#" class="lp-footer-link">Download App</a></li>
                </ul>
            </div>
            
            <div class="lp-footer-column">
                <h4 class="lp-footer-column-title">BANTUAN & INFORMASI</h4>
                <ul class="lp-footer-links">
                    <li><a href="#" class="lp-footer-link">Tentang Kami</a></li>
                    <li><a href="#" class="lp-footer-link">Panduan Keselamatan</a></li>
                    <li><a href="#" class="lp-footer-link">Kebijakan Privasi</a></li>
                    <li><a href="#" class="lp-footer-link">Kontak Kami</a></li>
                </ul>
            </div>
            
            <div class="lp-footer-column">
                <h4 class="lp-footer-column-title">LAYANAN 24/7</h4>
                <div class="lp-callcenter-card">
                    <span class="lp-callcenter-title">Call Center Darurat</span>
                    <span class="lp-callcenter-number">112</span>
                    <span class="lp-callcenter-note">Bebas Pulsa Untuk Seluruh Wilayah</span>
                </div>
            </div>
        </div>
        
        <div class="lp-footer-bottom lp-container">
            <p class="lp-copyright">
                © 2026 TitikAman Flood Management System. All Rights Reserved. Official Partnership: BPBD & BNPB.
            </p>
            <div class="lp-footer-socials">
                <a href="#" class="lp-social-link">
                    <img src="{{ asset('assets/landing/social-x.svg') }}" alt="X" class="lp-social-icon">
                </a>
                <a href="#" class="lp-social-link">
                    <img src="{{ asset('assets/landing/social-fb.svg') }}" alt="Facebook" class="lp-social-icon">
                </a>
                <a href="#" class="lp-social-link">
                    <img src="{{ asset('assets/landing/social-ig.svg') }}" alt="Instagram" class="lp-social-icon">
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
