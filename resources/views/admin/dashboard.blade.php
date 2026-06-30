@extends('layouts.dashboard')

@section('title', 'Portal Moderasi BPBD - TitikAman')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endsection

@section('topbar-left')
    <h1>Moderasi Laporan Warga</h1>
@endsection

@section('dashboard-content')
    <!-- Moderation Main -->
    <div class="moderation-main" style="padding-top: 0; display: flex; flex-direction: column; flex: 1; overflow-y: auto;">
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
                                <div class="log-item-desc" style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>Tindakan: <strong>{{ strtoupper($log->verification_status) }}</strong> pada {{ $log->updated_at->format('H:i') }}</span>
                                    @if($log->verification_status === 'verified' && $log->water_height_cm > 0)
                                    <form action="{{ route('admin.report.resolve', $log->report_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Tandai titik banjir ini sudah surut (0 cm)?');">
                                        @csrf
                                        <button type="submit" style="background: none; border: 1px solid var(--color-accent-green); color: var(--color-accent-green); border-radius: 4px; padding: 2px 8px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                            Set Surut
                                        </button>
                                    </form>
                                    @endif
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
@endsection

@section('dashboard-scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        const map = L.map('summary-map').setView([-6.24250000, 107.00220000], 12);
    // Batasi peta ke area Bekasi
    const bekasiBounds = L.latLngBounds([-6.5, 106.8], [-6.0, 107.3]);
    if(typeof map !== 'undefined') { map.setMaxBounds(bekasiBounds); map.setMinZoom(10); }
    if(typeof detailMap !== 'undefined') { detailMap.setMaxBounds(bekasiBounds); detailMap.setMinZoom(10); }
    if(typeof miniMap !== 'undefined') { miniMap.setMaxBounds(bekasiBounds); miniMap.setMinZoom(10); }

        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for verified reports
        @foreach($verifiedReports as $rep)
            @if($rep->latitude && $rep->longitude)
                L.marker([{{ $rep->latitude }}, {{ $rep->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <div style="text-align: center;">
                            <strong>{{ $rep->street_name }}</strong><br>
                            Tinggi Air: {{ $rep->water_height_cm }} cm<br>
                            <form action="{{ route('admin.report.resolve', $rep->report_id) }}" method="POST" style="margin-top: 10px;" onsubmit="return confirm('Tandai titik banjir ini sudah surut (0 cm)?');">
                                @csrf
                                <button type="submit" style="background: var(--color-accent-green); color: white; border: none; border-radius: 4px; padding: 4px 10px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Tandai Surut
                                </button>
                            </form>
                        </div>
                    `);
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
