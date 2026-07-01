<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <p>Enter your email and we'll send you a reset link.</p>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form action="{{ route('forgot.password.store') }}" method="POST">
        @csrf
        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <button class="btn">Send Reset Link</button>
    </form>

    <div class="footer">
        Remember your password? <a href="{{ route('login') }}">Login</a>
    </div>
</div>
</body>
</html>