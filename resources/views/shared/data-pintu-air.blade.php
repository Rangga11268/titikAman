@extends('layouts.app')

@section('title', 'Data Tinggi Muka Air (TMA) - TitikAman')

@section('styles')
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
        flex-direction: column;
        gap: 4px;
    }

    .breadcrumb {
        font-size: 11px;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
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

    /* Body Container */
    .dashboard-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    .live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--brand-teal);
        background-color: rgba(0, 106, 96, 0.05);
        padding: 8px 16px;
        border-radius: 6px;
        align-self: flex-start;
    }

    .title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--color-text-muted);
    }

    .btn-row {
        display: flex;
        gap: 12px;
    }

    .btn-outline {
        border: 1px solid var(--brand-teal);
        color: var(--brand-teal);
        background-color: transparent;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        background-color: rgba(0, 106, 96, 0.05);
    }

    .btn-teal {
        background-color: var(--brand-teal);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-teal:hover {
        background-color: var(--brand-teal-hover);
    }

    /* Sungai Cards Row */
    .sungai-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
    }

    .sungai-card {
        background-color: white;
        border-radius: 14px;
        border: 1px solid var(--card-border);
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .sungai-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -2px rgba(0, 0, 0, 0.08);
    }

    .sungai-card.selected {
        border-left: 4px solid var(--brand-teal);
    }

    .sungai-card.chart-active {
        border-color: var(--brand-teal);
        box-shadow: 0 0 0 2px rgba(0, 106, 96, 0.15);
    }

    #btnHistori7Hari.active {
        background-color: var(--navy-dark);
        color: white;
        border-color: var(--navy-dark);
    }

    .sungai-card.siaga1-active {
        border-left: 4px solid var(--accent-red);
    }

    .card-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-pill {
        padding: 4px 8px;
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

    .sungai-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .sungai-desc {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .level-large {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 32px;
        font-weight: 800;
        margin: 4px 0;
    }

    .level-large.text-red { color: var(--accent-red); }
    .level-large.text-orange { color: var(--accent-orange); }
    .level-large.text-yellow { color: var(--accent-yellow); }
    .level-large.text-green { color: var(--brand-teal); }

    .trend-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .trend-badge {
        display: flex;
        align-items: center;
        gap: 2px;
        font-weight: 700;
        font-size: 11px;
    }

    .alert-box-mini {
        background-color: rgba(186, 26, 26, 0.08);
        border: 1px solid rgba(186, 26, 26, 0.15);
        border-radius: 8px;
        padding: 10px;
        color: var(--accent-red);
        font-size: 11px;
        font-weight: 600;
        line-height: 1.4;
        display: flex;
        gap: 6px;
    }

    /* Chart Section */
    .chart-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-toggle-row {
        display: flex;
        gap: 8px;
    }

    .chart-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid var(--card-border);
        background-color: white;
        cursor: pointer;
        transition: background-color 0.15s, color 0.15s, border-color 0.15s;
    }

    .chart-btn:hover {
        border-color: var(--navy-dark);
        color: var(--navy-dark);
    }

    .chart-btn.active {
        background-color: var(--navy-dark);
        color: white;
        border-color: var(--navy-dark);
    }

    .canvas-container {
        position: relative;
        height: 320px;
        width: 100%;
    }

    /* Bottom 2 Columns */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 20px;
    }

    @media (max-width: 1024px) {
        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    .table-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 12px;
    }

    .table-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .table-data {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .table-data th {
        background-color: var(--bg-light);
        padding: 10px 12px;
        font-weight: 600;
        color: var(--navy-dark);
        border-bottom: 1px solid var(--card-border);
    }

    .table-data td {
        padding: 12px;
        border-bottom: 1px solid rgba(196, 198, 207, 0.2);
    }

    /* Batas Ketinggian Card */
    .side-stack {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .side-card {
        background-color: white;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .side-card-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--navy-dark);
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 10px;
    }

    .batas-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .batas-label-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 600;
    }

    .bar-track {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .bar-fill.red { background-color: var(--accent-red); }
    .bar-fill.orange { background-color: var(--accent-orange); }
    .bar-fill.yellow { background-color: var(--accent-yellow); }
    .bar-fill.green { background-color: var(--brand-teal); }

    /* Notifikasi Otomatis */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 10px;
        border-radius: 8px;
        background-color: var(--bg-light);
        border-left: 3px solid #3b82f6;
    }

    .notif-item.red { border-left-color: var(--accent-red); }
    .notif-item.orange { border-left-color: var(--accent-orange); }

    .notif-icon {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .notif-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .notif-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .notif-text {
        font-size: 11px;
        color: #4b5563;
        line-height: 1.4;
    }

    .notif-time {
        font-size: 10px;
        color: var(--color-text-muted);
        align-self: flex-end;
    }

    /* Footer */
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
                <span class="breadcrumb">Monitoring TMA / Bekasi & Sekitarnya</span>
                <h1>Data Tinggi Muka Air (TMA) Real-Time</h1>
            </div>
            <div class="topbar-right">
                <div class="notification-bell">
                    <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                    <div class="notification-dot"></div>
                </div>
                @php
                    $authUser = auth()->user();
                    $authInitials = 'TA';
                    if ($authUser) {
                        $parts = explode(' ', $authUser->fullname);
                        $authInitials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    }
                @endphp
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">{{ $authInitials }}</div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ $authUser?->fullname ?? 'Pengguna' }}</span>
                        <span class="user-widget-role">{{ str_replace('_', ' ', $authUser?->role ?? '') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="dashboard-body">
            <!-- Indicator & Header -->
            <div class="live-indicator">
                <span style="color: var(--brand-teal); font-size: 14px;">●</span>
                <span>Live — Diperbarui setiap 15 menit oleh petugas BPBD Kota Bekasi</span>
            </div>

            <div class="title-row">
                <div class="page-title-section">
                    <h2 class="page-title">Status TMA Sungai Utama</h2>
                    <span class="page-subtitle">Pemantauan sungai-sungai utama di wilayah Bekasi & sekitarnya</span>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn-outline" id="btnHistori7Hari">Histori 7 Hari</button>
                    <button class="btn-teal" onclick="window.location.href='{{ route('watergate.export') }}'"><i data-lucide="download" style="width: 16px; height: 16px;"></i> Rekapitulasi Data</button>
                </div>
            </div>

            <!-- Sungai Cards -->
            <div class="sungai-cards">
                @forelse($waterGates as $gate)
                    @php 
                        $statusClass = '';
                        $statusBadge = '';
                        $statusTextClass = '';
                        
                        if($gate->danger_status == 'Siaga_1') {
                            $statusClass = 'siaga1-active';
                            $statusBadge = 'badge-red';
                            $statusTextClass = 'text-red';
                            $badgeLabel = 'SIAGA 1';
                        } elseif($gate->danger_status == 'Siaga_2') {
                            $statusClass = '';
                            $statusBadge = 'badge-orange';
                            $statusTextClass = 'text-orange';
                            $badgeLabel = 'SIAGA 2';
                        } elseif($gate->danger_status == 'Siaga_3') {
                            $statusClass = '';
                            $statusBadge = 'badge-yellow';
                            $statusTextClass = 'text-yellow';
                            $badgeLabel = 'SIAGA 3';
                        } else {
                            $statusClass = 'selected';
                            $statusBadge = 'badge-green';
                            $statusTextClass = 'text-teal';
                            $badgeLabel = 'NORMAL';
                        }
                    @endphp
                    <div class="sungai-card {{ $statusClass }}" data-gate-id="{{ $gate->gate_id }}" role="button" tabindex="0" title="Klik untuk lihat grafik pintu air ini">
                        <div class="card-top-row">
                            <span class="badge-pill {{ $statusBadge }}">{{ $badgeLabel }}</span>
                            <i data-lucide="refresh-cw" style="width: 14px; height: 14px; color: var(--color-text-muted); cursor: pointer;"></i>
                        </div>
                        <div>
                            <span class="sungai-title" style="display: block;">{{ $gate->gate_name }}</span>
                            <span class="sungai-desc">{{ $gate->river_name }}</span>
                        </div>
                        <div class="level-large {{ $statusTextClass }}">{{ $gate->water_level_cm }} cm</div>
                        <div class="trend-row">
                            @php
                                $pctOfMax = $featuredGate && $featuredGate->water_level_cm > 0
                                    ? round(($gate->water_level_cm / 300) * 100)
                                    : 0;
                            @endphp
                            <span class="trend-badge {{ $gate->danger_status == 'Siaga_1' || $gate->danger_status == 'Siaga_2' ? 'text-red' : 'text-teal' }}">
                                <i data-lucide="{{ $gate->danger_status == 'Normal' ? 'trending-down' : 'trending-up' }}" style="width: 12px; height: 12px;"></i>
                                {{ $pctOfMax }}% dari batas
                            </span>
                            <span>{{ $gate->last_updated ? \Carbon\Carbon::parse($gate->last_updated)->format('H:i') . ' WIB' : 'N/A' }}</span>
                        </div>
                        @if($gate->danger_status == 'Siaga_1')
                            <div class="alert-box-mini">
                                <i data-lucide="alert-octagon" style="width: 14px; height: 14px; flex-shrink: 0; margin-top: 2px;"></i>
                                <span>Warga harus siap mengungsi, air kiriman tiba!</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="sungai-card text-center" style="grid-column: 1 / -1; padding: 40px;">
                        Tidak ada data pintu air saat ini.
                    </div>
                @endforelse
            </div>

            <!-- Chart -->
            <div class="chart-card" id="tmaChartSection">
                <div class="chart-header">
                    <div class="page-title-section">
                        <span class="sungai-title" style="font-size: 15px;">Grafik TMA 24 Jam Terakhir</span>
                        <span class="sungai-desc" id="chartSubtitle">Visualisasi tinggi muka air sungai utama</span>
                    </div>
                    <div class="chart-toggle-row" id="chartToggleRow">
                        @foreach($waterGates->take(4) as $cGate)
                            <button type="button" class="chart-btn chart-gate-btn {{ $loop->first ? 'active' : '' }}" data-gate-id="{{ $cGate->gate_id }}">{{ $cGate->gate_name }}</button>
                        @endforeach
                        <button type="button" class="chart-btn chart-range-btn active" data-range="24h">Hari Ini (24 Jam)</button>
                    </div>
                </div>
                <div class="canvas-container">
                    <canvas id="tmaChart"></canvas>
                </div>
            </div>

            <!-- Bottom 2-Column Layout -->
            <div class="bottom-grid">
                <!-- Left: Table -->
                <div class="table-card">
                    <div class="table-header">
                        <span class="table-title">Status Terkini — Semua Pintu Air
                            @if($featuredGate)
                                <span style="font-size:12px;font-weight:500;color:var(--color-text-muted);"> ({{ $totalGates }} pintu air terpantau)</span>
                            @endif
                        </span>
                        <button class="btn-outline" style="padding: 6px 12px; font-size: 12px;" onclick="window.location.href='{{ route('watergate.export') }}'"><i data-lucide="download" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle;"></i> Unduh Data CSV</button>
                    </div>
                    <div class="table-container">
                        <table class="table-data">
                            <thead>
                                <tr>
                                    <th>Nama Pintu Air</th>
                                    <th>Sungai</th>
                                    <th>TMA (cm)</th>
                                    <th>Status</th>
                                    <th>Terakhir Diperbarui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($waterGates as $gate)
                                    @php
                                        $rowBadge = 'badge-green';
                                        $rowLabel = 'NORMAL';
                                        $rowColor = 'text-teal';
                                        if ($gate->danger_status == 'Siaga_1') { $rowBadge = 'badge-red';    $rowLabel = 'SIAGA 1'; $rowColor = 'text-red'; }
                                        elseif ($gate->danger_status == 'Siaga_2') { $rowBadge = 'badge-orange'; $rowLabel = 'SIAGA 2'; $rowColor = 'text-orange'; }
                                        elseif ($gate->danger_status == 'Siaga_3') { $rowBadge = 'badge-yellow'; $rowLabel = 'SIAGA 3'; $rowColor = 'text-yellow'; }
                                    @endphp
                                    <tr>
                                        <td style="font-weight:600;">{{ $gate->gate_name }}</td>
                                        <td>{{ $gate->river_name }}</td>
                                        <td class="{{ $rowColor }} font-bold">{{ $gate->water_level_cm }} cm</td>
                                        <td><span class="badge-pill {{ $rowBadge }}">{{ $rowLabel }}</span></td>
                                        <td>{{ $gate->last_updated ? \Carbon\Carbon::parse($gate->last_updated)->format('d M Y H:i') . ' WIB' : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--color-text-muted);">Tidak ada data pintu air.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Stack side widgets -->
                <div class="side-stack">
                    <div class="side-card">
                        <span class="side-card-title">Batas Ketinggian Status Siaga</span>
                        <div class="logistik-rows" style="display: flex; flex-direction: column; gap: 14px;">
                            <div class="batas-row">
                                <div class="batas-label-row text-red">
                                    <span>SIAGA 1 (BAHAYA)</span>
                                    <span>> 250 cm</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill red" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-orange">
                                    <span>SIAGA 2 (WASPADA)</span>
                                    <span>150 - 250 cm</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill orange" style="width: 60%;"></div>
                                </div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-yellow">
                                    <span>SIAGA 3 (SIAGA)</span>
                                    <span>80 - 150 cm</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill yellow" style="width: 40%;"></div>
                                </div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-teal">
                                    <span>NORMAL</span>
                                    <span>< 80 cm</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-fill green" style="width: 20%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="side-card">
                        <span class="side-card-title">Log Notifikasi Otomatis</span>
                        <div class="notif-list">
                            @forelse($alertLog as $alert)
                                <div class="notif-item {{ $alert['type'] }}">
                                    <div class="notif-icon">
                                        <i data-lucide="{{ $alert['icon'] }}" class="{{ $alert['type'] == 'red' ? 'text-red' : ($alert['type'] == 'orange' ? 'text-orange' : '') }}" style="width: 16px; height: 16px; {{ $alert['type'] == '' ? 'color: #3b82f6;' : '' }}"></i>
                                    </div>
                                    <div class="notif-details">
                                        <span class="notif-title">{{ $alert['title'] }}</span>
                                        <span class="notif-text">{{ $alert['text'] }}</span>
                                        <span class="notif-time">{{ $alert['time'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <p style="font-size:13px;color:var(--color-text-muted);text-align:center;">Tidak ada notifikasi aktif.</p>
                            @endforelse
                        </div>
                    </div>
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
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('tmaChart').getContext('2d');

        const chartData24h = {
            labels: @json($chartLabels24h),
            datasets: @json($chartDatasets24h),
        };
        const chartData7d = {
            labels: @json($chartLabels7d),
            datasets: @json($chartDatasets7d),
        };

        let currentRange = '24h';
        let activeGateId = chartData24h.datasets[0]?.gateId ?? null;

        const chartTitleEl = document.querySelector('#tmaChartSection .sungai-title');
        const chartSubtitleEl = document.getElementById('chartSubtitle');
        const gateButtons = document.querySelectorAll('.chart-gate-btn');
        const rangeButtons = document.querySelectorAll('.chart-range-btn');
        const historiBtn = document.getElementById('btnHistori7Hari');
        const sungaiCards = document.querySelectorAll('.sungai-card[data-gate-id]');

        function cloneDatasets(sourceDatasets, visibleGateId) {
            return sourceDatasets.map(function(ds) {
                const copy = Object.assign({}, ds);
                copy.hidden = visibleGateId !== null && ds.gateId !== visibleGateId;
                return copy;
            });
        }

        function getActiveDatasets() {
            const source = currentRange === '7d' ? chartData7d : chartData24h;
            return cloneDatasets(source.datasets, activeGateId);
        }

        function updateActiveButtons() {
            gateButtons.forEach(function(btn) {
                btn.classList.toggle('active', parseInt(btn.dataset.gateId, 10) === activeGateId);
            });

            rangeButtons.forEach(function(btn) {
                btn.classList.toggle('active', btn.dataset.range === currentRange);
            });

            historiBtn.classList.toggle('active', currentRange === '7d');

            sungaiCards.forEach(function(card) {
                card.classList.toggle('chart-active', parseInt(card.dataset.gateId, 10) === activeGateId);
            });
        }

        function updateChartTitle() {
            const activeGate = chartData24h.datasets.find(function(ds) {
                return ds.gateId === activeGateId;
            });

            if (currentRange === '7d') {
                chartTitleEl.textContent = 'Grafik TMA 7 Hari Terakhir';
                chartSubtitleEl.textContent = activeGate
                    ? 'Tren mingguan — ' + activeGate.label
                    : 'Tren mingguan tinggi muka air sungai utama';
            } else {
                chartTitleEl.textContent = 'Grafik TMA 24 Jam Terakhir';
                chartSubtitleEl.textContent = activeGate
                    ? 'Visualisasi — ' + activeGate.label
                    : 'Visualisasi tinggi muka air sungai utama';
            }
        }

        function applyChartView() {
            const source = currentRange === '7d' ? chartData7d : chartData24h;
            tmaChart.data.labels = source.labels;
            tmaChart.data.datasets = getActiveDatasets();
            tmaChart.update();
            updateChartTitle();
            updateActiveButtons();
        }

        const tmaChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData24h.labels,
                datasets: getActiveDatasets(),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        onClick: function(e, legendItem, legend) {
                            const index = legendItem.datasetIndex;
                            const meta = legend.chart.getDatasetMeta(index);
                            meta.hidden = meta.hidden === null ? !legend.chart.data.datasets[index].hidden : null;
                            legend.chart.update();
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': ' + ctx.parsed.y + ' cm';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        suggestedMax: 320,
                        title: { display: true, text: 'Ketinggian (cm)' },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function(val) { return val + ' cm'; }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        gateButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                activeGateId = parseInt(btn.dataset.gateId, 10);
                applyChartView();
            });
        });

        rangeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentRange = btn.dataset.range;
                applyChartView();
            });
        });

        historiBtn.addEventListener('click', function() {
            currentRange = '7d';
            document.getElementById('tmaChartSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
            applyChartView();
        });

        function selectGateFromCard(gateId) {
            activeGateId = gateId;
            document.getElementById('tmaChartSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
            applyChartView();
        }

        sungaiCards.forEach(function(card) {
            card.addEventListener('click', function() {
                selectGateFromCard(parseInt(card.dataset.gateId, 10));
            });
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectGateFromCard(parseInt(card.dataset.gateId, 10));
                }
            });
        });

        updateChartTitle();
        updateActiveButtons();
    });
</script>
@endsection
