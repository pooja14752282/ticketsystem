@extends('layout')

@section('content')
<div style="max-width:600px; margin:auto;">
    <h2 style="margin-bottom:20px; color:#111827;">🎫 Create New Ticket</h2>

    <form method="POST" action="{{ route('ticketsystem.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Description --}}
        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Description *</label>
            <textarea name="description" rows="4" required
                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;">{{ old('description') }}</textarea>
            @error('description') <span style="color:red;font-size:12px;">{{ $message }}</span> @enderror
        </div>

        {{-- Category --}}
        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Category *</label>
            <input type="text" name="category" value="{{ old('category') }}" required
                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;">
            @error('category') <span style="color:red;font-size:12px;">{{ $message }}</span> @enderror
        </div>

        {{-- Priority --}}
        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Priority *</label>
            <select name="priority" id="priority-select" required
                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;">
                <option value="">-- Select Priority --</option>
                @foreach($priorities as $p)
                    <option value="{{ $p->value }}"
                        data-days="{{ ['low'=>5,'high'=>3,'urgent'=>2][$p->value] ?? 3 }}"
                        data-color="{{ $p->color }}"
                        data-text="{{ $p->text_color }}"
                        {{ old('priority') == $p->value ? 'selected' : '' }}>
                        {{ $p->label }}
                    </option>
                @endforeach
            </select>
            @error('priority') <span style="color:red;font-size:12px;">{{ $message }}</span> @enderror
        </div>

        {{-- Due Date --}}
        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                Due Date
                <span style="font-weight:400;color:#9ca3af;font-size:12px;">— auto-set by priority</span>
            </label>
            <div style="display:flex;align-items:center;gap:10px;">
                <input type="date" name="due_date" id="due-date-input"
                    value="{{ old('due_date') }}"
                    style="flex:1;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;color:#374151;">
                <span id="due-date-badge" style="display:none;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap;"></span>
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:4px;">
                Low = +5 days &nbsp;|&nbsp; High = +3 days &nbsp;|&nbsp; Urgent = +2 days
            </p>
        </div>

        {{-- Assign To --}}
        <div style="margin-bottom:24px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Assign To</label>
            <select name="assigned_to" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;">
                <option value="">-- Select --</option>
                @if($admins->count())
                <optgroup label="👑 Admin">
                    @foreach($admins as $admin)
                        <option value="user_{{ $admin->id }}" {{ old('assigned_to')=='user_'.$admin->id ? 'selected' : '' }}>
                            {{ $admin->name }} (Admin)
                        </option>
                    @endforeach
                </optgroup>
                @endif
                @if($supportMembers->count())
                <optgroup label="👥 Support Team">
                    @foreach($supportMembers as $member)
                        <option value="team_{{ $member->id }}" {{ old('assigned_to')=='team_'.$member->id ? 'selected' : '' }}>
                            {{ $member->name }} — {{ \App\Models\SupportTeam::APPS[$member->app_assigned] }}
                        </option>
                    @endforeach
                </optgroup>
                @endif
            </select>
        </div>

        {{-- Attachment --}}
        <div style="margin-bottom:24px;">
            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                Attach File <span style="font-weight:400;color:#9ca3af;">(optional)</span>
            </label>
            <input type="file" name="attachment" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx"
                style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;color:#374151;">
            <p style="font-size:11px;color:#9ca3af;margin-top:4px;">PNG, JPG, PDF, DOC up to 10MB</p>
            @error('attachment') <span style="color:red;font-size:12px;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" style="background:#1d4ed8; color:#fff; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
            Submit Ticket
        </button>
        <a href="{{ route('ticketsystem.my') }}" style="margin-left:12px; font-size:13px; color:#6b7280;">Back</a>
    </form>
</div>

<script>
document.getElementById('priority-select').addEventListener('change', function () {
    const option    = this.options[this.selectedIndex];
    const days      = parseInt(option.dataset.days || 0);
    const color     = option.dataset.color || '#f3f4f6';
    const textColor = option.dataset.text  || '#374151';
    const dateInput = document.getElementById('due-date-input');
    const badge     = document.getElementById('due-date-badge');

    if (!this.value) {
        dateInput.value = '';
        badge.style.display = 'none';
        return;
    }

    const today = new Date();
    today.setDate(today.getDate() + days);
    const yyyy = today.getFullYear();
    const mm   = String(today.getMonth() + 1).padStart(2, '0');
    const dd   = String(today.getDate()).padStart(2, '0');
    dateInput.value = `${yyyy}-${mm}-${dd}`;

    badge.textContent = `+${days} days`;
    badge.style.cssText = `display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:${color};color:${textColor};`;
});

window.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('priority-select');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});
</script>
@endsection