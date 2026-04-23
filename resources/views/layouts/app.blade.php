<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Tailor Management System' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tailor-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/tailor-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/tailor-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tailor-black: #111111;
            --tailor-panel: #ffffff;
            --tailor-panel-soft: #fbf8f1;
            --tailor-border: #d8bf82;
            --tailor-gold: #c89b2c;
            --tailor-gold-soft: #a57d1e;
            --tailor-text: #111111;
            --tailor-muted: #5f5a50;
            --tailor-success: #1f9d68;
            --tailor-warning: #e0b437;
            --tailor-info: #3f8bd6;
        }

        body {
            min-height: 100vh;
            background: #ffffff;
            color: var(--tailor-text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: Georgia, "Times New Roman", serif;
            letter-spacing: -0.02em;
        }

        .navbar-tailor,
        .card-tailor {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(252, 250, 245, 0.97));
            border: 1px solid rgba(200, 155, 44, 0.28);
            box-shadow: 0 18px 40px rgba(17, 17, 17, 0.06);
        }

        .shell-logo {
            max-width: 138px;
            height: auto;
            display: block;
        }

        .brand-divider {
            border-top: 1px solid rgba(200, 155, 44, 0.2);
        }

        .sidebar-link {
            color: var(--tailor-muted);
            text-decoration: none;
            border-radius: 0.95rem;
            padding: 0.85rem 1rem;
            display: block;
            border: 1px solid transparent;
            transition: 0.2s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.98), rgba(37, 37, 37, 0.98));
            border-color: rgba(200, 155, 44, 0.4);
            color: #ffffff;
        }

        .btn-tailor {
            background: linear-gradient(135deg, #111111, #2a2a2a);
            color: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.92);
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(17, 17, 17, 0.12);
        }

        .btn,
        .btn.rounded-4,
        .page-link {
            border-radius: 10px !important;
        }

        .btn.rounded-circle {
            border-radius: 10px !important;
        }

        .btn-tailor:hover {
            color: #ffffff;
            opacity: 0.96;
            transform: translateY(-1px);
        }

        .metric-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(252, 250, 245, 0.97));
            border: 1px solid rgba(200, 155, 44, 0.22);
            color: var(--tailor-text);
        }

        .surface-muted {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(200, 155, 44, 0.14);
            border-radius: 1rem;
        }

        .text-secondary,
        .form-text,
        .small,
        .text-muted {
            color: #676056 !important;
        }

        .form-label {
            color: #2b241a;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .form-control,
        .form-select {
            background-color: #ffffff;
            color: var(--tailor-text);
            border: 1px solid var(--tailor-border);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .form-control::placeholder {
            color: #8f7e5c;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            color: var(--tailor-text);
            border-color: rgba(200, 155, 44, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(200, 155, 44, 0.12);
        }

        .table {
            color: var(--tailor-text);
        }

        .table > :not(caption) > * > * {
            background-color: transparent;
            border-bottom-color: rgba(200, 155, 44, 0.12);
        }

        .table thead th {
            color: #3b3020;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .badge.text-bg-success,
        .badge.text-bg-warning,
        .badge.text-bg-primary {
            border: none;
        }

        .pagination {
            --bs-pagination-bg: #ffffff;
            --bs-pagination-border-color: rgba(200, 155, 44, 0.2);
            --bs-pagination-color: var(--tailor-text);
            --bs-pagination-hover-bg: rgba(200, 155, 44, 0.12);
            --bs-pagination-hover-color: var(--tailor-text);
            --bs-pagination-focus-bg: rgba(200, 155, 44, 0.12);
            --bs-pagination-focus-color: var(--tailor-text);
            --bs-pagination-active-bg: var(--tailor-gold);
            --bs-pagination-active-border-color: var(--tailor-gold);
            --bs-pagination-active-color: #111;
        }

        .btn-outline-dark,
        .btn-outline-secondary {
            color: var(--tailor-text);
            border-color: rgba(200, 155, 44, 0.28);
            background: rgba(255, 255, 255, 0.94);
        }

        .btn-outline-dark:hover,
        .btn-outline-secondary:hover {
            color: #ffffff;
            background: var(--tailor-black);
            border-color: var(--tailor-black);
        }

        .alert-success,
        .alert-danger {
            color: var(--tailor-text);
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.2);
        }

        .tailor-toast-stack {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            max-width: min(360px, calc(100vw - 2rem));
        }

        .tailor-toast {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 0.95rem 1rem;
            border-radius: 1rem;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(252, 250, 245, 0.98));
            border: 1px solid rgba(200, 155, 44, 0.24);
            box-shadow: 0 18px 36px rgba(17, 17, 17, 0.08);
            color: #111111;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .tailor-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .tailor-toast.success {
            border-left: 4px solid #1f9d68;
        }

        .tailor-toast.error {
            border-left: 4px solid #dc3545;
        }

        .tailor-toast-icon {
            width: 2.1rem;
            height: 2.1rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .tailor-toast.success .tailor-toast-icon {
            background: rgba(31, 157, 104, 0.14);
            color: #1f9d68;
        }

        .tailor-toast.error .tailor-toast-icon {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        .tailor-toast-title {
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 0.15rem;
        }

        .tailor-toast-message {
            font-size: 0.84rem;
            color: #4c402d;
            line-height: 1.45;
        }

        .tailor-toast-close {
            border: 0;
            background: transparent;
            color: #7a6947;
            line-height: 1;
            padding: 0;
            font-size: 1.15rem;
            margin-left: auto;
        }

        @media (max-width: 991.98px) {
            .shell-logo {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-3 col-xl-2">
                <div class="navbar-tailor rounded-4 p-4 position-sticky top-0">
                    <a href="{{ route('login') }}" class="text-decoration-none d-block">
                        <img src="{{ asset('images/tailor-logo.png') }}" alt="Al Handaam Gents Tailoring" class="shell-logo mb-2">
                    </a>
                    <div class="mb-3">
                        <div class="fw-semibold lh-sm" style="color: #111111;">{{ auth()->user()->name }}</div>
                        <div class="small text-uppercase text-secondary lh-sm mt-1">{{ auth()->user()->role }}</div>
                    </div>

                    <div class="mb-4 pb-2 brand-divider">
                        <div class="small text-secondary pt-2">Tailor workspace</div>
                    </div>

                    <nav class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                        @if (auth()->user()->canAccessOrderWorkspace())
                            <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="sidebar-link {{ request()->routeIs('admin.orders.index') && request('view', 'invoices') !== 'report' ? 'active' : '' }}">Tailor Invoice</a>
                        @endif
                        @if (auth()->user()->canManageOrderSettings())
                            <a href="{{ route('admin.orders.index', ['view' => 'report']) }}" class="sidebar-link {{ request()->routeIs('admin.orders.index') && request('view') === 'report' ? 'active' : '' }}">Report</a>
                        @endif
                        @if (auth()->user()->canManageUsers())
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Access Control</a>
                        @endif
                    </nav>

                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark w-100 rounded-4">Logout</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10">
                @yield('content')
            </div>
        </div>
    </div>

    @php
        $toastNotifications = collect();

        if (session('status')) {
            $toastNotifications->push([
                'type' => 'success',
                'title' => 'Success',
                'message' => session('status'),
            ]);
        }

        if ($errors->any()) {
            $toastNotifications->push([
                'type' => 'error',
                'title' => 'Something went wrong',
                'message' => $errors->first(),
            ]);
        }
    @endphp

    @if ($toastNotifications->isNotEmpty())
        <div class="tailor-toast-stack" aria-live="polite" aria-atomic="true">
            @foreach ($toastNotifications as $toast)
                <div class="tailor-toast {{ $toast['type'] }}" data-tailor-toast>
                    <div class="tailor-toast-icon">{{ $toast['type'] === 'success' ? '!' : 'x' }}</div>
                    <div>
                        <div class="tailor-toast-title">{{ $toast['title'] }}</div>
                        <div class="tailor-toast-message">{{ $toast['message'] }}</div>
                    </div>
                    <button type="button" class="tailor-toast-close" aria-label="Close notification" data-tailor-toast-close>&times;</button>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        (() => {
            const toasts = document.querySelectorAll('[data-tailor-toast]');

            toasts.forEach((toast, index) => {
                window.setTimeout(() => toast.classList.add('show'), 80 + (index * 120));

                const removeToast = () => {
                    toast.classList.remove('show');
                    window.setTimeout(() => toast.remove(), 250);
                };

                const closeButton = toast.querySelector('[data-tailor-toast-close]');
                closeButton?.addEventListener('click', removeToast);

                window.setTimeout(removeToast, 4200 + (index * 250));
            });
        })();
    </script>
</body>
</html>
