@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/support-tickets.css') }}">
@endsection

@section('content')



<div class="page-header">
    <div>
        <h1>🎫 Assigned Tickets</h1>
        <p>Tickets assigned to you — {{ $member->name ?? 'N/A' }}</p>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <p>Total Assigned</p>
        <p class="stat-num" style="color:#111827;">{{ method_exists($tickets, 'total') ? $tickets->total() : $tickets->count() }}</p>
    </div>
    <div class="stat-card">
        <p>Open</p>
        <p class="stat-num" style="color:#3b82f6;">{{ $tickets->where('status','open')->count() }}</p>
    </div>
    <div class="stat-card">
        <p>In Progress</p>
        <p class="stat-num" style="color:#f59e0b;">{{ $tickets->where('status','in_progress')->count() }}</p>
    </div>
    <div class="stat-card">
        <p>Completed</p>
        <p class="stat-num" style="color:#10b981;">{{ $tickets->where('status','completed')->count() }}</p>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <table id="ticketsTable">
        <thead>
            <tr>
                
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Created</th>
               
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $i => $ticket)
            <tr>
                
                <td style="font-weight:600;">{{ $ticket->ticket_id }}</td>
               <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $ticket->description }}">
    <a href="{{ route('support.ticket.show', $ticket->id) }}" style="color:inherit;text-decoration:none;">
        {{ \Str::limit($ticket->description, 50) }}
    </a>
</td>
                <td style="font-size:12px;color:#000000;">{{ $ticket->ticketCategory->name ?? $ticket->category ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($ticket->priority) }}">
                        {{ ucfirst($ticket->priority ?? '-') }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ str_replace(' ','_',$ticket->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status ?? '-')) }}
                    </span>
                </td>
                <td style="font-size:12px;white-space:nowrap;">
                    @if($ticket->due_date)
                        @php
                            $due = \Carbon\Carbon::parse($ticket->due_date);
                            $now = now();
                            $cssClass = $due->isPast() ? 'due-date-overdue' : ($due->diffInDays($now) <= 2 ? 'due-date-soon' : 'due-date-ok');
                        @endphp
                        <span class="{{ $cssClass }}">{{ $due->format('d M Y') }}</span>
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#000000;white-space:nowrap;">
                    {{ $ticket->created_at->format('d M Y') }}
                </td>
                
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        No tickets assigned to you yet.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($tickets->hasPages())
    <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

@push('scripts')
{{-- ── DataTables assets (skip these two lines if already loaded globally in layout.blade.php) ── --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#ticketsTable').DataTable({
        order: [],           // keep the latest()-first order from the controller on load
        searching: false,    // your own filter form already handles search
        paging: true,
        info: true,
        lengthChange: true,
        columnDefs: [
            { orderable: false, targets: -1 }, // Actions column
            { orderable: false, targets: -2 }  // Attachment column
        ]
    });
});
</script>
@endpush

@endsection