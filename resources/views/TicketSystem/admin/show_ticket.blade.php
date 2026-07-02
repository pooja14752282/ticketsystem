@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/show-ticket.css') }}">
@endsection

@section('content')

@php
    $authUser   = auth()->user();
    $isAdmin = $authUser->isAdmin();
    $teamMember = \App\Models\TicketSupportTeam::where('email', $authUser->email)->first();
    $canEdit    = $isAdmin
        || ($teamMember && $ticket->assigned_team_member_id === $teamMember->id)
        || $ticket->assigned_to === $authUser->id;
@endphp

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('admin.tickets.index') }}" style="color:#000000;text-decoration:none;">All Tickets</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#000000;font-weight:500;">Ticket #{{ $ticket->id }}</span>
</div>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>🎫 {{ $ticket->ticket_id }}</h1>
        <p>Full details for this support ticket</p>
    </div>
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
            <span id="status-flash-{{ $ticket->id }}" class="status-saved-flash">
                <i class="fas fa-check-circle"></i> Saved
            </span>

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
                    <div style="width:32px;height:32px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#000000;">
                        {{ strtoupper(substr($ticket->review->supportMember->name ?? 'S', 0, 2)) }}
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:600;margin:0;color:#111827;">{{ $ticket->review->supportMember->name ?? '—' }}</p>
                        <p style="font-size:11px;color:#000000;margin:0;">{{ $ticket->review->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <span style="margin-left:auto;font-size:11px;padding:3px 10px;border-radius:999px;border:1px solid #d1d5db;color:#000000;background:#fff;">
                        {{ $ticket->review->resolution_status }}
                    </span>
                </div>
                <p style="font-size:13px;color:#000000;margin:0;line-height:1.6;">{{ $ticket->review->notes }}</p>
            </div>
        </div>
    @else
        <div class="attach-empty">
            
            No review submitted for this ticket yet.
        </div>
    @endif

</div>

@endsection

@push('scripts')
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
            if (flash) {
                flash.style.display = 'inline';
                setTimeout(() => flash.style.display = 'none', 2000);
            }
        } else {
            alert('Failed to update status.');
        }
    })
    .catch(() => alert('Failed to update status. Please try again.'));
}
</script>
@endpush