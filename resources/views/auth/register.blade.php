<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

<div class="container">
    <h2>Create Account</h2>

    @if(session('success'))
        <p class="success-text">
            {{ session('success') }}
        </p>
    @endif

    @if($errors->any())
        <p class="error-summary">
            {{ $errors->first() }}
        </p>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="input-box">
            <input type="text" name="name" placeholder="Full Name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="input-box">
            <input type="email" name="email" placeholder="Email"
                   value="{{ old('email') }}" required>
        </div>

        {{-- ✅ ROLE DROPDOWN --}}
        <div class="input-box">
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="user"  {{ old('role') == 'user'  ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-box">
            <input type="password" name="password_confirmation"
                   placeholder="Confirm Password" required>
        </div>

        <button class="btn">Register</button>
    </form>

    <div class="footer">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </div>
</div>

</body>
</html>