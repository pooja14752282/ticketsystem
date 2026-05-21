@extends('layout')

@section('content')

<div style="font-size:12px;color:#9ca3af;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#9ca3af;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <a href="{{ route('admin.support-team.index') }}" style="color:#9ca3af;text-decoration:none;">Support Team</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#374151;font-weight:500;">Edit Member</span>
</div>

<div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;max-width:560px;">
    <h1 style="font-size:17px;font-weight:600;color:#111827;margin-bottom:20px;">
        <i class="fas fa-user-edit" style="color:#1d4ed8;margin-right:8px;"></i> Edit Member
    </h1>

    <form method="POST" action="{{ route('admin.support-team.update', $supportTeam) }}">
        @csrf @method('PUT')

        <div style="margin-bottom:14px;">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.4px;">Name *</label>
            <input type="text" name="name" value="{{ old('name', $supportTeam->name) }}" required
                   style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;font-family:'Segoe UI',sans-serif;">
            @error('name')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.4px;">Email *</label>
            <input type="email" name="email" value="{{ old('email', $supportTeam->email) }}" required
                   style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;font-family:'Segoe UI',sans-serif;">
            @error('email')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.4px;">App Assigned *</label>
            <select name="app_assigned" required
                    style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;background:#fff;font-family:'Segoe UI',sans-serif;">
                @foreach($apps as $key => $label)
                    <option value="{{ $key }}" {{ old('app_assigned', $supportTeam->app_assigned) == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('app_assigned')<p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit"
                    style="background:#1d4ed8;color:#fff;border:none;padding:9px 22px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;">
                <i class="fas fa-save"></i> Update Member
            </button>
            <a href="{{ route('admin.support-team.index') }}"
               style="background:#fff;color:#6b7280;border:1px solid #d1d5db;padding:9px 16px;border-radius:6px;font-size:13px;text-decoration:none;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection