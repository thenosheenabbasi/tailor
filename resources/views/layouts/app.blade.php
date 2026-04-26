<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Tailor Management System' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tailor-logo-sidebar.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/tailor-logo-sidebar.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/tailor-logo-sidebar.png') }}">
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
            --sidebar-bg: #111111;
            --sidebar-bg-soft: #1a1a1a;
            --sidebar-text: #ffffff;
            --sidebar-text-soft: rgba(255, 255, 255, 0.82);
            --sidebar-border: rgba(214, 183, 111, 0.14);
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

        .dashboard-shell {
            max-width: 1580px;
            margin: 0 auto;
        }

        .sidebar-panel {
            position: sticky;
            top: 1rem;
            min-height: calc(100vh - 2rem);
            padding: 1.8rem 1.1rem 1.35rem;
            border-radius: 0;
            color: var(--sidebar-text);
            background:
                radial-gradient(circle at 50% 43%, rgba(214, 183, 111, 0.16), transparent 7rem),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0)),
                linear-gradient(180deg, var(--sidebar-bg-soft), var(--sidebar-bg));
            border: 1px solid var(--sidebar-border);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.04),
                0 24px 48px rgba(17, 17, 17, 0.18);
            overflow: hidden;
        }

        .sidebar-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background:
                repeating-linear-gradient(
                    0deg,
                    rgba(255, 255, 255, 0.012) 0,
                    rgba(255, 255, 255, 0.012) 1px,
                    transparent 1px,
                    transparent 4px
                ),
                repeating-linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.04) 0,
                    rgba(0, 0, 0, 0.04) 1px,
                    transparent 1px,
                    transparent 5px
                );
            opacity: 0.42;
            pointer-events: none;
        }

        .sidebar-panel > * {
            position: relative;
            z-index: 1;
        }

        .shell-logo {
            max-width: 168px;
            height: auto;
            display: block;
            filter: brightness(0) invert(1);
        }

        .brand-divider {
            border-top: 1px solid rgba(230, 208, 154, 0.12);
        }

        .sidebar-brand {
            padding: 0.2rem 0 0.9rem;
        }

        .sidebar-profile {
            padding: 0.05rem 0 1rem;
        }

        .sidebar-name {
            color: #ffffff;
            font-size: 1.12rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .sidebar-role {
            margin-top: 0.35rem;
            color: var(--sidebar-text-soft) !important;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .sidebar-link {
            position: relative;
            color: var(--sidebar-text-soft);
            text-decoration: none;
            border-radius: 1.1rem;
            padding: 0.95rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border: 1px solid transparent;
            transition: 0.24s ease;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: "";
            position: absolute;
            left: 0.95rem;
            right: 0.95rem;
            top: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232, 206, 142, 0.65), transparent);
            opacity: 0;
            transition: 0.24s ease;
        }

        .sidebar-link-icon {
            width: 1.15rem;
            height: 1.15rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #d7bc7a;
        }

        .sidebar-link-icon svg {
            width: 1.05rem;
            height: 1.05rem;
        }

        .sidebar-link-label {
            position: relative;
            z-index: 1;
            font-weight: 600;
            line-height: 1.2;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background:
                radial-gradient(circle at right center, rgba(232, 206, 142, 0.22), transparent 14%),
                linear-gradient(180deg, rgba(69, 62, 43, 0.95), rgba(47, 42, 31, 0.97));
            border-color: rgba(214, 183, 111, 0.16);
            color: #ffffff;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.04),
                0 16px 26px rgba(0, 0, 0, 0.14);
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            opacity: 1;
        }

        .sidebar-link:hover .sidebar-link-icon,
        .sidebar-link.active .sidebar-link-icon {
            color: #f1d495;
        }

        .sidebar-section-label {
            color: rgba(255, 255, 255, 0.72) !important;
            font-size: 0.88rem;
        }

        .sidebar-content {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 1.2rem;
        }

        .sidebar-logout-btn {
            width: 100%;
            background: transparent;
            text-align: left;
            cursor: pointer;
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

            .sidebar-panel {
                min-height: auto;
                position: relative;
                top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4 dashboard-shell">
        <div class="row g-4">
            <div class="col-lg-3 col-xl-2">
                <div class="sidebar-panel">
                    <div class="sidebar-content">
                    <a href="{{ route('login') }}" class="text-decoration-none d-block sidebar-brand">
                        <img src="{{ asset('images/tailor-logo-sidebar.png') }}" alt="Al Handaam Gents Tailoring" class="shell-logo mb-2">
                    </a>
                    <div class="sidebar-profile">
                        <div class="sidebar-name">{{ auth()->user()->name }}</div>
                        <div class="sidebar-role text-uppercase">{{ auth()->user()->role }}</div>
                    </div>

                    <div class="mb-4 pb-2 brand-divider">
                        <div class="sidebar-section-label pt-2">Tailor workspace</div>
                    </div>

                    <nav class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 12a9 9 0 1 0 18 0"></path>
                                    <path d="M12 7v5l3 2"></path>
                                </svg>
                            </span>
                            <span class="sidebar-link-label">Dashboard</span>
                        </a>
                        @if (auth()->user()->canAccessOrderWorkspace())
                            <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="sidebar-link {{ request()->routeIs('admin.orders.index') && request('view', 'invoices') !== 'report' ? 'active' : '' }}">
                                <span class="sidebar-link-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7 3h7l5 5v13H7z"></path>
                                        <path d="M14 3v5h5"></path>
                                        <path d="M10 13h6"></path>
                                        <path d="M10 17h6"></path>
                                    </svg>
                                </span>
                                <span class="sidebar-link-label">Tailor Invoice</span>
                            </a>
                        @endif
                        @if (auth()->user()->canAccessReports())
                            <a href="{{ route('admin.orders.index', ['view' => 'report']) }}" class="sidebar-link {{ request()->routeIs('admin.orders.index') && request('view') === 'report' ? 'active' : '' }}">
                                <span class="sidebar-link-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 19h16"></path>
                                        <path d="M7 15l3-3 3 2 4-6"></path>
                                    </svg>
                                </span>
                                <span class="sidebar-link-label">Report</span>
                            </a>
                        @endif
                        @if (auth()->user()->canManageUsers())
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span class="sidebar-link-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="10" cy="10" r="4.5"></circle>
                                        <path d="M14 14l6 6"></path>
                                    </svg>
                                </span>
                                <span class="sidebar-link-label">Access Control</span>
                            </a>
                        @endif
                    </nav>

                    <form action="{{ route('logout') }}" method="POST" class="sidebar-logout">
                        @csrf
                        <button type="submit" class="sidebar-link sidebar-logout-btn">
                            <span class="sidebar-link-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                    <path d="M10 17l5-5-5-5"></path>
                                    <path d="M15 12H3"></path>
                                </svg>
                            </span>
                            <span class="sidebar-link-label">Logout</span>
                        </button>
                    </form>
                    </div>
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
