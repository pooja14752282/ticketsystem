@extends('layout')

@section('title', 'Ticket Options')

@section('styles')
<style>
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 16px;
    }

    .option-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 24px;
    }

    .option-section h2 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .option-section p {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .option-form {
        display: grid;
        grid-template-columns: 1fr auto auto auto;
        gap: 10px;
        align-items: end;
        margin-bottom: 20px;
    }

    .option-form .form-group label {
        font-size: 12px;
        color: #6b7280;
        display: block;
        margin-bottom: 6px;
    }

    .option-form .form-group input[type="text"] {
        width: 100%;
        padding: 8px 10px;
        font-size: 13px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        color: #111827;
    }

    .option-form .form-group input[type="text"]:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .color-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .color-group label {
        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;
    }

    .color-group input[type="color"] {
        width: 44px;
        height: 36px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        padding: 2px;
    }

    .btn-add {
        background: #1d4ed8;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
    }

    .btn-add:hover { background: #1e40af; }

    .options-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .options-table thead { background: #1d4ed8; }

    .options-table thead th {
        padding: 10px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        text-align: left;
    }

    .options-table tbody tr { border-bottom: 1px solid #f3f4f6; }
    .options-table tbody tr:last-child { border-bottom: none; }
    .options-table tbody tr:hover { background: #f9fafb; }

    .options-table tbody td {
        padding: 10px 12px;
        font-size: 13px;
        color: #374151;
        vertical-align: middle;
    }

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .action-btns { display: flex; gap: 4px; }

    .btn-edit {
        background: #dbeafe; color: #1d4ed8;
        padding: 4px 10px; border-radius: 5px;
        font-size: 12px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-edit:hover { background: #bfdbfe; }

    .btn-delete {
        background: #fee2e2; color: #991b1b;
        padding: 4px 10px; border-radius: 5px;
        font-size: 12px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-delete:hover { background: #fecaca; }

    .btn-toggle-on {
        background: #dcfce7; color: #166534;
        padding: 4px 10px; border-radius: 5px;
        font-size: 12px; border: none; cursor: pointer;
    }
    .btn-toggle-off {
        background: #f3f4f6; color: #6b7280;
        padding: 4px 10px; border-radius: 5px;
        font-size: 12px; border: none; cursor: pointer;
    }

    @media (max-width: 900px) {
        .options-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1>⚙️ Ticket Options</h1>
        <p>Add and manage status and priority options used across all tickets</p>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="options-grid">

    {{-- ── STATUS SECTION ── --}}
    <div class="option-section">

        <h2><i class="fas fa-circle-dot" style="color:#3b82f6;margin-right:6px;"></i> Manage Status</h2>
        <p>Create and manage ticket statuses</p>

        <form method="POST" action="{{ route('admin.ticket-options.store') }}">
            @csrf
            <input type="hidden" name="type" value="status">
            <div class="option-form">
                <div class="form-group">
                    <label>Status Label</label>
                    <input type="text" name="label" placeholder="e.g. In Review" required>
                </div>
                <div class="color-group">
                    <label>BG Color</label>
                    <input type="color" name="color" value="#dbeafe">
                </div>
                <div class="color-group">
                    <label>Text Color</label>
                    <input type="color" name="text_color" value="#1d4ed8">
                </div>
                <button type="submit" class="btn-add">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </form>

        <table class="options-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Preview</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statuses as $status)
                <tr>
                    <td>{{ $status->label }}</td>
                    <td>
                        <span class="badge" style="background:{{ $status->color }};color:{{ $status->text_color }};">
                            {{ $status->label }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.ticket-options.toggle', $status->id) }}" style="margin:0">
                            @csrf @method('PATCH')
                            <button type="submit" class="{{ $status->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                                {{ $status->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-btns">
                            <form method="POST" action="{{ route('admin.ticket-options.destroy', $status->id) }}"
                                  onsubmit="return confirm('Delete this status?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;">
                        No statuses yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- ── PRIORITY SECTION ── --}}
    <div class="option-section">

        <h2><i class="fas fa-flag" style="color:#ef4444;margin-right:6px;"></i> Manage SLA</h2>
        <p>Create and manage ticket sla</p>

        <form method="POST" action="{{ route('admin.ticket-options.store') }}">
            @csrf
            <input type="hidden" name="type" value="priority">
            <div class="option-form">
                <div class="form-group">
                    <label>SLA Label</label>
                    <input type="text" name="label" placeholder="e.g. Critical" required>
                </div>
                <div class="color-group">
                    <label>BG Color</label>
                    <input type="color" name="color" value="#fee2e2">
                </div>
                <div class="color-group">
                    <label>Text Color</label>
                    <input type="color" name="text_color" value="#b91c1c">
                </div>
                <button type="submit" class="btn-add">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </form>

        <table class="options-table">
            <thead>
                <tr>
                    <th>SLA</th>
                    <th>Preview</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($priorities as $priority)
                <tr>
                    <td>{{ $priority->label }}</td>
                    <td>
                        <span class="badge" style="background:{{ $priority->color }};color:{{ $priority->text_color }};">
                            {{ $priority->label }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.ticket-options.toggle', $priority->id) }}" style="margin:0">
                            @csrf @method('PATCH')
                            <button type="submit" class="{{ $priority->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                                {{ $priority->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-btns">
                            <form method="POST" action="{{ route('admin.ticket-options.destroy', $priority->id) }}"
                                  onsubmit="return confirm('Delete this priority?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;">
                        No priorities yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection