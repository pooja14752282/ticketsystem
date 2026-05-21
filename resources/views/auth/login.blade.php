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

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            background:#ffffff;
            overflow:hidden;
        }

        .main-wrapper{
            width:100%;
            height:100vh;
        }

        /* LEFT SIDE */

        .left-section{
            width:50%;
            height:100vh;
            background:#fff;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .login-card{
            width:100%;
            max-width:440px;
            border:1px solid #f1f1f1;
            border-radius:8px;
            background:#fff;
            padding:42px;
            box-shadow:0 2px 10px rgba(0,0,0,0.03);
            position:relative;
        }

        .login-title{
            font-size:40px;
            font-weight:700;
            color:#111827;
            margin-bottom:5px;
        }

        .login-subtitle{
            color:#9ca3af;
            font-size:14px;
            margin-bottom:35px;
        }

        .form-label{
            font-size:14px;
            font-weight:500;
            margin-bottom:8px;
            color:#374151;
        }

        .form-control{
            height:44px;
            border-radius:4px;
            border:1px solid #e5e7eb;
            font-size:14px;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:#60a5fa;
        }

        .password-wrapper{
            position:relative;
        }

        .toggle-password{
            position:absolute;
            top:50%;
            right:14px;
            transform:translateY(-50%);
            cursor:pointer;
            color:#9ca3af;
            font-size:16px;
        }

        .forgot-link{
            float:right;
            text-decoration:none;
            font-size:12px;
            color:#f8a4a4;
        }

        .forgot-link:hover{
            color:#ef4444;
        }

        .remember-text{
            font-size:13px;
            color:#9ca3af;
        }

        .btn-login{
            width:100%;
            height:46px;
            border:none;
            border-radius:4px;
            background:#5ea2ff;
            color:#fff;
            font-weight:500;
            margin-top:10px;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#3b82f6;
        }

        .divider{
            text-align:center;
            margin:28px 0;
            position:relative;
        }

        .divider::before{
            content:'';
            position:absolute;
            width:100%;
            height:1px;
            background:#eeeeee;
            left:0;
            top:50%;
        }

        .divider span{
            position:relative;
            background:#fff;
            padding:0 10px;
            color:#bdbdbd;
            font-size:12px;
        }

        .social-icons{
            display:flex;
            justify-content:center;
            gap:12px;
            margin-bottom:25px;
        }

        .social-icons button{
            width:35px;
            height:35px;
            border:none;
            border-radius:4px;
            background:#f9fafb;
        }

        .bottom-text{
            text-align:center;
            font-size:14px;
            color:#9ca3af;
        }

        .bottom-text a{
            text-decoration:none;
            color:#5ea2ff;
            font-weight:500;
        }

        /* RIGHT SIDE */

        .right-section{
            width:50%;
            height:100vh;
            background:linear-gradient(135deg,#5ea2ff,#b06cff);
            position:relative;
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
        }

        .right-section::before{
            content:'';
            position:absolute;
            top:-100px;
            right:-100px;
            width:250px;
            height:250px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
        }

        .right-section::after{
            content:'';
            position:absolute;
            bottom:-120px;
            left:-120px;
            width:250px;
            height:250px;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
        }

        .right-content{
            max-width:420px;
            color:#fff;
            z-index:2;
        }

        .small-box{
            width:22px;
            height:22px;
            background:rgba(255,255,255,0.5);
            margin-bottom:35px;
        }

        .right-content h1{
            font-size:54px;
            font-weight:700;
            line-height:1.1;
            margin-bottom:25px;
        }

        .right-content p{
            font-size:18px;
            line-height:1.8;
            opacity:0.95;
        }

        .error-message{
            background:#fee2e2;
            color:#dc2626;
            padding:10px;
            border-radius:4px;
            margin-bottom:20px;
            font-size:14px;
        }

        @media(max-width:991px){

            body{
                overflow:auto;
            }

            .left-section{
                width:100%;
                height:auto;
                padding:25px;
            }

            .right-section{
                display:none;
            }

            .login-card{
                max-width:100%;
                padding:35px;
            }
        }

    </style>
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