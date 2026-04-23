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
            padding: 2rem 2rem 1.6rem;
        }

        .hero-title {
            color: #111111;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 0.98;
            margin-bottom: 0.45rem;
        }

        .hero-copy {
            color: #222222;
            font-size: 1rem;
            max-width: 900px;
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
            border-radius: 1.5rem;
            padding: 1.35rem;
        }

        .report-toolbar .form-control,
        .report-toolbar .form-select {
            min-height: 52px;
        }

        .stat-card {
            border-radius: 1.35rem;
            padding: 1.25rem 1.35rem;
            min-height: 140px;
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
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .stat-value {
            color: #111111;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
            margin-top: 0.75rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .stat-value.revenue {
            color: #d7a72c;
            font-size: clamp(1.9rem, 4vw, 2.7rem);
        }

        .table-card {
            border-radius: 1.6rem;
            padding: 1.3rem;
        }

        .table-card .table-responsive {
            overflow-x: auto;
            padding-bottom: 0.35rem;
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
            min-width: 1320px;
        }

        .table-card .table thead th {
            color: #3b3020;
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            border-bottom-color: rgba(197, 150, 47, 0.16);
            white-space: nowrap;
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .table-card .table tbody td {
            color: #222222;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
            border-bottom-color: rgba(197, 150, 47, 0.12);
            vertical-align: middle;
        }

        .table-card .table tbody tr:hover td {
            background: rgba(215, 167, 44, 0.04);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.86rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .status-pill.pending {
            color: #111111;
            background: rgba(215, 167, 44, 0.18);
            border-color: rgba(215, 167, 44, 0.26);
            box-shadow: 0 0 18px rgba(215, 167, 44, 0.18);
        }

        .status-pill.in-progress {
            color: #111111;
            background: rgba(63, 139, 214, 0.16);
            border-color: rgba(63, 139, 214, 0.22);
            box-shadow: 0 0 18px rgba(63, 139, 214, 0.18);
        }

        .status-pill.completed {
            color: #111111;
            background: rgba(31, 157, 104, 0.16);
            border-color: rgba(31, 157, 104, 0.22);
            box-shadow: 0 0 18px rgba(31, 157, 104, 0.18);
        }

        .amount-cell {
            color: #111111;
            font-weight: 700;
            white-space: nowrap;
        }

        .date-cell {
            white-space: nowrap;
            min-width: 145px;
        }

        .subdued {
            color: #6b5b3c !important;
        }

        .actions-cell {
            min-width: 290px;
        }

        .actions-wrap {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex-wrap: nowrap;
        }

        .actions-wrap .status-update-form {
            min-width: 130px;
            margin: 0;
        }

        .actions-wrap .form-select {
            min-height: 52px;
            font-size: 0.88rem;
            border-radius: 1.2rem !important;
            border-color: rgba(197, 150, 47, 0.26);
            background-color: rgba(255, 252, 246, 0.98);
            padding-left: 1rem;
            padding-right: 2.2rem;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.8),
                0 8px 18px rgba(197, 150, 47, 0.06);
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

        .invoice-search {
            max-width: 340px;
            margin-left: auto;
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
                        @if ($pageMode === 'report' && $canManageSettings)
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
                        <div class="col-md-3">
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

                        <div class="col-md-2">
                            <label for="invoice_number" class="form-label">Invoice Number Wise</label>
                            <input type="text" id="invoice_number" name="invoice_number" value="{{ $filters['invoice_number'] }}" class="form-control rounded-4" placeholder="Invoice number">
                        </div>

                        <div class="col-md-2">
                            <label for="fatora_number" class="form-label">Fatora Number</label>
                            <input type="text" id="fatora_number" name="fatora_number" value="{{ $filters['fatora_number'] }}" class="form-control rounded-4" placeholder="Fatora number">
                        </div>

                        <div class="col-md-2">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-md-2">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}" class="form-control rounded-4">
                        </div>

                        <div class="col-md-1 d-grid gap-2">
                            <button type="submit" class="btn btn-tailor rounded-4">Filter</button>
                            <a href="{{ route('admin.orders.index', ['view' => 'report']) }}" class="btn btn-outline-secondary rounded-4">Reset</a>
                        </div>
                    </form>
                </div>

            @endif

            <div class="table-card">
                @if ($pageMode !== 'report')
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="invoice-search mb-4" id="invoice-search-form">
                        <input type="hidden" name="view" value="invoices">
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] }}"
                            class="form-control rounded-4"
                            placeholder="Search Here...">
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
                                <th>Assigned Tailor</th>
                                <th>Added By</th>
                                @if ($canManageSettings && $pageMode !== 'report')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->fatora_number ?: 'N/A' }}</td>
                                    <td>{{ $order->category_label }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="date-cell">{{ $order->order_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="status-pill {{ $order->status === \App\Models\TailorOrder::STATUS_COMPLETED ? 'completed' : ($order->status === \App\Models\TailorOrder::STATUS_IN_PROGRESS ? 'in-progress' : 'pending') }}">
                                            {{ $order->status_label }}
                                        </span>
                                        @if ($order->completed_at)
                                            <div class="small subdued mt-2">{{ $order->completed_at->format('d M Y h:i A') }}</div>
                                        @endif
                                    </td>
                                    <td class="amount-cell">{{ number_format($order->total_price, 2) }} QAR</td>
                                    <td>{{ $order->assignedUser?->name ?? 'Not assigned' }}</td>
                                    <td>{{ $order->creator?->name ?? 'N/A' }}</td>
                                    @if ($canManageSettings && $pageMode !== 'report')
                                        <td class="actions-cell">
                                            <div class="actions-wrap">
                                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="status-update-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm rounded-4" onchange="this.form.submit()">
                                                        @foreach (\App\Models\TailorOrder::statuses() as $statusValue => $statusLabel)
                                                            <option value="{{ $statusValue }}" @selected($order->status === $statusValue)>{{ $statusLabel }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
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
                                    <td colspan="{{ $canManageSettings && $pageMode !== 'report' ? 10 : 9 }}" class="text-center py-5 subdued">No invoices have been added yet.</td>
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
