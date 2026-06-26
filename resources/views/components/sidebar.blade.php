{{-- ══ SIDEBAR ══ --}}
<div class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fas fa-headset"></i>
        </div>
        <div class="logo-text">SEEL Support</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-title">
            {{ Auth::user()->isAdmin()
                ? 'Admin'
                : (Auth::user()->isTicketSupportTeam() ? 'Support' : 'Menu') }}
        </div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            Dashboard
        </a>

        {{-- Tickets link — admin goes to all tickets, support goes to assigned tickets --}}
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.tickets.index') }}"
               class="nav-link {{ request()->routeIs('admin.tickets.*') && !request()->routeIs('admin.tickets.duedates') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                Tickets
            </a>
        @elseif(Auth::user()->isTicketSupportTeam())
            <a href="{{ route('support.tickets') }}"
               class="nav-link {{ request()->routeIs('support.tickets*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                Tickets
            </a>
        @else
            <a href="{{ route('ticketsystem.my') }}"
               class="nav-link {{ request()->routeIs('ticketsystem.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i>
                My Tickets
            </a>
        @endif

        @if(Auth::user()->isAdmin())

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
               class="nav-link {{ request()->routeIs('admin.ticket-options.*', 'admin.tickets.duedates') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i>
                Settings
            </a>

        @endif

    </nav>

</div>