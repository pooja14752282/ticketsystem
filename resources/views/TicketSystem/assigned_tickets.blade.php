@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/assigned-tickets.css') }}">
<style>
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 12px;
        width: 600px;
        max-width: 90vw;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        font-size: 15px;
        color: #111827;
    }
    .modal-header button {
        background: none;
        border: none;
        cursor: pointer;
        color: #000000;
        font-size: 16px;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .modal-header button:hover { background: #f3f4f6; color: #111827; }
    .modal-body { padding: 20px; }
    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 16px;
    }
    .modal-box-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 14px;
    }
    .modal-box-item label {
        font-size: 11px;
        color: #000000;
        font-weight: 600;
        text-transform: uppercase;
        display: block;
        margin-bottom: 4px;
    }
    .modal-box-item div { font-size: 13px; color: #111827; font-weight: 500; }
    .modal-section { margin-bottom: 16px; }
    .modal-section label {
        font-size: 11px;
        color: #000000;
        font-weight: 600;
        text-transform: uppercase;
        display: block;
        margin-bottom: 6px;
    }
    .modal-description {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        font-size: 13px;
        color: 000000;
        line-height: 1.6;
    }
    .modal-loading {
        text-align: center;
        padding: 40px;
        color: #000000;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>
            Assigned To Me
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

@push('scripts')
<script>
function openTicketModal(id) {
    document.getElementById('ticketModalOverlay').classList.add('active');
    document.getElementById('modal-ticket-id').textContent = '#' + id;
    document.getElementById('modal-loading').style.display = 'block';
    document.getElementById('modal-content').style.display = 'none';

    fetch('/ticketsystem/' + id, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('modal-category').textContent    = data.category    ?? '—';
        document.getElementById('modal-priority').textContent    = data.priority    ?? '—';
        document.getElementById('modal-status').textContent      = data.status      ?? '—';
        document.getElementById('modal-created').textContent     = data.created_at  ?? '—';
        document.getElementById('modal-description').textContent = data.description ?? '—';

        const attach = document.getElementById('modal-attachment');
        if (data.attachment) {
            attach.innerHTML = `<a href="${data.attachment}" target="_blank">View Attachment</a>`;
        } else {
            attach.textContent = 'No attachment';
        }

        document.getElementById('modal-loading').style.display = 'none';
        document.getElementById('modal-content').style.display = 'block';
    })
    .catch(() => {
        document.getElementById('modal-loading').innerHTML =
            '<span style="color:#991b1b;">Failed to load ticket details.</span>';
    });
}

function closeTicketModal() {
    document.getElementById('ticketModalOverlay').classList.remove('active');
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeTicketModal();
});
</script>
@endpush