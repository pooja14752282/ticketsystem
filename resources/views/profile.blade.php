<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background:#f5f7fb;
        }

        .profile-page {
            max-width: 1000px;
            margin: auto;
        }

        .profile-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: #fff;
        }

        .profile-header {
            background: linear-gradient(135deg, #636ccb, #7c86e8);
            padding: 35px;
            text-align: center;
            color: white;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 4px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: 700;
            margin: auto;
            margin-bottom: 15px;
        }

        .profile-body {
            padding: 30px;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 18px;
            border: 1px solid #edf2f7;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .change-password-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 25px;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }

        .btn-brand {
            background: #636ccb;
            border: none;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .btn-brand:hover {
            background: #515ac0;
            color: white;
        }

    </style>
</head>

<body>

<div class="container py-5 profile-page">

    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <h3 class="mb-1">
                {{ $user->name }}
            </h3>

            <p class="mb-0 opacity-75">
                {{ $user->email }}
            </p>

        </div>

        <div class="profile-body">

            <h5 class="section-title">
                <i class="bi bi-person-circle me-2"></i>
                Personal Information
            </h5>

            <div class="row">

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Name</div>
                        <div class="info-value">
                            {{ $user->name }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Mobile</div>
                        <div class="info-value">
                            {{ Auth::user()->mobile ?? 'Not Available' }}
                        </div>
                    </div>
                </div>

                <div class="info-box">
    <div class="info-label">Assigned Project</div>
    <div class="info-value">

        @php
            $categories = $user->tickets->pluck('category')->unique();
        @endphp

        @forelse($categories as $category)
            {{ $category }} <br>
        @empty
            No Project Assigned
        @endforelse

    </div>
</div>

            </div>

            <div class="mt-4">

                <h5 class="section-title">
                    <i class="bi bi-lock-fill me-2"></i>
                    Change Password
                </h5>

                <div class="change-password-box">

                    <form method="POST" action="#">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-brand">
                            Update Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>