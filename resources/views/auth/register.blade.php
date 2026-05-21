<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    height: 100vh;
    background: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    width: 350px;
    padding: 25px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.input-box {
    margin-bottom: 15px;
}

.input-box input,
.input-box select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: #333;
    background: white;
}

.input-box input:focus,
.input-box select:focus {
    border-color: #4facfe;
}

.error-text {
    color: red;
    font-size: 12px;
    margin-top: 4px;
}

.btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 6px;
    background: #4facfe;
    color: white;
    font-weight: 500;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
}

.btn:hover {
    background: #3a8ee6;
}

.footer {
    text-align: center;
    margin-top: 12px;
    font-size: 14px;
}

.footer a {
    color: #4facfe;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">
    <h2>Create Account</h2>

    @if(session('success'))
        <p style="color: green; text-align: center; margin-bottom: 10px;">
            {{ session('success') }}
        </p>
    @endif

    @if($errors->any())
        <p style="color: red; text-align: center; margin-bottom: 10px; font-size:13px;">
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
