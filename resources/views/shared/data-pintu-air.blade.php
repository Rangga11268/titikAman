@extends('layouts.app')

@section('title', 'Data Tinggi Muka Air (TMA) - TitikAman')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/shared-tma.css') }}">
@endsection

@section('content')
<div class="dashboard-container">
    @include('partials.sidebar')

    <div class="dashboard-main">
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

        <div class="dashboard-body">
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
                    <div class="sungai-card {{ $statusClass }}" data-gate-id="{{ $gate->gate_id }}" role="button" tabindex="0">
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

            <div class="bottom-grid">
                <div class="table-card">
                    <div class="table-header">
                        <span class="table-title">Status Terkini — Semua Pintu Air
                            @if($featuredGate)
                                <span style="font-size:12px;font-weight:500;color:var(--color-text-muted);"> ({{ $totalGates }} pintu air terpantau)</span>
                            @endif
                        </span>
                        <button class="btn-outline" style="padding: 6px 12px; font-size: 12px;" onclick="window.location.href='{{ route('watergate.export') }}'">Unduh Data CSV</button>
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

                <div class="side-stack">
                    <div class="side-card">
                        <span class="side-card-title">Batas Ketinggian Status Siaga</span>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div class="batas-row">
                                <div class="batas-label-row text-red">
                                    <span>SIAGA 1 (BAHAYA)</span>
                                    <span>> 250 cm</span>
                                </div>
                                <div class="bar-track"><div class="bar-fill red" style="width: 100%;"></div></div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-orange">
                                    <span>SIAGA 2 (WASPADA)</span>
                                    <span>150 - 250 cm</span>
                                </div>
                                <div class="bar-track"><div class="bar-fill orange" style="width: 60%;"></div></div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-yellow">
                                    <span>SIAGA 3 (SIAGA)</span>
                                    <span>80 - 150 cm</span>
                                </div>
                                <div class="bar-track"><div class="bar-fill yellow" style="width: 40%;"></div></div>
                            </div>
                            <div class="batas-row">
                                <div class="batas-label-row text-teal">
                                    <span>NORMAL</span>
                                    <span>< 80 cm</span>
                                </div>
                                <div class="bar-track"><div class="bar-fill green" style="width: 20%;"></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="side-card">
                        <span class="side-card-title">Log Notifikasi Otomatis</span>
                        <div class="notif-list">
                            @forelse($alertLog as $alert)
                                <div class="notif-item {{ $alert['type'] }}">
                                    <div class="notif-icon">
                                        <i data-lucide="{{ $alert['icon'] }}" style="width: 16px; height: 16px; {{ $alert['type'] == 'red' ? 'color: var(--accent-red);' : ($alert['type'] == 'orange' ? 'color: var(--accent-orange);' : 'color: #3b82f6;') }}"></i>
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

        <footer class="dashboard-footer">
            <div class="footer-grid">
                <div class="footer-branding">
                    <span class="brand-title">TitikAman</span>
                    <p class="footer-desc">Sistem Informasi Manajemen Kebencanaan Kota Bekasi.</p>
                </div>
                <div>
                    <h3 class="footer-col-title">AKSES CEPAT</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard Utama</a></li>
                        <li><a href="{{ route('peta.evakuasi') }}" class="footer-link">Peta Evakuasi</a></li>
                        <li><a href="{{ route('posko') }}" class="footer-link">Posko Aktif</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2026 TitikAman Kota Bekasi.</span>
            </div>
        </footer>
    </div>
</div>
@endsection

@section('scripts')
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
                chartSubtitleEl.textContent = activeGate ? 'Tren mingguan — ' + activeGate.label : 'Tren mingguan tinggi muka air sungai utama';
            } else {
                chartTitleEl.textContent = 'Grafik TMA 24 Jam Terakhir';
                chartSubtitleEl.textContent = activeGate ? 'Visualisasi — ' + activeGate.label : 'Visualisasi tinggi muka air sungai utama';
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
            data: { labels: chartData24h.labels, datasets: getActiveDatasets() },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: function(ctx) { return ctx.dataset.label + ': ' + ctx.parsed.y + ' cm'; } } }
                },
                scales: {
                    y: {
                        min: 0,
                        suggestedMax: 320,
                        title: { display: true, text: 'Ketinggian (cm)' },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { callback: function(val) { return val + ' cm'; } }
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

        sungaiCards.forEach(function(card) {
            card.addEventListener('click', function() {
                activeGateId = parseInt(card.dataset.gateId, 10);
                document.getElementById('tmaChartSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
                applyChartView();
            });
        });

        updateChartTitle();
        updateActiveButtons();
    });
</script>
@endsection