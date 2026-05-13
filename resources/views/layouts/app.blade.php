<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HRMS Pro – {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body>

<div class="layout">

    {{-- SIDEBAR --}}
    <nav class="sidebar" aria-label="Main navigation">
        <div class="sb-top">
            <div class="brand">
                <div class="b-icon">
                    <svg viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div class="b-name">HRMS Pro</div>
                    <div class="b-tag">Philippines</div>
                </div>
            </div>

            <div class="ng">Workspace</div>
            <a href="{{ route('dashboard') }}" class="nl {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('employees.index') }}" class="nl {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Employees
                <span class="nb b">{{ $sidebarCounts['employeeCount'] }}</span>
            </a>
            @endif
            <a href="{{ route('attendance.index') }}" class="nl {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Attendance
            </a>
            <a href="{{ route('timesheets.index') }}" class="nl {{ request()->routeIs('timesheets.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Timesheets
            </a>

            <div class="ng">HR</div>
            <a href="{{ route('leaves.index') }}" class="nl {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Leaves
                @if($sidebarCounts['pendingLeaves']) <span class="nb">{{ $sidebarCounts['pendingLeaves'] }}</span> @endif
            </a>
            <a href="{{ route('violations.index') }}" class="nl {{ request()->routeIs('violations.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Violations
                @if($sidebarCounts['openViolations']) <span class="nb">{{ $sidebarCounts['openViolations'] }}</span> @endif
            </a>
            <a href="{{ route('performance.index') }}" class="nl {{ request()->routeIs('performance.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Performance
            </a>

            @if(auth()->user()->isAdmin())
            <div class="ng">Admin</div>
            <a href="{{ route('notifications.index') }}" class="nl {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notifications
                @if($sidebarCounts['unreadNotifications']) <span class="nb">{{ $sidebarCounts['unreadNotifications'] }}</span> @endif
            </a>
            <a href="{{ route('requests.index') }}" class="nl {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                <svg class="ni" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                Requests
                @if($sidebarCounts['pendingRequests']) <span class="nb">{{ $sidebarCounts['pendingRequests'] }}</span> @endif
            </a>
            @endif
        </div>

        <div class="sb-foot">
            <div class="u-row">
                <div class="u-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div>
                    <div class="u-name">{{ auth()->user()->name }}</div>
                    <div class="u-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left:auto" data-logout-form>
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="main">

        {{-- TOP BAR --}}
        <header class="topbar">
            <div class="tb-left">
                <div class="pg-title">{{ $title ?? 'Dashboard' }}</div>
                <div class="pg-crumb">{{ $crumb ?? 'Home · Overview' }}</div>
            </div>
            <div class="tb-right">
                <div class="time-chip" id="livetime">—</div>
<a href="{{ route('notifications.index') }}" class="tib notif-bell" style="position:relative;text-decoration:none">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" style="color:#475569"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if($sidebarCounts['unreadNotifications']) <span class="notif-count">{{ $sidebarCounts['unreadNotifications'] > 99 ? '99+' : $sidebarCounts['unreadNotifications'] }}</span> @endif
                </a>
                <a href="{{ route('profile.edit') }}" class="u-av tib-av" title="Profile">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</a>
            </div>
        </header>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
        <div class="flash flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
            <button onclick="this.parentElement.remove()" class="flash-close">×</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash flash-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
            <button onclick="this.parentElement.remove()" class="flash-close">×</button>
        </div>
        @endif

        <div class="content">
            {{ $slot }}
        </div>

    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModalOverlay" class="logout-modal-overlay"></div>
<div id="logoutConfirmationModal" class="logout-confirmation-modal">
    <div class="lc-content">
        <div class="lc-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <h3>Confirm Logout</h3>
        </div>
        <p class="lc-message">Are you sure you want to log out? You will need to log in again to access the system.</p>
        <div class="lc-actions">
            <button type="button" id="logoutCancelBtn" class="lc-btn lc-btn-cancel">Cancel</button>
            <button type="button" id="logoutConfirmBtn" class="lc-btn lc-btn-confirm">Log Out</button>
        </div>
    </div>
</div>

<script>
(function(){
    const t = document.getElementById('livetime');
    if (!t) return;
    function tick() {
        const n = new Date();
        t.textContent = String(n.getHours()).padStart(2,'0') + ':' + String(n.getMinutes()).padStart(2,'0') + ':' + String(n.getSeconds()).padStart(2,'0');
    }
    tick(); setInterval(tick, 1000);
})();
</script>
@stack('scripts')
</body>
</html>
