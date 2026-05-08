<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --student-primary: #3458ff;
            --student-primary-soft: rgba(52, 88, 255, 0.08);
            --student-surface: #ffffff;
            --student-border: rgba(15, 23, 42, 0.08);
            --student-text-muted: #6b7280;
        }

        /* Center all modals horizontally and vertically */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100vh - 1rem);
        }
        .modal.fade .modal-dialog {
            transition: transform .3s ease-out;
        }
        .modal-content {
            margin: auto;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(52, 88, 255, 0.08), transparent 28%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.08), transparent 24%),
                #f5f7fb;
            min-height: 100vh;
            color: #1f2937;
        }

        .student-navbar {
            background: #fff !important;
            border-bottom: 1px solid var(--student-border);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            backdrop-filter: none;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .student-navbar-brand {
            font-weight: 700;
            letter-spacing: 0.2px;
            color: #111827 !important;
        }

        .student-navbar-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: 0.85rem;
            background: var(--student-primary-soft);
            padding: 0.35rem;
            border: 1px solid rgba(52, 88, 255, 0.12);
        }

        .student-page {
            padding: 1.5rem 0 2rem;
        }

        .student-page .container,
        .student-page .container-fluid {
            max-width: 1200px;
        }

        .student-card {
            border: 1px solid var(--student-border);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            border-radius: 1rem;
        }

        .student-card .card-header {
            background: linear-gradient(180deg, rgba(52, 88, 255, 0.05), rgba(255, 255, 255, 0));
            border-bottom: 1px solid var(--student-border);
        }

        .student-badge {
            background: var(--student-primary-soft);
            color: var(--student-primary);
            border: 1px solid rgba(52, 88, 255, 0.15);
        }

        .text-muted {
            color: var(--student-text-muted) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3458ff, #5a7cff);
            border: none;
            box-shadow: 0 10px 18px rgba(52, 88, 255, 0.22);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #2946d9, #4e6cf0);
            border: none;
        }

        .btn-outline-secondary {
            border-color: rgba(107, 114, 128, 0.25);
            color: #374151;
        }

        .btn-outline-secondary:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .student-hero {
            background: linear-gradient(135deg, rgba(52, 88, 255, 0.10), rgba(255, 255, 255, 0.92));
            border: 1px solid var(--student-border);
            border-radius: 1.25rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .student-section-title {
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
        }

        .student-empty-state {
            background: #fff;
            border: 1px dashed rgba(107, 114, 128, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        @media (max-width: 991.98px) {
            .student-page {
                padding-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light student-navbar py-3">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand d-flex align-items-center gap-3 student-navbar-brand" href="{{ route('student.dashboard') }}">
                <img src="{{ asset('images/RMSLogo.png') }}" alt="Reseva logo" class="student-navbar-logo">
                <span>Student Portal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStudent" aria-controls="navbarStudent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarStudent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('student.dashboard') ? 'active fw-semibold text-primary' : 'text-dark' }}" href="{{ route('student.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('student.classes*') ? 'active fw-semibold text-primary' : 'text-dark' }}" href="{{ route('student.classes') }}">My Classes</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <form action="{{ route('logout') }}" method="POST" class="d-flex">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm px-3">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="student-page">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
