@extends('layout')

@section('styles')
<style>
    .page-header {
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        padding: 16px 20px; margin-bottom: 16px;
    }
    .page-header h1 { font-size: 18px; font-weight: 600; color: #111827; }
    .page-header p  { font-size: 13px; color: #6b7280; margin-top: 4px; }

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
    tbody td { padding: 11px 14px; font-size: 13px; color: #374151; vertical-align: middle; }

    .badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .badge-open    { background: #dcfce7; color: #166534; }
    .badge-on_hold { background: #fef3c7; color: #92400e; }
    .badge-closed  { background: #f3f4f6; color: #374151; }
    .badge-low     { background: #f0fdf4; color: #166634; }
    .badge-high    { background: #dbeafe; color: #1e40af; }
    .badge-urgent  { background: #fee2e2; color: #991b1b; }

    .due-cell { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .due-input {
        font-size: 12px; border: 1px solid #d1d5db; border-radius: 6px;
        padding: 3px 6px; background: #fff; color: #111827; width: 130px;
    }
    .due-reason-input {
        font-size: 12px; border: 1px solid #d1d5db; border-radius: 6px;
        padding: 4px 6px; background: #fff; color: #111827;
        width: 100%; margin-top: 6px; resize: vertical; min-height: 52px;
    }
    .due-reason-input:focus { outline: none; border-color: #3b82f6; }
    .d-none { display: none !important; }
    .btn-edit-due {
        background: none; border: none; cursor: pointer;
        color: #9ca3af; padding: 2px 4px; border-radius: 4px;
        display: inline-flex; align-items: center;
    }
    .btn-edit-due:hover { background: #f3f4f6; color: #1d4ed8; }
    .btn-save-due {
        background: #1d4ed8; color: #fff; border: none;
        padding: 3px 10px; border-radius: 5px; font-size: 11px; cursor: pointer;
    }
    .btn-save-due:hover { background: #1e40af; }
    .btn-cancel-due {
        background: #fff; color: #6b7280; border: 1px solid #d1d5db;
        padding: 3px 8px; border-radius: 5px; font-size: 11px; cursor: pointer;
    }
    .btn-cancel-due:hover { background: #f3f4f6; }
    .due-flash { font-size: 11px; color: #16a34a; }

    .no-due { color: #9ca3af; font-size: 12px; }
    .overdue { color: #991b1b; font-weight: 600; font-size: 12px; }
    .empty-state { padding: 60px 20px; text-align: center; color: #6b7280; }
</style>
@endsection

@section('content')

<div class="page-header">
    <h1>📅 Edit Due Dates</h1>
    <p>Set or update due dates for any ticket</p>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Assigned To</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $i => $ticket)
            <tr>
                <td style="color:#9ca3af; font-size:12px; text-align:center;">{{ $i + 1 }}</td>

                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500;"
                    title="{{ $ticket->description }}">
                    {{ $ticket->description }}
                </td>

                <td>
                    <span style="font-weight:500; font-size:13px;">{{ optional($ticket->creator)->name ?? '—' }}</span>
                    @if($ticket->creator)
                        <span style="font-size:11px; color:#9ca3af; display:block;">{{ $ticket->creator->email }}</span>
                    @endif
                </td>

                <td style="font-size:13px;">
                    {{ optional($ticket->assignee)->name ?? '—' }}
                </td>

                <td>
                    <span class="badge badge-{{ strtolower($ticket->priority) }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </td>

                <td>
                    <span class="badge badge-{{ str_replace(' ','_',$ticket->status) }}">
                        {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </td>

                <td>
                    <div class="due-cell" id="due-cell-{{ $ticket->id }}">

                        {{-- Display --}}
                        <span id="due-display-{{ $ticket->id }}">
                            @if($ticket->due_date)
                                @php $due = \Carbon\Carbon::parse($ticket->due_date); @endphp
                                <span class="{{ $due->isPast() ? 'overdue' : '' }}">
                                    {{ $due->format('d M Y') }}
                                    @if($due->isPast())
                                        <span style="background:#fee2e2;color:#991b1b;padding:1px 6px;border-radius:10px;font-size:10px;margin-left:4px;">Overdue</span>
                                    @endif
                                </span>
                            @else
                                <span class="no-due">Not set</span>
                            @endif
                        </span>

                        {{-- Edit button --}}
                        <button class="btn-edit-due" id="edit-btn-{{ $ticket->id }}"
                                onclick="startEdit({{ $ticket->id }})" title="Edit due date">
                            <i class="fas fa-calendar-alt" style="font-size:13px;"></i>
                        </button>

                        <span class="due-flash d-none" id="flash-{{ $ticket->id }}">✓ Saved</span>

                        {{-- Edit form (hidden by default) --}}
                        <div class="d-none" id="due-edit-{{ $ticket->id }}" style="width:100%;margin-top:4px;">
                            <input type="date" class="due-input" id="due-input-{{ $ticket->id }}"
                                   value="{{ $ticket->due_date ? \Carbon\Carbon::parse($ticket->due_date)->format('Y-m-d') : '' }}" />
                            <textarea
                                class="due-reason-input"
                                id="due-reason-{{ $ticket->id }}"
                                placeholder="Reason for changing due date (optional)"></textarea>
                            <div style="display:flex;gap:6px;margin-top:6px;">
                                <button class="btn-save-due" onclick="saveDate({{ $ticket->id }})">Save</button>
                                <button class="btn-cancel-due" onclick="cancelEdit({{ $ticket->id }})">Cancel</button>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">No tickets found.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
function startEdit(id) {
    document.getElementById('due-display-' + id).classList.add('d-none');
    document.getElementById('edit-btn-' + id).classList.add('d-none');
    document.getElementById('due-edit-' + id).classList.remove('d-none');
    document.getElementById('flash-' + id).classList.add('d-none');
    document.getElementById('due-input-' + id).focus();
}

function cancelEdit(id) {
    document.getElementById('due-display-' + id).classList.remove('d-none');
    document.getElementById('edit-btn-' + id).classList.remove('d-none');
    document.getElementById('due-edit-' + id).classList.add('d-none');
    document.getElementById('due-reason-' + id).value = '';
}

function saveDate(id) {
    const val    = document.getElementById('due-input-' + id).value;
    const reason = document.getElementById('due-reason-' + id).value;

    fetch('/tickets/' + id + '/due-date', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ due_date: val || null, due_date_reason: reason || null }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('due-display-' + id).innerHTML =
                data.due_date
                    ? `<span>${data.due_date}</span>`
                    : `<span class="no-due">Not set</span>`;
            cancelEdit(id);
            const flash = document.getElementById('flash-' + id);
            flash.classList.remove('d-none');
            setTimeout(() => flash.classList.add('d-none'), 2000);
        }
    })
    .catch(() => alert('Failed to update. Please try again.'));
}
</script>
@endsection