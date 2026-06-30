@extends('layout')

@section('content')

<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#000000;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <a href="{{ route('admin.support-team.index') }}" style="color:#000000;text-decoration:none;">Support Team</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:000000;font-weight:500;">Add Member</span>
</div>

<div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:32px;width:100%;">
    <h1 style="font-size:18px;font-weight:600;color:#111827;margin-bottom:24px;">👤 Add Support Team Member</h1>

    <form action="{{ route('admin.support-team.store') }}" method="POST">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:16px;">

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box;"
                       placeholder="Enter full name" required>
                @error('name')
                    <small style="color:#dc2626;font-size:12px;">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box;"
                       placeholder="Enter email address" required>
                @error('email')
                    <small style="color:#dc2626;font-size:12px;">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Default Password</label>
                <input type="password" name="password"
                       placeholder="Enter default password"
                       style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;color:000000;outline:none;box-sizing:border-box;">
                @error('password')
                    <p style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       placeholder="Confirm password"
                       style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;color:000000;outline:none;box-sizing:border-box;">
            </div>

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Assign to App</label>
                <select name="app_assigned"
                        style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box;"
                        required>
                    <option value="">-- Select App --</option>
                    @foreach($apps as $key => $label)
                        <option value="{{ $key }}" {{ old('app_assigned') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('app_assigned')
                    <small style="color:#dc2626;font-size:12px;">{{ $message }}</small>
                @enderror
            </div>

            <div>
                <label style="font-size:13px;font-weight:500;color:000000;display:block;margin-bottom:6px;">Role</label>
                <select name="role"
                  style="width:100%;padding:9px 12px;font-size:13px;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box;"
                  required>
                  <option value="">-- Select Role --</option>
                 @foreach($roles as $role)
                      <option value="{{ $role->value }}" {{ old('role') == $role->value ? 'selected' : '' }}>
                      {{ $role->label }}
                     </option>
                 @endforeach
                </select>
                @error('role')
                    <small style="color:#dc2626;font-size:12px;">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit"
                    style="background:#1d4ed8;color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">
                Add Member
            </button>
            <a href="{{ route('admin.support-team.index') }}"
               style="background:#f3f4f6;color:000000;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection