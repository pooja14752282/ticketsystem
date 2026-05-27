@extends('layout')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>
            👤 Assigned To Me
            <span class="page-subtitle">— Ticket List</span>
        </h1>
        <p>Tickets assigned to you that need your attention</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('ticketsystem.assigned') }}">
    <div class="filter-card">
        <div class="filter-row">

            <div class="filter-group">
                <label>Search Description</label>
                <input type="text" name="description" placeholder="Enter keyword..." value="{{ request('description') }}">
            </div>

            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s->value }}" {{ request('status') == $s->value ? 'selected' : '' }}>
                            {{ $s->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="">All Priority</option>
                    @foreach($priorities as $p)
                        <option value="{{ $p->value }}" {{ request('priority') == $p->value ? 'selected' : '' }}>
                            {{ $p->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Category</label>
                <select name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-apply">
                <i class="fas fa-search"></i> Filter
            </button>

            <a href="{{ route('ticketsystem.assigned') }}" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>

        </div>
    </div>
</form>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Sl.No.</th>
                <th>Created On</th>
                <th>Description</th>
                <th>Category</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($tickets as $index => $ticket)

            @php
                $statusOption   = $statuses->firstWhere('value', $ticket->status);
                $priorityOption = $priorities->firstWhere('value', $ticket->priority);
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>

                <td>{{ $ticket->created_at->format('d M Y') }}</td>

                <td class="ticket-desc">
                    {{ \Illuminate\Support\Str::limit($ticket->description, 50) }}
                </td>

                <td>{{ $ticket->category }}</td>

                <td>{{ optional($ticket->assignee)->name ?? '—' }}</td>

                {{-- Status --}}
                <td>
                    <span class="badge"
                          data-type="status"
                          data-color="{{ $statusOption->color ?? '' }}"
                          data-text="{{ $statusOption->text_color ?? '' }}">
                        {{ $statusOption->label ?? ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </td>

                {{-- Priority --}}
                <td>
                    <span class="badge"
                          data-type="priority"
                          data-color="{{ $priorityOption->color ?? '' }}"
                          data-text="{{ $priorityOption->text_color ?? '' }}">
                        {{ $priorityOption->label ?? ucfirst($ticket->priority) }}
                    </span>
                </td>

                <td>{{ $ticket->age }}d</td>

                {{-- Actions --}}
                <td class="action-cell">

                    <button class="btn-view"
                            onclick="openTicketModal({{ $ticket->id }})">
                        <i class="fas fa-eye"></i> View
                    </button>

                    <form method="POST"
                          action="{{ route('ticketsystem.updateStatus', $ticket) }}">
                        @csrf
                        @method('PATCH')

                        <select name="status"
                                class="status-select"
                                onchange="this.form.submit()">

                            @foreach($statuses as $s)
                                <option value="{{ $s->value }}"
                                    {{ $ticket->status == $s->value ? 'selected' : '' }}>
                                    {{ $s->label }}
                                </option>
                            @endforeach

                        </select>
                    </form>

                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No Tickets Assigned</p>
                        <span>You have no tickets assigned to you right now.</span>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ================= MODAL ================= --}}
<div id="ticketModalOverlay"
     class="modal-overlay"
     onclick="closeTicketModal()">

    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div>
                <i class="fas fa-ticket-alt"></i>
                Ticket <span id="modal-ticket-id"></span>
            </div>

            <button onclick="closeTicketModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">

            <div id="modal-loading" class="modal-loading">
                <i class="fas fa-spinner fa-spin"></i>
                Loading ticket details...
            </div>

            <div id="modal-content" style="display:none;">

                <div class="modal-grid">

                    <div class="modal-box-item">
                        <label>Category</label>
                        <div id="modal-category"></div>
                    </div>

                    <div class="modal-box-item">
                        <label>Priority</label>
                        <div id="modal-priority"></div>
                    </div>

                    <div class="modal-box-item">
                        <label>Status</label>
                        <div id="modal-status"></div>
                    </div>

                    <div class="modal-box-item">
                        <label>Created</label>
                        <div id="modal-created"></div>
                    </div>

                </div>

                <div class="modal-section">
                    <label>Description</label>
                    <div id="modal-description" class="modal-description"></div>
                </div>

                <div class="modal-section">
                    <label>Attachment</label>
                    <div id="modal-attachment"></div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/assigned-tickets.css') }}">
@endpush