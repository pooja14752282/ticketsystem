@extends('layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/ticket-categories.css') }}">
@endsection

@section('content')



<div style="display:flex;align-items:flex-start;justify-content:space-between;background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 20px;margin-bottom:16px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:#111827;">🗂️ Ticket Categories</h1>
        <p style="font-size:13px;color:#000000;margin-top:4px;">Manage ticket categories</p>
    </div>
    <a href="{{ route('admin.ticket-categories.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;background:#1d4ed8;color:#fff;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-card">
        <table id="ticketsTable">
        <thead style="background:#1d4ed8;">
            <tr>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;"></th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Sl.no</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Name</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Assignee</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $i => $category)
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:11px 14px;">
    <div class="action-dropdown">

        <button type="button"
                class="action-menu-btn"
                onclick="toggleTicketDropdown(this)">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <div class="dropdown-menu">

            <a href="{{ route('admin.ticket-categories.edit', $category) }}"
               class="dropdown-item"
               style="text-decoration:none;color:inherit;">
                <i class="fas fa-edit"></i> Edit
            </a>

            <form action="{{ route('admin.ticket-categories.destroy', $category) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this category?')"
                  style="margin:0;">
                @csrf
                @method('DELETE')

                <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>

        </div>

    </div>
</td>
                <td style="padding:11px 14px;font-size:13px;color:#000000;">{{ $i + 1 }}</td>
                <td style="padding:11px 14px;font-size:13px;color:#000000;"><strong>{{ $category->name }}</strong></td>
                <td style="padding:11px 14px;font-size:13px;color:#000000;">
                    {{ $category->assignee->name ?? 'Unassigned' }}
                </td>

                {{-- Status --}}
                <td style="padding:11px 14px;">
                    @if($category->is_active)
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
            @empty
            <tr>
                <td colspan="5" style="padding:60px 20px;text-align:center;color:#000000;font-size:13px;">
                    No categories yet. Add your first one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

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

<script>
function toggleTicketDropdown(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('show');

    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));

    if (!isOpen) {
        const rect = btn.getBoundingClientRect();
        menu.style.top = (rect.bottom + 4) + 'px';

        let left = rect.right - 170; // 170 = min-width of menu
        if (left < 8) left = rect.left;
        menu.style.left = left + 'px';

        menu.classList.add('show');
    }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

window.addEventListener('scroll', function() {
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        menu.classList.remove('show');
    });
}, true);
</script>
@endpush