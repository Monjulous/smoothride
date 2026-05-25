
@php
  $setting=\App\Models\Setting::find(1);

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ $setting->website_title }} - Login</title>
    @if($setting->website_favicon != null || !empty($setting->website_favicon))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->website_favicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/admin/img/favicon-def.png') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f2f9ff;
            color: #0f172a;
        }
        .login-shell {
            min-height: 100vh;
            display: flex;
        }
        .login-left {
            flex: 1;
            position: relative;
            display: none;
        }
        .login-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
        }
        .login-left-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
        }
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }
        .login-card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }
        .brand-wrap img {
            max-width: 220px;
            margin-bottom: 20px;
        }
        .title {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }
        .subtitle {
            margin: 6px 0 24px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 8px;
        }
        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #facc15;
            box-shadow: 0 0 0 0.15rem rgba(250, 204, 21, 0.25);
        }
        .pwd-field { position: relative; }
        #iconid {
            position: absolute;
            top: 40px;
            right: 14px;
            color: #64748b;
            cursor: pointer;
        }
        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 14px;
            background: #facc15;
            color: #0f172a;
            font-weight: 700;
            transition: .2s ease;
        }
        .btn-login:hover { background: #eab308; }
        @media (min-width: 992px) {
            .login-left { display: block; }
        }
    </style>
</head>
<body>
    @if(Session::has('logout'))
        <p style="margin: 10px; color: #dc2626;">{{ Session::get('logout') }}</p>
    @endif
    <div class="login-shell">
        <div class="login-left">
            <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&q=80&w=1920&h=1080" alt="Login Hero">
            <div class="login-left-overlay"></div>
        </div>
        <div class="login-right">
            <div class="login-card">
                <div class="brand-wrap">
                    @if($setting->website_logo_light != null || !empty($setting->website_logo_light))
                        <img src="{{ asset($setting->website_logo_light) }}" alt="{{ $setting->website_title }}">
                    @else
                        <img src="{{ asset('assets/admin/img/logo.png') }}" alt="Smooth Ride">
                    @endif
                </div>
                <h1 class="title">Admin Dashboard Login</h1>
                <p class="subtitle">Secure access for platform administrators.</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf()
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" name="email" type="text" placeholder="admin@example.com">
                    </div>
                    <div class="form-group pwd-field">
                        <label>Password</label>
                        <input class="form-control" name="password" type="password" id="passwordInput" placeholder="Enter your password">
                        <i class="fa fa-eye-slash" onclick="showpassword()" id="iconid"></i>
                    </div>
                    <button class="btn-login" type="submit">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/script.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}
    <script>
        function showpassword() {
            var x = document.getElementById("passwordInput");
            if (x.type === "password") {
                x.type = "text";
                $('#iconid').addClass('fa-eye').removeClass('fa-eye-slash');
            } else {
                x.type = "password";
                $('#iconid').removeClass('fa-eye').addClass('fa-eye-slash');
            }
        }
    </script>
</body>
</html>