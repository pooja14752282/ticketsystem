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
    $teamMember = \App\Models\TicketSupportTeam::where('email', $authUser->email)->first();
    $canEdit    = $isAdmin
        || ($teamMember && $ticket->assigned_team_member_id === $teamMember->id)
        || $ticket->assigned_to === $authUser->id;
@endphp

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#9ca3af;margin-bottom:12px;">
    <a href="{{ route('admin.tickets.index') }}" style="color:#9ca3af;text-decoration:none;">All Tickets</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#374151;font-weight:500;">Ticket #{{ $ticket->id }}</span>
</div>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>🎫 Ticket #{{ $ticket->id }}</h1>
        <p>Full details for this support ticket</p>
    </div>
    <a href="{{ route('admin.tickets.index') }}" class="btn-back">
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
        <div class="meta-val">{{ $ticket->ticket_id }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Title</div>
        <div class="meta-val">{{ $ticket->title }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Type</div>
        <div class="meta-val">{{ $ticket->type ?? '-' }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Status</div>

        @if($canEdit)
            @php
                $statuses = \App\Models\TicketOption::where('type','status')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                $statusKey = str_replace(' ','_',strtolower($ticket->status));
            @endphp

            <select
                class="status-select status-{{ $statusKey }}"
                id="status-select-{{ $ticket->id }}"
                onchange="updateStatus({{ $ticket->id }}, this)">

                @foreach($statuses as $s)
                    <option value="{{ $s->value }}"
                        {{ $ticket->status == $s->value ? 'selected' : '' }}>
                        {{ $s->label }}
                    </option>
                @endforeach
            </select>

        @else

            <span class="badge badge-{{ strtolower(str_replace(' ','_',$ticket->status)) }}">
                {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
            </span>

        @endif
    </div>

    <div class="meta-cell">
        <div class="meta-label">Priority</div>
        <div class="meta-val">
            <span class="badge badge-{{ strtolower($ticket->priority) }}">
                {{ ucfirst($ticket->priority) }}
            </span>
        </div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">App Name</div>
        <div class="meta-val">{{ $ticket->app_name ?? '-' }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Created By</div>
        <div class="meta-val">{{ $ticket->created_by }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Email</div>
        <div class="meta-val">{{ $ticket->creator_email }}</div>
    </div>

    <div class="meta-cell">
        <div class="meta-label">Created At</div>
        <div class="meta-val">
            {{ optional($ticket->created_at)->format('d M Y h:i A') }}
        </div>
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
             <i class="fas fa-paperclip"></i>
              No Attachment
        </div>
    @elseif(in_array(strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp']))
        <div class="img-preview">
            <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Attachment">
        </div>
        <div class="img-footer">
            <span><i class="fas fa-image" style="margin-right:5px;"></i>{{ basename($ticket->attachment) }}</span>
            <a href="{{ route('tickets.download', $ticket->id) }}" class="btn-dl">
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
            <a href="{{ route('tickets.download', $ticket->id) }}" class="btn-dl">
                <i class="fas fa-download"></i> Download
            </a>
        </div>
    @endif

    {{-- Review --}}
    <div class="section-label"><i class="fas fa-comment-alt"></i> Review</div>

    @if($ticket->review)
        <div style="padding:16px 18px;">
            <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px;background:#f9fafb;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:32px;height:32px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#374151;">
                        {{ strtoupper(substr($ticket->review->supportMember->name ?? 'S', 0, 2)) }}
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:600;margin:0;color:#111827;">{{ $ticket->review->supportMember->name ?? '—' }}</p>
                        <p style="font-size:11px;color:#6b7280;margin:0;">{{ $ticket->review->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <span style="margin-left:auto;font-size:11px;padding:3px 10px;border-radius:999px;border:1px solid #d1d5db;color:#374151;background:#fff;">
                        {{ $ticket->review->resolution_status }}
                    </span>
                </div>
                <p style="font-size:13px;color:#374151;margin:0;line-height:1.6;">{{ $ticket->review->notes }}</p>
            </div>
        </div>
    @else
        <div class="attach-empty">
            <i class="fas fa-comment-slash"></i>
            No review submitted for this ticket yet.
        </div>
    @endif

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
</script>
@endsection