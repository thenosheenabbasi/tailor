@php
    $logoPath = public_path('images/tailor-logo.png');
    $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $selectedUserName = $selectedAssignedUser?->name ?? 'All Users';
    $selectedCategoryName = $filters['thobe_category'] !== '' ? (\App\Models\TailorOrder::categories()[$filters['thobe_category']]['label'] ?? ucwords(str_replace('_', ' ', $filters['thobe_category']))) : 'All Categories';
    $selectedFatoraNumber = $filters['fatora_number'] !== '' ? $filters['fatora_number'] : 'All';
    $fromDate = $filters['date_from'] !== '' ? \Illuminate\Support\Carbon::parse($filters['date_from'])->format('d F Y') : 'All Dates';
    $toDate = $filters['date_to'] !== '' ? \Illuminate\Support\Carbon::parse($filters['date_to'])->format('d F Y') : 'Today';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tailor Report PDF</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f1a17;
            font-size: 12px;
            margin: 0;
            background: #ffffff;
        }

        .page {
            padding: 18px 22px 0;
            background: #ffffff;
        }

        .sheet {
            background: #fffdfa;
            border: 1px solid #dcc492;
            border-radius: 24px;
            padding: 18px 22px 10px;
            position: relative;
            overflow: hidden;
        }

        .top-wave {
            height: 30px;
            margin: -2px -22px 10px;
            position: relative;
        }

        .top-wave-line {
            position: absolute;
            left: -26px;
            right: -26px;
            height: 1px;
            background: #c7a24a;
            transform-origin: left center;
        }

        .top-wave-1 { top: 4px; transform: rotate(-0.3deg); }
        .top-wave-2 { top: 9px; transform: rotate(0.6deg); opacity: 0.9; }
        .top-wave-3 { top: 14px; transform: rotate(-1deg); opacity: 0.82; }
        .top-wave-4 { top: 19px; transform: rotate(-1.7deg); opacity: 0.74; }
        .top-wave-5 { top: 24px; transform: rotate(-2.3deg); opacity: 0.66; }

        .brand-table,
        .summary-table,
        .report-table,
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-left {
            width: 26%;
            vertical-align: middle;
        }

        .brand-center {
            width: 48%;
            vertical-align: middle;
            text-align: center;
            padding: 0 18px 0;
        }

        .brand-right {
            width: 26%;
            vertical-align: top;
            text-align: right;
        }

        .logo {
            max-width: 190px;
            max-height: 72px;
        }

        .company-title {
            margin: 0;
            font-size: 18px;
            line-height: 1.02;
            font-weight: bold;
            text-transform: uppercase;
            color: #be8f22;
            white-space: nowrap;
        }

        .brand-title {
            margin: 8px 0 2px;
            font-size: 17px;
            font-weight: bold;
            color: #1d1d1d;
        }

        .meta-stack {
            margin-top: 2px;
        }

        .meta-row {
            font-size: 12px;
            color: #2a2118;
            margin-bottom: 8px;
            white-space: nowrap;
        }

        .meta-key {
            font-weight: bold;
            color: #2a2118;
        }

        .meta-separator {
            padding: 0 6px;
            font-weight: bold;
            color: #7d6324;
        }

        .meta-value {
            font-weight: bold;
            color: #2a2118;
        }

        .summary-table {
            margin-top: 18px;
            border: 1px solid #b7903d;
            background: #120f0c;
            color: #ffffff;
        }

        .summary-table td {
            background: #120f0c;
            padding: 10px 16px;
            font-size: 11px;
            border-right: 1px solid #b48a2c;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #ffffff;
            display: block;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
        }

        .range-line {
            margin-top: 1px;
            text-align: center;
            font-size: 11px;
            color: #5c5045;
            line-height: 1.1;
        }

        .report-wrap {
            margin-top: 16px;
        }

        .report-table thead th {
            padding: 11px 12px 12px;
            font-size: 11px;
            text-align: left;
            color: #2a1f16;
            border-bottom: 1px solid #dcc492;
            font-weight: bold;
        }

        .report-table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #e6d5b7;
            vertical-align: top;
            font-size: 11px;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        .report-table tbody tr:nth-child(odd) {
            background: #fffdfa;
        }

        .amount {
            font-weight: bold;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
        }

        .status-pending {
            background: #d8ab21;
            color: #23170f;
        }

        .status-in-progress {
            background: #3e7ec9;
        }

        .status-completed {
            background: #27925d;
        }

        .completed-at {
            display: block;
            margin-top: 7px;
            color: #6d6257;
            font-size: 10px;
        }

        .empty {
            text-align: center;
            color: #7d6d5d;
            padding: 28px 0;
        }

        .footer-note {
            margin-top: 12px;
            padding-top: 8px;
            padding-bottom: 0;
            border-top: 1px solid #eadbbb;
            font-size: 10px;
            color: #6f6356;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="sheet">
            <div class="top-wave">
                <div class="top-wave-line top-wave-1"></div>
                <div class="top-wave-line top-wave-2"></div>
                <div class="top-wave-line top-wave-3"></div>
                <div class="top-wave-line top-wave-4"></div>
                <div class="top-wave-line top-wave-5"></div>
            </div>

            <table class="brand-table">
                <tr>
                    <td class="brand-left">
                        @if ($logoData)
                            <img src="{{ $logoData }}" alt="Tailor Logo" class="logo">
                        @endif
                    </td>
                    <td class="brand-center">
                        <h1 class="company-title">AL-HINDAM GENTS TAILORING</h1>
                        <div class="brand-title">Invoice Summary Report</div>
                        <div class="range-line">From {{ $fromDate }} To {{ $toDate }}</div>
                    </td>
                    <td class="brand-right">
                        <div class="meta-stack">
                              <div class="meta-row">
                                <span class="meta-key">DATE</span><span class="meta-separator">:</span><span class="meta-value">{{ $generatedAt->format('d F Y') }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-key">TAILOR NAME</span><span class="meta-separator">:</span><span class="meta-value">{{ $selectedUserName }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-key">CATEGORY</span><span class="meta-separator">:</span><span class="meta-value">{{ $selectedCategoryName }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-key">FATORA NUMBER</span><span class="meta-separator">:</span><span class="meta-value">{{ $selectedFatoraNumber }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="summary-table" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <span class="summary-label">Total Invoices</span>
                        <span class="summary-value">{{ $reportStats['orders'] }}</span>
                    </td>
                    <td>
                        <span class="summary-label">Total Revenue</span>
                        <span class="summary-value">{{ number_format($reportStats['revenue'], 2) }} QAR</span>
                    </td>
                    <td>
                        <span class="summary-label">Pending</span>
                        <span class="summary-value">{{ $orders->where('status', \App\Models\TailorOrder::STATUS_PENDING)->count() }}</span>
                    </td>
                    <td>
                        <span class="summary-label">Completed</span>
                        <span class="summary-value">{{ $orders->where('status', \App\Models\TailorOrder::STATUS_COMPLETED)->count() }}</span>
                    </td>
                </tr>
            </table>

            <div class="report-wrap">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Fatora #</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Main Note</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Assigned Tailor</th>
                            <th>Status</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->invoice_number }}</td>
                                <td>{{ $order->fatora_number ?: 'N/A' }}</td>
                                <td>{{ $order->category_label }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($order->note ?: 'No note added', 70) }}</td>
                                <td>{{ $order->order_date->format('d M Y h:i A') }}</td>
                                <td class="amount">{{ number_format((float) $order->total_price, 2) }} QAR</td>
                                <td>{{ $order->assignedUser?->name ?? 'Not assigned' }}</td>
                                  <td>
                                    <span class="status-badge {{ $order->status === \App\Models\TailorOrder::STATUS_COMPLETED ? 'status-completed' : ($order->status === \App\Models\TailorOrder::STATUS_IN_PROGRESS ? 'status-in-progress' : 'status-pending') }}">
                                        {{ $order->status_label }}
                                    </span>
                                    @if ($order->completed_at)
                                        <span class="completed-at">{{ $order->completed_at->format('d M Y h:i A') }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->creator?->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty">No report data found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="footer-note">
                Report generated by {{ $orders->first()?->creator?->name ?? 'System' }} on {{ $generatedAt->format('d F Y h:i A') }}.
            </div>
        </div>
    </div>
</body>
</html>
