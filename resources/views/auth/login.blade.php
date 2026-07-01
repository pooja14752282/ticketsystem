<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="d-flex main-wrapper">

    <!-- LEFT -->

    <div class="left-section">

        <div class="login-card">

            <p class="h4 mb-2 fw-semibold">Sign In</p>

            <p class="login-subtitle">
                Welcome back!
            </p>

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Email Address</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter email"
                           required>
                </div>

                 <div class="col-xl-12 mb-2">

                    <label class="form-label text-default d-block">
                        Password   
                        <a href="{{ route('forgot.password') }}" class="forgot-link">
                            Forget password ?
                        </a>
                    </label>

                    <div class="password-wrapper">

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Enter password"
                               required>

                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="ri-eye-off-line" id="eyeIcon"></i>
                        </span>

                    </div>

                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label remember-text" for="remember">
                        Remember password ?
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                </button>

            </form>

        <div>
            <div class="bottom-text">
                Dont have an account?
                <a href="{{ route('register') }}">
                    Sign Up
                </a>
            </div>
         </div>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="right-section">

                    <div class="d-flex align-items-center justify-content-center p-3 rounded m-5">
                    <div class="p-3">
                         <h2 class="text-white lh-base fw-semibold mt-4">Join the Revolution</h2>
                        <p class="mb-0 fs-16 lh-base text-white opacity-75">Be part of something extraordinary. Sign up now and unlock a world of opportunities, new experiences, and exclusive benefits.</p>
    </div>
    </div>
                        </div>

        </div>

    </div>

</div>

<script>

    function togglePassword(){

        let password = document.getElementById('password');
        let eyeIcon = document.getElementById('eyeIcon');

        if(password.type === "password"){

            password.type = "text";

            eyeIcon.classList.remove('ri-eye-off-line');
            eyeIcon.classList.add('ri-eye-line');

        }else{

            password.type = "password";

            eyeIcon.classList.remove('ri-eye-line');
            eyeIcon.classList.add('ri-eye-off-line');
        }
    }

</script>

</body>
</html>