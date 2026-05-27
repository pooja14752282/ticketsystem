@extends('layout')

@section('content')

<div class="profile-page">

    <div class="profile-card">

        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>

            <h2>{{ auth()->user()->name }}</h2>
            <p>{{ auth()->user()->email }}</p>
        </div>

        <div class="profile-body">

            <div class="section-title">Profile Information</div>

            <div class="info-box">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ auth()->user()->name }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ auth()->user()->email }}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Role</div>
                <div class="info-value">
                    {{ ucfirst(auth()->user()->role ?? 'User') }}
                </div>
            </div>

            <div class="section-title" style="margin-top:30px;">
                Change Password
            </div>

            <div class="change-password-box">

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <input type="password" name="current_password" placeholder="Current Password" class="form-control" required>
                    @error('current_password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <input type="password" name="password" placeholder="New Password" class="form-control" required>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" required>

                    <button class="btn-brand" type="submit">Update Password</button>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.profile-page {
    max-width: 900px;
    margin: auto;
}

.profile-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.profile-header {
    background: linear-gradient(135deg,#6366f1,#818cf8);
    padding: 35px;
    text-align: center;
    color: white;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: auto;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
}

.profile-body {
    padding: 25px;
}

.info-box {
    background: #f8fafc;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
    border: 1px solid #e5e7eb;
}

.section-title {
    font-weight: 700;
    margin: 20px 0 15px;
}

.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
}

.btn-brand {
    background: #6366f1;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
}

.error-text {
    color: red;
    font-size: 12px;
}
</style>
@endpush