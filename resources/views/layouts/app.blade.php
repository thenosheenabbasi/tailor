<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Tailor Management System' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tailor-logo-sidebar.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/tailor-logo-sidebar.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/tailor-logo-sidebar.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tailor-gold: #d79a1e;
            --tailor-gold-dark: #be8110;
            --tailor-black: #101010;
            --tailor-bg: #fbf8f1;
            --tailor-panel: #ffffff;
            --tailor-line: rgba(29, 22, 13, 0.07);
            --tailor-text: #171717;
            --tailor-muted: #6e685f;
            --tailor-shadow: 0 8px 22px rgba(34, 24, 10, 0.045);
            --dashboard-base-text: 0.92rem;
            --dashboard-button-padding-y: 0.85rem;
            --dashboard-button-padding-x: 1.2rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top center, rgba(215, 154, 30, 0.08), transparent 26%),
                linear-gradient(180deg, #fdfaf4 0%, #f8f4ed 100%);
            color: var(--tailor-text);
            font-family: "Manrope", sans-serif;
            font-size: var(--dashboard-base-text);
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            color: var(--tailor-text);
            font-family: "Outfit", sans-serif;
            letter-spacing: -0.03em;
        }

        a {
            color: inherit;
        }

        .dashboard-shell {
            min-height: 100vh;
            max-width: 100%;
            overflow-x: hidden;
        }

        .app-body {
            display: grid;
            grid-template-columns: 257px minmax(0, 1fr);
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dashboard-shell.sidebar-collapsed .app-body {
            grid-template-columns: 88px minmax(0, 1fr);
        }

        .sidebar-panel {
            position: sticky;
            top: 0;
            z-index: 80;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            padding: 0.82rem 0.72rem 0.8rem;
            background:
                radial-gradient(circle at top left, rgba(215, 154, 30, 0.14), transparent 36%),
                linear-gradient(180deg, #121212 0%, #0b0b0b 100%);
            color: #ffffff;
        }

        .sidebar-brand {
            min-height: 88px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem;
            text-align: center;
        }

        .sidebar-brand img {
            width: 128px;
            max-width: 100%;
            height: auto;
            display: block;
            margin-inline: auto;
        }

        .sidebar-section-label {
            padding: 0 0.3rem;
            color: rgba(255, 255, 255, 0.42);
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 0.16rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.68rem;
            min-height: 44px;
            padding: 0.64rem 0.78rem;
            border-radius: 14px;
            border: 1px solid transparent;
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #d79a1e 0%, #d79a1e 100%);
            box-shadow: 0 14px 26px rgba(215, 154, 30, 0.18);
            color: #ffffff;
        }

        .sidebar-link-icon {
            width: 1rem;
            height: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-link svg {
            width: 0.86rem;
            height: 0.86rem;
        }

        .sidebar-link-label {
            flex: 1;
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 0.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logout-btn {
            width: 100%;
            border: 0;
            background: transparent;
            cursor: pointer;
            justify-content: flex-start;
        }

        .sidebar-logout-btn .sidebar-link-label {
            flex: 0;
        }

        .workspace-main {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.85rem 1.7rem 0.85rem 1.5rem;
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(17, 17, 17, 0.05);
            box-shadow: 0 6px 20px rgba(34, 24, 10, 0.03);
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            flex: 1 1 auto;
            gap: 1rem;
            min-width: 0;
        }

        .topbar-menu-toggle {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 12px;
            background: transparent;
            color: var(--tailor-black);
            cursor: pointer;
        }

        .topbar-menu-toggle svg {
            width: 1.4rem;
            height: 1.4rem;
        }

        .topbar-page-title {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            flex: 0 1 auto;
            gap: 0.85rem;
            min-width: 0;
        }

        .topbar-alert {
            position: relative;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: var(--tailor-black);
        }

        .topbar-alert svg {
            width: 1.3rem;
            height: 1.3rem;
        }

        .topbar-alert-badge {
            position: absolute;
            top: 1px;
            right: 1px;
            min-width: 1.05rem;
            height: 1.05rem;
            padding: 0 0.25rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--tailor-gold);
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 800;
        }

        .topbar-language {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            min-height: 42px;
            padding: 0 0.72rem;
            border: 1px solid rgba(17, 17, 17, 0.12);
            border-radius: 999px;
            background: #ffffff;
            color: var(--tailor-text);
        }

        .topbar-language-label {
            color: var(--tailor-muted);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .topbar-language-select {
            border: 0;
            background: transparent;
            color: var(--tailor-text);
            font-size: 0.8rem;
            font-weight: 700;
            outline: 0;
            cursor: pointer;
        }

        .topbar-profile {
            position: relative;
            display: flex;
            align-items: center;
        }

        .topbar-profile-toggle {
            display: flex;
            align-items: center;
            min-width: 0;
            gap: 0.8rem;
            border: 0;
            background: transparent;
            color: var(--tailor-text);
            cursor: pointer;
            padding: 0;
        }

        .topbar-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #111111;
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 800;
            box-shadow: 0 8px 18px rgba(17, 17, 17, 0.16);
            overflow: hidden;
        }

        .topbar-avatar img {
            width: 74%;
            height: 74%;
            object-fit: contain;
            display: block;
        }

        .topbar-profile-copy {
            display: grid;
            gap: 0.1rem;
            min-width: 0;
            text-align: left;
        }

        .topbar-profile-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .topbar-profile-role {
            color: var(--tailor-muted);
            font-size: 0.76rem;
            text-transform: capitalize;
        }

        .topbar-profile-caret {
            color: var(--tailor-black);
        }

        .topbar-profile-menu {
            position: absolute;
            top: calc(100% + 0.85rem);
            right: 0;
            min-width: 220px;
            padding: 0.45rem 0;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            border-radius: 18px;
            box-shadow: 0 18px 32px rgba(32, 22, 8, 0.12);
            opacity: 0;
            pointer-events: none;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .topbar-profile.is-open .topbar-profile-menu {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .topbar-profile-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            color: var(--tailor-text);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .topbar-profile-menu button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            border: 0;
            background: transparent;
            color: var(--tailor-text);
            font-size: 0.8rem;
            font-weight: 600;
            text-align: left;
        }

        .topbar-profile-menu-label {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
        }

        .topbar-profile-menu-label svg {
            width: 0.95rem;
            height: 0.95rem;
            flex: 0 0 auto;
        }

        .workspace-content,
        .workspace-content p,
        .workspace-content li,
        .workspace-content label,
        .workspace-content input,
        .workspace-content select,
        .workspace-content textarea,
        .workspace-content .btn,
        .workspace-content .table tbody td,
        .workspace-content .table thead th,
        .workspace-content .form-text,
        .workspace-content .text-muted,
        .workspace-content .text-secondary {
            font-size: var(--dashboard-base-text);
        }

        .topbar-profile-menu a:hover {
            background: #faf5eb;
        }

        .topbar-profile-menu button:hover {
            background: #faf5eb;
        }

        .workspace-content {
            flex: 1 1 auto;
            min-width: 0;
            padding: 0.9rem 1.05rem 5.5rem;
        }

        .dashboard-footer {
            margin: 0;
            position: fixed;
            left: 257px;
            right: 0;
            bottom: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.85rem;
            border-radius: 0;
            background: #ffffff;
            border-top: 1px solid rgba(17, 17, 17, 0.08);
            color: rgba(17, 17, 17, 0.72);
            box-shadow: none;
            font-size: 0.82rem;
        }

        .dashboard-footer-title {
            font-family: "Outfit", sans-serif;
            font-size: 0.78rem;
            font-weight: 400;
            color: #111111;
            margin-bottom: 0;
        }

        .content-stage,
        .card-tailor,
        .metric-card,
        .surface-muted {
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(17, 17, 17, 0.05);
            box-shadow: var(--tailor-shadow);
        }

        .btn,
        .page-link {
            border-radius: 14px !important;
        }

        .workspace-content .btn:not(.action-btn):not(.topbar-alert):not(.topbar-menu-toggle):not(.password-toggle-btn):not(.entry-action-btn),
        .workspace-content a.btn:not(.action-btn):not(.topbar-alert):not(.topbar-menu-toggle):not(.password-toggle-btn):not(.entry-action-btn) {
            padding: var(--dashboard-button-padding-y) var(--dashboard-button-padding-x) !important;
            min-height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            line-height: 1.2;
            font-size: 0.84rem;
        }

        .btn-tailor {
            color: #ffffff;
            font-weight: 700;
            border: 1px solid var(--tailor-black);
            background: #111111;
        }

        .btn-tailor:hover,
        .btn-tailor:focus {
            color: #ffffff;
            background: #111111;
            border-color: var(--tailor-black);
        }

        .btn-outline-dark,
        .btn-outline-secondary {
            color: var(--tailor-text);
            border-color: rgba(17, 17, 17, 0.14);
            background: #ffffff;
        }

        .btn-outline-dark:hover,
        .btn-outline-secondary:hover {
            color: #ffffff;
            background: var(--tailor-black);
            border-color: var(--tailor-black);
        }

        .text-secondary,
        .text-muted,
        .small,
        .form-text {
            color: var(--tailor-muted) !important;
        }

        .form-label {
            color: #7c7367;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .form-control,
        .form-select {
            min-height: 2.95rem;
            color: var(--tailor-text);
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.12);
            border-radius: 16px !important;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            color: var(--tailor-text);
            background: #ffffff;
            border-color: rgba(215, 154, 30, 0.42);
            box-shadow: 0 0 0 0.22rem rgba(215, 154, 30, 0.12);
        }

        .table {
            color: var(--tailor-text);
        }

        .table > :not(caption) > * > * {
            border-bottom-color: rgba(17, 17, 17, 0.08);
        }

        .table thead th {
            color: #8a8278;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .pagination {
            --bs-pagination-bg: #ffffff;
            --bs-pagination-border-color: rgba(17, 17, 17, 0.08);
            --bs-pagination-color: var(--tailor-text);
            --bs-pagination-hover-bg: #f8efe0;
            --bs-pagination-hover-color: var(--tailor-text);
            --bs-pagination-focus-bg: #f8efe0;
            --bs-pagination-focus-color: var(--tailor-text);
            --bs-pagination-active-bg: var(--tailor-gold);
            --bs-pagination-active-border-color: var(--tailor-gold);
            --bs-pagination-active-color: #ffffff;
        }

        .tailor-toast-stack {
            position: fixed;
            top: 92px;
            right: 1rem;
            z-index: 1080;
            display: grid;
            gap: 0.8rem;
            max-width: min(360px, calc(100vw - 2rem));
        }

        .tailor-toast {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 0.95rem 1rem;
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 18px 34px rgba(17, 17, 17, 0.1);
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .tailor-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .tailor-toast.success {
            border-left: 4px solid #219159;
        }

        .tailor-toast.error {
            border-left: 4px solid #df615b;
        }

        .tailor-toast-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .tailor-toast.success .tailor-toast-icon {
            color: #1f7a4f;
            background: rgba(33, 145, 89, 0.12);
        }

        .tailor-toast.error .tailor-toast-icon {
            color: #df615b;
            background: rgba(223, 97, 91, 0.12);
        }

        .tailor-toast-title {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 0.12rem;
            color: var(--tailor-text);
        }

        .tailor-toast-message {
            color: var(--tailor-muted);
            font-size: 0.8rem;
            line-height: 1.45;
        }

        .tailor-toast-close {
            border: 0;
            background: transparent;
            color: #7f8791;
            line-height: 1;
            padding: 0;
            font-size: 1.05rem;
            margin-left: auto;
        }

        .dashboard-shell.sidebar-collapsed .sidebar-panel {
            padding-inline: 0.55rem;
        }

        .dashboard-shell.sidebar-collapsed .sidebar-brand img {
            width: 46px;
        }

        .dashboard-shell.sidebar-collapsed .dashboard-footer {
            left: 88px;
        }

        .dashboard-shell.sidebar-collapsed .sidebar-section-label,
        .dashboard-shell.sidebar-collapsed .sidebar-link-label {
            display: none;
        }

        .dashboard-shell.sidebar-collapsed .sidebar-link,
        .dashboard-shell.sidebar-collapsed .sidebar-logout-btn {
            justify-content: center;
            padding-inline: 0.8rem;
        }

        @media (max-width: 991.98px) {
            .app-body {
                grid-template-columns: 1fr;
            }

            .sidebar-panel {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1000;
                width: min(82vw, 288px);
                height: 100dvh;
                max-height: none;
                gap: 0.85rem;
                padding: 0.9rem 0.75rem;
                overflow-y: auto;
                transform: translateX(-105%);
                transition: transform 0.24s ease;
            }

            .workspace-content {
                padding: 0.85rem 0.75rem 5.8rem;
            }

            .dashboard-footer {
                left: 0;
            }

            .sidebar-brand {
                min-height: 72px;
                padding: 0.3rem;
            }

            .sidebar-brand img {
                width: 112px;
            }

            .dashboard-shell.sidebar-collapsed .sidebar-brand img {
                width: 112px;
            }

            .sidebar-section-label {
                display: block;
            }

            .sidebar-link-label {
                display: inline;
            }

            .dashboard-shell.sidebar-collapsed .sidebar-section-label {
                display: block;
            }

            .dashboard-shell.sidebar-collapsed .sidebar-link-label {
                display: inline;
            }

            .sidebar-nav {
                display: grid;
                gap: 0.24rem;
            }

            .sidebar-link,
            .sidebar-logout-btn {
                width: 100%;
                min-height: 46px;
                justify-content: flex-start;
                gap: 0.68rem;
                padding: 0.68rem 0.82rem;
                border-radius: 12px;
            }

            .dashboard-shell.sidebar-collapsed .sidebar-link,
            .dashboard-shell.sidebar-collapsed .sidebar-logout-btn {
                justify-content: flex-start;
                padding: 0.68rem 0.82rem;
            }

            .sidebar-link:hover {
                transform: none;
            }

            .sidebar-logout {
                margin-top: auto;
                padding-top: 0.8rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .topbar {
                top: 0;
            }

            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                z-index: 990;
                border: 0;
                padding: 0;
                background: rgba(0, 0, 0, 0.42);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.24s ease;
            }

            .dashboard-shell.mobile-sidebar-open .sidebar-panel {
                transform: translateX(0);
            }

            .dashboard-shell.mobile-sidebar-open .sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }

            body.mobile-sidebar-lock {
                overflow: hidden;
            }
        }

        @media (max-width: 767.98px) {
            :root {
                --dashboard-base-text: 0.86rem;
                --dashboard-button-padding-y: 0.68rem;
                --dashboard-button-padding-x: 0.9rem;
            }

            .topbar {
                min-height: 58px;
                gap: 0.55rem;
                padding: 0.58rem 0.68rem;
                align-items: center;
                flex-wrap: nowrap;
            }

            .topbar-brand {
                flex: 1 1 auto;
                width: auto;
                gap: 0.55rem;
                min-width: 0;
            }

            .topbar-page-title {
                flex: 1;
                text-align: left;
                font-size: 0.92rem;
            }

            .topbar-menu-toggle {
                width: 38px;
                height: 38px;
                flex: 0 0 auto;
            }

            .topbar-actions {
                width: auto;
                justify-content: flex-end;
                gap: 0.42rem;
                flex: 0 0 auto;
            }

            .topbar-language {
                min-height: 38px;
                padding-inline: 0.48rem;
                border-radius: 11px;
            }

            .topbar-language-label {
                display: none;
            }

            .topbar-language-select {
                max-width: 4.9rem;
                font-size: 0.76rem;
            }

            .topbar-alert,
            .topbar-avatar {
                width: 38px;
                height: 38px;
            }

            .topbar-profile-toggle {
                gap: 0.35rem;
            }

            .topbar-profile-copy {
                display: none;
            }

            .topbar-profile-menu {
                right: 0;
                min-width: min(220px, calc(100vw - 1.5rem));
            }

            .dashboard-footer {
                position: static;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.18rem;
                padding: 0.62rem 0.75rem 0.72rem;
                font-size: 0.72rem;
                box-shadow: none;
            }

            .workspace-content {
                padding: 0.72rem 0.62rem 0.85rem;
            }
        }

        @media (max-width: 380px) {
            .topbar {
                padding-inline: 0.55rem;
            }

            .topbar-language {
                display: none;
            }

            .topbar-page-title {
                font-size: 0.86rem;
            }
        }

        @media (min-width: 992px) {
            .sidebar-backdrop {
                display: none;
            }

            html,
            body {
                height: 100%;
            }

            body {
                overflow: hidden;
            }

            .dashboard-shell,
            .app-body {
                height: 100vh;
                min-height: 0;
            }

            .sidebar-panel {
                max-height: 100vh;
                overflow: hidden;
            }

            .workspace-main {
                height: 100vh;
                min-height: 0;
                overflow-y: auto;
                overscroll-behavior: contain;
            }
        }

        @media (max-width: 991.98px) {
            .dashboard-shell > .app-body > .sidebar-panel {
                position: fixed !important;
                top: 0 !important;
                right: auto !important;
                bottom: 0 !important;
                left: 0 !important;
                z-index: 1000 !important;
                width: min(82vw, 288px) !important;
                height: 100dvh !important;
                min-height: 0 !important;
                max-height: none !important;
                display: none !important;
                flex-direction: column !important;
                overflow-y: auto !important;
            }

            .dashboard-shell.mobile-sidebar-open > .app-body > .sidebar-panel {
                display: flex !important;
                transform: none !important;
            }

            .dashboard-shell > .app-body > .sidebar-backdrop {
                display: none !important;
            }

            .dashboard-shell.mobile-sidebar-open > .app-body > .sidebar-backdrop {
                display: block !important;
            }

            .dashboard-shell > .app-body > .workspace-main {
                width: 100% !important;
                min-width: 0 !important;
            }
        }
    </style>
</head>
<body>
    @php
        $activeUser = auth()->user();
        $userInitials = collect(explode(' ', trim($activeUser->name ?? 'User')))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
        $profileRoute = route('admin.users.profile');
        $isDashboardActive = request()->routeIs('dashboard');
        $isOrdersWorkspaceActive = request()->routeIs('admin.orders.*') && !(request()->routeIs('admin.orders.index') && request('view') === 'report');
        $isReportsActive = request()->routeIs('admin.orders.index') && request('view') === 'report';
        $isUsersActive = request()->routeIs('admin.users.*')
            && !request()->routeIs('admin.users.profile')
            && !request()->routeIs('admin.users.profile.edit');
    @endphp

    <div class="dashboard-shell">
        <div class="app-body">
            <aside class="sidebar-panel">
                <div class="sidebar-brand">
                    <img src="{{ asset('images/tailor-logo-sidebar.png') }}" alt="Al Handaam Gents Tailoring">
                </div>

                <div class="sidebar-section-label">Navigation</div>

                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ $isDashboardActive ? 'active' : '' }}">
                        <span class="sidebar-link-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="8" height="8" rx="1.8"></rect>
                                <rect x="13" y="3" width="8" height="8" rx="1.8"></rect>
                                <rect x="3" y="13" width="8" height="8" rx="1.8"></rect>
                                <rect x="13" y="13" width="8" height="8" rx="1.8"></rect>
                            </svg>
                        </span>
                        <span class="sidebar-link-label">Dashboard</span>
                    </a>
                    @if ($activeUser->canAccessOrderWorkspace())
                        <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="sidebar-link {{ $isOrdersWorkspaceActive ? 'active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M8 3h8l4 4v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                                    <path d="M16 3v5h5"></path>
                                    <path d="M9 13h6"></path>
                                    <path d="M9 17h6"></path>
                                </svg>
                            </span>
                            <span class="sidebar-link-label">Tailor Entry</span>
                        </a>
                    @endif
                    <a href="{{ $activeUser->canAccessReports() ? route('admin.orders.index', ['view' => 'report']) : route('dashboard') }}" class="sidebar-link {{ $isReportsActive ? 'active' : '' }}">
                        <span class="sidebar-link-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 20h16"></path>
                                <path d="M7 16V9"></path>
                                <path d="M12 16V5"></path>
                                <path d="M17 16v-4"></path>
                            </svg>
                        </span>
                        <span class="sidebar-link-label">Reports</span>
                    </a>
                    @if ($activeUser->canManageUsers())
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ $isUsersActive ? 'active' : '' }}">
                            <span class="sidebar-link-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path>
                                    <path d="M12 10v4"></path>
                                    <path d="M12 7h.01"></path>
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <path d="M10 17l5-5-5-5"></path>
                                <path d="M15 12H3"></path>
                            </svg>
                        </span>
                        <span class="sidebar-link-label">Logout</span>
                    </button>
                </form>
            </aside>
            <button type="button" class="sidebar-backdrop" data-sidebar-close aria-label="Close sidebar"></button>

            <main class="workspace-main">
                <header class="topbar">
                    <div class="topbar-brand">
                        <button type="button" class="topbar-menu-toggle" data-sidebar-toggle aria-label="Toggle sidebar" aria-expanded="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M4 7h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 17h16"></path>
                            </svg>
                        </button>
                        <h1 class="topbar-page-title">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>

                    <div class="topbar-actions">
                        <label class="topbar-language">
                            <span class="topbar-language-label">Language</span>
                            <select class="topbar-language-select" data-language-select aria-label="Language">
                                <option value="en">English</option>
                                <option value="ar">Arabic</option>
                            </select>
                        </label>

                        <button type="button" class="topbar-alert" aria-label="Notifications">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                                <path d="M10 20a2 2 0 0 0 4 0"></path>
                            </svg>
                            <span class="topbar-alert-badge">2</span>
                        </button>

                        <div class="topbar-profile" data-profile-menu>
                            <button type="button" class="topbar-profile-toggle" data-profile-toggle aria-expanded="false">
                                <div class="topbar-avatar" aria-hidden="true">
                                    <img src="{{ asset('images/tailor-logo-sidebar.png') }}" alt="Al Handaam Gents Tailoring">
                                </div>
                                <div class="topbar-profile-copy">
                                    <div class="topbar-profile-name">{{ $activeUser->name }}</div>
                                    <div class="topbar-profile-role">{{ $activeUser->role }}</div>
                                </div>
                                <span class="topbar-profile-caret" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6"></path>
                                    </svg>
                                </span>
                            </button>
                            <div class="topbar-profile-menu">
                                <a href="{{ $profileRoute }}">
                                    <span class="topbar-profile-menu-label">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                                            <circle cx="12" cy="8" r="4"></circle>
                                        </svg>
                                        <span>View Profile</span>
                                    </span>
                                    <span>›</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">
                                        <span class="topbar-profile-menu-label">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                                <path d="M10 17l5-5-5-5"></path>
                                                <path d="M15 12H3"></path>
                                            </svg>
                                            <span>Logout</span>
                                        </span>
                                        <span>›</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="workspace-content">
                    @yield('content')
                </div>

                <footer class="dashboard-footer">
                    <div>
                        <div class="dashboard-footer-title">Al Handaam Gents Tailoring</div>
                    </div>
                    <div>Copyright {{ now()->year }}</div>
                </footer>
            </main>
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
            const shell = document.querySelector('.dashboard-shell');
            const profileMenu = document.querySelector('[data-profile-menu]');
            const profileToggle = document.querySelector('[data-profile-toggle]');
            const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
            const sidebarClose = document.querySelector('[data-sidebar-close]');
            const languageSelect = document.querySelector('[data-language-select]');
            const mobileSidebarQuery = window.matchMedia('(max-width: 991.98px)');

            if (languageSelect) {
                const savedLanguage = localStorage.getItem('tailor_language') || 'en';
                languageSelect.value = savedLanguage;
                document.documentElement.lang = savedLanguage === 'ar' ? 'ar' : 'en';

                languageSelect.addEventListener('change', () => {
                    const selectedLanguage = languageSelect.value;
                    localStorage.setItem('tailor_language', selectedLanguage);
                    document.documentElement.lang = selectedLanguage === 'ar' ? 'ar' : 'en';
                });
            }

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

            if (profileMenu && profileToggle) {
                profileToggle.addEventListener('click', () => {
                    const isOpen = profileMenu.classList.toggle('is-open');
                    profileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                document.addEventListener('click', (event) => {
                    if (!profileMenu.contains(event.target)) {
                        profileMenu.classList.remove('is-open');
                        profileToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            if (shell && sidebarToggle) {
                const setMobileSidebar = (isOpen) => {
                    shell.classList.toggle('mobile-sidebar-open', isOpen);
                    document.body.classList.toggle('mobile-sidebar-lock', isOpen);
                    sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                };

                if (mobileSidebarQuery.matches) {
                    shell.classList.remove('sidebar-collapsed');
                    setMobileSidebar(false);
                }

                sidebarToggle.addEventListener('click', () => {
                    if (mobileSidebarQuery.matches) {
                        shell.classList.remove('sidebar-collapsed');
                        setMobileSidebar(!shell.classList.contains('mobile-sidebar-open'));
                        return;
                    }

                    const isCollapsed = shell.classList.toggle('sidebar-collapsed');
                    sidebarToggle.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
                });

                sidebarClose?.addEventListener('click', () => setMobileSidebar(false));

                shell.querySelectorAll('.sidebar-link').forEach((link) => {
                    link.addEventListener('click', () => {
                        if (mobileSidebarQuery.matches) {
                            setMobileSidebar(false);
                        }
                    });
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setMobileSidebar(false);
                    }
                });

                mobileSidebarQuery.addEventListener('change', (event) => {
                    if (event.matches) {
                        shell.classList.remove('sidebar-collapsed');
                        setMobileSidebar(false);
                    } else {
                        setMobileSidebar(false);
                        sidebarToggle.setAttribute('aria-expanded', shell.classList.contains('sidebar-collapsed') ? 'false' : 'true');
                    }
                });
            }
        })();
    </script>
</body>
</html>
