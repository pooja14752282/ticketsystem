@extends('layout')

@section('styles')
<style>
    /* ── Page Header ── */
    .page-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        padding: 16px 20px; margin-bottom: 16px;
    }
    .page-header h1 { font-size: 18px; font-weight: 600; color: #111827; }
    .page-header p  { font-size: 13px; color: #000000; margin-top: 4px; }
    .btn-create {
        display: inline-flex; align-items: center; gap: 8px;
        background: #1d4ed8; color: #fff; border: none;
        padding: 9px 16px; border-radius: 8px;
        font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none;
        white-space: nowrap;
    }
    .btn-create:hover { background: #1e40af; color: #fff; }

    /* ── Stats ── */
    .stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 12px; margin-bottom: 16px;
    }
    .stat-card {
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; border-top: 3px solid transparent;
    }
    .stat-card.orange  { border-top-color: #ea580c; }
    .stat-card.green { border-top-color: #22c55e; }
    .stat-card.amber { border-top-color: #f59e0b; }
    .stat-card.red   { border-top-color: #dc2626; }
    .stat-num   { font-size: 28px; font-weight: 600; color: #111827; }
    .stat-label { font-size: 12px; color: #000000; margin-top: 4px; }
    .stat-icon  {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .stat-icon.orange  { background: #dbeafe; color: #ea580c;; }
    .stat-icon.green { background: #dcfce7; color: #22c55e; }
    .stat-icon.amber { background: #fef3c7; color: #f59e0b; }
    .stat-icon.red   { background: #fee2e2; color: #dc2626; }

    /* ── Filters ── */
    .filter-card {
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        padding: 16px 20px; margin-bottom: 16px;
    }
    .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
        gap: 10px; align-items: end;
    }
    .filter-group label { font-size: 12px; color: #000000; display: block; margin-bottom: 6px; }
    .filter-group input,
    .filter-group select {
        width: 100%; padding: 8px 10px; font-size: 13px;
        border: 1px solid #d1d5db; border-radius: 6px;
        background: #fff; color: #111827;
    }
    .filter-group input:focus,
    .filter-group select:focus { outline: none; border-color: #ea580c; }
    .btn-apply {
        background: #1d4ed8; color: #fff; border: none;
        padding: 0 16px; border-radius: 6px; font-size: 13px;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        height: 36px; white-space: nowrap;
    }
    .btn-apply:hover { background: #1e40af; }
    .btn-clear {
        background: #fff; color: #000000; border: 1px solid #d1d5db;
        padding: 0 12px; border-radius: 6px; font-size: 13px;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        height: 36px; text-decoration: none; white-space: nowrap;
    }
    .btn-clear:hover { background: #f3f4f6; }

    /* ── Table ── */
    .table-card {
        background: #fff; border-radius: 10px;
        border: 1px solid #e5e7eb; overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #1d4ed8; }
    thead th {
        padding: 11px 14px; font-size: 12px; font-weight: 600;
        color: #fff; text-align: left; white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9fafb; }
    tbody td { padding: 11px 14px; font-size: 13px; color: 000000; vertical-align: middle; }
    tbody td:last-child { white-space: nowrap; }

    .sno-col  { width: 48px; text-align: center; color: #000000; font-size: 12px; }
    .date-col { white-space: nowrap; font-size: 12px; color: #000000; }
    .desc-col { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
    .cat-col  { font-size: 12px; color: #000000; white-space: nowrap; }
    .age-col  { font-size: 12px; color: #000000; white-space: nowrap; font-family: monospace; }

    .user-name  { font-weight: 500; display: block; }
    .user-email { font-size: 11px; color: #000000; display: block; margin-top: 1px; }

    /* ── Badges ── */
    .badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }

    /* ── Action buttons ── */
    .action-btns { display: flex; gap: 4px; flex-wrap: nowrap; align-items: center; }
    .btn-view {
        background: #dbeafe; color: #1d4ed8; padding: 4px 10px;
        border-radius: 5px; font-size: 12px; border: none; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
        white-space: nowrap;
    }
    .btn-view:hover { background: #bfdbfe; }
    .btn-delete {
        background: #fee2e2; color: #991b1b; padding: 4px 10px;
        border-radius: 5px; font-size: 12px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;
    }
    .btn-delete:hover { background: #fecaca; }
    .btn-reassign {
        background: #fef3c7; color: #92400e; padding: 4px 10px;
        border-radius: 5px; font-size: 12px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;
    }
    .btn-reassign:hover { background: #fde68a; }

    /* ── Empty state ── */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 15px; font-weight: 600; color: 000000; }

    /* ── Reassign Modal ── */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 1000;
        align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px; padding: 28px 28px 24px;
        width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        position: relative;
    }
    .modal-box h2 { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 4px; }
    .modal-box p  { font-size: 13px; color: #000000; margin-bottom: 18px; }
    .modal-label  { font-size: 12px; color: 000000; font-weight: 500; display: block; margin-bottom: 6px; }
    .modal-select {
        width: 100%; padding: 9px 10px; font-size: 13px;
        border: 1px solid #d1d5db; border-radius: 7px;
        background: #fff; color: #111827; margin-bottom: 20px;
    }
    .modal-select:focus { outline: none; border-color: #ea580c; }
    .modal-textarea {
        width: 100%; padding: 9px 10px; font-size: 13px;
        border: 1px solid #d1d5db; border-radius: 7px;
        background: #fff; color: #111827; margin-bottom: 20px;
        resize: vertical; min-height: 80px; font-family: inherit;
    }
    .modal-textarea:focus { outline: none; border-color: #ea580c; }
    .reason-note { font-size: 11px; color: #d97706; margin-left: 4px; }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 4px; }
    .btn-modal-cancel {
        background: #fff; color: #000000; border: 1px solid #d1d5db;
        padding: 8px 16px; border-radius: 7px; font-size: 13px; cursor: pointer;
    }
    .btn-modal-cancel:hover { background: #f3f4f6; }
    .btn-modal-confirm {
        background: #d97706; color: #fff; border: none;
        padding: 8px 18px; border-radius: 7px; font-size: 13px;
        font-weight: 500; cursor: pointer;
    }
    .btn-modal-confirm:hover { background: #b45309; }
    .modal-close {
        position: absolute; top: 14px; right: 16px;
        background: none; border: none; font-size: 18px;
        color: #000000; cursor: pointer; line-height: 1;
    }
    .modal-close:hover { color: 000000; }
    .reassign-flash {
        display: none; font-size: 12px; color: #16a34a;
        margin-top: 10px; text-align: right;
    }

    .table-responsive {
    width: 100%;
    overflow: hidden;
}

.table-scroll {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Prevent columns from breaking */
table {
    min-width: 1100px; /* adjust based on columns */
}

/* ── DataTables sort arrows + footer controls ── */
table.dataTable thead th {
    position: relative;
}
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-size: 12px; color: #000000; padding: 10px 14px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px; margin-left: 2px; border-radius: 5px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #1d4ed8 !important; color: #fff !important; border: none !important;
}
.action-dropdown {
    position: relative;
    display: inline-block;
}

.action-menu-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 18px;
    padding: 6px 10px;
    color: #555;
}

.action-menu-btn:hover {
    color: #000;
}

.dropdown-menu {
    display: none;
    position: fixed;
    background: #fff;
    min-width: 170px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,.15);
    z-index: 2000;
    overflow: hidden;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    width: 100%;
    padding: 10px 15px;
    border: none;
    background: transparent;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.dropdown-item:hover {
    background: #f5f5f5;
}

.dropdown-item.text-danger {
    color: #dc3545;
}

.dropdown-item i {
    width: 18px;
}
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        @if($isAdmin)
            <h1>All Tickets <span style="font-size:13px;font-weight:400;color:#000000;">— Admin View</span></h1>
            <p>Track and manage all support tickets across the system</p>
        @else
            <h1>My Tickets</h1>
            <p>Tickets you have submitted</p>
        @endif
    </div>
    <a href="{{ route('ticketsystem.create') }}" class="btn-create">
        <i class="fas fa-plus"></i> Create New Ticket
    </a>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card orange">
        <div>
            <div class="stat-num">{{ $stats['high'] }}</div>
            <div class="stat-label">High Priority</div>
        </div>
        <div class="stat-icon orange"><i class="fas fa-arrow-up"></i></div>
    </div>
    <div class="stat-card green">
        <div>
            <div class="stat-num">{{ $stats['open'] }}</div>
            <div class="stat-label">Open Tickets</div>
        </div>
        <div class="stat-icon green"><i class="fas fa-folder-open"></i></div>
    </div>
    <div class="stat-card amber">
        <div>
            <div class="stat-num">{{ $stats['onhold'] }}</div>
            <div class="stat-label">On Hold</div>
        </div>
        <div class="stat-icon amber"><i class="fas fa-pause-circle"></i></div>
    </div>
    <div class="stat-card red">
        <div>
            <div class="stat-num">{{ $stats['urgent'] }}</div>
            <div class="stat-label">Urgent Priority</div>
        </div>
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.tickets.index') }}">
    <div class="filter-card">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Description</label>
                <input type="text" name="description" value="{{ request('description') }}" placeholder="Enter keyword...">
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
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-apply">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.tickets.index') }}" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </div>
</form>

{{-- Tickets Table --}}
<div class="table-card table-responsive">
    <div class="table-scroll">
        <table id="ticketsTable">
        <thead>
            <tr>
                <th></th>
                <th>Ticket ID</th>
                <th>Title</th>
                <th>App Name</th>
                @if($isAdmin)
                <th>Created At</th>
                <th>Created By</th>
                @endif
                <th>Assigned To</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Age</th>
                <th>Attachment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $i => $ticket)
            @php
                $statusOption   = $statuses->firstWhere('value', $ticket->status);
                $priorityOption = $priorities->firstWhere('value', $ticket->priority);
            @endphp

            <tr id="row-{{ $ticket->id }}">
                </td>

                <td>
                    <div class="action-dropdown">
    <button type="button"
        class="action-menu-btn"
        onclick="toggleTicketDropdown(this)">
    <i class="fas fa-ellipsis-v"></i>
</button>

    <div class="dropdown-menu">
        @if($isAdmin)

        <button
            class="dropdown-item"
            onclick="openReassign({{ $ticket->id }}, '{{ addslashes($ticket->assignedTeamMember->name ?? optional($ticket->assignee)->name ?? 'Unassigned') }}')">
            <i class="fas fa-user-edit"></i> Reassign
        </button>

        <form method="POST"
              action="{{ route('admin.tickets.destroy', $ticket->id) }}"
              onsubmit="return confirm('Delete this ticket?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="dropdown-item text-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>

        @endif
    </div>
</div>
                </td>
                <td class="sno-col">{{ $ticket->ticket_id }}</td>

                <td class="desc-col" title="{{ $ticket->description }}">
                    <a href="{{ route('admin.tickets.show', $ticket->id) }}">
                    {{ $ticket->title }}
                    </a>
                </td>

                <td class="cat-col">
                    {{ $ticket->ticketCategory->name ?? $ticket->category ?? '—' }}
                </td>

                @if($isAdmin)
                <td>
                    <span class="user-name">{{ $ticket->created_at->format('d M Y') }}</span>
                </td>

                <td>
                    <span class="user-name">{{ optional($ticket->creator)->name ?? '—' }}</span>
                    @if($ticket->creator)
                        <span class="user-email">{{ $ticket->creator->email }}</span>
                    @endif
                </td>
                @endif

                <td id="assignee-cell-{{ $ticket->id }}">
                    @if($ticket->assignedTeamMember)
                        <span class="user-name">{{ $ticket->assignedTeamMember->name }}</span>
                        <span class="user-email">
                            {{ \App\Models\TicketSupportTeam::APPS[$ticket->assignedTeamMember->app_assigned] ?? '' }}
                        </span>
                    @elseif(optional($ticket->assignee)->name)
                        <span class="user-name">{{ $ticket->assignee->name }}</span>
                    @else
                        <span style="color:#000000;">—</span>
                    @endif
                </td>

                <td>
                        {{ $statusOption->label ?? ucfirst(str_replace('_',' ',$ticket->status)) }}
                </td>

                <td>
                        {{ $priorityOption->label ?? ucfirst($ticket->priority) }}
                </td>

                <td class="date-col">
                    @if($ticket->due_date)
                        {{ \Carbon\Carbon::parse($ticket->due_date)->format('d M Y') }}
                    @else
                        <span style="color:#000000;">—</span>
                    @endif
                </td>

                <td class="age-col">{{ $ticket->age }}hr</td>

               <td>
    @if($ticket->attachment)
        <a href="{{ route('tickets.download', $ticket->ticket_id) }}" class="btn-view">
            <i class="fas fa-paperclip"></i> Download
        </a>
    @else
        <span style="color:#000000;">—</span>
    @endif
</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $isAdmin ? 11 : 10 }}">
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <p>No tickets found</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</div>

@if($isAdmin)
{{-- ── Reassign Modal ── --}}
<div class="modal-overlay" id="reassignModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeReassign()" title="Close">&times;</button>
        <h2><i class="fas fa-user-edit" style="color:#d97706;margin-right:6px;"></i> Reassign Ticket</h2>
        <p id="modal-subtitle">Currently assigned to: <strong id="modal-current-assignee">—</strong></p>

        <label class="modal-label">Select New Support Member</label>
        <select class="modal-select" id="modal-member-select">
            <option value="">— Choose a member —</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" data-name="{{ $member->name }}">
                    {{ $member->name }}
                    ({{ \App\Models\TicketSupportTeam::APPS[$member->app_assigned] ?? $member->app_assigned }})
                </option>
            @endforeach
        </select>

        <label class="modal-label">
            Reason for Reassignment
            <span class="reason-note">(visible to the new assignee)</span>
        </label>
        <textarea class="modal-textarea" id="modal-reassign-reason"></textarea>

        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeReassign()">Cancel</button>
            <button class="btn-modal-confirm" onclick="submitReassign()">
                <i class="fas fa-check"></i> Confirm Reassign
            </button>
        </div>
        <div class="reassign-flash" id="reassign-flash">✓ Ticket reassigned successfully</div>
    </div>
</div>
@endif

@endsection

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

@if($isAdmin)
<script>
let _reassignTicketId = null;

function openReassign(ticketId, currentAssigneeName) {
    _reassignTicketId = ticketId;
    document.getElementById('modal-current-assignee').textContent = currentAssigneeName;
    document.getElementById('modal-member-select').value = '';
    document.getElementById('modal-reassign-reason').value = '';
    document.getElementById('reassign-flash').style.display = 'none';
    document.getElementById('reassignModal').classList.add('active');
}

function closeReassign() {
    document.getElementById('reassignModal').classList.remove('active');
    _reassignTicketId = null;
}

function submitReassign() {
    const select   = document.getElementById('modal-member-select');
    const memberId = select.value;
    if (!memberId) {
        select.style.borderColor = '#dc2626';
        setTimeout(() => select.style.borderColor = '#d1d5db', 1500);
        return;
    }

    const memberName = select.options[select.selectedIndex].dataset.name;
    const btn = document.querySelector('.btn-modal-confirm');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    fetch('/tickets/' + _reassignTicketId + '/reassign', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            assigned_team_member_id: memberId,
            reassign_reason: document.getElementById('modal-reassign-reason').value.trim(),
        }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const cell = document.getElementById('assignee-cell-' + _reassignTicketId);
            if (cell) {
                cell.innerHTML = '<span class="user-name">' + data.new_assignee + '</span>';
            }
            const flash = document.getElementById('reassign-flash');
            flash.style.display = 'block';
            setTimeout(() => closeReassign(), 1200);
        } else {
            alert('Reassignment failed. Please try again.');
        }
    })
    .catch(() => alert('An error occurred. Please try again.'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Confirm Reassign';
    });
}

document.getElementById('reassignModal').addEventListener('click', function(e) {
    if (e.target === this) closeReassign();
});

function toggleTicketDropdown(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('show');

    // Close all open menus first
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));

    if (!isOpen) {
        const rect = btn.getBoundingClientRect();
        menu.style.top = (rect.bottom + 4) + 'px';

        // Align right edge of menu with right edge of button,
        // but keep it on-screen if that would overflow left
        let left = rect.right - 170; // 170 = min-width of menu
        if (left < 8) left = rect.left;
        menu.style.left = left + 'px';

        menu.classList.add('show');
    }
}

window.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Reposition/close on scroll so it doesn't float in the wrong place
window.addEventListener('scroll', function() {
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        menu.classList.remove('show');
    });
}, true);

</script>
@endif
@endpush