@extends('layout')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>👤 Assigned To Me <span style="font-size:13px;font-weight:400;color:#9ca3af;">— Ticket List</span></h1>
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
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-apply"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('ticketsystem.assigned') }}" class="btn-clear"><i class="fas fa-times"></i> Clear</a>
        </div>
    </div>
</form>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Sno</th>
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
                <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ Str::limit($ticket->description, 50) }}
                </td>
                <td>{{ $ticket->category }}</td>
                <td>{{ optional($ticket->assignee)->name ?? '—' }}</td>

                {{-- Status — dynamic color --}}
                <td>
                    <span class="badge" style="background:{{ $statusOption->color ?? '#f3f4f6' }};color:{{ $statusOption->text_color ?? '#374151' }};">
                        {{ $statusOption->label ?? ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </td>

                {{-- Priority — dynamic color --}}
                <td>
                    <span class="badge" style="background:{{ $priorityOption->color ?? '#f3f4f6' }};color:{{ $priorityOption->text_color ?? '#374151' }};">
                        {{ $priorityOption->label ?? ucfirst($ticket->priority) }}
                    </span>
                </td>

                <td>{{ $ticket->age }}d</td>

                <td style="display:flex;align-items:center;gap:8px;">
                    <button class="btn-view" onclick="openTicketModal({{ $ticket->id }})" title="View ticket details">
                        <i class="fas fa-eye"></i> View
                    </button>

                    {{-- Status Update — dynamic options --}}
                    <form method="POST" action="{{ route('ticketsystem.updateStatus', $ticket) }}" style="margin:0">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="status-select">
                            @foreach($statuses as $s)
                                <option value="{{ $s->value }}" {{ $ticket->status == $s->value ? 'selected' : '' }}>
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

{{-- ===================== TICKET VIEW MODAL ===================== --}}
<div id="ticketModalOverlay" onclick="closeTicketModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div onclick="event.stopPropagation()" style="background:#fff;border-radius:12px;width:100%;max-width:580px;margin:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;">

        <div style="background:#1d4ed8;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <i class="fas fa-ticket-alt" style="color:#fff;font-size:16px;"></i>
                <span style="color:#fff;font-size:15px;font-weight:600;">Ticket <span id="modal-ticket-id"></span></span>
            </div>
            <button onclick="closeTicketModal()" style="background:none;border:none;color:#fff;cursor:pointer;font-size:18px;opacity:0.8;line-height:1;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="padding:20px;">
            <div id="modal-loading" style="text-align:center;padding:40px 0;color:#9ca3af;">
                <i class="fas fa-spinner fa-spin" style="font-size:24px;margin-bottom:10px;display:block;"></i>
                Loading ticket details...
            </div>

            <div id="modal-content" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 14px;border:1px solid #f3f4f6;">
                        <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Category</div>
                        <div style="font-size:13px;font-weight:600;color:#111827;" id="modal-category"></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 14px;border:1px solid #f3f4f6;">
                        <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Priority</div>
                        <div id="modal-priority"></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 14px;border:1px solid #f3f4f6;">
                        <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Status</div>
                        <div id="modal-status"></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:10px 14px;border:1px solid #f3f4f6;">
                        <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Created</div>
                        <div style="font-size:13px;font-weight:600;color:#111827;" id="modal-created"></div>
                    </div>
                </div>

                <div style="margin-bottom:18px;">
                    <div style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                        <i class="fas fa-align-left" style="margin-right:5px;"></i> Description
                    </div>
                    <div id="modal-description" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px;font-size:13px;color:#374151;line-height:1.7;white-space:pre-wrap;"></div>
                </div>

                <div>
                    <div style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                        <i class="fas fa-paperclip" style="margin-right:5px;"></i> Attachment
                    </div>
                    <div id="modal-attachment"></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ===================== END MODAL ===================== --}}

@endsection

