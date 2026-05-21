<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body { height: 100vh; background: #f4f4f4; display: flex; justify-content: center; align-items: center; }
.container { width: 350px; padding: 25px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.container h2 { text-align: center; margin-bottom: 20px; color: #333; }
.input-box { margin-bottom: 15px; }
.input-box input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; outline: none; font-size: 14px; }
.input-box input:focus { border-color: #4facfe; }
.btn { width: 100%; padding: 10px; border: none; border-radius: 6px; background: #4facfe; color: white; font-weight: 500; cursor: pointer; }
.btn:hover { background: #3a8ee6; }
.error { color: red; text-align: center; margin-bottom: 10px; font-size: 14px; }
</style>
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
