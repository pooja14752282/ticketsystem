@extends('layout')

@section('styles')
<style>
    .page-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        padding: 16px 20px; margin-bottom: 16px;
    }
    .page-header h1 { font-size: 18px; font-weight: 600; color: #111827; }
    .page-header p  { font-size: 13px; color: #6b7280; margin-top: 4px; }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .section-card {
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; overflow: hidden;
    }
    .section-header {
        padding: 14px 18px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; gap: 8px;
    }
    .section-header h2 { font-size: 15px; font-weight: 600; color: #111827; }
    .section-header span {
        font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 600;
    }

    /* Add form */
    .add-form { padding: 16px 18px; border-bottom: 1px solid #f3f4f6; background: #f9fafb; }
    .add-form-row { display: grid; grid-template-columns: 1fr auto auto auto; gap: 8px; align-items: end; }
    .add-form label { font-size: 11px; color: #6b7280; display: block; margin-bottom: 4px; }
    .add-form input[type="text"] {
        width: 100%; padding: 7px 10px; font-size: 13px;
        border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #111827;
    }
    .add-form input[type="text"]:focus { outline: none; border-color: #3b82f6; }
    .color-group { display: flex; flex-direction: column; }
    .color-group input[type="color"] {
        width: 44px; height: 34px; border: 1px solid #d1d5db;
        border-radius: 6px; cursor: pointer; padding: 2px;
    }
    .btn-add {
        background: #1d4ed8; color: #fff; border: none;
        padding: 7px 14px; border-radius: 6px; font-size: 12px;
        font-weight: 500; cursor: pointer; white-space: nowrap;
        display: inline-flex; align-items: center; gap: 5px; height: 34px;
    }
    .btn-add:hover { background: #1e40af; }

    /* Options list */
    .options-list { padding: 0; }
    .option-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 18px; border-bottom: 1px solid #f3f4f6;
    }
    .option-row:last-child { border-bottom: none; }
    .option-left { display: flex; align-items: center; gap: 10px; }
    .badge-preview {
        display: inline-block; padding: 3px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .option-value { font-size: 11px; color: #9ca3af; font-family: monospace; }
    .option-actions { display: flex; gap: 6px; align-items: center; }

    .btn-toggle-on {
        background: #dcfce7; color: #166534; border: none;
        padding: 4px 10px; border-radius: 5px; font-size: 11px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-toggle-on:hover { background: #bbf7d0; }
    .btn-toggle-off {
        background: #fef3c7; color: #92400e; border: none;
        padding: 4px 10px; border-radius: 5px; font-size: 11px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-toggle-off:hover { background: #fde68a; }
    .btn-del {
        background: #fee2e2; color: #991b1b; border: none;
        padding: 4px 10px; border-radius: 5px; font-size: 11px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-del:hover { background: #fecaca; }

    .empty-opts { padding: 30px; text-align: center; color: #9ca3af; font-size: 13px; }

    .alert-success {
        background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
        padding: 10px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .alert-error {
        background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
        padding: 10px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    /* Ticket Tabs */
    .ticket-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 20px;
    }
    .ticket-tab {
        padding: 8px 16px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        text-decoration: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }
    .ticket-tab:hover {
        background: #f3f4f6;
        color: #111827;
    }
    .ticket-tab.active {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
    }
</style>
@endsection

@section('content')

<div class="ticket-tabs">
    <a href="{{ route('admin.ticket-options.index') }}"
       class="ticket-tab {{ request()->routeIs('admin.ticket-options.*') ? 'active' : '' }}">
        <i class="fas fa-sliders-h"></i> Ticket Options
    </a>
    <a href="{{ route('admin.tickets.duedates') }}"
       class="ticket-tab {{ request()->routeIs('admin.tickets.duedates') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i> Edit Due Dates
    </a>
</div>

<div class="page-header">
    <div>
        <h1>⚙️ Ticket Options <span style="font-size:13px;font-weight:400;color:#9ca3af;">— Status & Priority</span></h1>
        <p>Add and manage status and priority options used across all tickets</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="two-col">

    {{-- ── STATUS ── --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-tag" style="color:#1d4ed8;"></i>
            <h2>Manage Status</h2>
            <span style="background:#dbeafe;color:#1e40af;">{{ $statuses->count() }} options</span>
        </div>

        {{-- Add Status Form --}}
        <form method="POST" action="{{ route('admin.ticket-options.store') }}" class="add-form">
            @csrf
            <input type="hidden" name="type" value="status">
            <div class="add-form-row">
                <div>
                    <label>Status Label</label>
                    <input type="text" name="label" placeholder="e.g. In Review" required>
                </div>
                <div class="color-group">
                    <label>Background</label>
                    <input type="color" name="color" value="#dbeafe" title="Badge background color">
                </div>
                <div class="color-group">
                    <label>Text</label>
                    <input type="color" name="text_color" value="#1e40af" title="Badge text color">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
        </form>

        {{-- Status List --}}
        <div class="options-list">
            @forelse($statuses as $option)
            <div class="option-row">
                <div class="option-left">
                    <span class="badge-preview"
                          style="background:{{ $option->color }};color:{{ $option->text_color }};">
                        {{ $option->label }}
                    </span>
                    <span class="option-value">{{ $option->value }}</span>
                    @if(!$option->is_active)
                        <span style="font-size:10px;color:#9ca3af;">(inactive)</span>
                    @endif
                </div>
                <div class="option-actions">
                    <form method="POST" action="{{ route('admin.ticket-options.toggle', $option) }}" style="margin:0">
                        @csrf @method('PATCH')
                        @if($option->is_active)
                            <button type="submit" class="btn-toggle-on" title="Deactivate">
                                <i class="fas fa-eye"></i> Active
                            </button>
                        @else
                            <button type="submit" class="btn-toggle-off" title="Activate">
                                <i class="fas fa-eye-slash"></i> Inactive
                            </button>
                        @endif
                    </form>
                    <form method="POST" action="{{ route('admin.ticket-options.destroy', $option) }}"
                          onsubmit="return confirm('Delete this status option?')" style="margin:0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
                <div class="empty-opts">No status options yet. Add one above.</div>
            @endforelse
        </div>
    </div>

    {{-- ── PRIORITY ── --}}
    <div class="section-card">
        <div class="section-header">
            <i class="fas fa-flag" style="color:#d97706;"></i>
            <h2>Manage Priority</h2>
            <span style="background:#fef3c7;color:#92400e;">{{ $priorities->count() }} options</span>
        </div>

        {{-- Add Priority Form --}}
        <form method="POST" action="{{ route('admin.ticket-options.store') }}" class="add-form">
            @csrf
            <input type="hidden" name="type" value="priority">
            <div class="add-form-row">
                <div>
                    <label>Priority Label</label>
                    <input type="text" name="label" placeholder="e.g. Critical" required>
                </div>
                <div class="color-group">
                    <label>Background</label>
                    <input type="color" name="color" value="#fee2e2" title="Badge background color">
                </div>
                <div class="color-group">
                    <label>Text</label>
                    <input type="color" name="text_color" value="#991b1b" title="Badge text color">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
        </form>

        {{-- Priority List --}}
        <div class="options-list">
            @forelse($priorities as $option)
            <div class="option-row">
                <div class="option-left">
                    <span class="badge-preview"
                          style="background:{{ $option->color }};color:{{ $option->text_color }};">
                        {{ $option->label }}
                    </span>
                    <span class="option-value">{{ $option->value }}</span>
                    @if(!$option->is_active)
                        <span style="font-size:10px;color:#9ca3af;">(inactive)</span>
                    @endif
                </div>
                <div class="option-actions">
                    <form method="POST" action="{{ route('admin.ticket-options.toggle', $option) }}" style="margin:0">
                        @csrf @method('PATCH')
                        @if($option->is_active)
                            <button type="submit" class="btn-toggle-on" title="Deactivate">
                                <i class="fas fa-eye"></i> Active
                            </button>
                        @else
                            <button type="submit" class="btn-toggle-off" title="Activate">
                                <i class="fas fa-eye-slash"></i> Inactive
                            </button>
                        @endif
                    </form>
                    <form method="POST" action="{{ route('admin.ticket-options.destroy', $option) }}"
                          onsubmit="return confirm('Delete this priority option?')" style="margin:0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
                <div class="empty-opts">No priority options yet. Add one above.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection