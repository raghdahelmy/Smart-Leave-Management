<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login - Smart Leave System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
        .login-card .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-card .brand .icon {
            width: 60px; height: 60px;
            background: #4361ee;
            border-radius: .75rem;
            display: inline-flex;
            align-items: center; justify-content: center;
            color: #fff; font-size: 1.75rem;
            margin-bottom: .75rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="icon"><i class="bi bi-calendar2-check"></i></div>
            <h4 class="fw-bold">Smart Leave System</h4>
            <p class="text-muted small mb-0">Sign in to continue</p>
        </div>
        @yield('content')
    </div>
</body>
</html>
