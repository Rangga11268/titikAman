<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TitikAman - Sistem Mitigasi Banjir')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-titikaman.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body data-user-role="{{ auth()->check() ? auth()->user()->role : 'Guest' }}">
    @php
        $realNotifs = [];
        try {
            if (auth()->check()) {
                $role = auth()->user()->role;
                
                if ($role == 'Warga') {
                    // 1. Water Gates warnings
                    $gates = \App\Models\WaterGate::whereIn('danger_status', ['Siaga_1', 'Siaga_2', 'Siaga_3'])
                        ->latest('last_updated')
                        ->take(2)
                        ->get();
                    foreach ($gates as $g) {
                        $realNotifs[] = [
                            'text' => "Peringatan TMA: Pintu Air {$g->gate_name} berstatus " . str_replace('_', ' ', $g->danger_status) . " ({$g->water_level_cm} cm).",
                            'time' => 'Baru saja',
                            'priority' => ($g->danger_status == 'Siaga_1' ? 'red' : ($g->danger_status == 'Siaga_2' ? 'orange' : 'blue'))
                        ];
                    }
                    
                    // 2. Active Shelters
                    $shelters = \App\Models\Shelter::where('status', 'active')
                        ->latest()
                        ->take(2)
                        ->get();
                    foreach ($shelters as $s) {
                        $realNotifs[] = [
                            'text' => "Posko Aktif: {$s->shelter_name} siap menampung warga ({$s->current_occupants}/{$s->max_capacity} jiwa).",
                            'time' => 'Baru',
                            'priority' => 'blue'
                        ];
                    }

                    // 3. Flood reports
                    $reports = \App\Models\FloodReport::where('verification_status', 'verified')
                        ->latest()
                        ->take(2)
                        ->get();
                    foreach ($reports as $r) {
                        $realNotifs[] = [
                            'text' => "Laporan Banjir Terverifikasi di {$r->street_name} (Tinggi Air: {$r->water_height_cm} cm).",
                            'time' => 'Terbaru',
                            'priority' => 'orange'
                        ];
                    }
                } elseif ($role == 'Relawan') {
                    // 1. Waiting SOS
                    $sos = \App\Models\SosRequest::where('status', 'waiting')
                        ->latest()
                        ->take(3)
                        ->get();
                    foreach ($sos as $s) {
                        $loc = ($s->user?->kelurahan ?? 'Bekasi') . ', ' . ($s->user?->kecamatan ?? '');
                        $realNotifs[] = [
                            'text' => "SOS Aktif: {$s->people_trapped} orang terjebak di {$loc} membutuhkan evakuasi segera.",
                            'time' => 'Mendesak',
                            'priority' => 'red'
                        ];
                    }
                    
                    // 2. My Active Missions
                    $missions = \App\Models\RescueMission::where('volunteer_id', auth()->id())
                        ->whereNull('resolved_at')
                        ->latest()
                        ->get();
                    foreach ($missions as $m) {
                        $loc = ($m->sosRequest?->user?->kelurahan ?? 'Bekasi');
                        $realNotifs[] = [
                            'text' => "Misi Aktif Anda: Penyelamatan warga di {$loc} sedang dalam progress.",
                            'time' => 'Berjalan',
                            'priority' => 'orange'
                        ];
                    }
                } elseif ($role == 'Pengelola_Posko') {
                    $shelterId = session('managed_shelter_id');
                    if ($shelterId) {
                        $shelter = \App\Models\Shelter::find($shelterId);
                        if ($shelter) {
                            $donations = \App\Models\Donation::whereHas('shelterNeed', function($query) use ($shelterId) {
                                $query->where('shelter_id', $shelterId);
                            })->where('status', 'pending')->latest()->take(3)->get();
                            
                            foreach ($donations as $d) {
                                $donor = $d->donor?->fullname ?? 'Donatur';
                                $item = $d->shelterNeed?->item_name ?? 'Logistik';
                                $realNotifs[] = [
                                    'text' => "Donasi Masuk: Paket {$item} ({$d->quantity_donated} unit) dari {$donor} menunggu verifikasi.",
                                    'time' => 'Pending',
                                    'priority' => 'orange'
                                ];
                            }
                            
                            if ($shelter->current_occupants >= $shelter->max_capacity * 0.9) {
                                $realNotifs[] = [
                                    'text' => "Kapasitas Kritis: Shelter {$shelter->shelter_name} terisi {$shelter->current_occupants}/{$shelter->max_capacity} jiwa.",
                                    'time' => 'Hampir Penuh',
                                    'priority' => 'red'
                                ];
                            }
                        }
                    } else {
                        $realNotifs[] = [
                            'text' => 'Silakan pilih posko yang ingin Anda kelola untuk melihat notifikasi spesifik posko.',
                            'time' => 'Info',
                            'priority' => 'blue'
                        ];
                    }
                } elseif ($role == 'Admin_BPBD') {
                    $reports = \App\Models\FloodReport::where('verification_status', 'pending')
                        ->latest()
                        ->take(3)
                        ->get();
                    foreach ($reports as $r) {
                        $reporter = $r->user?->fullname ?? 'Warga';
                        $realNotifs[] = [
                            'text' => "Laporan Baru: Laporan banjir di {$r->street_name} oleh {$reporter} menunggu verifikasi Anda.",
                            'time' => 'Moderasi',
                            'priority' => 'orange'
                        ];
                    }
                    
                    $gates = \App\Models\WaterGate::whereIn('danger_status', ['Siaga_1', 'Siaga_2'])
                        ->latest('last_updated')
                        ->take(2)
                        ->get();
                    foreach ($gates as $g) {
                        $realNotifs[] = [
                            'text' => "TMA Kritis: Pintu Air {$g->gate_name} berstatus " . str_replace('_', ' ', $g->danger_status) . " ({$g->water_level_cm} cm).",
                            'time' => 'Mendesak',
                            'priority' => 'red'
                        ];
                    }
                }
            } else {
                $gates = \App\Models\WaterGate::whereIn('danger_status', ['Siaga_1', 'Siaga_2', 'Siaga_3'])
                    ->latest('last_updated')
                    ->take(3)
                    ->get();
                foreach ($gates as $g) {
                    $realNotifs[] = [
                        'text' => "Info TMA: Pintu Air {$g->gate_name} berada di tingkat " . str_replace('_', ' ', $g->danger_status) . " ({$g->water_level_cm} cm).",
                        'time' => 'Info Publik',
                        'priority' => 'blue'
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        if (empty($realNotifs)) {
            $realNotifs[] = [
                'text' => 'Belum ada notifikasi baru untuk saat ini.',
                'time' => 'Terbaru',
                'priority' => 'blue'
            ];
        }
    @endphp

    @yield('content')

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activeNotifs = @json($realNotifs);

            const dropdown = document.createElement('div');
            dropdown.className = 'global-notification-dropdown';
            dropdown.innerHTML = `
                <div class="notif-dropdown-header">
                    <h4>Notifikasi Terbaru</h4>
                    <button class="notif-clear-btn" id="notifClearBtn">Tandai Dibaca</button>
                </div>
                <div class="notif-dropdown-list" id="notifDropdownList"></div>
            `;
            document.body.appendChild(dropdown);

            const listContainer = dropdown.querySelector('#notifDropdownList');

            function renderNotifications() {
                if (activeNotifs.length === 0 || (activeNotifs.length === 1 && activeNotifs[0].text === 'Belum ada notifikasi baru untuk saat ini.')) {
                    listContainer.innerHTML = '<div class="notif-dropdown-empty">Tidak ada notifikasi baru</div>';
                    return;
                }
                
                listContainer.innerHTML = activeNotifs.map(notif => `
                    <div class="notif-dropdown-item">
                        <span class="notif-status-dot ${notif.priority}"></span>
                        <div class="notif-dropdown-details">
                            <span class="notif-dropdown-text">${notif.text}</span>
                            <span class="notif-dropdown-time">${notif.time}</span>
                        </div>
                    </div>
                `).join('');
            }

            renderNotifications();

            // Clear dot indicator if there's no real notifications initially
            if (activeNotifs.length === 0 || (activeNotifs.length === 1 && activeNotifs[0].text === 'Belum ada notifikasi baru untuk saat ini.')) {
                document.querySelectorAll('.notification-dot').forEach(dot => dot.style.display = 'none');
            }

            dropdown.querySelector('#notifClearBtn').addEventListener('click', function(e) {
                e.stopPropagation();
                activeNotifs.length = 0;
                renderNotifications();
                document.querySelectorAll('.notification-dot').forEach(dot => dot.style.display = 'none');
            });

            document.addEventListener('click', function (e) {
                const bell = e.target.closest('.notification-bell, [title="Notifikasi"], .topbar-btn[title="Notifikasi"]');
                
                if (bell) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isVisible = dropdown.style.display === 'flex';
                    if (isVisible) {
                        dropdown.style.display = 'none';
                    } else {
                        const rect = bell.getBoundingClientRect();
                        dropdown.style.top = (rect.bottom + window.scrollY + 8) + 'px';
                        dropdown.style.left = (rect.right - 320 + window.scrollX) + 'px';
                        dropdown.style.display = 'flex';
                    }
                } else if (!e.target.closest('.global-notification-dropdown')) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
