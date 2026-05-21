{{-- resources/views/ticketsystem/admin/categories/create.blade.php --}}
@extends('layout')

@section('styles')
<style>
    .form-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 28px 32px; max-width: 600px; }
    .form-card h2 { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px; }
    .form-group input { width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #111827; outline: none; font-family: 'Segoe UI', sans-serif; box-sizing: border-box; }
    .form-group input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-group .error { font-size: 12px; color: #dc2626; margin-top: 4px; }
    .form-footer { display: flex; gap: 10px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
    .btn-submit { background: #1d4ed8; color: #fff; border: none; padding: 9px 24px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .btn-submit:hover { background: #1e40af; }
    .btn-back { background: #fff; color: #6b7280; border: 1px solid #d1d5db; padding: 9px 20px; border-radius: 6px; font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
    .btn-back:hover { background: #f3f4f6; color: #374151; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#9ca3af;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#9ca3af;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <a href="{{ route('admin.ticket-categories.index') }}" style="color:#9ca3af;text-decoration:none;">Categories</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#374151;font-weight:500;">Add Category</span>
</div>

<div class="form-card">
    <h2><i class="fas fa-plus-circle" style="color:#1d4ed8;"></i> Add New Category</h2>

    <form method="POST" action="{{ route('admin.ticket-categories.store') }}">
        @csrf

        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. IT Support" required autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Save Category</button>
            <a href="{{ route('admin.ticket-categories.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </form>
</div>

@endsection