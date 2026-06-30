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
            
            @if(session('error'))
                <div class="alert-danger" style="margin-bottom: 24px; padding: 16px; background: #fee2e2; color: #b91c1c; border-radius: 8px;">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('approved_member_name'))
                <div style="background: linear-gradient(135deg, #eff6ff, #f0f5ff); padding: 16px; border-radius: 12px; margin-bottom: 24px; border: 2px solid #2563eb; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;">
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 4px 0; color: #2563eb; font-size: 15px;">Anggota Baru Disetujui!</h3>
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #374151;">
                            <strong>{{ session('approved_member_name') }}</strong> telah bergabung dengan <strong>{{ session('approved_member_team') }}</strong>.
                        </p>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            @if(session('approved_wa_send_url') && session('approved_wa_send_url') !== '#')
                                <a href="{{ session('approved_wa_send_url') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Kirim Info via WA ke {{ session('approved_member_name') }}
                                </a>
                            @endif
                            <a href="{{ session('approved_wa_group_link') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #006a60; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;">
                                <i data-lucide="link" style="width: 16px; height: 16px;"></i>
                                Link Grup {{ session('approved_member_team') }}
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('relawan.dismiss.approval') }}" style="background: none; border: none; cursor: pointer; color: #9ca3af; padding: 4px; text-decoration: none;">
                        <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
            @endif

            @if(session('wa_url'))
                <div class="wa-banner" style="background: linear-gradient(135deg, #e0f2f1, #f0fdfa); padding: 20px; border-radius: 12px; margin-bottom: 24px; border: 2px solid #006a60; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <span style="background: #006a60; color: white; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 8px; border-radius: 4px;">MISI BARU</span>
                        </div>
                        <h3 style="margin: 0 0 6px 0; color: #006a60; font-size: 16px;">Misi Berhasil Ditugaskan!</h3>
                        <p style="margin: 0 0 4px 0; font-size: 13px; color: #374151;">
                            Tim: <strong>{{ session('wa_team_info') }}</strong> — Relawan: <strong>{{ session('wa_name') }}</strong>
                        </p>
                        <p style="margin: 0 0 12px 0; font-size: 12px; color: #6b7280;">
                            Lokasi: {{ session('wa_lokasi') }} — Pelapor: {{ session('wa_pelapor') }}
                        </p>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ session('wa_url') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; transition: background 0.2s;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Kirim ke WhatsApp ({{ session('wa_name') }})
                            </a>
                            <a href="{{ session('wa_share_team_url') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #006a60; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Share Grup {{ session('wa_team_info') }}
                            </a>
                            <a href="{{ session('wa_share_backup_url') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #b45309; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Minta Bantuan (Grup Gabungan)
                            </a>
                            <a href="{{ session('wa_maps') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: white; color: #006a60; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; border: 1px solid #006a60;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#006a60" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Buka Google Maps
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('relawan.dismiss.wa') }}" style="background: none; border: none; cursor: pointer; color: #9ca3af; padding: 4px; text-decoration: none;">
                        <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
            @endif

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
                    <div class="stat-card-sub">{{ $misiAktifku > 0 ? $misiAktifku . ' misi sedang berjalan' : 'Tidak ada misi aktif' }}</div>
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
                        <span class="stat-card-label navy">Relawan Terdaftar</span>
                        <i data-lucide="users" class="stat-card-icon" style="color:#031f41;"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalRelawan }}</div>
                    <div class="stat-card-sub">Total terverifikasi aktif</div>
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
                                    <div style="display: flex; gap: 4px; align-items: center;">
                                        @if($sos->status === 'assigned')
                                            <span style="font-size: 9px; font-weight: 700; color: #b45309; background: #fef3c7; padding: 2px 6px; border-radius: 4px;">BANTUAN</span>
                                        @endif
                                        <span class="sos-time">{{ $sos->created_at->format('H:i') }} WIB</span>
                                    </div>
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
                                @if(!$activeMissions->isNotEmpty() || true)
                                    @if($sos->status === 'waiting')
                                    <button type="button" class="btn-accept-mission" onclick="document.getElementById('assign_sos_id').value = '{{ $sos->sos_id }}'; openModal('assignModal');" style="background-color: #006a60; color: white; border: none; border-radius: 8px; font-weight: 700; width: 100%; padding: 10px; cursor: pointer; text-transform: uppercase;">
                                         TUGASKAN KE TIM
                                    </button>
                                    @else
                                    <button type="button" onclick="document.getElementById('assign_sos_id').value = '{{ $sos->sos_id }}'; openModal('assignModal');" style="background-color: #b45309; color: white; border: none; border-radius: 8px; font-weight: 700; width: 100%; padding: 10px; cursor: pointer; text-transform: uppercase;">
                                         KIRIM BANTUAN TIM
                                    </button>
                                    @endif
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
                            <button class="map-icon-btn" id="map-refresh-btn" title="Refresh peta">
                                <i data-lucide="refresh-cw"></i>
                            </button>
                            <button class="map-icon-btn" id="map-fullscreen-btn" title="Fullscreen">
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

                    @if ($activeMissions->isNotEmpty())
                        {{-- Active Mission Cards --}}
                        <div style="max-height: 400px; overflow-y: auto; padding-right: 8px; margin-bottom: 16px;">
                            @foreach($activeMissions as $activeMission)
                            <div class="active-mission-card" style="margin-bottom: 16px;">
                                <div class="active-mission-header">
                                    <div class="active-mission-title">MISI AKTIF ({{ $activeMission->volunteer->fullname ?? 'Relawan' }})</div>
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
                            @endforeach
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
                                                @if($mission->resolved_at){{ $mission->resolved_at->format('H:i') }} WIB • @endif
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

            {{-- ============ ROW 2.5: TEAM MANAGEMENT PANEL (TABLE + CARDS) ============ --}}
            <div class="history-panel" style="margin-top: 24px; margin-bottom: 24px;">
                <!-- Section A: Pendaftar Anggota Tim Baru (Table) -->
                <div class="history-panel-header">
                    <div class="history-panel-header-left">
                        <i data-lucide="user-plus"></i>
                        <span>Pendaftar Anggota Tim Baru</span>
                    </div>
                    <span class="panel-count-badge" style="background: #ca8a04; color: white; font-size: 10px; font-weight: 700; border-radius: 9999px; padding: 2px 8px;">{{ $pendaftarTim->count() }} PENDING</span>
                </div>
                <div style="overflow-x: auto; max-height: 220px; overflow-y: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>No. HP</th>
                                <th>Keahlian</th>
                                <th>Organisasi</th>
                                <th>Waktu Mendaftar</th>
                                <th style="text-align: right;">Aksi</th>
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
                                    <td style="text-align: right; display: flex; justify-content: flex-end; gap: 8px;">
                                        <button type="button" class="btn-green-link" onclick="openReviewModal({{ $pendaftar->user_id }}, '{{ addslashes($pendaftar->fullname) }}', '{{ addslashes($pendaftar->phone) }}', '{{ addslashes($pendaftar->keahlian ?? '-') }}', '{{ addslashes($pendaftar->organisasi ?? '-') }}', '{{ addslashes($pendaftar->kecamatan ?? '-') }}', '{{ addslashes($pendaftar->kelurahan ?? '-') }}', '{{ addslashes($pendaftar->document_path ?? '') }}')" style="padding: 4px 12px; font-size: 12px; border-radius: 4px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; font-weight: 600; cursor: pointer;">
                                            Review
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-history-row">
                                    <td colspan="6">Belum ada anggota baru yang mendaftar hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Section B: Anggota Tim Aktif (Cards) -->
                <div class="history-panel-header" style="border-top: 1px solid #c4c6cf;">
                    <div class="history-panel-header-left">
                        <i data-lucide="users"></i>
                        <span>Anggota Tim Aktif (Berdasarkan Wilayah)</span>
                    </div>
                </div>
                <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; background: #f8f9fa; max-height: 280px; overflow-y: auto;">
                    @forelse($anggotaTim as $teamName => $members)
                        <div class="team-card" 
                             data-team-name="{{ $teamName }}" 
                              data-members="{!! htmlspecialchars(json_encode($members->map(fn($m) => [
                                 'id' => $m->user_id,
                                 'name' => $m->fullname,
                                 'phone' => $m->phone,
                                 'keahlian' => $m->keahlian ?? '',
                                 'organisasi' => $m->organisasi ?? '',
                                 'kecamatan' => $m->kecamatan ?? '',
                                 'kelurahan' => $m->kelurahan ?? '',
                                 'in_mission' => in_array($m->user_id, $activeVolunteerIds)
                              ])->values()->all()), ENT_QUOTES, 'UTF-8') !!}"
                             onclick="openTeamMembersModal(this)"
                             style="min-width: 0; flex: none; border-left-color: var(--brand-teal); margin: 0; padding: 16px;">
                            <div class="team-card-header">
                                <span class="team-card-title">{{ $teamName }}</span>
                                <i data-lucide="users" class="team-card-icon" style="color: var(--brand-teal);"></i>
                            </div>
                            <div class="team-card-value">{{ str_pad($members->count(), 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="team-card-sub">Anggota aktif terdaftar</div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; color: #6b7280; font-size: 13px; padding: 24px 0;">
                            <i data-lucide="users" style="width: 32px; height: 32px; margin: 0 auto 8px; color: #9ca3af;"></i>
                            <span>Belum ada anggota tim aktif.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ============ ROW 3: RIWAYAT MISI TABLE ============ --}}
            <div class="history-panel">
                <div class="history-panel-header">
                    <div class="history-panel-header-left">
                        <i data-lucide="clipboard-list"></i>
                        <span>Riwayat Misi</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span style="font-size: 11px; font-weight: 600; color: #6b7280;">{{ $totalMissionsCount }} misi</span>
                        <a href="{{ route('relawan.mission.export') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #006a60; text-decoration: none; padding: 4px 10px; border: 1px solid #006a60; border-radius: 6px;">
                            <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Waktu Selesai</th>
                                <th>Lokasi</th>
                                <th>Tim / Lead</th>
                                <th>Jumlah Orang</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedMissionsDisplay as $mission)
                                <tr>
                                    <td data-label="Waktu Selesai" class="td-time">{{ $mission->resolved_at ? $mission->resolved_at->format('d M H:i') : ($mission->assigned_at ? $mission->assigned_at->format('d M H:i') : '-') }}</td>
                                    <td data-label="Lokasi" class="td-location">
                                        {{ $mission->sosRequest->user->kelurahan ?? 'Lokasi' }},
                                        {{ $mission->sosRequest->user->kecamatan ?? 'Bekasi' }}
                                    </td>
                                    <td data-label="Tim" class="td-regular">{{ $mission->volunteer ? ('Tim ' . ($mission->volunteer->kecamatan ?? 'Reguler') . ' (Lead: ' . $mission->volunteer->fullname . ')') : '-' }}</td>
                                    <td data-label="Jumlah Orang" class="td-regular">{{ $mission->sosRequest->people_trapped }} Orang</td>
                                    <td data-label="Durasi" class="td-duration">
                                        @if($mission->resolved_at)
                                            {{ (int) $mission->resolved_at->diffInMinutes($mission->created_at) }} Menit
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td data-label="Status">
                                        @if($mission->resolved_at)
                                            <span class="badge-terkonsepsi">SELESAI</span>
                                        @else
                                            <span class="badge-terkonsepsi" style="background: #fef3c7; color: #d97706;">BERJALAN</span>
                                        @endif
                                    </td>
                                    <td data-label="Aksi" style="text-align: right; white-space: nowrap;">
                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mission->volunteer->phone ?? '') }}?text=Halo%20{{ urlencode($mission->volunteer->fullname ?? 'Relawan') }}%2C%20saya%20dari%20Tim%20Admin%20Relawan%20TitikAman." target="_blank" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; font-size: 11px; font-weight: 700; color: #25D366; text-decoration: none; border: 1px solid #25D366; border-radius: 6px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                            <button type="button" class="btn-tinjau" style="padding: 4px 10px; font-size: 11px;" onclick="openMissionDetail({{ $mission->mission_id }})">Detail</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-history-row">
                                    <td colspan="7">Belum ada riwayat misi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end .relawan-content-area --}}
    </div>{{-- end .relawan-main-canvas --}}

    <!-- Review Volunteer Modal -->
    <div id="reviewModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 100%; max-width: 450px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 18px; color: var(--navy-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="user-check" style="width: 20px; height: 20px;"></i> Review Pendaftar
                </h2>
                <button type="button" onclick="closeReviewModal()" style="background: none; border: none; cursor: pointer; color: #6b7280;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <div style="margin-bottom: 24px;">
                <p><strong>Nama:</strong> <span id="rev_name"></span></p>
                <p><strong>Telepon:</strong> <span id="rev_phone"></span></p>
                <p><strong>Keahlian:</strong> <span id="rev_skill"></span></p>
                <p><strong>Organisasi:</strong> <span id="rev_org"></span></p>
                <p><strong>Lokasi:</strong> <span id="rev_loc"></span></p>
                <div id="rev_doc_wrapper" style="margin-top: 12px; display: none;">
                    <strong>Dokumen KTP / Sertifikat:</strong>
                    <div style="margin-top: 8px;">
                        <img id="rev_doc_img" src="" alt="Dokumen Verifikasi" style="max-width: 100%; max-height: 200px; border-radius: 6px; border: 1px solid rgba(196,198,207,0.4); object-fit: contain; background: #f8f9fa; display: none;">
                        <a id="rev_doc_link" href="#" target="_blank" class="btn-view-document" style="display: none; align-items: center; gap: 6px; padding: 8px 14px; background: #006a60; color: white; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                            <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                            <span>Lihat Dokumen</span>
                        </a>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <form id="rejectForm" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="padding: 10px 16px; border-radius: 6px; border: 1px solid #ef4444; background: #fef2f2; color: #b91c1c; font-weight: 600; cursor: pointer;">Tolak</button>
                </form>
                <form id="approveForm" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="padding: 10px 16px; border-radius: 6px; border: none; background: #10b981; color: white; font-weight: 600; cursor: pointer;">Terima & Masukkan Tim</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Mission Modal -->
    <div id="assignModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 100%; max-width: 450px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 18px; color: var(--navy-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="users" style="width: 20px; height: 20px;"></i> Tugaskan Misi SOS
                </h2>
                <button type="button" onclick="closeModal('assignModal')" style="background: none; border: none; cursor: pointer; color: #6b7280;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            
            <form action="{{ route('relawan.mission.accept') }}" method="POST">
                @csrf
                <input type="hidden" name="sos_id" id="assign_sos_id" value="">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Pilih Tim Respon:</label>
                    <select name="volunteer_id" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; outline: none;">
                        <option value="">-- Pilih Tim --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team['id'] }}" style="{{ in_array($team['id'], $activeVolunteerIds) ? 'color: #b45309; font-weight: 600;' : 'font-weight: 500;' }}">
                                {{ $team['label'] }} {{ in_array($team['id'], $activeVolunteerIds) ? '(Dalam Misi — Kirim Bantuan)' : '(Tersedia)' }}
                            </option>
                        @endforeach
                    </select>
                    <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">Tim yang sedang dalam misi tetap bisa dipilih sebagai bantuan (backup).</p>
                    @if($teams->isEmpty())
                        <p style="margin-top: 8px; font-size: 12px; color: #9ca3af;">Belum ada tim yang terdaftar.</p>
                    @endif
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal('assignModal')" style="padding: 10px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: white; color: #374151; font-weight: 600; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 10px 16px; border-radius: 6px; border: none; background: #006a60; color: white; font-weight: 600; cursor: pointer;">Tugaskan Misi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mission Detail Modal -->
    <div id="missionDetailModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #031f41; display: flex; align-items: center; gap: 8px; margin: 0;">
                    <i data-lucide="clipboard-list" style="width: 18px; height: 18px;"></i>
                    Detail Misi <span id="md_mission_id" style="color: #6b7280; font-weight: 500;"></span>
                </h2>
                <button type="button" onclick="closeModal('missionDetailModal')" style="background: none; border: none; cursor: pointer; color: #6b7280; padding: 4px;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px; grid-column: span 2;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Pelapor</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_reporter"></div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;" id="md_reporter_phone"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px; grid-column: span 2;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Lokasi</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_location"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Jumlah Orang</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_people"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Kelompok Rentan</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_vulnerable"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Prioritas</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_priority"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">ID SOS</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_sos_id"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px; grid-column: span 2;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Deskripsi</div>
                    <div style="font-size: 13px; color: #374151; line-height: 1.5;" id="md_description"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px; grid-column: span 2;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Relawan Ditugaskan</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_volunteer"></div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;" id="md_volunteer_phone"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Ditugaskan Pada</div>
                    <div style="font-weight: 700; color: #031f41; font-size: 12px;" id="md_assigned"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Selesai Pada</div>
                    <div style="font-weight: 700; color: #031f41; font-size: 12px;" id="md_resolved"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Durasi</div>
                    <div style="font-weight: 700; color: #031f41;" id="md_duration"></div>
                </div>
                <div style="padding: 10px; background: #f9fafb; border-radius: 8px;">
                    <div style="font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                    <div style="font-weight: 700;" id="md_status"></div>
                </div>
            </div>

            <div style="margin-top: 16px; display: flex; justify-content: flex-end;">
                <button type="button" onclick="closeModal('missionDetailModal')" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: white; color: #374151; font-weight: 600; cursor: pointer; font-size: 13px;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div id="editMemberModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 100%; max-width: 480px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #031f41; display: flex; align-items: center; gap: 8px; margin: 0;">
                    <i data-lucide="user-cog" style="width: 18px; height: 18px;"></i>
                    Edit Anggota Tim
                </h2>
                <button type="button" onclick="closeEditMemberModal()" style="background: none; border: none; cursor: pointer; color: #6b7280;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>

            <form id="editMemberForm" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf
                <input type="hidden" name="user_id" id="em_user_id">

                <div style="background: #f3f4f6; border-radius: 8px; padding: 12px;">
                    <div style="font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">ANGGOTA</div>
                    <div style="font-weight: 700; font-size: 15px; color: #111827;" id="em_name"></div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">Tim saat ini: <span id="em_current_team" style="font-weight: 600; color: #006a60;"></span></div>
                </div>

                <div style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                    <div style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 8px;">EDIT DATA</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label for="em_keahlian" style="font-size: 12px; font-weight: 700; color: #374151; display: block; margin-bottom: 4px;">Keahlian</label>
                            <input type="text" id="em_keahlian" name="keahlian" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box;" placeholder="Evakuasi, Medis">
                        </div>
                        <div>
                            <label for="em_organisasi" style="font-size: 12px; font-weight: 700; color: #374151; display: block; margin-bottom: 4px;">Organisasi</label>
                            <input type="text" id="em_organisasi" name="organisasi" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box;" placeholder="PMI, SAR">
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                    <div style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 8px;">PINDAH TIM</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label for="em_kecamatan" style="font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">Tim Tujuan (Kecamatan)</label>
                            <select id="em_kecamatan" name="kecamatan" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; background: white; box-sizing: border-box; cursor: pointer;" onchange="updateKelurahanEdit()">
                                <option value="">— Biarkan Sama —</option>
                                <option value="Pondok Gede">Pondok Gede</option>
                                <option value="Jatiasih">Jatiasih</option>
                                <option value="Bekasi Timur">Bekasi Timur</option>
                                <option value="Bekasi Selatan">Bekasi Selatan</option>
                                <option value="Bekasi Barat">Bekasi Barat</option>
                                <option value="Bekasi Utara">Bekasi Utara</option>
                                <option value="Rawalumbu">Rawalumbu</option>
                                <option value="Mustikajaya">Mustikajaya</option>
                            </select>
                        </div>
                        <div>
                            <label for="em_kelurahan" style="font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">Kelurahan</label>
                            <select id="em_kelurahan" name="kelurahan" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; background: white; box-sizing: border-box; cursor: pointer;">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>
                    <p style="font-size: 11px; color: #9ca3af; margin: 6px 0 0 0;">Pilih kecamatan yang berbeda untuk memindahkan anggota ke tim wilayah lain.</p>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px;">
                    <button type="button" onclick="closeEditMemberModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: white; color: #374151; font-weight: 600; cursor: pointer; font-size: 13px;">Batal</button>
                    <button type="submit" style="padding: 8px 16px; border-radius: 6px; border: none; background: #006a60; color: white; font-weight: 600; cursor: pointer; font-size: 13px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Team Members Modal -->
    <div id="teamMembersModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 12px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 16px; font-weight: 700; color: var(--navy-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    <span id="tmm_title">Anggota Tim</span>
                </h2>
                <button type="button" onclick="closeModal('teamMembersModal')" style="background: none; border: none; cursor: pointer; color: #6b7280; padding: 4px;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            
            <div style="overflow-x: auto; max-height: 400px; overflow-y: auto;">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tmm_body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('dashboard-scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@php
    // Prepare map data as simple PHP variables to avoid Blade @json() parse errors
    $activeMissionsMapData = [];
    foreach($activeMissions as $m) {
        $activeMissionsMapData[] = [
            'lat'    => (float) $m->sosRequest->latitude,
            'lng'    => (float) $m->sosRequest->longitude,
            'name'   => $m->sosRequest->user->fullname,
            'people' => (int) $m->sosRequest->people_trapped,
            'volunteer' => $m->volunteer ? ('Tim ' . ($m->volunteer->kecamatan ?? 'Reguler') . ' (Lead: ' . $m->volunteer->fullname . ')') : 'Relawan',
        ];
    }

    $sosQueueMapData = $waitingSos->map(function ($s) {
        return [
            'lat'      => (float) $s->latitude,
            'lng'      => (float) $s->longitude,
            'priority' => $s->priority_level,
            'location' => ($s->user->kelurahan ?? '') . ', ' . ($s->user->kecamatan ?? ''),
            'people'   => (int) $s->people_trapped,
        ];
    })->values()->all();

    // Shelter map data
    $shelterMapData = $activeShelters->map(fn($s) => [
        'lat' => (float) $s->latitude,
        'lng' => (float) $s->longitude,
        'name' => $s->shelter_name,
        'address' => $s->address,
        'status' => $s->status,
        'occupants' => $s->current_occupants,
        'capacity' => $s->max_capacity,
        'toilet' => $s->has_toilet_facilities,
    ])->values();

    // Flood report map data
    $reportMapData = $verifiedReports->map(fn($r) => [
        'lat' => (float) $r->latitude,
        'lng' => (float) $r->longitude,
        'street' => $r->street_name,
        'height' => $r->water_height_cm,
        'reporter' => $r->user->fullname ?? '-',
    ])->values();

    // Missions history data for detail modal
    $missionsJson = $completedMissions->map(fn($m) => [
        'mission_id' => $m->mission_id,
        'sos_id' => $m->sos_id,
        'reporter' => $m->sosRequest?->user?->fullname ?? '-',
        'reporter_phone' => $m->sosRequest?->user?->phone ?? '-',
        'location' => ($m->sosRequest?->user?->kelurahan ?? '') . ', ' . ($m->sosRequest?->user?->kecamatan ?? ''),
        'people_trapped' => $m->sosRequest?->people_trapped ?? 0,
        'vulnerable' => $m->sosRequest?->vulnerable_groups_count ?? 0,
        'priority' => $m->sosRequest?->priority_level ?? '-',
        'description' => $m->sosRequest?->description ?? '-',
        'volunteer' => $m->volunteer ? ('Tim ' . ($m->volunteer->kecamatan ?? 'Reguler') . ' (Lead: ' . $m->volunteer->fullname . ')') : '-',
        'volunteer_phone' => $m->volunteer?->phone ?? '-',
        'assigned_at' => $m->assigned_at ? $m->assigned_at->format('d M Y H:i') : '-',
        'resolved_at' => $m->resolved_at ? $m->resolved_at->format('d M Y H:i') : '-',
        'duration' => $m->resolved_at ? (int) $m->resolved_at->diffInMinutes($m->created_at) . ' Menit' : '-',
        'status' => $m->resolved_at ? 'Selesai' : 'Berjalan',
    ])->values();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Map Initialization ----

    // ---- Leaflet Map ----
    const DEFAULT_LAT = -6.2383;
    const DEFAULT_LNG = 106.9922;

    const map = L.map('volunteer-map', { zoomControl: false }).setView([DEFAULT_LAT, DEFAULT_LNG], 13);
        const bekasiBounds = L.latLngBounds([
        [-6.5, 106.8], // South West
        [-6.0, 107.3]  // North East
    ]);
    map.setMaxBounds(bekasiBounds);
    map.setMinZoom(10);
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
    const activeMissionsData = @json($activeMissionsMapData);

    if (activeMissionsData && activeMissionsData.length > 0) {
        activeMissionsData.forEach(function(m) {
            const victimIcon = L.divIcon({
                html: `<div style="width:32px;height:32px;position:relative;">
                    <div style="position:absolute;top:0;left:0;width:32px;height:32px;border-radius:50%;background:#ba1a1a;border:3px solid white;box-shadow:0 0 0 4px rgba(186,26,26,0.4),0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;animation:pulse-ring 2s infinite;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                </div>`,
                className: '',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });
            L.marker([m.lat, m.lng], { icon: victimIcon })
                .addTo(map)
                .bindPopup(`<b>KORBAN AKTIF:</b> ${m.name}<br>${m.people} jiwa terjebak<br><b>Tim:</b> ${m.volunteer}`)
                .openPopup();
        });
        if (activeMissionsData.length === 1) {
            map.setView([activeMissionsData[0].lat, activeMissionsData[0].lng], 14);
        }
    }

    // Custom marker SVG icons
    function createSosIcon(color) {
        return L.divIcon({
            html: `<div style="width:28px;height:28px;position:relative;">
                <div style="position:absolute;top:0;left:0;width:28px;height:28px;border-radius:50%;background:${color};border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
            </div>`,
            className: '',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
    }

    function createShelterIcon() {
        return L.divIcon({
            html: `<div style="width:28px;height:28px;position:relative;">
                <div style="position:absolute;top:0;left:0;width:28px;height:28px;border-radius:6px;background:#006a60;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
            </div>`,
            className: '',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
    }

    function createFloodIcon() {
        return L.divIcon({
            html: `<div style="width:28px;height:28px;position:relative;">
                <div style="position:absolute;top:0;left:0;width:28px;height:28px;border-radius:50%;background:#0284c7;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                </div>
            </div>`,
            className: '',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
    }

    // SOS markers layer group (for refresh)
    var sosMarkerLayer = L.layerGroup().addTo(map);
    const priorityColors = { high: '#dc2626', medium: '#f59e0b', low: '#006a60' };

    function plotSosMarkers(sosQueue) {
        sosMarkerLayer.clearLayers();
        sosQueue.forEach(function (sos) {
            if (!sos.lat || !sos.lng) return;
            const color = priorityColors[sos.priority] || '#6b7280';
            var marker = L.marker([sos.lat, sos.lng], {
                icon: L.divIcon({
                    html: `<div style="width:24px;height:24px;border-radius:50%;background:${color};border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
                    className: '',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                })
            }).bindPopup(`<strong>SOS</strong> ${sos.location}<br>${sos.people} jiwa`);
            sosMarkerLayer.addLayer(marker);
        });
    }

    // Plot initial SOS markers
    plotSosMarkers(@json($sosQueueMapData));

    // Global helper for "tinjau detail" button
    window.focusOnSos = function(lat, lng) {
        map.setView([lat, lng], 15);
    };

    // ---- Map Refresh Button ----
    document.getElementById('map-refresh-btn').addEventListener('click', function () {
        this.style.animation = 'spin 1s linear infinite';
        fetch('{{ route("relawan.sos.data") }}')
            .then(res => res.json())
            .then(function (data) {
                var newSos = data.map(function (s) {
                    return {
                        lat: parseFloat(s.latitude),
                        lng: parseFloat(s.longitude),
                        priority: s.priority_level,
                        location: (s.user?.kelurahan ?? '') + ', ' + (s.user?.kecamatan ?? ''),
                        people: s.people_trapped
                    };
                });
                plotSosMarkers(newSos);
            })
            .catch(function () {})
            .finally(function () {
                document.getElementById('map-refresh-btn').style.animation = '';
            });
    });

    // ---- Map Fullscreen Toggle ----
    document.getElementById('map-fullscreen-btn').addEventListener('click', function () {
        var panel = document.querySelector('.map-panel');
        var icon = this.querySelector('i');
        panel.classList.toggle('fullscreen-mode');
        if (panel.classList.contains('fullscreen-mode')) {
            icon.setAttribute('data-lucide', 'minimize-2');
            document.querySelector('.relawan-main-canvas').style.overflow = 'hidden';
        } else {
            icon.setAttribute('data-lucide', 'maximize-2');
            document.querySelector('.relawan-main-canvas').style.overflow = '';
        }
        lucide.createIcons();
        let resizeCount = 0; let resizeInterval = setInterval(function() { map.invalidateSize(); resizeCount++; if(resizeCount > 10) clearInterval(resizeInterval); }, 50);
    });

    // ---- SOS Queue Auto-Refresh every 30s ----
    setInterval(function () {
        fetch('{{ route("relawan.sos.data") }}')
            .then(res => res.json())
            .catch(() => {});
    }, 30000);

    lucide.createIcons();
});

// Modal Logic
window.openReviewModal = function(id, name, phone, skill, org, kec, kel, docPath) {
    document.getElementById('rev_name').textContent = name;
    document.getElementById('rev_phone').textContent = phone;
    document.getElementById('rev_skill').textContent = skill;
    document.getElementById('rev_org').textContent = org;
    document.getElementById('rev_loc').textContent = kel + ', ' + kec;
    
    document.getElementById('approveForm').action = '/relawan/member/' + id + '/approve';
    document.getElementById('rejectForm').action = '/relawan/member/' + id + '/reject';
    
    var docWrapper = document.getElementById('rev_doc_wrapper');
    var docImg = document.getElementById('rev_doc_img');
    var docLink = document.getElementById('rev_doc_link');
    
    if (docPath) {
        docWrapper.style.display = 'block';
        var fileUrl = '/storage/' + docPath;
        var isImage = docPath.match(/\.(jpg|jpeg|png|gif|webp)$/i);
        
        if (isImage) {
            docImg.src = fileUrl;
            docImg.style.display = 'block';
            docLink.style.display = 'none';
        } else {
            docImg.style.display = 'none';
            docLink.href = fileUrl;
            docLink.style.display = 'inline-flex';
        }
    } else {
        docWrapper.style.display = 'none';
    }
    
    window.openModal('reviewModal');
};

window.closeReviewModal = function() {
    window.closeModal('reviewModal');
};

// Mission Detail Data
const missionsData = @json($missionsJson);

window.openMissionDetail = function(id) {
    const mission = missionsData.find(m => m.mission_id === id);
    if (!mission) return;

    document.getElementById('md_mission_id').textContent = '#' + mission.mission_id;
    document.getElementById('md_sos_id').textContent = '#' + mission.sos_id;
    document.getElementById('md_reporter').textContent = mission.reporter;
    document.getElementById('md_reporter_phone').textContent = mission.reporter_phone;
    document.getElementById('md_location').textContent = mission.location;
    document.getElementById('md_people').textContent = mission.people_trapped + ' Orang';
    document.getElementById('md_vulnerable').textContent = mission.vulnerable + ' Orang';
    document.getElementById('md_priority').textContent = mission.priority.charAt(0).toUpperCase() + mission.priority.slice(1);
    document.getElementById('md_description').textContent = mission.description;
    document.getElementById('md_volunteer').textContent = mission.volunteer;
    document.getElementById('md_volunteer_phone').textContent = mission.volunteer_phone;
    document.getElementById('md_assigned').textContent = mission.assigned_at;
    document.getElementById('md_resolved').textContent = mission.resolved_at;
    document.getElementById('md_duration').textContent = mission.duration;
    document.getElementById('md_status').textContent = mission.status;
    document.getElementById('md_status').style.color = mission.status === 'Selesai' ? '#006a60' : '#d97706';

    window.openModal('missionDetailModal');
};

// Edit Member
const editKelurahanDb = {
    'Pondok Gede': ['Jatiwaringin', 'Jatibening', 'Jatibening Baru', 'Jaticempaka', 'Jatimakmur'],
    'Jatiasih': ['Jatiasih', 'Jatikramat', 'Jatiluhur', 'Jatirasa', 'Jatisari', 'Jati Mekar'],
    'Bekasi Timur': ['Aren Jaya', 'Bekasi Jaya', 'Duren Jaya', 'Margahayu'],
    'Bekasi Selatan': ['Jakamulya', 'Jakasetia', 'Kayuringin Jaya', 'Marga Jaya', 'Pekayon Jaya'],
    'Bekasi Barat': ['Bintara', 'Bintara Jaya', 'Jakasampurna', 'Kota Baru', 'Kranji'],
    'Bekasi Utara': ['Harapan Baru', 'Harapan Jaya', 'Kaliabang Tengah', 'Marga Mulya', 'Perwira', 'Teluk Pucung'],
    'Rawalumbu': ['Bojong Rawalumbu', 'Bojong Menteng', 'Pengasinan', 'Sepanjang Jaya'],
    'Mustikajaya': ['Mustikajaya', 'Mustikasari', 'Pedurenan', 'Cimuning']
};

window.openEditMember = function(id, name, keahlian, organisasi, kec, kel) {
    document.getElementById('em_user_id').value = id;
    document.getElementById('em_name').textContent = name;
    document.getElementById('em_current_team').textContent = kec ? 'Tim ' + kec : 'Tim Reguler';
    document.getElementById('em_keahlian').value = keahlian;
    document.getElementById('em_organisasi').value = organisasi;
    document.getElementById('em_kecamatan').value = kec;
    document.getElementById('editMemberForm').action = '/relawan/member/' + id + '/update';
    
    var kelSelect = document.getElementById('em_kelurahan');
    kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    if (kec && editKelurahanDb[kec]) {
        editKelurahanDb[kec].forEach(function(k) {
            var opt = document.createElement('option');
            opt.value = k;
            opt.textContent = k;
            if (k === kel) opt.selected = true;
            kelSelect.appendChild(opt);
        });
    }
    
    window.openModal('editMemberModal');
};

window.updateKelurahanEdit = function() {
    var kec = document.getElementById('em_kecamatan').value;
    var kelSelect = document.getElementById('em_kelurahan');
    kelSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
    if (kec && editKelurahanDb[kec]) {
        editKelurahanDb[kec].forEach(function(k) {
            var opt = document.createElement('option');
            opt.value = k;
            opt.textContent = k;
            kelSelect.appendChild(opt);
        });
    }
};

window.openPendaftarListModal = function() {
    window.openModal('pendaftarListModal');
};

window.openTeamMembersModal = function(element) {
    const teamName = element.getAttribute('data-team-name');
    const membersJson = element.getAttribute('data-members');
    
    document.getElementById('tmm_title').textContent = 'Anggota ' + teamName;
    const members = JSON.parse(membersJson);
    const tbody = document.getElementById('tmm_body');
    tbody.innerHTML = '';

    if (members.length === 0) {
        tbody.innerHTML = '<tr class="empty-history-row"><td colspan="4">Belum ada anggota di tim ini.</td></tr>';
    } else {
        members.forEach(function(m) {
            const statusHtml = m.in_mission 
                ? '<span class="badge-terkonsepsi" style="background: #fef08a; color: #b45309;">DALAM MISI</span>' 
                : '<span class="badge-terkonsepsi" style="background: #d1f4e0; color: #006a60;">TERSEDIA</span>';
            
            const safeName = (m.name || '').replace(/'/g, "\\'");
            const safeKeahlian = (m.keahlian || '').replace(/'/g, "\\'");
            const safeOrganisasi = (m.organisasi || '').replace(/'/g, "\\'");
            const safeKecamatan = m.kecamatan || '';
            const safeKelurahan = m.kelurahan || '';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="td-regular" style="font-weight: 600; color: #111827;">${m.name}</td>
                <td class="td-regular">${m.phone}</td>
                <td>${statusHtml}</td>
                <td style="text-align: right; white-space: nowrap;">
                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                        <button type="button" class="btn-tinjau" style="padding: 3px 8px; font-size: 10px;" onclick="closeModal('teamMembersModal'); openEditMember(${m.id}, '${safeName}', '${safeKeahlian}', '${safeOrganisasi}', '${safeKecamatan}', '${safeKelurahan}')">Edit</button>
                        <form action="/relawan/member/${m.id}/remove" method="POST" onsubmit="return confirm('Hapus ${m.name} dari tim?')" style="margin: 0;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn-tinjau" style="padding: 3px 8px; font-size: 10px; border-color: #ef4444; color: #dc2626;">Hapus</button>
                        </form>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.openModal('teamMembersModal');
};

window.closeEditMemberModal = function() {
    window.closeModal('editMemberModal');
};
</script>
@endsection


