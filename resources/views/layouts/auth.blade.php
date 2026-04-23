<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Research Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.auth-page {
            background: #edf5fb;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            width: min(100%, 480px);
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12);
            padding: 44px 42px;
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        .auth-brand {
            width: 92px;
            height: 92px;
            margin: 0 auto 22px;
            display: grid;
            place-items: center;
            border-radius: 50%;
        
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
        }
        .auth-title {
            margin-bottom: 0.25rem;
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #101828;
        }
        .auth-subtitle {
            margin-bottom: 2.25rem;
            color: #64748b;
            text-align: center;
            font-size: 0.98rem;
        }
        .auth-card .form-control {
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            padding: 1rem 1.25rem;
            height: auto;
            box-shadow: none;
        }
        .auth-card .form-control:focus {
            border-color: #5b54d6;
            box-shadow: 0 0 0 0.15rem rgba(93, 48, 255, 0.12);
            background: #ffffff;
        }
        .auth-card .form-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        .input-group .input-group-text {
            background: #f8fafc;
            border: 1px solid #d1d5db;
            border-right: none;
            border-top-left-radius: 999px;
            border-bottom-left-radius: 999px;
            color: #64748b;
            width: 52px;
            justify-content: center;
        }
        .input-group .form-control {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 999px;
            border-bottom-right-radius: 999px;
        }
        .auth-card .btn-primary {
            border-radius: 999px;
            padding: 0.95rem 1rem;
            font-size: 1rem;
            font-weight: 600;
            background: #0f172a;
            border-color: #0f172a;
        }
        .auth-card .btn-primary:hover,
        .auth-card .btn-primary:focus {
            background: #111827;
            border-color: #111827;
        }
        .auth-card .form-text {
            font-size: 0.9rem;
            color: #64748b;
        }
        .auth-card .aux-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.92rem;
        }
        .auth-card .aux-link:hover {
            color: #0f172a;
        }
    </style>
</head>
<body class="auth-page">
    <main class="auth-container">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
