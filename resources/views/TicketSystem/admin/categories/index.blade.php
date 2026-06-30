@extends('layout')

@section('content')

<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('dashboard') }}" style="color:#000000;text-decoration:none;">Home</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#374151;font-weight:500;">Ticket Categories</span>
</div>

<div style="display:flex;align-items:flex-start;justify-content:space-between;background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 20px;margin-bottom:16px;">
    <div>
        <h1 style="font-size:18px;font-weight:600;color:#111827;">🗂️ Ticket Categories</h1>
        <p style="font-size:13px;color:#000000;margin-top:4px;">Manage ticket categories</p>
    </div>
    <a href="{{ route('admin.ticket-categories.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;background:#1d4ed8;color:#fff;border:none;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif

<div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#1d4ed8;">
            <tr>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Sno</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Name</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Assignee</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Status</th>
                <th style="padding:11px 14px;font-size:13px;font-weight:500;color:#fff;text-align:left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $i => $category)
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:11px 14px;font-size:13px;color:#374151;">{{ $i + 1 }}</td>
                <td style="padding:11px 14px;font-size:13px;color:#374151;"><strong>{{ $category->name }}</strong></td>
                <td style="padding:11px 14px;font-size:13px;color:#374151;">
                    {{ $category->assignee->name ?? 'Unassigned' }}
                </td>

                {{-- Status --}}
                <td style="padding:11px 14px;">
                    @if($category->is_active)
                        <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:7px;margin-right:4px;"></i> Active
                        </span>
                    @else
                        <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            <i class="fas fa-circle" style="font-size:7px;margin-right:4px;"></i> Inactive
                        </span>
                    @endif
                </td>

                {{-- Actions --}}
                <td style="padding:11px 14px;">
                    <div style="display:flex;align-items:center;gap:6px;">

                        {{-- Edit --}}
                        <a href="{{ route('admin.ticket-categories.edit', $category) }}"
                           style="background:#dbeafe;color:#1d4ed8;padding:4px 10px;border-radius:5px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('admin.ticket-categories.destroy', $category) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this category?')"
                              style="margin:0">
                            @csrf @method('DELETE')
                            <button style="background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:5px;font-size:12px;border:none;cursor:pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:60px 20px;text-align:center;color:#000000;font-size:13px;">
                    No categories yet. Add your first one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection