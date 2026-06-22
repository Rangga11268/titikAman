@extends('layouts.app')

@section('title', 'Dashboard Utama - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    :root {
        --navy-sidebar: #1d3557;
        --navy-dark: #031f41;
        --brand-teal: #006a60;
        --brand-teal-hover: #004d46;
        --accent-red: #ba1a1a;
        --accent-orange: #f59e0b;
        --accent-yellow: #ca8a04;
        --bg-light: #f8f9fa;
        --card-border: rgba(196, 198, 207, 0.4);
    }

    body {
        background-color: var(--bg-light);
        margin: 0;
        font-family: 'Inter', sans-serif;
    }

    .dashboard-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }



    /* Main Content Wrapper */
    .dashboard-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow-y: auto;
        position: relative;
    }

    /* Banner Warning */
    .warning-banner {
        background-color: #fff3cd;
        border-bottom: 1px solid #ffeeba;
        color: #856404;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .warning-banner i, .warning-banner svg {
        color: var(--accent-orange);
        flex-shrink: 0;
    }

    /* Top Bar Header */
    .dashboard-topbar {
        padding: 16px 24px;
        background-color: white;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .topbar-left h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .notification-bell {
        color: var(--color-text-muted);
        position: relative;
        cursor: pointer;
    }

    .notification-dot {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background-color: var(--accent-red);
        border-radius: 50%;
    }

    .user-profile-widget {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-widget-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(0, 106, 96, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-teal);
        font-weight: 700;
        font-size: 14px;
        border: 2px solid var(--brand-teal);
    }

    .user-widget-info {
        display: flex;
        flex-direction: column;
    }

    .user-widget-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .user-widget-role {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    /* Main Dashboard Grid Body */
    .dashboard-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* Stat Cards (5 Cards) */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .stat-card {
        background-color: white;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid var(--card-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card.red-border { border-left: 4px solid var(--accent-red); }
    .stat-card.orange-border { border-left: 4px solid var(--accent-orange); }
    .stat-card.teal-border { border-left: 4px solid var(--brand-teal); }

    .stat-header {
        font-size: 12px;
        color: var(--color-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 8px 0 4px;
    }

    .stat-footer {
        font-size: 12px;
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .text-red { color: var(--accent-red); }
    .text-orange { color: var(--accent-orange); }
    .text-teal { color: var(--brand-teal); }
    .text-gray { color: var(--color-text-muted); }
    .font-bold { font-weight: 700; }

    /* Map + Pintu Air Section */
    .map-section-row {
        display: grid;
        grid-template-columns: 3.5fr 2.5fr;
        gap: 20px;
    }

    @media (max-width: 1024px) {
        .map-section-row {
            grid-template-columns: 1fr;
        }
    }

    .map-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .map-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .map-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .map-toggles {
        display: flex;
        gap: 8px;
    }

    .toggle-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--card-border);
        background-color: white;
        transition: all 0.2s;
    }

    .toggle-btn.siaga1 { border-color: var(--accent-red); color: var(--accent-red); }
    .toggle-btn.siaga1:hover { background-color: var(--accent-red); color: white; }
    .toggle-btn.siaga2 { border-color: var(--accent-orange); color: var(--accent-orange); }
    .toggle-btn.siaga2:hover { background-color: var(--accent-orange); color: white; }
    .toggle-btn.siaga3 { border-color: var(--accent-yellow); color: var(--accent-yellow); }
    .toggle-btn.siaga3:hover { background-color: var(--accent-yellow); color: white; }

    #dashboard-map {
        height: 360px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--card-border);
    }

    /* Pintu Air Side Panel */
    .pintu-air-panel {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 16px;
    }

    .pintu-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pintu-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        background-color: var(--bg-light);
        border: 1px solid rgba(196, 198, 207, 0.2);
    }

    .pintu-name-col {
        display: flex;
        flex-direction: column;
    }

    .pintu-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .pintu-river {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .pintu-level {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .badge-pill {
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-red { background-color: rgba(186, 26, 26, 0.15); color: var(--accent-red); }
    .badge-orange { background-color: rgba(245, 158, 11, 0.15); color: var(--accent-orange); }
    .badge-yellow { background-color: rgba(202, 138, 4, 0.15); color: var(--accent-yellow); }
    .badge-green { background-color: rgba(0, 106, 96, 0.15); color: var(--brand-teal); }
    .badge-blue { background-color: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .badge-gray { background-color: rgba(107, 114, 128, 0.15); color: var(--color-text-muted); }

    .pintu-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .prediction-box {
        background-color: var(--navy-dark);
        border-radius: 12px;
        padding: 16px;
        color: white;
        border-left: 4px solid var(--accent-orange);
    }

    .prediction-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--accent-orange);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .prediction-text {
        font-size: 12px;
        color: #d1d5db;
        line-height: 1.5;
    }

    /* Antrean SOS + Logistik */
    .two-column-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .two-column-row {
            grid-template-columns: 1fr;
        }
    }

    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .card-header-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--navy-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-green-link {
        color: var(--brand-teal);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-green-link:hover {
        text-decoration: underline;
    }

    .sos-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .sos-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background-color: var(--bg-light);
        border-radius: 8px;
        border: 1px solid rgba(196, 198, 207, 0.2);
    }

    .sos-item-left {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .sos-number {
        background-color: rgba(186, 26, 26, 0.1);
        color: var(--accent-red);
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .sos-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .sos-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .sos-meta {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .sos-desc {
        font-size: 12px;
        color: #4b5563;
        margin-top: 4px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-action.red { background-color: var(--accent-red); color: white; }
    .btn-action.red:hover { background-color: #9e1414; }
    .btn-action.gray { background-color: #e5e7eb; color: #4b5563; cursor: not-allowed; }

    /* Logistik Panel */
    .logistik-rows {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .logistik-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .logistik-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
    }

    .logistik-name {
        font-weight: 600;
        color: var(--navy-dark);
    }

    .logistik-qty {
        color: var(--color-text-muted);
    }

    .progress-bar-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .progress-track {
        flex: 1;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
    }

    .progress-fill.green { background-color: var(--brand-teal); }
    .progress-fill.orange { background-color: var(--accent-orange); }
    .progress-fill.red { background-color: var(--accent-red); }

    .btn-add-mini {
        background-color: var(--bg-light);
        border: 1px solid var(--card-border);
        color: var(--brand-teal);
        width: 60px;
        height: 26px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        transition: all 0.2s;
    }

    .btn-add-mini:hover {
        background-color: var(--brand-teal);
        color: white;
        border-color: var(--brand-teal);
    }

    /* Log Aktivitas Table Section */
    .table-section-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    .btn-teal-outline {
        border: 1px solid var(--brand-teal);
        color: var(--brand-teal);
        background-color: white;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-teal-outline:hover {
        background-color: var(--brand-teal);
        color: white;
    }

    .btn-teal-filled {
        background-color: var(--brand-teal);
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-teal-filled:hover {
        background-color: var(--brand-teal-hover);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }

    .custom-table th {
        background-color: var(--bg-light);
        padding: 12px 16px;
        font-weight: 600;
        color: var(--navy-dark);
        border-bottom: 1px solid var(--card-border);
    }

    .custom-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(196, 198, 207, 0.2);
        color: #4b5563;
        vertical-align: middle;
    }

    /* Footer Section */
    .dashboard-footer {
        background-color: #031f41;
        color: white;
        padding: 48px 24px 24px;
        margin-top: 24px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 32px;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }

    .footer-branding {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .footer-desc {
        font-size: 13px;
        color: #879ec6;
        line-height: 1.6;
        max-width: 320px;
    }

    .footer-col-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: white;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .footer-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
        list-style: none;
        padding: 0;
    }

    .footer-link {
        color: #879ec6;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s;
    }

    .footer-link:hover {
        color: white;
    }

    .footer-bottom {
        max-width: 1400px;
        margin: 40px auto 0;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #879ec6;
    }

    @media (max-width: 768px) {
        .footer-bottom {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
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
                                        @if($gate->danger_status == 'danger_1')
                                            <span class="badge-pill badge-red">SIAGA 1</span>
                                        @elseif($gate->danger_status == 'danger_2')
                                            <span class="badge-pill badge-orange">SIAGA 2</span>
                                        @elseif($gate->danger_status == 'danger_3')
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
                        <button class="btn-teal-outline"><i data-lucide="filter" style="width: 14px; height: 14px;"></i> Filter Data</button>
                        <button class="btn-teal-filled"><i data-lucide="download" style="width: 14px; height: 14px;"></i> Unduh Laporan PDF</button>
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
                                <tr>
                                    <td>{{ $log->created_at->format('H:i:s WIB') }}</td>
                                    <td>
                                        <span class="badge-pill badge-gray">LAPORAN</span>
                                    </td>
                                    <td>{{ $log->street_name }} (Tinggi Air: {{ $log->water_height_cm }} cm)</td>
                                    <td>{{ $log->user->fullname }}</td>
                                    <td>
                                        <span class="badge-pill badge-green">Tercatat</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>11:45 WIB</td>
                                    <td><span class="badge-pill badge-red">SOS EMERGENCY</span></td>
                                    <td>Penyelamatan 4 Jiwa terjebak di atap rumah Kavling Jati</td>
                                    <td>Relawan Budi</td>
                                    <td><span class="badge-pill badge-green">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>11:32 WIB</td>
                                    <td><span class="badge-pill badge-blue">PINTU AIR</span></td>
                                    <td>Kenaikan TMA Pintu Air Pondok Gede Hulu ke 185 cm</td>
                                    <td>Petugas Bambang</td>
                                    <td><span class="badge-pill badge-yellow">Masuk</span></td>
                                </tr>
                                <tr>
                                    <td>11:20 WIB</td>
                                    <td><span class="badge-pill badge-green">VERIFIKASI</span></td>
                                    <td>Laporan Genangan Jalan Perjuangan validasi lapangan</td>
                                    <td>BPBD Bekasi</td>
                                    <td><span class="badge-pill badge-blue">Valid</span></td>
                                </tr>
                                <tr>
                                    <td>10:55 WIB</td>
                                    <td><span class="badge-pill badge-orange">DONASI</span></td>
                                    <td>Donasi 100 Box Selimut tiba di Posko Serbaguna Jatiasih</td>
                                    <td>Donatur Yayasan</td>
                                    <td><span class="badge-pill badge-green">Masuk</span></td>
                                </tr>
                            @endforelse
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
                return [
                    'name' => $shelter->shelter_name,
                    'lat' => floatval($shelter->latitude),
                    'lng' => floatval($shelter->longitude),
                    'status' => $shelter->status,
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
    });
</script>
@endsection
