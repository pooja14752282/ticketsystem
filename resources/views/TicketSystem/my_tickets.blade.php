@extends('layout')

@php $hideAssigned = true; @endphp

@section('styles')
<style>
    .stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: white; border-radius: 10px; padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        border-left: 4px solid transparent;
    }
    .stat-card.high   { border-left-color: #e53e3e; }
    .stat-card.open   { border-left-color: #3182ce; }
    .stat-card.onhold { border-left-color: #d69e2e; }
    .stat-card.urgent { border-left-color: #805ad5; }
    .stat-card .label { font-size: 12px; color: #718096; margin-bottom: 6px; }
    .stat-card .value { font-size: 28px; font-weight: 600; color: #2d3748; }

    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px;
    }
    .page-header h2 { font-size: 20px; color: #2d3748; font-weight: 600; }

    .btn-create {
        background: #1d4ed8; color: white; border: none;
        padding: 10px 20px; border-radius: 6px; cursor: pointer;
        font-size: 13px; text-decoration: none;
    }
    .btn-create:hover { background: #1e40af; }

    .filters {
        background: white; border-radius: 10px; padding: 16px 20px;
        display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    }
    .filters input, .filters select {
        padding: 8px 12px; border: 1px solid #e2e8f0;
        border-radius: 6px; font-size: 13px; color: #4a5568; outline: none;
    }
    .filters input { flex: 1; min-width: 160px; }

    .table-card {
        background: white; border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07); overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f7fafc; }
    thead th {
        padding: 12px 16px; text-align: left;
        font-size: 12px; font-weight: 600; color: #718096;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    tbody tr { border-top: 1px solid #f0f0f0; }
    tbody tr:hover { background: #f7fafc; }
    tbody td { padding: 13px 16px; font-size: 13px; color: #4a5568; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }

    .empty-state { text-align: center; padding: 60px 20px; color: #a0aec0; }
    .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }

    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.5); z-index: 999;
        justify-content: center; align-items: center;
    }
    .modal-box {
        background: white; border-radius: 12px; padding: 32px;
        width: 480px; max-width: 95%;
        max-height: 90vh; overflow-y: auto;
    }
    .modal-box h3 { margin-bottom: 20px; color: #2d3748; font-size: 16px; }
    .form-group { margin-bottom: 14px; }
    .form-group label { font-size: 13px; color: #4a5568; display: block; margin-bottom: 5px; font-weight: 500; }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: 9px 12px;
        border: 1px solid #e2e8f0; border-radius: 6px;
        font-size: 13px; outline: none; font-family: inherit;
    }
    .form-group textarea { resize: vertical; }
    .file-upload-area {
        border: 2px dashed #e2e8f0; border-radius: 8px;
        padding: 20px; text-align: center; cursor: pointer;
        transition: border-color 0.2s;
    }
    .file-upload-area:hover { border-color: #1d4ed8; }
    .file-upload-area i { font-size: 24px; color: #a0aec0; margin-bottom: 8px; display: block; }
    .file-upload-area p { font-size: 13px; color: #718096; margin: 0; }
    .file-upload-area span { font-size: 11px; color: #a0aec0; }
    .file-name { font-size: 12px; color: #1d4ed8; margin-top: 6px; }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
    .btn-cancel {
        padding: 9px 20px; border: 1px solid #e2e8f0;
        border-radius: 6px; background: white; cursor: pointer; font-size: 13px;
    }
    .btn-submit {
        padding: 9px 20px; background: #1d4ed8; color: white;
        border: none; border-radius: 6px; cursor: pointer; font-size: 13px;
    }
    .btn-submit:hover { background: #1e40af; }
    .auto-assign-info {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px;
        padding: 10px 12px; font-size: 12px; color: #1d4ed8; margin-top: 8px;
        display: none;
    }
    .auto-assign-info i { margin-right: 6px; }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>My Tickets</h2>
    <a href="#" class="btn-create"
       onclick="document.getElementById('createModal').style.display='flex'">
        + Create New Ticket
    </a>
</div>

{{-- STATS --}}
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
</div>

{{-- FILTERS --}}
<div class="filters">
    <input type="text" id="searchInput" placeholder="🔍 Search tickets..." onkeyup="filterTable()">
    <select id="statusFilter" onchange="filterTable()">
        <option value="">All Status</option>
        @foreach($statuses as $s)
            <option value="{{ $s->value }}">{{ $s->label }}</option>
        @endforeach
    </select>
    <select id="priorityFilter" onchange="filterTable()">
        <option value="">All Priority</option>
        @foreach($priorities as $p)
            <option value="{{ $p->value }}">{{ $p->label }}</option>
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
            @forelse($tickets as $ticket)
            @php
                $priorityOption = $priorities->firstWhere('value', $ticket->priority);
                $statusOption   = $statuses->firstWhere('value', $ticket->status);
            @endphp
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $ticket->description }}
                </td>
                <td>
                    <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                        {{ \App\Models\SupportTeam::APPS[$ticket->category] ?? $ticket->category }}
                    </span>
                </td>
                <td>
                    <span class="badge" style="background:{{ $priorityOption->color ?? '#f3f4f6' }};color:{{ $priorityOption->text_color ?? '#374151' }};">
                        {{ $priorityOption->label ?? ucfirst($ticket->priority) }}
                    </span>
                </td>
                <td>
                    <span class="badge" style="background:{{ $statusOption->color ?? '#f3f4f6' }};color:{{ $statusOption->text_color ?? '#374151' }};">
                        {{ $statusOption->label ?? ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="icon">🎫</div>
                        <p>No tickets found. Create your first ticket!</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')

{{-- CREATE TICKET MODAL --}}
<div id="createModal" class="modal-overlay">
    <div class="modal-box">
        <h3>🎫 Create New Ticket</h3>
        <form action="{{ route('ticketsystem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="3" required
                    placeholder="Describe your issue..."></textarea>
            </div>

            <div class="form-group">
                <label>App *</label>
                <select name="category" id="appSelect" required onchange="showAutoAssign()">
                    <option value="">-- Select App --</option>
                    @foreach(\App\Models\SupportTeam::APPS as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="auto-assign-info" id="autoAssignInfo">
                    <i class="fas fa-user-check"></i>
                    <span id="autoAssignText">This ticket will be auto-assigned to the support member for this app.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Priority *</label>
                <select name="priority" required>
                    <option value="">-- Select Priority --</option>
                    @foreach($priorities as $p)
                        <option value="{{ $p->value }}">{{ $p->label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Attach File <span style="color:#a0aec0;font-weight:400;">(optional)</span></label>
                <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload a file</p>
                    <span>PNG, JPG, PDF, DOC up to 10MB</span>
                    <div class="file-name" id="fileName"></div>
                </div>
                <input type="file" id="fileInput" name="attachment"
                       accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                       style="display:none"
                       onchange="document.getElementById('fileName').textContent = this.files[0]?.name || ''">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel"
                    onclick="document.getElementById('createModal').style.display='none'">
                    Cancel
                </button>
                <button type="submit" class="btn-submit">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterTable() {
    const search   = document.getElementById('searchInput').value.toLowerCase();
    const status   = document.getElementById('statusFilter').value.toLowerCase();
    const priority = document.getElementById('priorityFilter').value.toLowerCase();
    const rows     = document.querySelectorAll('#ticketTable tbody tr');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = (
            text.includes(search) &&
            (!status   || text.includes(status)) &&
            (!priority || text.includes(priority))
        ) ? '' : 'none';
    });
}

const appMembers = @json(\App\Models\SupportTeam::where('is_active', true)->get(['app_assigned','name']));

function showAutoAssign() {
    const selected = document.getElementById('appSelect').value;
    const infoBox  = document.getElementById('autoAssignInfo');
    const infoText = document.getElementById('autoAssignText');

    if (!selected) { infoBox.style.display = 'none'; return; }

    const match = appMembers.find(m => m.app_assigned === selected);
    if (match) {
        infoText.textContent = '✅ Will be assigned to: ' + match.name;
    } else {
        infoText.textContent = '⚠️ No active support member found for this app.';
    }
    infoBox.style.display = 'block';
}
</script>
@endsection
