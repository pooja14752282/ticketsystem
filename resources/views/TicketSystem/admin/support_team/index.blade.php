@extends('layout')

@section('content')

<style>
#ticketsTable_wrapper {
    padding: 0 0 16px;
}

#ticketsTable_length {
    padding-left: 20px;
    padding-top: 14px;
    padding-bottom: 10px;
    font-size: 13px;
    color: #000000;
}

#ticketsTable_length select {
    padding: 4px 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    margin: 0 6px;
}

#ticketsTable_info {
    padding-left: 20px;
    font-size: 12px;
    color: #000000;
}

#ticketsTable_paginate {
    padding-right: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
}

#ticketsTable_paginate .paginate_button {
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
    cursor: pointer;
}

#ticketsTable_paginate .paginate_button.current {
    background: #1d4ed8 !important;
    color: #fff !important;
    border-color: #1d4ed8 !important;
}

#ticketsTable_paginate .paginate_button.disabled {
    color: #9ca3af;
    cursor: not-allowed;
}

.dataTables_wrapper .row:last-child {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
}
</style>

<div style="display:flex;align-items:flex-start;justify-content:space-between;background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 20px;margin-bottom:16px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:#111827;">Support Team</h1>
        <p style="font-size:13px;color:#000000;margin-top:4px;">Manage support team members and their app assignments</p>
    </div>
    <a href="{{ route('admin.support-team.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;background:#1d4ed8;color:#fff;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;">
        <i class="fas fa-plus"></i> Add Member
    </a>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif

<div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;">
    <table id="ticketsTable" style="width:100%;border-collapse:collapse;">
        <thead style="background:#1d4ed8;">
            <tr>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;"></th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Sno</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Name</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Email</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">App Assigned</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $i => $member)
            <tr style="border-bottom:1px solid #f3f4f6;">
            <td style="padding:11px 14px;position:relative;">
    <div style="position:relative;display:inline-block;">

        <!-- Three Dots Button -->
        <button type="button"
    class="dots-btn"
    onclick="toggleActionDropdown({{ $member->id }}, this)"
    style="background:none;border:none;cursor:pointer;font-size:18px;color:#374151;padding:4px 8px;">
    <i class="fas fa-ellipsis-v"></i>
</button>

        <!-- Dropdown -->
        <div id="dropdown-{{ $member->id }}"
     class="action-dropdown"
     style="display:none;position:fixed;background:#fff;
            min-width:170px;border:1px solid #e5e7eb;border-radius:8px;
            box-shadow:0 6px 18px rgba(0,0,0,.12);z-index:9999;overflow:hidden;">

            {{-- Toggle Status --}}
            <form action="{{ route('admin.support-team.toggle', $member) }}"
                  method="POST">
                @csrf
                @method('PATCH')

                <button type="submit"
                        style="width:100%;text-align:left;background:none;border:none;
                               padding:10px 14px;font-size:13px;cursor:pointer;">
                    @if($member->is_active)
                        <i class="fas fa-toggle-on"></i> Deactivate
                    @else
                        <i class="fas fa-toggle-off"></i> Activate
                    @endif
                </button>
            </form>

            {{-- Edit --}}
            <a href="{{ route('admin.support-team.edit', $member) }}"
               style="display:block;padding:10px 14px;font-size:13px;
                      color:#1d4ed8;text-decoration:none;">
                <i class="fas fa-edit"></i> Edit
            </a>

            {{-- Delete --}}
            <form action="{{ route('admin.support-team.destroy', $member) }}"
                  method="POST"
                  onsubmit="return confirm('Remove this member?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        style="width:100%;text-align:left;background:none;border:none;
                               padding:10px 14px;font-size:13px;color:#dc2626;
                               cursor:pointer;">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>

        </div>

    </div>
</td>
                <td style="padding:11px 14px;font-size:13px;color:000000;">{{ $i + 1 }}</td>
                <td style="padding:11px 14px;font-size:13px;color:000000;"><strong>{{ $member->name }}</strong></td>
                <td style="padding:11px 14px;font-size:13px;color:000000;">{{ $member->email }}</td>
                <td style="padding:11px 14px;font-size:13px;">
                    <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                        {{ \App\Models\TicketSupportTeam::APPS[$member->app_assigned] }}
                    </span>
                </td>

                {{-- Status Column --}}
                <td style="padding:11px 14px;">
                    @if($member->is_active)
                        <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:7px;margin-right:4px;"></i> Active
                        </span>
                    @else
                        <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:7px;margin-right:4px;"></i> Inactive
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



@push('scripts')

{{-- ── DataTables assets (skip these two lines if already loaded globally in layout.blade.php) ── --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#ticketsTable').DataTable({
        order: [],
        searching: false,
        paging: true,
        info: true,
        lengthChange: true,
        columnDefs: [
            { orderable: false, targets: 0 },  // Actions (three-dot) column
            { orderable: false, targets: 1 }   // Sno — not meaningful to sort
        ],
        language: {
            emptyTable: "No team members yet. Add your first member!"
        }
    });
});
</script>

<script>
function toggleActionDropdown(id, btn) {
    let dropdown = document.getElementById('dropdown-' + id);
    const isOpen = dropdown.style.display === 'block';

    document.querySelectorAll('.action-dropdown').forEach(function(menu){
        menu.style.display = 'none';
    });

    if (!isOpen) {
        const rect = btn.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + 4) + 'px';

        let left = rect.right - 170; // 170 = min-width of dropdown
        if (left < 8) left = rect.left;
        dropdown.style.left = left + 'px';

        dropdown.style.display = 'block';
    }
}

document.addEventListener('click', function(event){
    if (!event.target.closest('.action-dropdown') &&
        !event.target.closest('.dots-btn')) {
        document.querySelectorAll('.action-dropdown').forEach(function(menu){
            menu.style.display = 'none';
        });
    }
});

window.addEventListener('scroll', function() {
    document.querySelectorAll('.action-dropdown').forEach(function(menu){
        menu.style.display = 'none';
    });
}, true);
</script>

@endpush

@endsection