@section('styles')
<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 16px 20px; margin-bottom: 16px; }
    .page-header h1 { font-size: 18px; font-weight: 600; color: #111827; }
    .page-header p { font-size: 13px; color: #6b7280; margin-top: 4px; }

    .filter-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 16px 20px; margin-bottom: 16px; }
    .filter-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto auto; gap: 10px; align-items: end; }
    .filter-group label { font-size: 12px; color: #6b7280; display: block; margin-bottom: 6px; }
    .filter-group input,
    .filter-group select { width: 100%; padding: 8px 10px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #111827; font-family: 'Segoe UI', sans-serif; }
    .btn-apply { background: #1d4ed8; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px; height: 36px; white-space: nowrap; }
    .btn-apply:hover { background: #1e40af; }
    .btn-clear { background: #fff; color: #6b7280; border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px; height: 36px; text-decoration: none; white-space: nowrap; }
    .btn-clear:hover { background: #f3f4f6; }

    .table-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #1d4ed8; }
    thead th { padding: 11px 14px; font-size: 13px; font-weight: 500; color: #fff; text-align: left; }
    tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9fafb; }
    tbody td { padding: 11px 14px; font-size: 13px; color: #374151; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: capitalize; }

    .status-select { font-size: 12px; padding: 4px 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; cursor: pointer; }

    .btn-view { background: #1d4ed8; color: #fff; border: none; padding: 5px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; }
    .btn-view:hover { background: #1e40af; }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 15px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .empty-state span { font-size: 13px; color: #9ca3af; }
</style>
@endsection

@section('scripts')
<script>
    // Pass ticket_options to JS for modal badges
    const ticketOptions = {
        statuses:   @json($statuses->keyBy('value')),
        priorities: @json($priorities->keyBy('value')),
    };

    const ticketShowBaseUrl = "{{ url('/support/tickets') }}";

    function openTicketModal(ticketId) {
        const overlay = document.getElementById('ticketModalOverlay');
        overlay.style.display = 'flex';
        document.getElementById('modal-loading').style.display = 'block';
        document.getElementById('modal-content').style.display = 'none';

        fetch(`${ticketShowBaseUrl}/${ticketId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to load ticket');
            return res.json();
        })
        .then(data => {
            document.getElementById('modal-ticket-id').textContent  = '#' + data.id;
            document.getElementById('modal-category').textContent   = data.category;
            document.getElementById('modal-created').textContent    = data.created_at;
            document.getElementById('modal-description').textContent = data.description;

            // Priority badge — use DB colors if available
            const pOpt   = ticketOptions.priorities[data.priority];
            const pBg    = pOpt ? pOpt.color      : '#f3f4f6';
            const pColor = pOpt ? pOpt.text_color  : '#374151';
            const pLabel = pOpt ? pOpt.label       : capitalize(data.priority);
            document.getElementById('modal-priority').innerHTML =
                `<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${pBg};color:${pColor};">${pLabel}</span>`;

            // Status badge — use DB colors if available
            const sOpt   = ticketOptions.statuses[data.status];
            const sBg    = sOpt ? sOpt.color      : '#f3f4f6';
            const sColor = sOpt ? sOpt.text_color  : '#374151';
            const sLabel = sOpt ? sOpt.label       : capitalize(data.status.replace('_',' '));
            document.getElementById('modal-status').innerHTML =
                `<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${sBg};color:${sColor};">${sLabel}</span>`;

            // Attachment
            const attachEl = document.getElementById('modal-attachment');
            if (!data.attachment) {
                attachEl.innerHTML = `
                    <div style="background:#f9fafb;border:1px dashed #d1d5db;border-radius:8px;padding:20px;text-align:center;color:#9ca3af;font-size:13px;">
                        <i class="fas fa-folder-open" style="font-size:22px;display:block;margin-bottom:8px;"></i>
                        No attachment uploaded
                    </div>`;
            } else if (data.is_image) {
                attachEl.innerHTML = `
                    <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                        <img src="${data.attachment}" alt="Attachment" style="width:100%;max-height:280px;object-fit:contain;display:block;background:#f9fafb;">
                        <div style="padding:10px 14px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f3f4f6;">
                            <span style="font-size:12px;color:#6b7280;"><i class="fas fa-image" style="margin-right:5px;"></i>${data.filename}</span>
                            <a href="${data.attachment}" download="${data.filename}" style="background:#1d4ed8;color:#fff;padding:4px 12px;border-radius:6px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>`;
            } else {
                const extIcons = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word' };
                const ext  = data.filename.split('.').pop().toLowerCase();
                const icon = extIcons[ext] || 'fa-file-alt';
                attachEl.innerHTML = `
                    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px;display:flex;align-items:center;gap:14px;">
                        <div style="width:40px;height:40px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas ${icon}" style="color:#1d4ed8;font-size:18px;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:13px;font-weight:600;color:#111827;">${data.filename}</div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">${ext.toUpperCase()} file</div>
                        </div>
                        <a href="${data.attachment}" download="${data.filename}" style="background:#1d4ed8;color:#fff;padding:6px 14px;border-radius:6px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>`;
            }

            document.getElementById('modal-loading').style.display = 'none';
            document.getElementById('modal-content').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('modal-loading').innerHTML =
                `<div style="color:#dc2626;"><i class="fas fa-exclamation-circle" style="display:block;font-size:24px;margin-bottom:8px;"></i>Failed to load ticket. Please try again.</div>`;
        });
    }

    function closeTicketModal() {
        document.getElementById('ticketModalOverlay').style.display = 'none';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeTicketModal();
    });

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endsection