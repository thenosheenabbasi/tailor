@extends('layouts.app', ['title' => ($pageMode === 'report' ? 'Report' : 'Tailor Invoice') . ' | Tailor'])

@section('content')
    <style>
        .orders-view {
            position: relative;
            overflow: hidden;
            padding: 0.25rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.2);
            border-radius: 1.6rem;
        }

        .orders-view::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, transparent 0%, rgba(197, 150, 47, 0.85) 20%, rgba(197, 150, 47, 0.45) 50%, transparent 100%),
                linear-gradient(90deg, transparent 0%, rgba(197, 150, 47, 0.7) 28%, rgba(197, 150, 47, 0.35) 56%, transparent 100%),
                linear-gradient(90deg, transparent 0%, rgba(197, 150, 47, 0.5) 32%, rgba(197, 150, 47, 0.28) 60%, transparent 100%);
            background-size: 100% 2px, 100% 2px, 100% 2px;
            background-position: top 18px left 0, top 26px left 0, top 34px left 0;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .orders-view::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(197, 150, 47, 0.12), transparent 22%),
                radial-gradient(circle at left center, rgba(241, 226, 193, 0.22), transparent 24%);
            pointer-events: none;
        }

        .orders-content {
            position: relative;
            z-index: 1;
        }

        .hero-panel,
        .table-card,
        .stat-card,
        .report-toolbar {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(252, 250, 245, 0.97));
            border: 1px solid rgba(200, 155, 44, 0.2);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.85),
                0 16px 36px rgba(17, 17, 17, 0.06);
        }

        .hero-panel {
            border-radius: 1.8rem;
            padding: 1.2rem 1.45rem 1rem;
        }

        .hero-title {
            color: #111111;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .hero-copy {
            color: #222222;
            font-size: 0.92rem;
            max-width: 760px;
        }

        .hero-action .btn-tailor {
            min-width: 230px;
            min-height: 58px;
            border-radius: 10px;
            box-shadow: 0 0 22px rgba(215, 167, 44, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .report-toolbar {
            border-radius: 1rem;
            padding: 1rem 1.1rem;
        }

        .report-toolbar .form-label {
            font-size: 0.78rem;
            margin-bottom: 0.45rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .report-toolbar .form-control,
        .report-toolbar .form-select {
            min-height: 46px;
            border-radius: 0.7rem !important;
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        .report-toolbar .btn {
            border-radius: 0.7rem !important;
            min-height: 44px;
        }

        .report-filter-actions {
            min-width: 132px;
        }

        .report-summary-section {
            display: grid;
            gap: 0.85rem;
        }

        .category-summary-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(252, 250, 245, 0.97));
            border: 1px solid rgba(200, 155, 44, 0.2);
            border-radius: 1.35rem;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.85),
                0 16px 36px rgba(17, 17, 17, 0.06);
            padding: 1rem 1.05rem;
        }

        .category-summary-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .category-summary-toggle-icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(200, 155, 44, 0.24);
            color: #111111;
            background: rgba(255, 252, 245, 0.95);
            flex-shrink: 0;
            transition: transform 0.18s ease;
        }

        .category-summary-toggle[aria-expanded="true"] .category-summary-toggle-icon {
            transform: rotate(180deg);
        }

        .category-summary-body {
            margin-top: 0.8rem;
        }

        .category-summary-title {
            color: #111111;
            font-size: 0.96rem;
            font-weight: 700;
            margin-bottom: 0.12rem;
        }

        .category-summary-copy {
            color: #6b5b3c;
            font-size: 0.82rem;
        }

        .category-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.7rem;
            margin-top: 0.8rem;
        }

        .category-summary-card {
            border-radius: 0.85rem;
            border: 1px solid rgba(200, 155, 44, 0.18);
            background: rgba(255, 252, 245, 0.92);
            padding: 0.8rem 0.85rem;
            min-height: 96px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .category-summary-label {
            color: #111111;
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .category-summary-price {
            color: #6b5b3c;
            font-size: 0.72rem;
            margin-top: 0.28rem;
            font-weight: 600;
        }

        .category-summary-qty {
            color: #b88719;
            font-size: 1.28rem;
            line-height: 1;
            margin-top: 0.45rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .category-summary-footer {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 0.6rem;
            margin-top: 0.55rem;
        }

        .category-summary-meta {
            color: #6b5b3c;
            font-size: 0.54rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .category-summary-amount {
            color: #111111;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .stat-card {
            border-radius: 1.35rem;
            padding: 1rem 1.1rem;
            min-height: 108px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: auto -10% -50% auto;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            filter: blur(10px);
            opacity: 0.18;
            background: var(--stat-glow, rgba(215, 167, 44, 0.22));
            pointer-events: none;
        }

        .stat-card.orders { --stat-glow: rgba(76, 126, 219, 0.24); }
        .stat-card.thobes { --stat-glow: rgba(215, 167, 44, 0.22); }
        .stat-card.revenue { --stat-glow: rgba(31, 157, 104, 0.24); }

        .stat-label {
            color: #222222;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .stat-value {
            color: #111111;
            font-size: clamp(1.8rem, 3vw, 2.45rem);
            line-height: 1;
            margin-top: 0.5rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .stat-value.revenue {
            color: #d7a72c;
            font-size: clamp(1.55rem, 3vw, 2.15rem);
        }

        .table-card {
            border-radius: 1rem;
            padding: 0.85rem 0.4rem 0.4rem;
            background: #ffffff;
            border: 1px solid rgba(193, 153, 80, 0.16);
        }

        .table-card .table-responsive {
            overflow-x: auto;
            padding-bottom: 0.2rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(197, 150, 47, 0.65) rgba(197, 150, 47, 0.08);
        }

        .table-card .table-responsive::-webkit-scrollbar {
            height: 10px;
        }

        .table-card .table-responsive::-webkit-scrollbar-track {
            background: rgba(197, 150, 47, 0.08);
            border-radius: 999px;
        }

        .table-card .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, rgba(215, 167, 44, 0.95), rgba(184, 135, 25, 0.95));
            border-radius: 999px;
        }

        .table-card .table {
            color: #111111;
            margin-bottom: 0;
            min-width: 1120px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-card .table thead th {
            color: #ffffff;
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            background: #111111;
            border-bottom: 1px solid rgba(0, 0, 0, 0.14);
            white-space: nowrap;
            padding: 0.95rem 1rem;
            vertical-align: middle;
        }

        .table-card .table thead th:first-child {
            border-top-left-radius: 0.85rem;
        }

        .table-card .table thead th:last-child {
            border-top-right-radius: 0.85rem;
        }

        .table-card .table tbody td {
            color: #222222;
            padding: 1.05rem 1rem;
            border-bottom: 1px solid rgba(201, 166, 101, 0.22);
            vertical-align: middle;
            background: rgba(255, 252, 245, 0.94);
        }

        .table-card .table tbody tr:hover td {
            background: #fffaf1;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 108px;
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 500;
            border: 1px solid transparent;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .status-pill.pending {
            color: #4c3a1f;
            background: #f8e3be;
            border-color: #efcf96;
            box-shadow: none;
        }

        .status-pill.in-progress {
            color: #35506d;
            background: #dcebfa;
            border-color: #c6dcf4;
            box-shadow: none;
        }

        .status-pill.completed {
            color: #245640;
            background: #dcefe6;
            border-color: #c2e1d2;
            box-shadow: none;
        }

        .amount-cell {
            color: #111111;
            font-weight: 800;
            font-size: 0.98rem;
            white-space: nowrap;
        }

        .date-cell {
            white-space: nowrap;
            min-width: 178px;
        }

        .subdued {
            color: #6b5b3c !important;
        }

        .details-cell {
            min-width: 150px;
        }

        .details-btn {
            min-height: auto;
            padding: 0;
            font-size: 0.95rem;
            font-weight: 700;
            white-space: nowrap;
            color: #b48a4d !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            text-decoration: underline;
            text-underline-offset: 0.18rem;
            box-shadow: none !important;
        }

        .details-btn:hover,
        .details-btn:focus {
            color: #8f682c !important;
            text-decoration-thickness: 2px;
        }

        .actions-cell {
            min-width: 190px;
        }

        .actions-wrap {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex-wrap: nowrap;
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            box-shadow: 0 10px 20px rgba(197, 150, 47, 0.08);
        }

        .action-btn.edit-btn {
            background: rgba(17, 17, 17, 0.95);
            color: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.24);
        }

        .action-btn.edit-btn:hover {
            background: #2a2a2a;
            color: #ffffff;
        }

        .action-btn.hide-btn {
            background: rgba(17, 17, 17, 0.08);
            color: #111111;
            border: 1px solid rgba(17, 17, 17, 0.18);
        }

        .action-btn.hide-btn:hover {
            background: #111111;
            color: #ffffff;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        .print-icon-btn {
            width: 44px;
            height: 44px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px !important;
            color: #111111;
            border-color: rgba(197, 150, 47, 0.32) !important;
            background: linear-gradient(180deg, #ffffff, #fcf7ed);
            box-shadow: 0 0 18px rgba(200, 155, 44, 0.12);
        }

        .print-icon-btn:hover {
            color: #111111 !important;
            background: linear-gradient(135deg, #f0ca65, #ca9828);
            border-color: rgba(197, 150, 47, 0.5) !important;
        }

        .table-card .table tbody td.actions-cell {
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .table-card .table tbody td:first-child,
        .table-card .table tbody td:nth-child(2),
        .table-card .table tbody td:nth-child(3),
        .table-card .table tbody td:nth-child(4) {
            color: #292522;
        }

        .invoice-search {
            margin-bottom: 0.55rem !important;
        }

        .invoice-search .form-control,
        .invoice-search .form-select {
            min-height: 48px;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            border-radius: 4px !important;
        }

        .details-modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1.25rem 2rem;
            z-index: 1070;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s ease;
        }

        .details-modal.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .details-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(17, 17, 17, 0.58);
            backdrop-filter: blur(3px);
        }

        .details-modal-dialog {
            position: relative;
            display: flex;
            flex-direction: column;
            width: min(1140px, 100%);
            max-height: calc(100vh - 3.25rem);
            overflow: hidden;
            border-radius: 1.1rem;
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.12);
            box-shadow: 0 26px 80px rgba(17, 17, 17, 0.22);
        }

        .details-modal-header {
            position: relative;
            padding: 1.9rem 2rem 1rem;
        }

        .details-modal-title {
            margin: 0.2rem 0 0;
            color: #111111;
            font-size: clamp(1.45rem, 2vw, 2rem);
            text-align: center;
        }

        .details-modal-close {
            position: absolute;
            top: 1.9rem;
            right: 2rem;
            width: 46px;
            height: 38px;
            border: 0;
            border-radius: 0.45rem;
            background: #ff5a5f;
            color: #ffffff;
            font-size: 1.4rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .details-modal-close:hover {
            background: #eb4348;
            color: #ffffff;
        }

        .details-modal-body {
            flex: 1 1 auto;
            min-height: 0;
            padding: 0 2rem 3rem;
            overflow-y: auto;
        }

        .details-hero {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            align-items: start;
            margin-bottom: 1.4rem;
            width: 100%;
        }

        .details-hero-side {
            min-width: 0;
        }

        .details-hero-label {
            display: block;
            color: #111111;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .details-hero-value {
            color: #222222;
            font-size: 0.92rem;
        }

        .details-hero-center {
            text-align: center;
            min-width: 0;
            align-self: center;
        }

        .details-grid {
            display: grid;
            gap: 1.35rem;
        }

        .details-section {
            border: 1px solid rgba(17, 17, 17, 0.18);
            border-radius: 0;
            overflow: hidden;
            background: #ffffff;
        }

        .details-section-title {
            padding: 0.7rem 0.9rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.18);
            font-size: 0.86rem;
            font-weight: 700;
            color: #111111;
            background: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .details-section-body {
            padding: 0.9rem 0 1.1rem;
        }

        .details-info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem 2rem;
            padding: 0 0.9rem;
        }

        .details-info-grid.two-col {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .details-item-label {
            display: block;
            color: #3b3020;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .details-item-value {
            color: #222222;
            font-size: 0.96rem;
            line-height: 1.5;
            word-break: break-word;
        }

        .details-note {
            padding: 0 0.9rem;
            color: #222222;
            font-size: 0.98rem;
            line-height: 1.65;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .details-status-form {
            padding: 0 0.9rem 1.1rem;
        }

        .details-status-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-bottom: 0.35rem;
            flex-wrap: wrap;
        }

        .details-status-form .form-select {
            min-height: 52px;
            border-radius: 0 !important;
        }

        .details-update-btn {
            min-width: 170px;
            min-height: 46px;
        }

        .details-close-btn {
            min-width: 110px;
            min-height: 46px;
        }

        body.modal-open {
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            .actions-wrap {
                flex-wrap: wrap;
            }

            .actions-cell {
                min-width: 240px;
            }

            .details-hero-center {
                text-align: center;
            }

            .details-info-grid,
            .details-info-grid.two-col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .details-modal-header,
            .details-modal-body {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .details-modal {
                padding-bottom: 1.5rem;
            }

            .details-modal-dialog {
                max-height: calc(100vh - 2.5rem);
            }

            .details-modal-close {
                right: 1rem;
            }

            .details-info-grid,
            .details-info-grid.two-col {
                grid-template-columns: 1fr;
            }

            .details-status-actions {
                justify-content: stretch;
            }

            .details-status-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="orders-view">
        <div class="orders-content">
            <div class="hero-panel mb-4">
                <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-start gap-4">
                    <div>
                        <h2 class="hero-title">{{ $pageMode === 'report' ? 'Reports' : 'Tailor Invoice Overview' }}</h2>
                        <p class="hero-copy mb-0">
                            @if ($pageMode === 'report')
                                Monthly and tailor performance reports with filterable summaries and clean exports.
                            @else
                                Track all invoices, payments, and current status in one place.
                            @endif
                        </p>
                    </div>

                    <div class="hero-action d-flex gap-2 flex-wrap">
                        @if ($pageMode === 'report')
                            <a href="{{ route('admin.orders.index', array_merge($filters, ['export' => 'pdf'])) }}" class="btn btn-tailor px-4">Download PDF</a>
                        @else
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-tailor px-4">Create New Invoice</a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($pageMode === 'report')
                <div class="report-toolbar mb-4">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                        <input type="hidden" name="view" value="report">
                        @if ($canFilterTailors)
                            <div class="col-12 col-md-6 col-xl">
                                <label for="assigned_user_id" class="form-label">Tailor Wise</label>
                                <select id="assigned_user_id" name="assigned_user_id" class="form-select rounded-4">
                                    <option value="">All Tailors</option>
                                    @foreach ($assignableUsers as $assignableUser)
                                        <option value="{{ $assignableUser->id }}" @selected($filters['assigned_user_id'] !== '' && (int) $filters['assigned_user_id'] === $assignableUser->id)>
                                            {{ $assignableUser->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="assigned_user_id" value="{{ $filters['assigned_user_id'] }}">
                        @endif

                        <div class="col-12 col-md-6 col-xl">
                            <label for="thobe_category" class="form-label">Category Wise</label>
                            <select id="thobe_category" name="thobe_category" class="form-select rounded-4">
                                <option value="">All Categories</option>
                                @foreach ($categories as $categoryValue => $category)
                                    <option value="{{ $categoryValue }}" @selected($filters['thobe_category'] === $categoryValue)>
                                        {{ $category['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <label for="invoice_number" class="form-label">Invoice Number Wise</label>
                            <input type="text" id="invoice_number" name="invoice_number" value="{{ $filters['invoice_number'] }}" class="form-control rounded-4" placeholder="Invoice number">
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <label for="fatora_number" class="form-label">Fatora Number</label>
                            <input type="text" id="fatora_number" name="fatora_number" value="{{ $filters['fatora_number'] }}" class="form-control rounded-4" placeholder="Fatora number">
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-12 col-md-6 col-xl-auto d-grid gap-2 report-filter-actions">
                            <button type="submit" class="btn btn-tailor rounded-4">Filter</button>
                            <a href="{{ route('admin.orders.index', ['view' => 'report']) }}" class="btn btn-outline-secondary rounded-4">Reset</a>
                        </div>
                    </form>
                </div>

                @if ($hasActiveReportFilters)
                    <div class="report-summary-section mb-4">
                        <div class="category-summary-panel">
                            <div class="category-summary-body" id="category-summary-body">
                                <div class="mb-3">
                                    <h3 class="category-summary-title mb-1">
                                        {{ $filters['thobe_category'] === '' ? 'All Categories Summary' : 'Selected Category Summary' }}
                                    </h3>
                                    <p class="category-summary-copy mb-0">
                                        Quantity and amount are calculated based on the current report filters.
                                    </p>
                                </div>
                                <div class="category-summary-grid">
                                    @foreach ($reportCategorySummaries as $summary)
                                        <div class="category-summary-card" data-category-summary="{{ $summary['key'] }}">
                                            <div class="category-summary-label">{{ $summary['label'] }}</div>
                                            <div class="category-summary-price">Single Price: {{ number_format($summary['unit_price'], 2) }} QAR</div>
                                            <div class="category-summary-qty" data-category-quantity="{{ $summary['quantity'] }}">{{ $summary['quantity'] }}</div>
                                            <div class="category-summary-footer">
                                                <div class="category-summary-meta">Total Thobes</div>
                                                <div class="category-summary-amount" data-category-amount="{{ number_format($summary['amount'], 2, '.', '') }}">{{ number_format($summary['amount'], 2) }} QAR</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endif

            <div class="table-card">
                @if ($pageMode !== 'report')
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end invoice-search" id="invoice-search-form">
                        <input type="hidden" name="view" value="invoices">
                        <div class="col-12 col-xl-5 ms-xl-auto">
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ $filters['search'] }}"
                                class="form-control rounded-4"
                                placeholder="Search Here...">
                        </div>
                    </form>
                @endif

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Fatora #</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>View Details</th>
                                @if ($canManageSettings && $pageMode !== 'report')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $detailPayload = [
                                        'invoice_number' => $order->invoice_number,
                                        'fatora_number' => $order->fatora_number ?: 'N/A',
                                        'category' => $order->category_label,
                                        'quantity' => (string) $order->quantity,
                                        'order_date' => $order->order_date->format('d M Y h:i A'),
                                        'unit_price' => number_format((float) $order->unit_price, 2) . ' QAR',
                                        'total_amount' => number_format((float) $order->total_price, 2) . ' QAR',
                                        'status' => $order->status_label,
                                        'status_value' => $order->status,
                                        'completed_at' => optional($order->completed_at)->format('d M Y h:i A') ?: 'Not completed yet',
                                        'assigned_tailor' => $order->assignedUser?->name ?? 'Not assigned',
                                        'added_by' => $order->creator?->name ?? 'N/A',
                                        'note' => $order->note ?: 'No note added',
                                        'update_status_url' => route('admin.orders.update-status', $order),
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->fatora_number ?: 'N/A' }}</td>
                                    <td>{{ $order->category_label }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="date-cell">{{ $order->order_date->format('d M Y h:i A') }}</td>
                                    <td>
                                        <span class="status-pill {{ $order->status === \App\Models\TailorOrder::STATUS_COMPLETED ? 'completed' : ($order->status === \App\Models\TailorOrder::STATUS_IN_PROGRESS ? 'in-progress' : 'pending') }}">
                                            {{ $order->status_label }}
                                        </span>
                                        @if ($order->completed_at)
                                            <div class="small subdued mt-2">{{ $order->completed_at->format('d M Y h:i A') }}</div>
                                        @endif
                                    </td>
                                    <td class="amount-cell">{{ number_format($order->total_price, 2) }} QAR</td>
                                    <td class="details-cell">
                                        <button
                                            type="button"
                                            class="btn btn-outline-dark details-btn view-details-btn"
                                            data-order="{{ json_encode($detailPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}">
                                            View Details
                                        </button>
                                    </td>
                                    @if ($canManageSettings && $pageMode !== 'report')
                                        <td class="actions-cell">
                                            <div class="actions-wrap">
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn action-btn edit-btn" title="Edit Invoice" aria-label="Edit Invoice">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M12 20h9"/>
                                                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="delete-order-form" data-invoice-number="{{ $order->invoice_number }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn action-btn hide-btn" title="Delete Invoice" aria-label="Delete Invoice">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path d="M3 6h18"/>
                                                            <path d="M8 6V4h8v2"/>
                                                            <path d="M19 6l-1 14H6L5 6"/>
                                                            <path d="M10 11v6"/>
                                                            <path d="M14 11v6"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.orders.receipt', $order) }}" class="btn btn-sm btn-outline-secondary rounded-circle print-icon-btn" target="_blank" rel="noopener" aria-label="Print Receipt" title="Print Receipt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M6 9V4h12v5"/>
                                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                                        <path d="M6 14h12v6H6z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canManageSettings && $pageMode !== 'report' ? 9 : 8 }}" class="text-center py-5 subdued">No invoices have been added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="details-modal" id="order-details-modal" hidden>
        <div class="details-modal-backdrop" data-close-details-modal></div>
        <div class="details-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="order-details-title">
            <div class="details-modal-header">
                <div class="details-hero">
                    <div class="details-hero-side">
                        <span class="details-hero-label">Start Date & Time:</span>
                        <div class="details-hero-value" data-detail="order_date"></div>
                    </div>
                    <div class="details-hero-center">
                        <h3 class="details-modal-title" id="order-details-title">Invoice Details</h3>
                    </div>
                </div>
                <button type="button" class="details-modal-close" aria-label="Close details modal" data-close-details-modal>&times;</button>
            </div>
            <div class="details-modal-body">
                <div class="details-grid">
                    <section class="details-section">
                        <div class="details-section-title">Invoice Details</div>
                        <div class="details-section-body">
                            <div class="details-info-grid">
                                <div>
                                    <span class="details-item-label">Invoice #:</span>
                                    <div class="details-item-value" data-detail="invoice_number"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Fatora #:</span>
                                    <div class="details-item-value" data-detail="fatora_number"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Category:</span>
                                    <div class="details-item-value" data-detail="category"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Quantity:</span>
                                    <div class="details-item-value" data-detail="quantity"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Unit Price:</span>
                                    <div class="details-item-value" data-detail="unit_price"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Total Amount:</span>
                                    <div class="details-item-value" data-detail="total_amount"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">People Info</div>
                        <div class="details-section-body">
                            <div class="details-info-grid">
                                <div>
                                    <span class="details-item-label">Assigned Tailor:</span>
                                    <div class="details-item-value" data-detail="assigned_tailor"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Added By:</span>
                                    <div class="details-item-value" data-detail="added_by"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Completed At:</span>
                                    <div class="details-item-value" data-detail="completed_at"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">Other Info</div>
                        <div class="details-section-body">
                            <div class="details-info-grid two-col">
                                <div>
                                    <span class="details-item-label">Current Status:</span>
                                    <div class="details-item-value" data-detail="status"></div>
                                </div>
                                <div>
                                    <span class="details-item-label">Order Date & Time:</span>
                                    <div class="details-item-value" data-detail="order_date"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="details-section">
                        <div class="details-section-title">Main Note</div>
                        <div class="details-section-body">
                            <div class="details-note" data-detail="note"></div>
                        </div>
                    </section>

                    @if ($canManageSettings)
                        <section class="details-section">
                            <div class="details-section-title">Status Update</div>
                            <div class="details-section-body">
                                <form method="POST" class="details-status-form" id="modal-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" id="modal-status-select" class="form-select" aria-label="Status">
                                        @foreach (\App\Models\TailorOrder::statuses() as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}">{{ $statusLabel }}</option>
                                        @endforeach
                                    </select>

                                    <div class="details-status-actions">
                                        <button type="submit" class="btn btn-tailor details-update-btn">Update Status</button>
                                        <button type="button" class="btn btn-outline-secondary details-close-btn" data-close-details-modal>Close</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @else
                        <div class="details-status-actions">
                            <button type="button" class="btn btn-outline-secondary details-close-btn" data-close-details-modal>Close</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const detailsModal = document.getElementById('order-details-modal');
            const detailButtons = document.querySelectorAll('.view-details-btn');
            const closeDetailsButtons = document.querySelectorAll('[data-close-details-modal]');
            const detailsTitle = document.getElementById('order-details-title');
            const modalStatusForm = document.getElementById('modal-status-form');
            const modalStatusSelect = document.getElementById('modal-status-select');
            const detailFields = detailsModal
                ? Array.from(detailsModal.querySelectorAll('[data-detail]')).reduce((fields, field) => {
                    const key = field.dataset.detail;

                    fields[key] = fields[key] || [];
                    fields[key].push(field);

                    return fields;
                }, {})
                : {};

            const closeDetailsModal = () => {
                if (!detailsModal) {
                    return;
                }

                detailsModal.classList.remove('is-open');
                document.body.classList.remove('modal-open');
                window.setTimeout(() => {
                    detailsModal.hidden = true;
                }, 180);
            };

            const openDetailsModal = (order) => {
                if (!detailsModal) {
                    return;
                }

                if (detailsTitle) {
                    detailsTitle.textContent = `Invoice Details - ${order.invoice_number ?? ''}`;
                }

                Object.entries(detailFields).forEach(([key, fields]) => {
                    fields.forEach((field) => {
                        field.textContent = order[key] ?? 'N/A';
                    });
                });

                if (modalStatusForm && order.update_status_url) {
                    modalStatusForm.action = order.update_status_url;
                }

                if (modalStatusSelect && order.status_value) {
                    modalStatusSelect.value = order.status_value;
                }

                detailsModal.hidden = false;
                document.body.classList.add('modal-open');
                window.requestAnimationFrame(() => detailsModal.classList.add('is-open'));
            };

            detailButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    openDetailsModal(JSON.parse(button.dataset.order || '{}'));
                });
            });

            closeDetailsButtons.forEach((button) => {
                button.addEventListener('click', closeDetailsModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && detailsModal && !detailsModal.hidden) {
                    closeDetailsModal();
                }
            });
        })();
    </script>

    @if ($pageMode !== 'report')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            (() => {
                const form = document.getElementById('invoice-search-form');
                const searchInput = document.getElementById('search');
                const deleteForms = document.querySelectorAll('.delete-order-form');

                if (form && searchInput) {
                    let timeoutId;

                    searchInput.addEventListener('input', () => {
                        window.clearTimeout(timeoutId);
                        timeoutId = window.setTimeout(() => form.submit(), 350);
                    });
                }

                deleteForms.forEach((deleteForm) => {
                    deleteForm.addEventListener('submit', (event) => {
                        event.preventDefault();

                        const invoiceNumber = deleteForm.dataset.invoiceNumber || 'this invoice';

                        Swal.fire({
                            title: 'Delete invoice?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#c5962f',
                            cancelButtonColor: '#6c757d',
                            background: '#fffdfa',
                            color: '#1f1a17',
                            text: invoiceNumber,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteForm.submit();
                            }
                        });
                    });
                });
            })();
        </script>
    @endif
@endsection
