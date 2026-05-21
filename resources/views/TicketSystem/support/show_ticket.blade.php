@extends('layout')

@section('styles')
<style>
    .page-header { display:flex;align-items:flex-start;justify-content:space-between;background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 20px;margin-bottom:16px; }
    .page-header h1 { font-size:18px;font-weight:600;color:#111827; }
    .page-header p  { font-size:13px;color:#6b7280;margin-top:4px; }
    .btn-back { display:inline-flex;align-items:center;gap:8px;background:#f3f4f6;color:#374151;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none; }
    .btn-back:hover { background:#e5e7eb;color:#111827; }

    .card { background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:16px; }
    .card-header { background:#1d4ed8;padding:14px 20px;display:flex;align-items:center;gap:10px; }
    .card-header span { color:#fff;font-size:14px;font-weight:600; }

    .meta-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:#e5e7eb; }
    .meta-cell { background:#fff;padding:14px 18px; }
    .meta-label { font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px; }
    .meta-val { font-size:13px;font-weight:600;color:#111827; }

    .badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;text-transform:capitalize; }
    .badge-in_progress { background:#fef3c7;color:#92400e; }
    .badge-completed   { background:#dcfce7;color:#166534; }
    .badge-on_hold     { background:#fce7f3;color:#9d174d; }
    .badge-re_opened   { background:#ede9fe;color:#5b21b6; }
    .badge-low         { background:#f0fdf4;color:#166534; }
    .badge-high        { background:#dbeafe;color:#1e40af; }
    .badge-urgent      { background:#fee2e2;color:#991b1b; }

    .status-select {
        padding:5px 10px;font-size:12px;font-weight:600;
        border-radius:20px;border:1px solid #d1d5db;
        cursor:pointer;outline:none;appearance:auto;transition:background .15s;
    }
    .status-select:focus { border-color:#3b82f6; }
    .status-select.status-in_progress { background:#fef3c7;color:#92400e;border-color:#fde68a; }
    .status-select.status-completed   { background:#dcfce7;color:#166534;border-color:#bbf7d0; }
    .status-select.status-on_hold     { background:#fce7f3;color:#9d174d;border-color:#fbcfe8; }
    .status-select.status-re_opened   { background:#ede9fe;color:#5b21b6;border-color:#ddd6fe; }
    .status-saved-flash { font-size:11px;color:#16a34a;margin-left:8px;display:none; }

    .section-label { padding:10px 18px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;background:#f9fafb;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:6px; }
    .desc-box { padding:16px 18px;font-size:13px;color:#374151;line-height:1.75;white-space:pre-wrap; }

    .attach-empty { padding:30px 18px;text-align:center;color:#9ca3af;font-size:13px; }
    .attach-empty i { font-size:26px;display:block;margin-bottom:8px;color:#d1d5db; }
    .file-row { padding:14px 18px;display:flex;align-items:center;gap:14px; }
    .file-icon { width:42px;height:42px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .file-icon i { color:#1d4ed8;font-size:18px; }
    .file-name { font-size:13px;font-weight:600;color:#111827; }
    .file-ext  { font-size:11px;color:#9ca3af;margin-top:2px; }
    .btn-dl { display:inline-flex;align-items:center;gap:6px;background:#1d4ed8;color:#fff;padding:7px 14px;border-radius:6px;font-size:12px;text-decoration:none;white-space:nowrap;margin-left:auto; }
    .btn-dl:hover { background:#1e40af;color:#fff; }
    .img-preview img { width:100%;max-height:340px;object-fit:contain;display:block;background:#f9fafb; }
    .img-footer { padding:10px 18px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #e5e7eb; }
    .img-footer span { font-size:12px;color:#6b7280; }
</style>
@endsection

@section('content')

@php
    $authUser   = auth()->user();
    $isAdmin    = $authUser->role === 'admin';
    $teamMember = \App\Models\SupportTeam::where('email', $authUser->email)->first();
    $canEdit    = $isAdmin
        || ($teamMember && $ticket->assigned_team_member_id === $teamMember->id)
        || $ticket->assigned_to === $authUser->id;
@endphp

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#9ca3af;margin-bottom:12px;">
    <a href="{{ route('support.tickets') }}" style="color:#9ca3af;text-decoration:none;">My Assigned Tickets</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#374151;font-weight:500;">Ticket #{{ $ticket->id }}</span>
</div>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>🎫 Ticket #{{ $ticket->id }}</h1>
        <p>Full details for this support ticket</p>
    </div>
    <a href="{{ route('support.tickets') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- Card --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-ticket-alt" style="color:#fff;font-size:15px;"></i>
        <span>Ticket Details</span>
    </div>

    <div class="meta-grid">
        <div class="meta-cell">
            <div class="meta-label">Ticket ID</div>
            <div class="meta-val">#{{ $ticket->id }}</div>
        </div>

        {{-- Status --}}
        <div class="meta-cell">
            <div class="meta-label">Status</div>
            <div class="meta-val" style="display:flex;align-items:center;gap:8px;">
                @if($canEdit)
                    @php $statusKey = str_replace(' ','_',strtolower($ticket->status)); @endphp
                    <select
                        class="status-select status-{{ $statusKey }}"
                        id="status-select-{{ $ticket->id }}"
                        onchange="updateStatus({{ $ticket->id }}, this)">
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}" {{ $ticket->status === $s->value ? 'selected' : '' }}>
                                {{ $s->label }}
                            </option>
                        @endforeach
                    </select>
                    <span class="status-saved-flash" id="status-flash-{{ $ticket->id }}">✓ Saved</span>
                @else
                    <span class="badge badge-{{ str_replace(' ','_',strtolower($ticket->status)) }}">
                        {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="meta-cell">
            <div class="meta-label">Priority</div>
            <div class="meta-val" style="display:flex;align-items:center;gap:8px;">
                @if($canEdit)
                    @php $priorityKey = strtolower($ticket->priority); @endphp
                    <select
                        class="status-select status-{{ $priorityKey }}"
                        id="priority-select-{{ $ticket->id }}"
                        onchange="updatePriority({{ $ticket->id }}, this)">
                        @foreach($priorities as $p)
                            <option value="{{ $p->value }}" {{ $ticket->priority === $p->value ? 'selected' : '' }}>
                                {{ $p->label }}
                            </option>
                        @endforeach
                    </select>
                    <span class="status-saved-flash" id="priority-flash-{{ $ticket->id }}">✓ Saved</span>
                @else
                    <span class="badge badge-{{ strtolower($ticket->priority) }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="meta-cell">
            <div class="meta-label">Category</div>
            <div class="meta-val">{{ $ticket->ticketCategory->name ?? $ticket->category ?? '-' }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Created By</div>
            <div class="meta-val">{{ optional($ticket->creator)->name ?? '-' }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Assigned To</div>
            <div class="meta-val">
                @if($ticket->assignedTeamMember)
                    {{ $ticket->assignedTeamMember->name }}
                    <div style="font-size:11px;color:#6b7280;font-weight:400;margin-top:2px;">
                        {{ \App\Models\SupportTeam::APPS[$ticket->assignedTeamMember->app_assigned] ?? '' }}
                    </div>
                @else
                    {{ optional($ticket->assignee)->name ?? '—' }}
                @endif
            </div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Created On</div>
            <div class="meta-val">{{ $ticket->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Last Updated</div>
            <div class="meta-val">{{ $ticket->updated_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">Age</div>
            <div class="meta-val">{{ $ticket->age }} hr</div>
        </div>
    </div>

    {{-- Due Date Change Reason --}}
    @if($ticket->due_date_reason)
        <div style="padding:10px 18px;background:#fffbeb;border-top:1px solid #fde68a;">
            <small style="font-size:12px;color:#92400e;">
                <i class="fas fa-info-circle"></i>
                <strong>Due Date Change Reason:</strong> {{ $ticket->due_date_reason }}
            </small>
        </div>
    @endif

    {{-- Description --}}
    <div class="section-label"><i class="fas fa-align-left"></i> Description</div>
    <div class="desc-box">{{ $ticket->description ?? 'No description provided.' }}</div>

    {{-- Attachment --}}
    <div class="section-label"><i class="fas fa-paperclip"></i> Attachment</div>

    @if(!$ticket->attachment)
        <div class="attach-empty">
            <i class="fas fa-folder-open"></i>
            No attachment uploaded for this ticket.
        </div>
    @elseif(in_array(strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp']))
        <div class="img-preview">
            <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Attachment">
        </div>
        <div class="img-footer">
            <span><i class="fas fa-image" style="margin-right:5px;"></i>{{ basename($ticket->attachment) }}</span>
            <a href="{{ asset('storage/' . $ticket->attachment) }}"
               download="{{ basename($ticket->attachment) }}" class="btn-dl">
                <i class="fas fa-download"></i> Download
            </a>
        </div>
    @else
        @php
            $ext      = strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION));
            $iconMap  = ['pdf'=>'fa-file-pdf','doc'=>'fa-file-word','docx'=>'fa-file-word'];
            $fileIcon = $iconMap[$ext] ?? 'fa-file-alt';
        @endphp
        <div class="file-row">
            <div class="file-icon"><i class="fas {{ $fileIcon }}"></i></div>
            <div>
                <div class="file-name">{{ basename($ticket->attachment) }}</div>
                <div class="file-ext">{{ strtoupper($ext) }} file</div>
            </div>
            <a href="{{ asset('storage/' . $ticket->attachment) }}"
               download="{{ basename($ticket->attachment) }}" class="btn-dl">
                <i class="fas fa-download"></i> Download
            </a>
        </div>
    @endif

    {{-- Review --}}
    <div class="section-label"><i class="fas fa-comment-alt"></i> Review</div>

    <div style="padding:16px 18px;">

        @if(session('success'))
            <div style="margin-bottom:14px;padding:10px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#15803d;">
                <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
            </div>
        @endif

        @if($ticket->review && $ticket->review->support_member_id === auth()->id())
            <div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px;background:#f9fafb;margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <p style="font-size:12px;color:#6b7280;margin:0;">Your review · {{ $ticket->review->created_at->format('d M Y, h:i A') }}</p>
                    <span style="font-size:11px;padding:3px 10px;border-radius:999px;border:1px solid #d1d5db;color:#374151;background:#fff;">
                        {{ $ticket->review->resolution_status }}
                    </span>
                </div>
                <p style="font-size:13px;color:#374151;margin:0;line-height:1.6;">{{ $ticket->review->notes }}</p>
            </div>
        @endif

        <form action="{{ route('ticket.review.store', $ticket) }}" method="POST">
            @csrf

            <div style="margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <label style="font-size:12px;color:#6b7280;white-space:nowrap;">Resolution status</label>
                <select name="resolution_status" style="font-size:13px;padding:6px 10px;border-radius:8px;border:1px solid #d1d5db;background:#f9fafb;color:#111827;">
                    <option value="Resolved"           {{ optional($ticket->review)->resolution_status == 'Resolved'           ? 'selected' : '' }}>Resolved</option>
                    <option value="Partially resolved" {{ optional($ticket->review)->resolution_status == 'Partially resolved' ? 'selected' : '' }}>Partially resolved</option>
                    <option value="Unresolved"         {{ optional($ticket->review)->resolution_status == 'Unresolved'         ? 'selected' : '' }}>Unresolved</option>
                    <option value="Escalated"          {{ optional($ticket->review)->resolution_status == 'Escalated'          ? 'selected' : '' }}>Escalated</option>
                </select>
            </div>

            <div style="margin-bottom:14px;">
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;">Review / Notes</label>
                <textarea name="notes" rows="4"
                    placeholder="Write your review or notes about this ticket..."
                    style="width:100%;box-sizing:border-box;padding:10px 12px;border-radius:8px;border:1px solid #d1d5db;background:#f9fafb;color:#111827;font-size:13px;resize:vertical;line-height:1.6;">{{ optional($ticket->review)->notes }}</textarea>
                @error('notes')
                    <p style="font-size:12px;color:#dc2626;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit"
                    style="padding:7px 20px;font-size:13px;font-weight:500;border-radius:8px;border:1px solid #d1d5db;background:#f9fafb;color:#111827;cursor:pointer;">
                    {{ $ticket->review ? 'Update Review' : 'Submit Review' }}
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

@section('scripts')
<script>
function updateStatus(id, selectEl) {
    const status = selectEl.value;
    fetch('/tickets/' + id + '/status', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status: status }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectEl.className = 'status-select status-' + status;
            const flash = document.getElementById('status-flash-' + id);
            flash.style.display = 'inline';
            setTimeout(() => flash.style.display = 'none', 2000);
        } else {
            alert('Failed to update status.');
        }
    })
    .catch(() => alert('Failed to update status. Please try again.'));
}

function updatePriority(id, selectEl) {
    const priority = selectEl.value;
    fetch('/tickets/' + id + '/priority', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ priority: priority }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectEl.className = 'status-select status-' + priority;
            const flash = document.getElementById('priority-flash-' + id);
            flash.style.display = 'inline';
            setTimeout(() => flash.style.display = 'none', 2000);
        } else {
            alert('Failed to update priority.');
        }
    })
    .catch(() => alert('Failed to update priority. Please try again.'));
}
</script>
@endsection
