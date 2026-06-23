<div class="dashboard-sidebar">
    <div class="sidebar-logo-area">
        <div class="sidebar-brand-row">
            <div class="sidebar-logo-bg">
                <img src="{{ asset('assets/logo-titikaman.png') }}" alt="Logo TitikAman" onerror="this.src='https://placehold.co/44x44/f3f3f3/006a60?text=TA'">
            </div>
            <div class="sidebar-brand-name">TitikAman</div>
        </div>
        <div class="sidebar-partner-badge">
            <div class="partner-logo">
                <img src="{{ asset('assets/logo-bpbd.png') }}" alt="BPBD" onerror="this.src='https://placehold.co/24x24/ffffff/031f41?text=B'">
            </div>
            <div class="partner-text-wrap">
                <span class="partner-label">Official Partner</span>
                <span class="partner-name">BPBD Kota Bekasi</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('warga.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard Utama</span>
        </a>
        <a href="{{ route('peta.evakuasi') }}" class="nav-item {{ request()->routeIs('peta.evakuasi') ? 'active' : '' }}">
            <i data-lucide="map"></i>
            <span>Peta Evakuasi</span>
        </a>
        <a href="{{ route('pintu.air') }}" class="nav-item {{ request()->routeIs('pintu.air') ? 'active' : '' }}">
            <i data-lucide="droplet"></i>
            <span>Data Pintu Air</span>
        </a>
        <a href="{{ route('posko') }}" class="nav-item {{ request()->routeIs('posko') ? 'active' : '' }}">
            <i data-lucide="home"></i>
            <span>Posko Pengungsian</span>
        </a>
        
        <div class="nav-divider"></div>
        
        @if(auth()->user()->role == 'Warga')
            <a href="{{ route('warga.sos') }}" class="nav-item {{ request()->routeIs('warga.sos') ? 'active' : '' }}">
                <i data-lucide="alert-octagon" style="color: var(--accent-red);"></i>
                <span style="color: var(--accent-red); font-weight: 700;">SOS Darurat</span>
            </a>
            <a href="{{ route('warga.lapor') }}" class="nav-item {{ request()->routeIs('warga.lapor') ? 'active' : '' }}">
                <i data-lucide="file-text"></i>
                <span>Form Laporan</span>
            </a>
        @endif

        @if(auth()->user()->role == 'Relawan')
            <a href="{{ route('relawan.dashboard') }}" class="nav-item {{ request()->routeIs('relawan.dashboard') ? 'active' : '' }}">
                <i data-lucide="shield"></i>
                <span>Portal Relawan</span>
            </a>
        @endif

        @if(auth()->user()->role == 'Pengelola_Posko')
            <a href="{{ route('pengelola.dashboard') }}" class="nav-item {{ request()->routeIs('pengelola.dashboard') ? 'active' : '' }}">
                <i data-lucide="home"></i>
                <span>Kelola Kebutuhan</span>
            </a>
            <a href="{{ route('donasi.hub') }}" class="nav-item {{ request()->routeIs('donasi.hub') ? 'active' : '' }}">
                <i data-lucide="heart"></i>
                <span>Hub Logistik & Donasi</span>
            </a>
        @endif

        @if(auth()->user()->role == 'Admin_BPBD')
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="shield-alert"></i>
                <span>Kelola Laporan BPBD</span>
            </a>
            <a href="{{ route('admin.tma') }}" class="nav-item {{ request()->routeIs('admin.tma') ? 'active' : '' }}">
                <i data-lucide="sliders"></i>
                <span>Kelola TMA Air</span>
            </a>
        @endif
        


        <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="margin-top: auto;">
            <i data-lucide="log-out" class="text-red"></i>
            <span class="text-red">Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
    <div class="sidebar-footer">
        © 2024 BNPB × BPBD Bekasi
    </div>
</div>
