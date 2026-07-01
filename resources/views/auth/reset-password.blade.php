<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="New Password" required>
        </div>
        <div class="input-box">
            <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
        </div>
        <button class="btn">Reset Password</button>
    </form>
</div>
</body>
</html>