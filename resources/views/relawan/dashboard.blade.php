@extends('layouts.dashboard')

@section('title', 'Dashboard Relawan - TitikAman')

@section('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/relawan-dashboard.css') }}">
@endsection

@section('topbar-left')
    <h1 class="topbar-title">Mission Control Relawan</h1>
@endsection

@section('dashboard-content')
    {{-- ===================== MAIN CANVAS ===================== --}}
    <div class="relawan-main-canvas" style="padding-top: 0;">
        <div class="relawan-topbar" style="display: none;">
            <div class="topbar-left">
                <h1 class="topbar-title">Mission Control Relawan</h1>
                <div class="topbar-sos-badge">
                    <i data-lucide="bell-ring"></i>
                    <span>{{ $sosAntriCount }} SOS Aktif</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-search-wrap">
                    <i data-lucide="search"></i>
                    <input type="text" class="topbar-search" placeholder="Cari misi atau koordinat...">
                </div>
                <div class="topbar-icon-btn notification-bell" title="Notifikasi" style="position: relative; cursor: pointer;">
                    <i data-lucide="bell"></i>
                    <div class="notification-dot" style="position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background-color: var(--color-accent-red); border-radius: 50%;"></div>
                </div>
                <div class="topbar-icon-btn">
                    <i data-lucide="user-circle"></i>
                </div>
            </div>
        </div>

        {{-- ============== CONTENT AREA ============== --}}
        <div class="relawan-content-area">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="dashboard-toast" style="margin-bottom: 0;">
                    <i data-lucide="check-circle" style="color: #006a60; width: 20px; height: 20px;"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="toast-close">
                        <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="dashboard-toast" style="margin-bottom: 0; border-left-color: #ba1a1a;">
                    <i data-lucide="alert-octagon" style="color: #ba1a1a; width: 20px; height: 20px;"></i>
                    <span style="color: #b61722;">{{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="toast-close">
                        <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            @endif

            {{-- ============ ROW 1: STAT CARDS ============ --}}
            <div class="stat-cards-row">
                <div class="stat-card danger">
                    <div class="stat-card-header">
                        <span class="stat-card-label danger">SOS Antri</span>
                        <i data-lucide="bell" class="stat-card-icon" style="color:#ba1a1a;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($sosAntriCount, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Permintaan menunggu respons</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-card-header">
                        <span class="stat-card-label danger">Prioritas Tinggi</span>
                        <i data-lucide="alert-triangle" class="stat-card-icon" style="color:#ba1a1a;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($highPrioritySos, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Butuh evakuasi segera</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-card-header">
                        <span class="stat-card-label warning">Misi Aktifku</span>
                        <i data-lucide="user-check" class="stat-card-icon" style="color:#d97706;"></i>
                    </div>
                    <div class="stat-card-value">{{ $misiAktifku }}</div>
                    <div class="stat-card-sub">{{ $activeMission ? $activeMission->sosRequest->user->kelurahan ?? 'Sedang berjalan' : 'Tidak ada misi aktif' }}</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-card-header">
                        <span class="stat-card-label success">Misi Selesai</span>
                        <i data-lucide="check-circle-2" class="stat-card-icon" style="color:#006a60;"></i>
                    </div>
                    <div class="stat-card-value">{{ str_pad($misiSelesaiCount, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="stat-card-sub">Akumulasi hari ini</div>
                </div>
                <div class="stat-card navy">
                    <div class="stat-card-header">
                        <span class="stat-card-label navy">Relawan Online</span>
                        <i data-lucide="users" class="stat-card-icon" style="color:#031f41;"></i>
                    </div>
                    <div class="stat-card-value">—</div>
                    <div class="stat-card-sub">Sekitar wilayah Bekasi</div>
                </div>
            </div>

            {{-- ============ ROW 2: 3-COLUMN LAYOUT ============ --}}
            <div class="main-grid">

                {{-- Column 1: Antrian SOS --}}
                <div class="sos-queue-panel">
                    <div class="panel-header">
                        <div class="panel-header-left">
                            <i data-lucide="list-ordered"></i>
                            <span>Antrian SOS Terbaru</span>
                        </div>
                        <span class="panel-count-badge">{{ $sosAntriCount }} REQUEST</span>
                    </div>
                    <div class="panel-body" id="sos-queue-container">
                        @forelse ($waitingSos as $sos)
                            @php
                                $priorityLabel = match($sos->priority_level) {
                                    'high'   => 'TINGGI',
                                    'medium' => 'SEDANG',
                                    'low'    => 'RENDAH',
                                    default  => strtoupper($sos->priority_level)
                                };
                            @endphp
                            <div class="sos-item {{ $sos->priority_level }}" data-sos-id="{{ $sos->sos_id }}">
                                <div class="sos-item-header">
                                    <span class="sos-priority-badge {{ $sos->priority_level }}">{{ $priorityLabel }}</span>
                                    <span class="sos-time">{{ $sos->created_at->format('H:i') }} WIB</span>
                                </div>
                                <div class="sos-location">
                                    {{ $sos->user->kelurahan ?? 'Lokasi' }}, {{ $sos->user->kecamatan ?? 'Bekasi' }}
                                </div>
                                <div class="sos-tags">
                                    <span class="sos-tag">
                                        <i data-lucide="users"></i>
                                        {{ $sos->people_trapped }} Orang
                                    </span>
                                    @if($sos->vulnerable_groups_count > 0)
                                        <span class="sos-tag">
                                            <i data-lucide="accessibility"></i>
                                            {{ $sos->vulnerable_groups_count }} Rentan
                                        </span>
                                    @endif
                                    @if($sos->description)
                                        <span class="sos-tag{{ $sos->priority_level === 'high' ? ' danger' : '' }}">
                                            <i data-lucide="alert-circle"></i>
                                            {{ Str::limit($sos->description, 20) }}
                                        </span>
                                    @endif
                                </div>
                                @if(!$activeMission)
                                    <form action="{{ route('relawan.mission.accept') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="sos_id" value="{{ $sos->sos_id }}">
                                        <button type="submit" class="btn-accept-mission">AMBIL MISI INI</button>
                                    </form>
                                @else
                                    <button type="button" class="btn-tinjau" onclick="focusOnSos({{ $sos->latitude }}, {{ $sos->longitude }})">
                                        TINJAU DETAIL
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div style="text-align: center; padding: 32px 16px; color: #6b7280; font-size: 14px; display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                <i data-lucide="smile" style="width: 48px; height: 48px; color: #006a60;"></i>
                                <span>Tidak ada permintaan SOS aktif saat ini. Semua wilayah aman.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Column 2: Peta Operasional --}}
                <div class="map-panel">
                    <div class="map-panel-header">
                        <div class="map-panel-header-left">
                            <i data-lucide="map-pin"></i>
                            <span>Peta Operasional Bekasi</span>
                        </div>
                        <div class="map-panel-actions">
                            <button class="map-icon-btn" title="Refresh peta">
                                <i data-lucide="refresh-cw"></i>
                            </button>
                            <button class="map-icon-btn" title="Fullscreen">
                                <i data-lucide="maximize-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="map-wrapper">
                        <div id="volunteer-map"></div>
                        <div class="map-legend">
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #ba1a1a;"></div>
                                <span>Prioritas Tinggi</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #f59e0b;"></div>
                                <span>Prioritas Sedang</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background: #006a60;"></div>
                                <span>Prioritas Rendah</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 3: Misi Aktif + Statistik --}}
                <div class="right-panel-col">

                    @if ($activeMission)
                        {{-- Active Mission Card --}}
                        <div class="active-mission-card">
                            <div class="active-mission-header">
                                <div class="active-mission-title">MISI AKTIF SAAT INI</div>
                                <span class="status-badge-diproses">DIPROSES</span>
                            </div>
                            <div>
                                <div class="mission-info-block">
                                    <div class="mission-info-label">LOKASI</div>
                                    <div class="mission-info-value">
                                        {{ $activeMission->sosRequest->user->kelurahan ?? 'Lokasi' }},
                                        {{ $activeMission->sosRequest->user->kecamatan ?? 'Bekasi' }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mission-info-block">
                                    <div class="mission-info-label">PELAPOR</div>
                                    <div class="mission-reporter-row">
                                        <div class="mission-info-value">{{ $activeMission->sosRequest->user->fullname }}</div>
                                        <a href="tel:{{ $activeMission->sosRequest->user->phone }}" class="btn-phone-call">
                                            <i data-lucide="phone"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mission-action-btns">
                                <a href="https://maps.google.com/maps?q={{ $activeMission->sosRequest->latitude }},{{ $activeMission->sosRequest->longitude }}"
                                   target="_blank" class="btn-maps">
                                    <i data-lucide="map"></i>
                                    MAPS
                                </a>
                                <form action="{{ route('relawan.mission.complete', $activeMission->mission_id) }}" method="POST"
                                      onsubmit="return confirm('Konfirmasi: Korban sudah berhasil dievakuasi dengan aman?')">
                                    @csrf
                                    <button type="submit" class="btn-selesai" style="width: 100%;">
                                        <i data-lucide="check-circle-2"></i>
                                        SELESAI
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="idle-mission-card">
                            <i data-lucide="shield-check"></i>
                            <div style="font-weight: 700; color: #006a60;">Siap Bertugas</div>
                            <div style="font-size: 12px; color: #44474e;">Pilih misi SOS dari antrian untuk mulai bertugas</div>
                        </div>
                    @endif

                    {{-- Daily Stats --}}
                    <div class="daily-stats-card">
                        <div class="daily-stats-title">Statistik Hari Ini</div>
                        <div class="stats-grid">
                            <div class="stat-box">
                                <div class="stat-box-value">{{ $misiSelesaiCount }}</div>
                                <div class="stat-box-label">Misi Selesai</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value" style="font-size: 18px;">
                                    {{ $avgResponseMinutes > 0 ? $avgResponseMinutes . 'm' : '—' }}
                                </div>
                                <div class="stat-box-label">Rata Respon</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value">{{ $sosAntriCount + $misiSelesaiCount + $misiAktifku }}</div>
                                <div class="stat-box-label">Total SOS Hari Ini</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-value">4.9</div>
                                <div class="stat-box-label">Rating</div>
                            </div>
                        </div>

                        @if($completedMissions->isNotEmpty())
                            <div class="activity-section-label">
                                <i data-lucide="clock"></i>
                                AKTIVITAS TERAKHIR
                            </div>
                            <div class="activity-timeline">
                                @foreach($completedMissions->take(3) as $mission)
                                    <div class="activity-item">
                                        <div class="activity-dot {{ $loop->first ? 'teal' : 'navy' }}"></div>
                                        <div class="activity-text-wrap">
                                            <div class="activity-title">
                                                Misi Selesai: {{ $mission->sosRequest->user->kelurahan ?? 'Lokasi' }}
                                            </div>
                                            <div class="activity-meta">
                                                {{ $mission->resolved_at->format('H:i') }} WIB
                                                • {{ $mission->sosRequest->people_trapped }} Orang terevakuasi
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 16px 0; color: #6b7280; font-size: 12px;">
                                Belum ada aktivitas hari ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============ ROW 2.5: PENDAFTAR TIM ============ --}}
            <div class="history-panel" style="margin-top: 24px; margin-bottom: 24px;">
                <div class="history-panel-header">
                    <div class="history-panel-header-left">
                        <i data-lucide="users"></i>
                        <span>Pendaftar Anggota Tim Baru</span>
                    </div>
                    <span style="font-size: 11px; font-weight: 700; color: #031f41; cursor: pointer;">KELOLA ANGGOTA</span>
                </div>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>No. HP</th>
                                <th>Keahlian</th>
                                <th>Organisasi</th>
                                <th>Waktu Mendaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftarTim as $pendaftar)
                                <tr>
                                    <td class="td-regular" style="font-weight: 600; color: #111827;">{{ $pendaftar->fullname }}</td>
                                    <td class="td-regular">{{ $pendaftar->phone }}</td>
                                    <td class="td-regular">{{ $pendaftar->keahlian ?? '-' }}</td>
                                    <td class="td-regular">{{ $pendaftar->organisasi ?? '-' }}</td>
                                    <td class="td-time">{{ $pendaftar->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr class="empty-history-row">
                                    <td colspan="5">Belum ada anggota baru yang mendaftar hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============ ROW 3: RIWAYAT MISI TABLE ============ --}}
            <div class="history-panel">
                <div class="history-panel-header">
                    <div class="history-panel-header-left">
                        <i data-lucide="clipboard-list"></i>
                        <span>Riwayat Misi Hari Ini</span>
                    </div>
                    <span style="font-size: 11px; font-weight: 700; color: #031f41; cursor: pointer;">LIHAT SEMUA</span>
                </div>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Waktu Selesai</th>
                                <th>Lokasi</th>
                                <th>Jumlah Orang</th>
                                <th>Kelompok Rentan</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedMissions as $mission)
                                <tr>
                                    <td class="td-time">{{ $mission->resolved_at->format('H:i') }} WIB</td>
                                    <td class="td-location">
                                        {{ $mission->sosRequest->user->kelurahan ?? 'Lokasi' }},
                                        {{ $mission->sosRequest->user->kecamatan ?? 'Bekasi' }}
                                    </td>
                                    <td class="td-regular">{{ $mission->sosRequest->people_trapped }} Orang</td>
                                    <td class="td-regular">{{ $mission->sosRequest->vulnerable_groups_count }} Orang</td>
                                    <td class="td-duration">
                                        {{ (int) $mission->resolved_at->diffInMinutes($mission->created_at) }} Menit
                                    </td>
                                    <td>
                                        <span class="badge-terkonsepsi">TERKONSEPSI</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-history-row">
                                    <td colspan="6">Belum ada misi selesai hari ini. Terus semangat! 💪</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end .relawan-content-area --}}
    </div>{{-- end .relawan-main-canvas --}}
