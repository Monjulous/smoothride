@php
    $setting = \App\Models\Setting::find(1);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{$setting->website_title}} - Forgot Password</title>
    
    <!-- Favicon -->
    @if($setting->website_favicon != null || !empty($setting->website_favicon))
        <link rel="shortcut icon" type="image/x-icon" href="{{$setting->website_favicon}}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="/assets/admin/img/favicon-def.png">
    @endif

    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/toastr.min.css">
    <link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css">
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
        .auth-shell { min-height: 100vh; display: flex; }
        .auth-left { flex: 1; position: relative; display: none; }
        .auth-left img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); }
        .auth-left::after { content: ""; position: absolute; inset: 0; background: rgba(0,0,0,.45); }
        .auth-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 32px; }
        .auth-card {
            width: 100%; max-width: 480px; background: #fff; border-radius: 24px;
            padding: 36px; box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;
        }
        .auth-card img { max-width: 220px; margin-bottom: 20px; }
        .auth-title { margin: 0; font-size: 28px; font-weight: 800; }
        .auth-subtitle { margin: 6px 0 24px; color: #64748b; font-weight: 500; }
        .form-control {
            height: 46px; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: none;
        }
        .form-control:focus {
            border-color: #facc15;
            box-shadow: 0 0 0 0.15rem rgba(250, 204, 21, 0.25);
        }
        .btn-auth {
            width: 100%; height: 46px; border: 0; border-radius: 12px;
            background: #facc15; color: #0f172a; font-weight: 700;
        }
        .btn-auth:hover { background: #eab308; }
        @media (min-width: 992px) { .auth-left { display: block; } }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-left">
            <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&q=80&w=1920&h=1080" alt="Forgot Password">
        </div>
        <div class="auth-right">
            <div class="auth-card">
                @if($setting->website_logo_light != null || !empty($setting->website_logo_light))
                    <img src="{{ $setting->website_logo_light }}" alt="{{ $setting->website_title }}">
                @else
                    <img src="/assets/admin/img/logo-def.png" alt="Logo">
                @endif
                <h1 class="auth-title">Recover Password</h1>
                <p class="auth-subtitle">Get the reset password email.</p>
                <form action="{{ route('forget.password.post') }}" method="POST">
                    @csrf()
                    <div class="form-group">
                        <input class="form-control" name="email" type="text" placeholder="Email address">
                    </div>
                    <div class="form-group mb-0">
                        <button class="btn-auth" type="submit">Send Reset Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="/assets/admin/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap Core JS -->
    <script src="/assets/admin/js/popper.min.js"></script>
    <script src="/assets/admin/js/bootstrap.min.js"></script>
    <!-- toastr JS -->
    <script src="/assets/admin/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    <script src="/assets/admin/js/script.js"></script>
</body>
</html>