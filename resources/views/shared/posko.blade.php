@extends('layouts.app')

@section('title', 'Posko Pengungsian - TitikAman')

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
        --accent-blue: #3b82f6;
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

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-input {
        width: 240px;
        height: 38px;
        background-color: var(--bg-light);
        border: 1px solid rgba(196, 198, 207, 0.6);
        border-radius: 8px;
        padding: 8px 12px 8px 36px;
        font-size: 13px;
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .btn-emergency-header {
        background-color: var(--accent-red);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); box-shadow: 0 0 10px rgba(186,26,26,0.5); }
        100% { transform: scale(1); }
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

    /* Page Content Body */
    .dashboard-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .live-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: var(--color-text-muted);
    }

    .btn-outline {
        border: 1px solid var(--card-border);
        color: var(--navy-dark);
        background-color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        background-color: var(--bg-light);
        border-color: var(--navy-dark);
    }

    /* Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 1024px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
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

    .stat-card.orange-border { border-left: 4px solid var(--accent-orange); }
    .stat-card.blue-border { border-left: 4px solid var(--accent-blue); }
    .stat-card.teal-border { border-left: 4px solid var(--brand-teal); }
    .stat-card.red-border { border-left: 4px solid var(--accent-red); }

    .stat-header {
        font-size: 11px;
        color: var(--color-text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 8px 0 4px;
    }

    .stat-footer {
        font-size: 11px;
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .text-red { color: var(--accent-red); }
    .text-orange { color: var(--accent-orange); }
    .text-teal { color: var(--brand-teal); }
    .text-blue { color: var(--accent-blue); }

    /* Filter Chips */
    .filter-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .chip {
        padding: 8px 16px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--card-border);
        color: var(--color-text-muted);
        background-color: white;
        transition: all 0.2s;
    }

    .chip:hover {
        border-color: var(--navy-dark);
        color: var(--navy-dark);
    }

    .chip.active {
        background-color: var(--navy-dark);
        color: white;
        border-color: var(--navy-dark);
    }

    /* Main split layout */
    .split-layout {
        display: grid;
        grid-template-columns: 3.5fr 2.5fr;
        gap: 20px;
    }

    @media (max-width: 1024px) {
        .split-layout {
            grid-template-columns: 1fr;
        }
    }

    .shelter-cards-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .shelter-horizontal-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        display: flex;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
    }

    @media (max-width: 640px) {
        .shelter-horizontal-card {
            flex-direction: column;
        }
    }

    .shelter-image-col {
        width: 180px;
        position: relative;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .shelter-image-col {
            width: 100%;
            height: 120px;
        }
    }

    .shelter-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-overlay {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-overlay.green { background-color: var(--brand-teal); }
    .status-overlay.orange { background-color: var(--accent-orange); }
    .status-overlay.red { background-color: var(--accent-red); }

    .shelter-body-col {
        padding: 16px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
    }

    .shelter-title-row {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .shelter-h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0;
    }

    .shelter-address {
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .capacity-bar-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .capacity-labels {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        font-weight: 600;
    }

    .capacity-bar-track {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .capacity-bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .capacity-bar-fill.green { background-color: var(--brand-teal); }
    .capacity-bar-fill.orange { background-color: var(--accent-orange); }
    .capacity-bar-fill.red { background-color: var(--accent-red); }

    .facility-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .facility-pill {
        background-color: #f3f4f6;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .shelter-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-card {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        border: none;
        transition: all 0.2s;
    }

    .btn-card.outline {
        border: 1px solid var(--brand-teal);
        color: var(--brand-teal);
        background-color: transparent;
    }

    .btn-card.outline:hover {
        background-color: rgba(0, 106, 96, 0.05);
    }

    .btn-card.filled {
        background-color: var(--brand-teal);
        color: white;
    }

    .btn-card.filled:hover {
        background-color: var(--brand-teal-hover);
    }

    .btn-card.disabled {
        background-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        width: 100%;
    }

    /* Right Mini Map */
    .map-card-right {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: max-content;
        position: sticky;
        top: 20px;
    }

    #shelter-mini-map {
        height: 340px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--card-border);
    }

    .map-legend {
        display: flex;
        gap: 16px;
        font-size: 11px;
        font-weight: 600;
        color: var(--navy-dark);
        margin-top: -6px;
    }

    .legend-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 4px;
        vertical-align: middle;
    }

    .legend-dot.green { background-color: var(--brand-teal); }
    .legend-dot.orange { background-color: var(--accent-orange); }
    .legend-dot.red { background-color: var(--accent-red); }

    /* Kirim Donasi Form Section */
    .donasi-section {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .donasi-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .donasi-icon-bg {
        background-color: rgba(0, 106, 96, 0.1);
        color: var(--brand-teal);
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .donasi-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0;
    }

    .donasi-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 640px) {
        .donasi-form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: var(--brand-teal);
        box-shadow: 0 0 0 3px rgba(0, 106, 96, 0.15);
    }

    .info-row {
        background-color: rgba(59, 130, 246, 0.08);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--navy-dark);
    }

    .info-row-text {
        font-size: 12px;
        line-height: 1.5;
    }

    .btn-submit-full {
        width: 100%;
        background-color: var(--brand-teal);
        color: white;
        border: none;
        height: 46px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-submit-full:hover {
        background-color: var(--brand-teal-hover);
    }

    /* Alerts */
    .alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
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
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left">
                <div class="search-wrapper">
                    <i data-lucide="search" class="search-icon" style="width: 14px; height: 14px;"></i>
                    <input type="text" class="search-input" placeholder="Cari nama posko...">
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-emergency-header">🚨 Emergency Alert</button>
                <div class="notification-bell">
                    <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                    <div class="notification-dot"></div>
                </div>
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">AL</div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->user()->fullname }}</span>
                        <span class="user-widget-role">Admin LogiGuard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="dashboard-body">
            <!-- Alert success -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
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
                            }
                        @endphp
                        <div class="shelter-horizontal-card">
                            <div class="shelter-image-col">
                                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=200&auto=format&fit=crop" class="shelter-img" alt="{{ $shelter->shelter_name }}">
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
                                    @if($shelter->status != 'full')
                                        <button class="btn-card outline">Lihat Rute</button>
                                        <button class="btn-card filled" onclick="focusShelterMap({{ $shelter->latitude }}, {{ $shelter->longitude }}, '{{ $shelter->shelter_name }}')">Fokus Peta</button>
                                    @else
                                        <button class="btn-card disabled" disabled>Posko Terisi Penuh</button>
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
                        <i data-lucide="truck" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <h3 class="donasi-title">Kirim Bantuan Logistik ke Posko</h3>
                        <span class="page-subtitle">Formulir komitmen penyaluran donasi logistik warga</span>
                    </div>
                </div>

                <form action="{{ route('posko.donasi') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    <div class="donasi-form-grid">
                        <div class="form-group">
                            <label class="form-label" for="jenis_bantuan">JENIS BANTUAN *</label>
                            <input type="text" name="jenis_bantuan" id="jenis_bantuan" class="form-input" placeholder="Contoh: Logistik / Makanan / Obat-obatan" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="keterangan">JUMLAH / KETERANGAN DETAIL *</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-input" placeholder="Contoh: 50 Box Air Mineral, 30 Selimut hangat" required>
                        </div>
                    </div>

                    <div class="info-row">
                        <i data-lucide="navigation-2" class="text-blue" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
                        <span class="info-row-text">
                            <strong>Penjemputan Aktif:</strong> Armada logistik relawan siap membantu penjemputan barang bantuan untuk area Jakarta - Bekasi Raya.
                        </span>
                    </div>

                    <button type="submit" class="btn-submit-full">
                        <span>Konfirmasi Pengiriman Bantuan</span>
                        <i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i>
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
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let miniMap;

    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        miniMap = L.map('shelter-mini-map').setView([-6.241586, 106.992416], 12); // Bekasi center
        
        // CartoDB Voyager tiles (light theme suited for maps)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(miniMap);

        // Precomputed shelter coordinates
        @php
            $shelterMapData = $shelters->map(function($shelter) {
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
