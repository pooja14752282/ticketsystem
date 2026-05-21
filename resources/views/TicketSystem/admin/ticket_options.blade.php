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

    .card {
        background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .card-header {
        background: #1d4ed8; padding: 12px 18px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h2 { font-size: 14px; font-weight: 600; color: #fff; }
    .card-body { padding: 18px; }

    /* Add form */
    .add-form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
    .add-form input[type="text"] {
        padding: 8px 10px; font-size: 13px;
        border: 1px solid #d1d5db; border-radius: 6px;
        background: #fff; color: #111827; width: 100%;
    }
    .add-form input[type="text"]:focus { outline: none; border-color: #3b82f6; }
    .color-row { display: flex; gap: 10px; align-items: center; }
    .color-row label { font-size: 12px; color: #6b7280; white-space: nowrap; }
    .color-row input[type="color"] {
        width: 40px; height: 32px; border: 1px solid #d1d5db;
        border-radius: 6px; cursor: pointer; padding: 2px;
    }
    .color-preview {
        flex: 1; padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600; text-align: center;
        transition: all 0.2s;
    }
    .btn-add {
        background: #1d4ed8; color: #fff; border: none;
        padding: 9px 16px; border-radius: 6px; font-size: 13px;
        font-weight: 500; cursor: pointer; display: inline-flex;
        align-items: center; gap: 6px; width: 100%; justify-content: center;
    }
    .btn-add:hover { background: #1e40af; }

    /* Options list */
    .options-list { display: flex; flex-direction: column; gap: 8px; }
    .option-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 12px; border-radius: 8px; border: 1px solid #f3f4f6;
        background: #f9fafb;
    }
    .option-row.inactive { opacity: 0.45; }
    .badge-preview {
        display: inline-block; padding: 3px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600;
    }
    .option-actions { display: flex; gap: 6px; }
    .btn-toggle {
        background: #fef3c7; color: #92400e; border: none;
        padding: 3px 10px; border-radius: 5px; font-size: 11px;
        cursor: pointer; white-space: nowrap;
    }
    .btn-toggle:hover { background: #fde68a; }
    .btn-toggle.active-btn { background: #dcfce7; color: #166534; }
    .btn-toggle.active-btn:hover { background: #bbf7d0; }
    .btn-del {
        background: #fee2e2; color: #991b1b; border: none;
        padding: 3px 10px; border-radius: 5px; font-size: 11px;
        cursor: pointer; white-space: nowrap;
    }
    .btn-del:hover { background: #fecaca; }

    .empty-msg { font-size: 13px; color: #9ca3af; text-align: center; padding: 20px 0; }

    .alert-success {
        background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
        padding: 10px 16px; border-radius: 8px; font-size: 13px;
        margin-bottom: 16px;
    }
    .alert-error {
        background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
        padding: 10px 16px; border-radius: 8px; font-size: 13px;
        margin-bottom: 16px;
    }
    .divider { border: none; border-top: 1px solid #e5e7eb; margin: 16px 0; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1>⚙️ Ticket Options <span style="font-size:13px;font-weight:400;color:#9ca3af;">— Status & Priority</span></h1>
        <p>Add new statuses and priorities. They appear everywhere instantly.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">✗ {{ session('error') }}</div>
@endif

<div class="two-col">

    {{-- ── STATUS COLUMN ── --}}
    <div class="card">
        <div class="card-header">
            <h2>🔵 Statuses</h2>
            <span style="font-size:12px;color:#bfdbfe;">{{ $statuses->count() }} total</span>
        </div>
        <div class="card-body">

            {{-- Add Status Form --}}
            <form method="POST" action="{{ route('admin.ticket-options.store') }}">
                @csrf
                <input type="hidden" name="type" value="status">
                <div class="add-form">
                    <input type="text" name="label" placeholder="Status name e.g. In Review" required>
                    <div class="color-row">
                        <label>Background</label>
                        <input type="color" name="color" id="status-bg" value="#dbeafe" oninput="updateStatusPreview()">
                        <label>Text</label>
                        <input type="color" name="text_color" id="status-text" value="#1e40af" oninput="updateStatusPreview()">
                        <span class="color-preview" id="status-preview" style="background:#dbeafe;color:#1e40af;">Preview</span>
                    </div>
                    <button type="submit" class="btn-add">
                        <i class="fas fa-plus"></i> Add Status
                    </button>
                </div>
            </form>

            <hr class="divider">

            {{-- Existing Statuses --}}
            <div class="options-list">
                @forelse($statuses as $s)
                <div class="option-row {{ $s->is_active ? '' : 'inactive' }}">
                    <span class="badge-preview" style="background:{{ $s->color }};color:{{ $s->text_color }};">
                        {{ $s->label }}
                    </span>
                    <div class="option-actions">
                        <form method="POST" action="{{ route('admin.ticket-options.toggle', $s) }}" style="margin:0">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle {{ $s->is_active ? '' : 'active-btn' }}">
                                {{ $s->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.ticket-options.destroy', $s) }}"
                              onsubmit="return confirm('Delete this status?')" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="empty-msg">No statuses yet. Add one above.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── PRIORITY COLUMN ── --}}
    <div class="card">
        <div class="card-header">
            <h2>🔴 Priorities</h2>
            <span style="font-size:12px;color:#bfdbfe;">{{ $priorities->count() }} total</span>
        </div>
        <div class="card-body">

            {{-- Add Priority Form --}}
            <form method="POST" action="{{ route('admin.ticket-options.store') }}">
                @csrf
                <input type="hidden" name="type" value="priority">
                <div class="add-form">
                    <input type="text" name="label" placeholder="Priority name e.g. Critical" required>
                    <div class="color-row">
                        <label>Background</label>
                        <input type="color" name="color" id="priority-bg" value="#fee2e2" oninput="updatePriorityPreview()">
                        <label>Text</label>
                        <input type="color" name="text_color" id="priority-text" value="#991b1b" oninput="updatePriorityPreview()">
                        <span class="color-preview" id="priority-preview" style="background:#fee2e2;color:#991b1b;">Preview</span>
                    </div>
                    <button type="submit" class="btn-add">
                        <i class="fas fa-plus"></i> Add Priority
                    </button>
                </div>
            </form>

            <hr class="divider">

            {{-- Existing Priorities --}}
            <div class="options-list">
                @forelse($priorities as $p)
                <div class="option-row {{ $p->is_active ? '' : 'inactive' }}">
                    <span class="badge-preview" style="background:{{ $p->color }};color:{{ $p->text_color }};">
                        {{ $p->label }}
                    </span>
                    <div class="option-actions">
                        <form method="POST" action="{{ route('admin.ticket-options.toggle', $p) }}" style="margin:0">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle {{ $p->is_active ? '' : 'active-btn' }}">
                                {{ $p->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.ticket-options.destroy', $p) }}"
                              onsubmit="return confirm('Delete this priority?')" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="empty-msg">No priorities yet. Add one above.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
function updateStatusPreview() {
    const bg   = document.getElementById('status-bg').value;
    const text = document.getElementById('status-text').value;
    const prev = document.getElementById('status-preview');
    prev.style.background = bg;
    prev.style.color = text;
}

function updatePriorityPreview() {
    const bg   = document.getElementById('priority-bg').value;
    const text = document.getElementById('priority-text').value;
    const prev = document.getElementById('priority-preview');
    prev.style.background = bg;
    prev.style.color = text;
}
</script>
@endsection