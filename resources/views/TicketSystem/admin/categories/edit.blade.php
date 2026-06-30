{{-- resources/views/ticketsystem/admin/categories/edit.blade.php --}}
@extends('layout')

@section('styles')
<style>
    .form-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 28px 32px; max-width: 600px; }
    .form-card h2 { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { font-size: 12px; font-weight: 600; color: 000000; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px; }
    .form-group input,
    .form-group select { width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #111827; outline: none; font-family: 'Segoe UI', sans-serif; }
    .form-group input:focus,
    .form-group select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-group .error { font-size: 12px; color: #dc2626; margin-top: 4px; }
    .form-footer { display: flex; gap: 10px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
    .btn-submit { background: #1d4ed8; color: #fff; border: none; padding: 9px 24px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .btn-submit:hover { background: #1e40af; }
    .btn-back { background: #fff; color: #000000; border: 1px solid #d1d5db; padding: 9px 20px; border-radius: 6px; font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
    .btn-back:hover { background: #f3f4f6; color: 000000; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#000000;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <a href="{{ route('admin.ticket-categories.index') }}" style="color:#000000;text-decoration:none;">Categories</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:000000;font-weight:500;">Edit — {{ $ticketCategory->name }}</span>
</div>

<div class="form-card">
    <h2><i class="fas fa-pencil-alt" style="color:#1d4ed8;"></i> Edit Category</h2>

    <form method="POST" action="{{ route('admin.ticket-categories.update', $ticketCategory->id) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $ticketCategory->name) }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Assign To</label>
            <select name="assign_to">
                <option value="">— Select User —</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('assign_to', $ticketCategory->assign_to) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('assign_to') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $ticketCategory->email) }}" placeholder="support@example.com">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                <option value="active"   {{ old('status', $ticketCategory->status) == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $ticketCategory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Update Category</button>
            <a href="{{ route('admin.ticket-categories.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </form>
</div>

@endsection