@endsection

@section('dashboard-scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@php
    // Prepare map data as simple PHP variables to avoid Blade @json() parse errors
    $activeMissionMapData = $activeMission ? [
        'lat'    => (float) $activeMission->sosRequest->latitude,
        'lng'    => (float) $activeMission->sosRequest->longitude,
        'name'   => $activeMission->sosRequest->user->fullname,
        'people' => (int) $activeMission->sosRequest->people_trapped,
    ] : null;

    $sosQueueMapData = $waitingSos->map(function ($s) {
        return [
            'lat'      => (float) $s->latitude,
            'lng'      => (float) $s->longitude,
            'priority' => $s->priority_level,
            'location' => ($s->user->kelurahan ?? '') . ', ' . ($s->user->kecamatan ?? ''),
            'people'   => (int) $s->people_trapped,
        ];
    })->values()->all();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Map Initialization ----

    // ---- Leaflet Map ----
    const DEFAULT_LAT = -6.2383;
    const DEFAULT_LNG = 106.9922;

    const map = L.map('volunteer-map', { zoomControl: false }).setView([DEFAULT_LAT, DEFAULT_LNG], 13);
    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 20
    }).addTo(map);

    // Volunteer marker (navy)
    const volunteerIcon = L.divIcon({
        html: `<div class="volunteer-marker"></div>`,
        className: '',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    let volunteerMarker = L.marker([DEFAULT_LAT, DEFAULT_LNG], { icon: volunteerIcon })
        .addTo(map)
        .bindTooltip('Posisi Anda', { permanent: true, direction: 'top', className: 'volunteer-tooltip' });

    // Try geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            volunteerMarker.setLatLng([lat, lng]);
        });
    }

    // Plot active mission victim
    const activeMissionData = @json($activeMissionMapData ?? null);

    if (activeMissionData) {
        const victimIcon = L.divIcon({
            html: `<div class="victim-pulse"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([activeMissionData.lat, activeMissionData.lng], { icon: victimIcon })
            .addTo(map)
            .bindPopup(`<b>KORBAN AKTIF:</b> ${activeMissionData.name}<br>${activeMissionData.people} jiwa terjebak`)
            .openPopup();
        map.setView([activeMissionData.lat, activeMissionData.lng], 14);
    }

    // Plot SOS queue markers
    const sosQueue = @json($sosQueueMapData);

    const priorityColors = { high: '#ba1a1a', medium: '#f59e0b', low: '#006a60' };

    sosQueue.forEach(function (sos) {
        if (!sos.lat || !sos.lng) return;
        const color = priorityColors[sos.priority] || '#031f41';
        const icon = L.divIcon({
            html: `<div style="width:16px;height:16px;border-radius:50%;background:${color};border:2px solid white;box-shadow:0 0 0 3px ${color}44;"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([sos.lat, sos.lng], { icon })
            .addTo(map)
            .bindPopup(`<b>${sos.location}</b><br>${sos.people} jiwa terjebak`);
    });

    // Global helper for "tinjau detail" button
    window.focusOnSos = function(lat, lng) {
        map.setView([lat, lng], 15);
    };

    // ---- SOS Queue Auto-Refresh every 30s ----
    setInterval(function () {
        fetch('{{ route("relawan.sos.data") }}')
            .then(res => res.json())
            .catch(() => {});
    }, 30000);

    lucide.createIcons();
});
</script>
@endsection