@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/show-ticket.css') }}">
@endsection

@section('content')

@php
    $authUser   = auth()->user();
    $isAdmin    = $authUser->isAdmin();
    $teamMember = \App\Models\TicketSupportTeam::where('email', $authUser->email)->first();
    $canEdit    = $isAdmin
        || ($teamMember && $ticket->assigned_team_member_id === $teamMember->id)
        || $ticket->assigned_to === $authUser->id;
@endphp

{{-- Breadcrumb --}}
<div style="font-size:12px;color:#000000;margin-bottom:12px;">
    <a href="{{ route('support.tickets') }}" style="color:#000000;text-decoration:none;">My Assigned Tickets</a>
    <i class="fas fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
    <span style="color:#000000;font-weight:500;">Ticket #{{ $ticket->id }}</span>
</div>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>🎫 {{ $ticket->ticket_id }}</h1>
        <p>Full details for this support ticket</p>
    </div>
    <a href="{{ route('support.tickets') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- Card --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-ticket-alt" style="color:#fff;font-size:15px;"></i>
        <span>Ticket Details</span>
    </div>

    <div class="meta-grid">
        <div class="meta-cell">
            <div class="meta-label">Ticket ID</div>
            <div class="meta-val">{{ $ticket->ticket_id }}</div>
        </div>

        {{-- Status --}}
        <div class="meta-cell">
            <div class="meta-label">Status</div>
            <div class="meta-val" style="display:flex;align-items:center;gap:8px;">
                @if($canEdit)
                    @php $statusKey = str_replace(' ','_',strtolower($ticket->status)); @endphp
                    <select
                        class="status-select status-{{ $statusKey }}"
                        id="status-select-{{ $ticket->id }}"
                        onchange="updateStatus({{ $ticket->id }}, this)">
                        @foreach($statuses as $s)