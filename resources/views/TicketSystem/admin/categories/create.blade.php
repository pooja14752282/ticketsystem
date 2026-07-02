{{-- resources/views/ticketsystem/admin/categories/create.blade.php --}}
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
    <span style="color:#000000;font-weight:500;">Add Category</span>
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