@extends('layouts.app')

@section('title', 'Portal Moderasi BPBD - TitikAman')

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
        --accent-red-hover: #930006;
        --bg-light: #f8f9fa;
        --card-border: rgba(196, 198, 207, 0.4);
        --green-success: #1a8754;
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



    /* Main Moderation Layout */
    .moderation-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
    }

    /* Topbar */
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

    .user-profile-widget {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-widget-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--brand-teal);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
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
        color: #6c757d;
    }

    /* 3-Column Content Body */
    .moderation-grid {
        flex: 1;
        display: grid;
        grid-template-columns: 280px 1fr 340px;
        overflow: hidden;
        background-color: #f1f3f5;
    }

    /* Column 1: Queue */
    .queue-column {
        background-color: white;
        border-right: 1px solid var(--card-border);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .column-header {
        padding: 16px;
        border-bottom: 1px solid var(--card-border);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: var(--navy-dark);
        font-size: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .queue-badge {
        background-color: var(--accent-red);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .queue-list {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .queue-item {
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        background-color: #fafbfc;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .queue-item:hover {
        border-color: var(--brand-teal);
        background-color: rgba(0, 106, 96, 0.02);
    }

    .queue-item.active {
        border-color: var(--brand-teal);
        background-color: rgba(0, 106, 96, 0.05);
        box-shadow: 0 0 0 1px var(--brand-teal);
    }

    .queue-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .height-badge {
        font-weight: 700;
        font-size: 12px;
        color: var(--accent-red);
        background-color: rgba(186, 26, 26, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .queue-item-street {
        font-weight: 600;
        font-size: 13px;
        color: var(--navy-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .queue-item-meta {
        font-size: 11px;
        color: #6c757d;
        display: flex;
        justify-content: space-between;
    }

    /* Column 2: Detail Workspace */
    .workspace-column {
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .workspace-card {
        background-color: white;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .workspace-header {
        border-bottom: 1px solid #f1f3f5;
        padding-bottom: 16px;
        margin-bottom: 20px;
    }

    .workspace-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--navy-dark);
        margin: 0 0 6px 0;
    }

    .workspace-subtitle {
        font-size: 13px;
        color: #6c757d;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #879ec6;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--navy-dark);
    }

    .checklist-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        border: 1px solid var(--card-border);
    }

    .checklist-title {
        font-weight: 700;
        font-size: 13px;
        color: var(--navy-dark);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .checklist-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
    }

    .checklist-item.active {
        color: var(--accent-red);
    }

    .checklist-item.inactive {
        color: #adb5bd;
    }

    .evidence-box {
        margin-bottom: 24px;
    }

    .evidence-image {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--card-border);
    }

    .action-row {
        display: flex;
        gap: 12px;
        margin-top: 12px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-verify {
        background-color: var(--brand-teal);
        color: white;
    }

    .btn-verify:hover {
        background-color: var(--brand-teal-hover);
    }

    .btn-reject {
        background-color: var(--accent-red);
        color: white;
    }

    .btn-reject:hover {
        background-color: var(--accent-red-hover);
    }

    /* Column 3: Summary Map & Modern Logs */
    .summary-column {
        background-color: white;
        border-left: 1px solid var(--card-border);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .summary-map-container {
        height: 200px;
        width: 100%;
        border-bottom: 1px solid var(--card-border);
        position: relative;
    }

    #summary-map {
        height: 100%;
        width: 100%;
    }

    .logs-container {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }

    .log-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        overflow-y: auto;
    }

    .log-item {
        border-left: 3px solid #ccc;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: 0 6px 6px 0;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .log-item.verified {
        border-left-color: var(--green-success);
    }

    .log-item.rejected {
        border-left-color: var(--accent-red);
    }

    .log-item-header {
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        color: var(--navy-dark);
    }

    .log-item-desc {
        color: #495057;
    }

    .export-btn-container {
        padding: 16px;
        border-top: 1px solid var(--card-border);
        background-color: #fafbfc;
    }

    .btn-export {
        width: 100%;
        padding: 12px;
        background-color: var(--navy-sidebar);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-export:hover {
        background-color: var(--navy-dark);
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Moderation Main -->
    <div class="moderation-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left">
                <h1>Moderasi Laporan Warga</h1>
            </div>
            <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">
                <div class="notification-bell" title="Notifikasi" style="position: relative; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: #f1f3f5; color: var(--color-neutral-dark);">
                    <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                    <div class="notification-dot" style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background-color: var(--color-accent-red); border-radius: 50%;"></div>
                </div>
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname, 0, 2)) }}
                    </div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->user()->fullname }}</span>
                        <span class="user-widget-role">Admin BPBD</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 24px; font-weight: 500; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Moderation 3-Column Grid -->
        <div class="moderation-grid">
            <!-- Column 1: Moderation Queue -->
            <div class="queue-column">
                <div class="column-header">
                    <span>Antrean Moderasi</span>
                    <span class="queue-badge">{{ $pendingReports->count() }}</span>
                </div>
                <div class="queue-list">
                    @forelse($pendingReports as $rep)
                        <a href="{{ route('admin.dashboard', ['report_id' => $rep->report_id]) }}" class="queue-item {{ $selectedReport && $selectedReport->report_id == $rep->report_id ? 'active' : '' }}">
                            <div class="queue-item-header">
                                <span class="queue-item-street">{{ $rep->street_name }}</span>
                                <span class="height-badge">{{ $rep->water_height_cm }} cm</span>
                            </div>
                            <div class="queue-item-meta">
                                <span>{{ $rep->kelurahan }}, {{ $rep->kecamatan }}</span>
                                <span>{{ $rep->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <div style="padding: 24px; text-align: center; color: #879ec6; font-size: 13px;">
                            Tidak ada laporan tertunda
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: Workspace / Detail -->
            <div class="workspace-column">
                @if($selectedReport)
                    <div class="workspace-card">
                        <div class="workspace-header">
                            <h2 class="workspace-title">{{ $selectedReport->street_name }}</h2>
                            <span class="workspace-subtitle">Dilaporkan oleh: <strong>{{ $selectedReport->user->fullname }}</strong> ({{ $selectedReport->user->phone }})</span>
                        </div>

                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Kecamatan / Kelurahan</span>
                                <span class="detail-value">{{ $selectedReport->kecamatan }} / {{ $selectedReport->kelurahan }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ketinggian Air</span>
                                <span class="detail-value text-red">{{ $selectedReport->water_height_cm }} cm</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Akses Jalan</span>
                                <span class="detail-value">{{ $selectedReport->status_akses_jalan }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Waktu Pengiriman</span>
                                <span class="detail-value">{{ $selectedReport->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="checklist-container">
                            <div class="checklist-title">Cross-Check Status Laporan</div>
                            <div class="checklist-grid">
                                <div class="checklist-item {{ $selectedReport->listrik_padam ? 'active' : 'inactive' }}">
                                    <i data-lucide="{{ $selectedReport->listrik_padam ? 'check-square' : 'square' }}"></i>
                                    <span>Listrik Padam</span>
                                </div>
                                <div class="checklist-item {{ $selectedReport->air_masih_naik ? 'active' : 'inactive' }}">
                                    <i data-lucide="{{ $selectedReport->air_masih_naik ? 'check-square' : 'square' }}"></i>
                                    <span>Air Masih Naik</span>
                                </div>
                                <div class="checklist-item {{ $selectedReport->butuh_evakuasi ? 'active' : 'inactive' }}">
                                    <i data-lucide="{{ $selectedReport->butuh_evakuasi ? 'check-square' : 'square' }}"></i>
                                    <span>Butuh Evakuasi</span>
                                </div>
                                <div class="checklist-item {{ $selectedReport->warga_terisolasi ? 'active' : 'inactive' }}">
                                    <i data-lucide="{{ $selectedReport->warga_terisolasi ? 'check-square' : 'square' }}"></i>
                                    <span>Warga Terisolasi</span>
                                </div>
                            </div>
                        </div>

                        @if($selectedReport->keterangan_bebas)
                            <div style="margin-bottom: 24px;">
                                <span class="detail-label">Keterangan Tambahan</span>
                                <p style="margin: 6px 0 0 0; font-size: 13px; color: #495057; line-height: 1.5; background-color: #f8f9fa; padding: 12px; border-radius: 6px;">
                                    "{{ $selectedReport->keterangan_bebas }}"
                                </p>
                            </div>
                        @endif

                        @if($selectedReport->photo_evidence)
                            <div class="evidence-box">
                                <span class="detail-label" style="display: block; margin-bottom: 6px;">Bukti Foto Genangan</span>
                                <img src="{{ asset('storage/' . $selectedReport->photo_evidence) }}" class="evidence-image" alt="Bukti Foto">
                            </div>
                        @endif

                        <div class="action-row">
                            <form action="{{ route('admin.report.verify', $selectedReport->report_id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn-action btn-verify">
                                    <i data-lucide="check-circle-2"></i>
                                    <span>Verifikasi / Terima</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.report.reject', $selectedReport->report_id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn-action btn-reject">
                                    <i data-lucide="x-circle"></i>
                                    <span>Tolak Laporan</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="workspace-card" style="text-align: center; padding: 80px 24px; color: #879ec6;">
                        <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
                        <h3>Semua Laporan Selesai Dimoderasi</h3>
                        <p style="font-size: 13px;">Tidak ada antrean laporan banjir warga yang perlu divalidasi saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- Column 3: Map & Logs -->
            <div class="summary-column">
                <div class="column-header">
                    <span>Ringkasan Peta Lokasi</span>
                </div>
                <div class="summary-map-container">
                    <div id="summary-map"></div>
                </div>

                <div class="logs-container">
                    <span class="detail-label" style="margin-bottom: 8px;">Log Moderasi Hari Ini</span>
                    <div class="log-list">
                        @forelse($moderatedToday as $log)
                            <div class="log-item {{ $log->verification_status }}">
                                <div class="log-item-header">
                                    <span>{{ $log->street_name }}</span>
                                    <span class="text-teal font-bold">{{ $log->water_height_cm }} cm</span>
                                </div>
                                <div class="log-item-desc">
                                    Tindakan: <strong>{{ strtoupper($log->verification_status) }}</strong> pada {{ $log->updated_at->format('H:i') }}
                                </div>
                            </div>
                        @empty
                            <div style="font-size: 11px; text-align: center; color: #adb5bd; padding: 24px;">
                                Belum ada tindakan moderasi hari ini
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="export-btn-container">
                    <a href="{{ route('admin.report.export') }}" class="btn-export">
                        <i data-lucide="download"></i>
                        <span>Ekspor Laporan (CSV)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        const map = L.map('summary-map').setView([-6.24250000, 107.00220000], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for verified reports
        @foreach($verifiedReports as $rep)
            @if($rep->latitude && $rep->longitude)
                L.marker([{{ $rep->latitude }}, {{ $rep->longitude }}])
                    .addTo(map)
                    .bindPopup("<strong>{{ $rep->street_name }}</strong><br>Tinggi: {{ $rep->water_height_cm }} cm");
            @endif
        @endforeach

        @if($selectedReport && $selectedReport->latitude && $selectedReport->longitude)
            // Focus on currently selected report
            map.setView([{{ $selectedReport->latitude }}, {{ $selectedReport->longitude }}], 14);
            L.circle([{{ $selectedReport->latitude }}, {{ $selectedReport->longitude }}], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.3,
                radius: 200
            }).addTo(map).bindPopup("<strong>Laporan Sedang Diproses</strong>").openPopup();
        @endif
    });
</script>
@endsection
