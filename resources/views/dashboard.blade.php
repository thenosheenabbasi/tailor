@extends('layouts.app', ['title' => 'Dashboard | Tailor', 'pageTitle' => 'Dashboard'])

@section('content')
    @php
        $totalStatusCount = max(1, collect($statusChart)->sum('count'));
        $statusCards = collect($statusChart)->map(function (array $item) use ($totalStatusCount) {
            $normalized = strtolower(str_replace(' ', '-', $item['label']));

            $palette = match ($normalized) {
                'pending' => ['tone' => '#d79a1e'],
                'in-progress' => ['tone' => '#212121'],
                default => ['tone' => '#e6e1da'],
            };

            return [
                'label' => $item['label'],
                'count' => $item['count'],
                'share' => round(($item['count'] / $totalStatusCount) * 100),
                'tone' => $palette['tone'],
            ];
        })->values();

        $ordersQuery = \App\Models\TailorOrder::query();

        if ($isScopedToAssignedUser) {
            $ordersQuery->where('assigned_user_id', $user->id);
        }

        $recentOrders = (clone $ordersQuery)
            ->latestFirst()
            ->limit(4)
            ->get();

        $monthlyTrendRows = (clone $ordersQuery)
            ->whereYear('order_date', now()->year)
            ->whereMonth('order_date', now()->month)
            ->get(['order_date'])
            ->groupBy(fn ($order) => optional($order->order_date)->day)
            ->map(fn ($group) => $group->count());

        $lastDayOfMonth = now()->endOfMonth()->day;
        $anchorDays = collect([1, 5, 10, 15, 20, 25, $lastDayOfMonth])->unique()->values();

        $trendLabels = $anchorDays
            ->map(fn ($day) => $day . ' ' . now()->format('M'))
            ->values();

        $trendSeries = $anchorDays
            ->map(function ($day) use ($monthlyTrendRows) {
                return (int) $monthlyTrendRows
                    ->filter(fn ($count, $countDay) => (int) $countDay <= (int) $day)
                    ->sum();
            })
            ->values();

        $summaryCards = [
            [
                'label' => "Today's Orders",
                'value' => $stats['today_orders'],
                'meta' => 'Orders created today',
                'icon' => 'bag',
                'legacy' => null,
            ],
            [
                'label' => 'Pending Orders',
                'value' => $statusCards->firstWhere('label', 'Pending')['count'] ?? 0,
                'meta' => 'Orders in progress',
                'icon' => 'clock',
                'legacy' => 'Monthly Orders',
            ],
            [
                'label' => 'Completed Orders',
                'value' => $statusCards->firstWhere('label', 'Completed')['count'] ?? 0,
                'meta' => 'Orders completed',
                'icon' => 'check',
                'legacy' => $isScopedToAssignedUser ? 'My Completed Thobes' : 'Total Stitched Thobes',
            ],
            [
                'label' => 'Total Revenue',
                'value' => 'SAR ' . number_format((float) $stats['revenue'], 0),
                'meta' => 'All time revenue',
                'icon' => 'coin',
                'legacy' => null,
            ],
        ];
    @endphp

    <style>
        .legacy-copy {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .dashboard-view {
            display: grid;
            gap: 0.9rem;
        }

        .dashboard-top {
            display: grid;
            grid-template-columns: minmax(280px, 0.96fr) minmax(0, 2.04fr);
            gap: 0.85rem;
            align-items: stretch;
        }

        .welcome-card {
            padding: 1.3rem 1.25rem;
            border-radius: 18px;
            background:
                radial-gradient(circle at top left, rgba(215, 154, 30, 0.05), transparent 36%),
                linear-gradient(180deg, #fffefb 0%, #fdf9f2 100%);
            border: 1px solid rgba(26, 20, 12, 0.05);
            box-shadow: 0 8px 22px rgba(34, 24, 10, 0.04);
            min-height: 142px;
        }

        .welcome-title {
            max-width: 270px;
            font-size: clamp(1.3rem, 2.2vw, 1.65rem);
            line-height: 1.25;
            font-weight: 700;
        }

        .welcome-rule {
            width: 52px;
            height: 3px;
            margin: 0.8rem 0 0.75rem;
            border-radius: 999px;
            background: #d79a1e;
        }

        .welcome-copy {
            max-width: 290px;
            color: #5e5850;
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .summary-card {
            position: relative;
            min-height: 142px;
            padding: 0.82rem 0.82rem 0.7rem;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid rgba(26, 20, 12, 0.05);
            box-shadow: 0 8px 22px rgba(34, 24, 10, 0.04);
        }

        .summary-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff9ef;
            border: 1px solid rgba(215, 154, 30, 0.18);
            color: #d79a1e;
            margin-bottom: 0.65rem;
        }

        .summary-icon svg {
            width: 0.9rem;
            height: 0.9rem;
        }

        .summary-label {
            color: #1a1a1a;
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1.45;
        }

        .summary-value {
            margin-top: 0.72rem;
            color: #d79a1e;
            font-family: "Outfit", sans-serif;
            font-size: clamp(1rem, 1.5vw, 1.35rem);
            line-height: 1;
            font-weight: 700;
            word-break: break-word;
        }

        .summary-meta {
            margin-top: 0.45rem;
            color: #666056;
            font-size: 0.92rem;
            line-height: 1.35;
        }

        .dashboard-middle {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(360px, 0.92fr);
            gap: 0.85rem;
        }

        .panel-card {
            padding: 0.9rem 0.95rem 0.88rem;
            border-radius: 16px;
        }

        .panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.72rem;
        }

        .panel-title {
            font-size: 0.98rem;
            line-height: 1.1;
        }

        .panel-copy {
            margin-top: 0.2rem;
            color: #686259;
            font-size: 0.92rem;
        }

        .panel-filter {
            min-width: 94px;
            padding: 0.42rem 0.6rem;
            border-radius: 10px;
            border: 1px solid rgba(17, 17, 17, 0.08);
            background: #ffffff;
            color: #232323;
            font-size: 0.92rem;
            font-weight: 600;
            text-align: center;
        }

        .analytics-chart {
            height: 205px;
        }

        .status-layout {
            display: grid;
            grid-template-columns: minmax(220px, 235px) minmax(0, 1fr);
            gap: 0.9rem;
            align-items: center;
        }

        .status-chart-shell {
            position: relative;
            width: min(100%, 145px);
            aspect-ratio: 1 / 1;
            margin: 0 auto;
        }

        .status-chart-shell canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .status-center {
            position: absolute;
            inset: 50% auto auto 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .status-center-total {
            color: #171717;
            font-family: "Outfit", sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            line-height: 1;
        }

        .status-center-copy {
            margin-top: 0.3rem;
            color: #605950;
            font-size: 0.64rem;
        }

        .status-list {
            display: grid;
        }

        .status-row {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 0.62rem;
            align-items: center;
            padding: 0.68rem 0;
            border-bottom: 1px solid rgba(17, 17, 17, 0.07);
        }

        .status-row:first-child {
            padding-top: 0;
        }

        .status-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .status-name {
            color: #1e1e1e;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .status-share {
            color: #23201c;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .status-share strong {
            font-weight: 700;
        }

        .recent-card {
            padding: 0.95rem 1rem 0.88rem;
            border-radius: 16px;
        }

        .recent-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.58rem;
        }

        .recent-title {
            font-size: 0.9rem;
            line-height: 1.1;
        }

        .recent-copy {
            margin-top: 0.25rem;
            color: #686259;
            font-size: 0.67rem;
        }

        .recent-link {
            color: #d79a1e;
            font-size: 0.68rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .recent-link:hover {
            color: #bc810f;
        }

        .recent-table {
            width: 100%;
            border-collapse: collapse;
        }

        .recent-table thead th {
            padding: 0.64rem 0.74rem 0.56rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.07);
            color: #202020;
            font-size: 0.8rem;
            font-weight: 700;
            text-align: left;
        }

        .recent-table tbody td {
            padding: 0.54rem 0.74rem;
            border-bottom: 1px solid rgba(17, 17, 17, 0.06);
            color: #2a251f;
            font-size: 0.92rem;
            vertical-align: middle;
        }

        .recent-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 66px;
            padding: 0.24rem 0.5rem;
            border-radius: 5px;
            font-size: 0.72rem;
            line-height: 1;
            font-weight: 600;
        }

        .status-pill.pending {
            background: #d79a1e;
            color: #ffffff;
        }

        .status-pill.in-progress {
            background: #222222;
            color: #ffffff;
        }

        .status-pill.completed {
            background: #ece7df;
            color: #202020;
        }

        .order-actions {
            width: 18px;
            text-align: right;
            color: #1b1b1b;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
        }

        .empty-orders {
            padding: 1rem 0.35rem 0.2rem;
            color: #6e685f;
            font-size: 0.88rem;
        }

        @media (max-width: 1399.98px) {
            .dashboard-top,
            .dashboard-middle {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 1199.98px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-top,
            .dashboard-middle,
            .status-layout {
                grid-template-columns: 1fr;
            }

            .dashboard-view {
                gap: 0.75rem;
            }

            .dashboard-top,
            .dashboard-middle {
                gap: 0.75rem;
            }

            .welcome-card,
            .panel-card,
            .recent-card {
                padding: 0.88rem;
                border-radius: 14px;
            }

            .welcome-card {
                min-height: 0;
            }

            .welcome-title {
                max-width: none;
                font-size: 1.18rem;
            }

            .welcome-copy {
                max-width: none;
                font-size: 0.84rem;
            }

            .summary-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.65rem;
            }

            .summary-card {
                min-height: auto;
                display: block;
                padding: 0.78rem;
            }

            .summary-icon {
                width: 36px;
                height: 36px;
                margin-bottom: 0.5rem;
            }

            .summary-label,
            .summary-meta {
                font-size: 0.8rem;
            }

            .summary-value {
                margin-top: 0.22rem;
                font-size: 1.15rem;
                line-height: 1.18;
            }

            .panel-head,
            .recent-head {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.48rem;
            }

            .panel-filter {
                width: 100%;
                min-width: 0;
            }

            .analytics-chart {
                height: 180px;
            }

            .status-chart-shell {
                width: min(100%, 132px);
            }

            .status-layout {
                gap: 0.75rem;
            }

            .status-row {
                padding: 0.58rem 0;
            }

            .recent-link {
                width: 100%;
                display: inline-flex;
                min-height: 38px;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(215, 154, 30, 0.24);
                border-radius: 10px;
                background: #fffaf0;
            }

            .table-responsive {
                overflow: visible;
            }

            .recent-table,
            .recent-table thead,
            .recent-table tbody,
            .recent-table tr,
            .recent-table td {
                display: block;
                width: 100%;
            }

            .recent-table thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            .recent-table tbody {
                display: grid;
                gap: 0.62rem;
            }

            .recent-table tbody tr {
                padding: 0.72rem;
                border: 1px solid rgba(17, 17, 17, 0.07);
                border-radius: 12px;
                background: #fffdf8;
            }

            .recent-table tbody td {
                display: grid;
                grid-template-columns: minmax(84px, 0.44fr) minmax(0, 1fr);
                align-items: center;
                gap: 0.65rem;
                padding: 0.38rem 0;
                border-bottom: 0;
                font-size: 0.82rem;
                word-break: break-word;
            }

            .recent-table tbody td::before {
                color: #736c62;
                font-size: 0.72rem;
                font-weight: 700;
            }

            .recent-table tbody td:nth-child(1)::before {
                content: "Invoice";
            }

            .recent-table tbody td:nth-child(2)::before {
                content: "Client";
            }

            .recent-table tbody td:nth-child(3)::before {
                content: "Status";
            }

            .recent-table tbody td:nth-child(4)::before {
                content: "Date";
            }

            .recent-table tbody td:nth-child(5)::before {
                content: "Amount";
            }

            .recent-table tbody td:nth-child(6) {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .dashboard-view {
                gap: 0.62rem;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .recent-table tbody td {
                grid-template-columns: 74px minmax(0, 1fr);
            }
        }

        @media (max-width: 360px) {
            .welcome-card,
            .panel-card,
            .recent-card,
            .summary-card {
                padding: 0.72rem;
            }

            .recent-table tbody td {
                grid-template-columns: 1fr;
                gap: 0.18rem;
            }
        }
    </style>

    <div class="dashboard-view">
        <section class="dashboard-top">
            <div class="welcome-card">
                <h2 class="welcome-title">Welcome back,<br>{{ $user->name }}</h2>
                <div class="welcome-rule"></div>
                <p class="welcome-copy">
                    Monitor your business, track orders, manage revenue and generate reports.
                    @if ($isScopedToAssignedUser)
                        This dashboard only shows your assigned invoices and completed thobes.
                    @endif
                </p>
            </div>

            <div class="summary-grid">
                @foreach ($summaryCards as $card)
                    <article class="summary-card">
                        @if ($card['legacy'])
                            <span class="legacy-copy">{{ $card['legacy'] }}</span>
                        @endif
                        <div class="summary-icon" aria-hidden="true">
                            @if ($card['icon'] === 'bag')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 8Z"></path>
                                    <path d="M9 8a3 3 0 0 1 6 0"></path>
                                </svg>
                            @elseif ($card['icon'] === 'clock')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 7v5l3 2"></path>
                                </svg>
                            @elseif ($card['icon'] === 'check')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="m9 12 2 2 4-4"></path>
                                </svg>
                            @elseif ($card['icon'] === 'coin')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="7" rx="6.5" ry="3.2"></ellipse>
                                    <path d="M5.5 7v6c0 1.8 2.9 3.2 6.5 3.2s6.5-1.4 6.5-3.2V7"></path>
                                    <path d="M5.5 13v4c0 1.8 2.9 3.2 6.5 3.2s6.5-1.4 6.5-3.2v-4"></path>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="8"></circle>
                                    <path d="M12 8v8"></path>
                                    <path d="M9.5 10.5c0-1.4 1-2.5 2.5-2.5s2.5 1.1 2.5 2.5-1 2-2.5 2-2.5.6-2.5 2 1 2.5 2.5 2.5 2.5-1.1 2.5-2.5"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="summary-label">{{ $card['label'] }}</div>
                        <div class="summary-value">{{ $card['value'] }}</div>
                        <div class="summary-meta">{{ $card['meta'] }}</div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="dashboard-middle">
            <div class="content-stage panel-card">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Order Analytics</h3>
                        <p class="panel-copy">Overview of orders over time</p>
                    </div>
                    <div class="panel-filter">This Month</div>
                </div>

                <div class="analytics-chart">
                    <canvas id="overviewLineChart"></canvas>
                </div>
            </div>

            <div class="content-stage panel-card">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Order Status Summary</h3>
                        <p class="panel-copy">Breakdown of all orders by status</p>
                        <span class="legacy-copy">Status wise orders ka visual summary.</span>
                    </div>
                </div>

                <div class="status-layout">
                    <div class="status-chart-shell">
                        <canvas id="statusSummaryChart"></canvas>
                        <div class="status-center">
                            <div class="status-center-total">{{ $totalStatusCount }}</div>
                            <div class="status-center-copy">Total Orders</div>
                        </div>
                    </div>

                    <div class="status-list">
                        @foreach ($statusCards as $status)
                            <div class="status-row">
                                <span class="status-dot" style="background: {{ $status['tone'] }};"></span>
                                <div class="status-name">{{ $status['label'] }}</div>
                                <div class="status-share"><strong>{{ $status['count'] }}</strong> ({{ $status['share'] }}%)</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="content-stage recent-card">
            <div class="recent-head">
                <div>
                    <h3 class="recent-title">Recent Orders</h3>
                    <p class="recent-copy">Latest orders from your business</p>
                </div>
                <a href="{{ route('admin.orders.index', ['view' => 'invoices']) }}" class="recent-link">View All Orders →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="empty-orders">No recent orders available right now.</div>
            @else
                <div class="table-responsive">
                    <table class="recent-table">
                        <thead>
                            <tr>
                                <th>Invoice No.</th>
                                <th>Client Name</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->invoice_number ?: 'INV-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->tailor_name }}</td>
                                    <td>
                                        <span class="status-pill {{ str_replace('_', '-', $order->status ?: 'pending') }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ optional($order->order_date)->format('d M Y') }}</td>
                                    <td>SAR {{ number_format((float) $order->total_price, 0) }}</td>
                                    <td class="order-actions">⋮</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const statusData = @json($statusChart);
            const trendLabels = @json($trendLabels);
            const trendSeries = @json($trendSeries);
            const overviewCtx = document.getElementById('overviewLineChart');
            const summaryCtx = document.getElementById('statusSummaryChart');

            if (overviewCtx) {
                const trendMax = Math.max(...trendSeries, 0);
                const yAxisMax = Math.max(40, Math.ceil(trendMax / 10) * 10);

                new Chart(overviewCtx, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            data: trendSeries,
                            borderColor: '#d79a1e',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            pointRadius: 3.2,
                            pointHoverRadius: 4,
                            pointBackgroundColor: '#d79a1e',
                            pointBorderColor: '#d79a1e',
                            pointBorderWidth: 0,
                            tension: 0,
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: '#111111',
                                titleColor: '#ffffff',
                                bodyColor: '#f7eedf',
                                padding: 12,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: yAxisMax,
                                grid: {
                                    color: 'rgba(17, 17, 17, 0.09)',
                                    borderDash: [4, 4],
                                    drawBorder: false,
                                },
                                ticks: {
                                    stepSize: 10,
                                    precision: 0,
                                    color: '#7d786f',
                                    font: {
                                        size: 10,
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    color: '#3a342d',
                                    font: {
                                        size: 10,
                                    }
                                },
                                border: {
                                    display: false,
                                },
                            }
                        }
                    }
                });
            }

            if (summaryCtx) {
                new Chart(summaryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusData.map((item) => item.label),
                        datasets: [{
                            data: statusData.map((item) => item.count),
                            backgroundColor: ['#d79a1e', '#212121', '#e6e1da'],
                            borderColor: '#ffffff',
                            borderWidth: 6,
                            hoverOffset: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: '#111111',
                                titleColor: '#ffffff',
                                bodyColor: '#f7f3ec',
                                padding: 12,
                                displayColors: true,
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endsection
