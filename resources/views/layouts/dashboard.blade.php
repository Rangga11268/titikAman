@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="topbar-left" style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar" style="background: none; border: none; padding: 6px; cursor: pointer; color: var(--navy-dark); border-radius: 6px; position: static !important; width: auto; height: auto;">
                    <i data-lucide="menu"></i>
                </button>
                @yield('topbar-left')
            </div>
            <div class="topbar-right">
                @yield('topbar-right')
                
                <div class="notification-bell">
                    <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                    <div class="notification-dot"></div>
                </div>
                <div class="user-profile-widget">
                    <div class="user-widget-avatar">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->fullname, 0, 2)) : 'GU' }}
                    </div>
                    <div class="user-widget-info">
                        <span class="user-widget-name">{{ auth()->check() ? auth()->user()->fullname : 'Guest' }}</span>
                        <span class="user-widget-role">{{ auth()->check() ? auth()->user()->role : 'Umum' }}</span>
                    </div>
                </div>
            </div>
        </div>

        @yield('dashboard-content')
    </div>
</div>

<div class="mobile-overlay" id="mobileOverlay"></div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        const overlay = document.getElementById('mobileOverlay');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                if(overlay) {
                    overlay.classList.toggle('active');
                }
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@yield('dashboard-scripts')
@endsection
