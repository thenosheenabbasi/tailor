@php
    $logoPath = public_path('images/tailor-logo.png');
    $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $completedAt = $order->completed_at?->format('d M Y h:i A') ?? 'Pending';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailor Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f1a17;
            margin: 0;
            background: #f7f1e6;
        }

        .page {
            max-width: 1180px;
            margin: 18px auto;
            padding: 16px;
            background: #f7f1e6;
        }

        .sheet {
            background: #fffdfa;
            border: 1px solid #dcc492;
            border-radius: 24px;
            padding: 18px 22px 20px;
            overflow: hidden;
        }

        .top-wave {
            height: 30px;
            margin: -2px -22px 14px;
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

        .actions {
            text-align: right;
            margin-bottom: 10px;
        }

        .print-btn {
            border: 1px solid #c5962f;
            background: linear-gradient(135deg, #e7c877, #c5962f);
            color: #111111;
            padding: 11px 22px;
            border-radius: 999px;
            font-weight: bold;
            cursor: pointer;
        }

        .brand-table,
        .info-table,
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-left {
            width: 24%;
            vertical-align: middle;
        }

        .brand-center {
            width: 52%;
            vertical-align: middle;
            text-align: center;
            padding: 0 18px;
        }

        .brand-right {
            width: 24%;
            vertical-align: top;
            text-align: right;
        }

        .logo {
            max-width: 180px;
            max-height: 68px;
        }

        .company-title {
            margin: 0;
            font-size: 18px;
            line-height: 1.05;
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

        .brand-copy {
            font-size: 11px;
            color: #5c5045;
        }

        .meta-row {
            font-size: 12px;
            color: #2a2118;
            margin-bottom: 8px;
            white-space: nowrap;
        }

        .meta-key,
        .meta-value {
            font-weight: bold;
        }

        .meta-separator {
            padding: 0 6px;
            font-weight: bold;
            color: #7d6324;
        }

        .summary-table {
            margin-top: 18px;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #b7903d;
            background: #120f0c;
            color: #ffffff;
        }

        .summary-table td {
            background: #120f0c;
            padding: 10px 16px;
            font-size: 11px;
            border-right: 1px solid #b48a2c;
            vertical-align: top;
        }

        .summary-table td:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #ffffff;
            display: block;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
        }

        .info-card {
            margin-top: 16px;
            border: 1px solid #dcc492;
            border-radius: 18px;
            overflow: hidden;
            background: #fffdfa;
        }

        .info-table td {
            width: 50%;
            padding: 16px 22px;
            vertical-align: top;
        }

        .info-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #9a7423;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 15px;
            font-weight: bold;
            color: #171411;
        }

        .detail-wrap {
            margin-top: 16px;
            border: 1px solid #dcc492;
            border-radius: 18px;
            overflow: hidden;
            background: #fffdfa;
        }

        .detail-table thead th {
            background: #120f0c;
            color: #ffffff;
            padding: 12px 14px;
            font-size: 11px;
            text-align: left;
            text-transform: uppercase;
            border-right: 1px solid #b48a2c;
        }

        .detail-table thead th:last-child {
            border-right: none;
        }

        .detail-table tbody td {
            padding: 14px;
            border-bottom: 1px solid #eadbbb;
            font-size: 11px;
        }

        .detail-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        .detail-table tbody tr:nth-child(odd) {
            background: #fffdfa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
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

        .total-line {
            margin-top: 16px;
            text-align: right;
            font-size: 19px;
            font-weight: bold;
            color: #1d1d1d;
        }

        .total-line span {
            color: #be8f22;
        }

        .note {
            margin-top: 14px;
            font-size: 11px;
            color: #6d6257;
            line-height: 1.55;
        }

        .footer-note {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #eadbbb;
            font-size: 10px;
            color: #6f6356;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .page {
                max-width: none;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .sheet {
                border-radius: 0;
                border: none;
                padding: 0;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="sheet">
            <div class="actions">
                <button type="button" class="print-btn" onclick="window.print()">Print Receipt</button>
            </div>

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
                        <div class="brand-title">Tailor Delivery Receipt</div>
                        <div class="brand-copy">Receipt preview in the same white report style.</div>
                    </td>
                    <td class="brand-right">
                        <div class="meta-row">
                            <span class="meta-key">DATE</span><span class="meta-separator">:</span><span class="meta-value">{{ $generatedAt->format('d F Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key">TAILOR NAME</span><span class="meta-separator">:</span><span class="meta-value">{{ $order->assignedUser?->name ?? $order->tailor_name }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key">INVOICE #</span><span class="meta-separator">:</span><span class="meta-value">{{ $order->invoice_number }}</span>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="summary-table" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <span class="summary-label">Invoice Number</span>
                        <span class="summary-value">{{ $order->invoice_number }}</span>
                    </td>
                    <td>
                        <span class="summary-label">Total Amount</span>
                        <span class="summary-value">{{ number_format((float) $order->total_price, 2) }} QAR</span>
                    </td>
                    <td>
                        <span class="summary-label">Status</span>
                        <span class="summary-value">{{ $order->status_label }}</span>
                    </td>
                    <td>
                        <span class="summary-label">Quantity</span>
                        <span class="summary-value">{{ $order->quantity }}</span>
                    </td>
                </tr>
            </table>

            <div class="info-card">
                <table class="info-table">
                    <tr>
                        <td>
                            <span class="info-label">Assigned Tailor</span>
                            <span class="info-value">{{ $order->assignedUser?->name ?? $order->tailor_name }}</span>
                        </td>
                        <td>
                            <span class="info-label">Completed At</span>
                            <span class="info-value">{{ $completedAt }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="info-label">Fatora Number</span>
                            <span class="info-value">{{ $order->fatora_number ?: 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge {{ $order->status === \App\Models\TailorOrder::STATUS_COMPLETED ? 'status-completed' : ($order->status === \App\Models\TailorOrder::STATUS_IN_PROGRESS ? 'status-in-progress' : 'status-pending') }}">
                                    {{ $order->status_label }}
                                </span>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="detail-wrap">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Thobe Category</th>
                            <th>Quantity</th>
                            <th>Order Date</th>
                            <th>Unit Price</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $order->category_label }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->order_date->format('d M Y') }}</td>
                            <td>{{ number_format((float) $order->unit_price, 2) }} QAR</td>
                            <td><strong>{{ number_format((float) $order->total_price, 2) }} QAR</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="total-line">Total Payable: <span>{{ number_format((float) $order->total_price, 2) }} QAR</span></div>

            @if ($order->note)
                <div class="note">
                    <strong>Note:</strong> {{ $order->note }}
                </div>
            @endif

            <div class="footer-note">
                Generated on {{ $generatedAt->format('d F Y h:i A') }} by {{ $order->creator?->name ?? 'System' }}.
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
