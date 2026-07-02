@extends('layout')

@section('title', 'Ticket Options')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/ticket-options.css') }}">
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
                    <td colspan="4" style="text-align:center;padding:20px;color:#000000;font-size:13px;">
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
                    <td colspan="4" style="text-align:center;padding:20px;color:#000000;font-size:13px;">
                        No priorities yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>


{{-- ── ROLES SECTION ── --}}
<div class="options-grid" style="margin-top:24px;">
    <div class="option-section">

        <h2><i class="fas fa-user-tag" style="color:#8b5cf6;margin-right:6px;"></i> Manage Roles</h2>
        <p>Create and manage support team roles</p>

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="option-form" style="grid-template-columns: 1fr auto;">
                <div class="form-group">
                    <label>Role Name</label>
                    <input type="text" name="role_name" placeholder="e.g. Admin" required>
                </div>
                <button type="submit" class="btn-add">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </form>

        <table class="options-table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $index => $role)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $role->label }}</td>
                    <td>
                        <div class="action-btns">
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                  onsubmit="return confirm('Delete this role?')" style="margin:0">
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
                    <td colspan="3" style="text-align:center;padding:20px;color:#000000;font-size:13px;">
                        No roles yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection