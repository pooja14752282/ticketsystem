@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/ticket-due-dates.css') }}">
@endsection

@section('content')

<div class="page-header">
    <h6>📅 Edit Due Dates</h6>
    <p>Set or update due dates for any ticket</p>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Ticket id </th>
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
                <td>{{ $ticket->ticket_id }}</td>
               
                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:500;"
                    title="{{ $ticket->description }}">
                    {{ $ticket->description }}
                </td>

                <td>
                    <span style="font-weight:500; font-size:13px;">{{ optional($ticket->creator)->name ?? '—' }}</span>
                    @if($ticket->creator)
                        <span style="font-size:11px; color:#000000; display:block;">{{ $ticket->creator->email }}</span>
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

                        <button class="btn-edit-due" id="edit-btn-{{ $ticket->id }}"
                                onclick="startEdit({{ $ticket->id }})" title="Edit due date">
                            <i class="fas fa-calendar-alt" style="font-size:13px;"></i>
                        </button>

                        <span class="due-flash d-none" id="flash-{{ $ticket->id }}">✓ Saved</span>

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

@push('scripts')
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
@endpush