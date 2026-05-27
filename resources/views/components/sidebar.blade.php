{{-- ══ SIDEBAR (ticket system style) ══ --}}
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-headset"></i></div>
        <div class="logo-text">Seel Support</div>
    </div>
<nav class="sidebar-nav">

    <div class="nav-section-title">
        {{ Auth::user()->role === 'admin' ? 'Admin' : (Auth::user()->role === 'Support' ? 'Support' : 'Menu') }}
    </div>

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-pie"></i> Dashboard
    </a>

    {{-- Tickets --}}
    <a href="{{ route('admin.tickets.index') }}" class="nav-link">
        <i class="fas fa-ticket-alt"></i> Tickets
    </a>

    {{-- Admin-only sections --}}
    @if(Auth::user()->role === 'admin')

        <a href="{{ route('admin.ticket-categories.index') }}" class="nav-link">
            <i class="fas fa-tags"></i> Categories
        </a>

        <a href="{{ route('admin.support-team.index') }}" class="nav-link">
            <i class="fas fa-users"></i> Support Team
        </a>
        <a href="{{ route('admin.ticket-options.index') }}" class="nav-link">
            <i class="fas fa-sliders-h"></i>Settings
        </a>
    @endif

</nav>

</div>


<div class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fas fa-headset"></i>
        </div>
        <div class="logo-text">SEEL Support</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-title">
            {{ Auth::user()->role === 'admin' ? 'Admin' : (Auth::user()->role === 'support' ? 'Support' : 'Menu') }}
        </div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.tickets.index') }}"
           class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            Tickets
        </a>

        @if(Auth::user()->role === 'admin')

            <a href="{{ route('admin.ticket-categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.ticket-categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                Categories
            </a>

            <a href="{{ route('admin.support-team.index') }}"
               class="nav-link {{ request()->routeIs('admin.support-team.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Support Team
            </a>

            <a href="{{ route('admin.ticket-options.index') }}"
               class="nav-link {{ request()->routeIs('admin.ticket-options.*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i>
                Settings
            </a>

        @endif

    </nav>

</div>

<div class="main">

