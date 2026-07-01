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
<style>

.profile-page-header {
    margin-bottom:20px;
}

.page-title {
    font-size:22px;
    font-weight:700;
    margin:0;
}

.page-subtitle {
    color:#666;
}

/* MAIN 3 COLUMN CARD */
.profile-main-card {
    background:#fff;
    border-radius:15px;
    box-shadow:0 5px 18px rgba(0,0,0,.08);
    display:grid;
    grid-template-columns:1fr 1fr 260px;
    gap:25px;
    padding:30px;
}

/* LEFT PROFILE INFO */
.profile-left-section,
.profile-middle-section {
    padding-right:20px;
    border-right:1px solid #eee;
}

.section-heading {
    font-size:16px;
    font-weight:700;
    color:#4a49e0;
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:20px;
}

.section-heading .bar {
    width:4px;
    height:18px;
    background:#6366f1;
    border-radius:3px;
}

/* DETAILS */
.detail-row {
    display:flex;
    justify-content:space-between;
    padding:14px 0;
    border-bottom:1px solid #eee;
}

.detail-label {
    color:black;
}

.detail-value {
    font-weight:600;
}

/* PASSWORD */
.form-control {
    width:100%;
    padding:11px;
    margin-bottom:12px;
    border:1px solid #ddd;
    border-radius:8px;
}

.btn-brand {
    width:100%;
    background:#6e70f5;
    color:white;
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

/* RIGHT PROFILE IMAGE */
.profile-right-section {
    text-align:center;
}

.profile-avatar-circle {
    width:100px;
    height:100px;
    border-radius:50%;
    background:linear-gradient(135deg,#6366f1,#818cf8);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px;
    font-weight:700;
    margin:20px auto;
}

.profile-name {
    font-size:18px;
    margin:0;
}

.profile-role {
    color:#999;
}

.status-badge {
    background:#e6f9ee;
    color:#17a463;
    padding:5px 15px;
    border-radius:20px;
    font-size:12px;
}

.error-text {
    color:red;
    font-size:12px;
}

/* MOBILE */
@media(max-width:900px){

.profile-main-card{
grid-template-columns:1fr;
}

.profile-left-section,
.profile-middle-section{
border:none;
}
}

.page-header{
    background:#FFFF;
}
</style>
@endpush