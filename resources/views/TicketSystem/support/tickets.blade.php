@extends('layout')

@section('styles')
<style>
    .page-header { display:flex;align-items:flex-start;justify-content:space-between;background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 20px;margin-bottom:16px; }
    .page-header h1 { font-size:18px;font-weight:600;color:#111827; }
    .page-header p  { font-size:13px;color:#000000;margin-top:4px; }

    .stats-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px; }
    .stat-card  { background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px; }
    .stat-card p:first-child { font-size:12px;color:#000000;margin-bottom:4px; }
    .stat-num   { font-size:24px;font-weight:700; }

    .table-card { background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden; }
    table { width:100%;border-collapse:collapse; }
    thead { background:#1d4ed8; }
    thead th { padding:11px 14px;font-size:12px;font-weight:600;color:#fff;text-align:left;white-space:nowrap; }
    tbody tr { border-bottom:1px solid #f3f4f6;transition:background .1s; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:#f9fafb; }
    tbody td { padding:11px 14px;font-size:13px;color:000000;vertical-align:middle; }

    .badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600; }
    .badge-open        { background:#dbeafe;color:#1d4ed8; }
    .badge-in_progress { background:#fef3c7;color:#92400e; }
    .badge-completed   { background:#dcfce7;color:#166534; }
    .badge-on_hold     { background:#fce7f3;color:#9d174d; }
    .badge-re_opened   { background:#ede9fe;color:#5b21b6; }
    .badge-low         { background:#dcfce7;color:#166534; }
    .badge-medium      { background:#fef9c3;color:#854d0e; }
    .badge-high        { background:#fee2e2;color:#991b1b; }
    .badge-urgent      { background:#fce7f3;color:#9d174d; }

    .btn-view { background:#dbeafe;color:#1d4ed8;padding:4px 12px;border-radius:5px;font-size:12px;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;white-space:nowrap; }
    .btn-view:hover { background:#bfdbfe; }

    .due-date-overdue  { color:#dc2626;font-weight:600; }
    .due-date-soon     { color:#f59e0b;font-weight:600; }
    .due-date-ok       { color:#000000; }

    .empty-state { padding:60px 20px;text-align:center;color:#000000;font-size:13px; }
    .empty-state i { font-size:40px;display:block;margin-bottom:10px;color:#d1d5db; }
</style>
@endsection

@section('content')

<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <span style="color:000000;font-weight:500;">My Assigned Tickets</span>
</div>

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
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $i => $ticket)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:600;">#{{ $ticket->id }}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $ticket->description }}">
                    {{ \Str::limit($ticket->description, 50) }}
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
                <td>
                    <a href="{{ route('support.ticket.show', $ticket->id) }}" class="btn-view">
                        <i class="fas fa-eye"></i> View
                    </a>
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

@endsection