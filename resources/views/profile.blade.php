@extends('layout')

@section('content')

    <div class="page-header">
        <h2 class="page-title">My Profile</h2>
    </div>

    <div class="profile-main-card">

    {{-- RIGHT PROFILE IMAGE --}}
        <div class="profile-right-section">

            <div class="profile-avatar-circle">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>

            <h3 class="profile-name">
                {{ auth()->user()->name }}
            </h3>

            <p class="profile-role">
                {{ auth()->user()->isAdmin() ? 'Admin' : (auth()->user()->isTicketSupportTeam() ? 'Support' : 'User') }}
            </p>

        </div>

        {{-- LEFT SECTION --}}
        <div class="profile-left-section">

            <div class="section-heading">
                <span class="bar"></span> Profile Information
            </div>

            <div class="detail-row">
                <div class="detail-label">Full Name:</div>
                <div class="detail-value">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Email Address:</div>
                <div class="detail-value">
                    {{ auth()->user()->email }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Role:</div>
                <div class="detail-value">
                    {{ auth()->user()->isAdmin() ? 'Admin' : (auth()->user()->isTicketSupportTeam() ? 'Support' : 'User') }}
                </div>
            </div>
            <br>
            <br>

        {{-- PASSWORD SECTION --}}
            <div class="section-heading">
                <span class="bar"></span> Change Password
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <input type="password" 
                       name="current_password" 
                       placeholder="Current Password"
                       class="form-control"
                       required>

                @error('current_password')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                <input type="password" 
                       name="password" 
                       placeholder="New Password"
                       class="form-control"
                       required>

                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                <input type="password"
                       name="password_confirmation"
                       placeholder="Confirm Password"
                       class="form-control"
                       required>

                <button class="btn-brand">
                    Update Password
                </button>

            </form>
        </div>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush