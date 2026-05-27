    {{-- SHOW TABS ONLY FOR TICKET PAGES — but NOT on the due dates page --}}
    @if(
        (
            request()->routeIs('admin.tickets.*') ||
            request()->routeIs('ticketsystem.*') ||
            request()->routeIs('support.tickets')
        )
        && !request()->routeIs('admin.tickets.duedates')
    )
    
{{-- TICKET TABS --}}
        <div class="ticket-tabs">

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.tickets.index') }}"
                   class="ticket-tab {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}">
                    <i class="fas fa-list-ul"></i> All Tickets
                </a>

                <a href="{{ route('ticketsystem.assigned') }}"
                   class="ticket-tab {{ request()->routeIs('ticketsystem.assigned') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Assigned To Me
                </a>
            @endif

            @if(Auth::user()->role === 'support')
                <a href="{{ route('support.tickets') }}"
                   class="ticket-tab {{ request()->routeIs('support.tickets') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Assigned To Me
                </a>
            @endif

            <a href="{{ route('ticketsystem.my') }}"
               class="ticket-tab {{ request()->routeIs('ticketsystem.my') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> My Tickets
            </a>

        </div>