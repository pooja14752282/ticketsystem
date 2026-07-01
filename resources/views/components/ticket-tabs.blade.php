{{-- ── SETTINGS PAGES: show only Ticket Options + Edit Due Dates ── --}}
@if(
    request()->routeIs('admin.ticket-options.*') ||
    request()->routeIs('admin.tickets.duedates')
)
<div class="ticket-tabs">
    <a href="{{ route('admin.ticket-options.index') }}"
       class="ticket-tab {{ request()->routeIs('admin.ticket-options.*') ? 'active' : '' }}">
        <i class="fas fa-sliders-h"></i> Ticket Options
    </a>
    <a href="{{ route('admin.tickets.duedates') }}"
       class="ticket-tab {{ request()->routeIs('admin.tickets.duedates') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i> Edit Due Dates
    </a>
</div>

{{-- ── TICKET PAGES: show All Tickets, Assigned, My Tickets, Due Dates, Options ── --}}
@elseif(
    request()->routeIs('admin.tickets.*') ||
    request()->routeIs('ticketsystem.*') ||
    request()->routeIs('support.tickets')
)
@unless(Auth::user()->isTicketSupportTeam())
<div class="ticket-tabs">

    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.tickets.index') }}"
           class="ticket-tab {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}">
            <i class="fas fa-list-ul"></i> All Tickets
        </a>

        <a href="{{ route('ticketsystem.assigned') }}"
           class="ticket-tab {{ request()->routeIs('ticketsystem.assigned') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> Assigned To Me
        </a>
    @endif

    <a href="{{ route('ticketsystem.my') }}"
       class="ticket-tab {{ request()->routeIs('ticketsystem.my') ? 'active' : '' }}">
        <i class="fas fa-ticket-alt"></i> My Tickets
    </a>

</div>
@endunless
@endif