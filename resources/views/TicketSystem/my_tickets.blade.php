@extends('layout')

@php $hideAssigned = true; @endphp

@section('styles')
<link rel="stylesheet" href="{{ asset('css/my-tickets.css') }}">
@endsection

@section('content')

<div class="page-header">
    <h2>My Tickets</h2>

    <a href="#"
       class="btn-create"
       onclick="document.getElementById('createModal').style.display='flex'">
        + Create New Ticket
    </a>
</div>

{{-- STATS 
<div class="stats-grid">
    <div class="stat-card high">
        <div class="label">High Priority</div>
        <div class="value">{{ $stats['high'] }}</div>
    </div>

    <div class="stat-card open">
        <div class="label">Open Tickets</div>
        <div class="value">{{ $stats['open'] }}</div>
    </div>

    <div class="stat-card onhold">
        <div class="label">On Hold</div>
        <div class="value">{{ $stats['onhold'] }}</div>
    </div>

    <div class="stat-card urgent">
        <div class="label">Urgent Priority</div>
        <div class="value">{{ $stats['urgent'] }}</div>
    </div>
</div>--}}

{{-- FILTERS --}}
<div class="filters">

    <input type="text"
           id="searchInput"
           placeholder=" Search tickets..."
           onkeyup="filterTable()">

    <select id="statusFilter" onchange="filterTable()">
        <option value="">All Status</option>

        @foreach($statuses as $s)
            <option value="{{ $s->value }}">
                {{ $s->label }}
            </option>
        @endforeach
    </select>

    <select id="priorityFilter" onchange="filterTable()">
        <option value="">All Priority</option>

        @foreach($priorities as $p)
            <option value="{{ $p->value }}">
                {{ $p->label }}
            </option>
        @endforeach
    </select>

</div>

{{-- TABLE --}}
<div class="table-card">

    <table id="ticketTable">

        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>App</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>

            @foreach($tickets as $ticket)

            @php
                $priorityOption = $priorities->firstWhere('value', $ticket->priority);
                $statusOption   = $statuses->firstWhere('value', $ticket->status);
            @endphp

            <tr>

                <td>{{ $ticket->ticket_id }}</td>

                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $ticket->description }}
                </td>

                <td>
                    <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                        {{ \App\Models\TicketSupportTeam::APPS[$ticket->category] ?? $ticket->category }}
                    </span>
                </td>

                <td>
                    <span class="badge"
                          style="background:{{ $priorityOption->color ?? '#f3f4f6' }};
                                 color:{{ $priorityOption->text_color ?? '000000' }};">
                        {{ $priorityOption->label ?? ucfirst($ticket->priority) }}
                    </span>
                </td>

                <td>
                    <span class="badge"
                          style="background:{{ $statusOption->color ?? '#f3f4f6' }};
                                 color:{{ $statusOption->text_color ?? '000000' }};">
                        {{ $statusOption->label ?? ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </td>

                <td>
                    {{ $ticket->created_at->format('d M Y') }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@include('components.create-ticket-modal')

@endsection

@push('scripts')
{{-- ── DataTables assets (skip these two lines if already loaded globally in layout.blade.php) ── --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#ticketTable').DataTable({
        order: [],
        searching: false,
        paging: true,
        info: true,
        lengthChange: true,
        columnDefs: [
            { orderable: false, targets: -1 }, // Actions column
            { orderable: false, targets: -2 }  // Attachment column
        ],
        language: {
            emptyTable: "No tickets found. Create your first ticket!"
        }
    });
});
</script>

<script src="{{ asset('js/my-tickets.js') }}"></script>
@endpush