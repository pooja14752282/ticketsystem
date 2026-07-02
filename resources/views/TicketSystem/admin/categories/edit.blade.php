{{-- resources/views/ticketsystem/admin/categories/edit.blade.php --}}
@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/ticket-category-form.css') }}">
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#000000;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <a href="{{ route('admin.ticket-categories.index') }}" style="color:#000000;text-decoration:none;">Categories</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#000000;font-weight:500;">Edit — {{ $ticketCategory->name }}</span>
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