@extends('layouts.app', ['title' => 'Dashboard | Tailor'])

@section('content')
    <style>
        .dashboard-view {
            position: relative;
            overflow: hidden;
            padding: 0.2rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.2);
            border-radius: 1.6rem;
        }

        .dashboard-view::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, transparent 0%, rgba(200, 155, 44, 0.55) 20%, rgba(200, 155, 44, 0.22) 50%, transparent 100%);
            background-size: 100% 1px;
            background-position: top 22px left 0;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .dashboard-view::after {
            content: "";
            position: absolute;
            inset: 0;
            background: none;
            pointer-events: none;
        }

        .dashboard-content {
            position: relative;
            z-index: 1;
        }

        .hero-panel,
        .chart-panel,
        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.2);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.85),
                0 16px 36px rgba(17, 17, 17, 0.06);
        }

        .hero-panel {
            border-radius: 1.2rem;
            padding: 1.2rem 1.35rem;
        }

        .hero-title {
            color: #111111;
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            line-height: 1.08;
            margin-bottom: 0.2rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .hero-copy {
            color: #222222;
            font-size: 0.9rem;
            max-width: 680px;
        }

        .hero-revenue {
            min-width: 200px;
            border-radius: 0.9rem;
            padding: 0.75rem 0.9rem;
            background: #ffffff;
            border: 1px solid rgba(200, 155, 44, 0.28);
            box-shadow: 0 14px 24px rgba(17, 17, 17, 0.06);
        }

        .hero-revenue-label {
            color: #222222;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-revenue-value {
            color: #111111;
            font-size: 1rem;
            font-weight: 700;
        }

        .revenue-toggle {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border-color: #111111 !important;
            color: #ffffff !important;
            background: #111111;
        }

        .revenue-toggle:hover,
        .revenue-toggle:focus {
            background: #2a2a2a;
            color: #ffffff !important;
            border-color: #2a2a2a !important;
        }

        .stat-card {
            border-radius: 1rem;
            padding: 1rem 1.05rem;
            position: relative;
            overflow: hidden;
            min-height: 118px;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: auto -10% -45% auto;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            opacity: 0.12;
            filter: blur(16px);
            background: var(--accent-glow, rgba(197, 150, 47, 0.16));
            pointer-events: none;
        }

        .stat-label {
            color: #222222;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-value {
            color: #111111;
            font-size: clamp(1.45rem, 2.5vw, 2rem);
            line-height: 1;
            margin-top: 0.45rem;
            font-family: Georgia, "Times New Roman", serif;
        }

        .stat-hint {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 0.65rem;
            padding: 0.28rem 0.6rem;
            border-radius: 999px;
            font-size: 0.74rem;
            border: 1px solid rgba(197, 150, 47, 0.16);
            color: #222222;
            background: rgba(197, 150, 47, 0.08);
        }

        .stat-visual {
            position: absolute;
            right: 0.9rem;
            bottom: 0.7rem;
            opacity: 0.5;
        }

        .stat-card.orders { --accent-glow: rgba(197, 150, 47, 0.22); }
        .stat-card.orders .stat-visual svg { stroke: #c5962f; }
        .stat-card.stitched { --accent-glow: rgba(215, 182, 95, 0.22); }
        .stat-card.stitched .stat-hint { background: rgba(197, 150, 47, 0.11); border-color: rgba(197, 150, 47, 0.22); color: #222222; }
        .stat-card.today { --accent-glow: rgba(235, 221, 191, 0.58); }
        .stat-card.today .stat-hint { background: rgba(255, 251, 242, 0.95); border-color: rgba(197, 150, 47, 0.15); color: #222222; }
        .stat-card.monthly { --accent-glow: rgba(178, 135, 31, 0.18); }
        .stat-card.monthly .stat-hint { background: rgba(197, 150, 47, 0.09); border-color: rgba(197, 150, 47, 0.18); color: #222222; }

        .chart-panel {
            border-radius: 1.1rem;
            padding: 1.1rem 1.1rem 1rem;
            position: relative;
            overflow: hidden;
        }

        .chart-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: none;
            pointer-events: none;
        }

        .chart-title {
            color: #111111;
        }

        .chart-copy {
            color: #222222 !important;
        }

        .chart-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.8rem;
        }

        .chart-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.34rem 0.64rem;
            border-radius: 999px;
            background: rgba(255, 250, 242, 0.92);
            border: 1px solid rgba(197, 150, 47, 0.16);
            color: #222222;
            font-size: 0.75rem;
        }

        .chart-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>

    <div class="dashboard-view">
        <div class="dashboard-content">
            <div class="hero-panel mb-3">
                <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 align-items-xl-start">
                    <div>
                        <h2 class="hero-title">Dashboard</h2>
                        <p class="hero-copy mb-0">
                            Daily and monthly work overview for {{ $user->name }}.
                            @if ($isScopedToAssignedUser)
                                Here you can view only your assigned invoices and completed thobes.
                            @endif
                        </p>
                    </div>

                    <div class="hero-revenue d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <div class="hero-revenue-label">Total Revenue</div>
                            <div class="hero-revenue-value" id="revenue-value" data-revenue="{{ number_format($stats['revenue'], 2) }} QAR">••••••</div>
                        </div>
                        <button type="button" class="btn btn-outline-dark revenue-toggle d-inline-flex align-items-center justify-content-center" id="toggle-revenue" aria-label="Toggle revenue visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card orders">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value">{{ $stats['orders'] }}</div>
                        <div class="stat-hint">All entries</div>
                        <div class="stat-visual">
                            <svg width="92" height="38" viewBox="0 0 142 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 46C14.8889 46 17.1111 28 28 28C38.8889 28 41.1111 40 52 40C62.8889 40 65.1111 16 76 16C86.8889 16 89.1111 30 100 30C110.889 30 113.111 10 124 10C134.889 10 137.111 28 138 28" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card stitched">
                        <div class="stat-label">{{ $isScopedToAssignedUser ? 'My Completed Thobes' : 'Total Stitched Thobes' }}</div>
                        <div class="stat-value">{{ $stats['thobes'] }}</div>
                        <div class="stat-hint">Completed / stitched work</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card today">
                        <div class="stat-label">Today's Orders</div>
                        <div class="stat-value">{{ $stats['today_orders'] }}</div>
                        <div class="stat-hint">Today activity</div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card monthly">
                        <div class="stat-label">Monthly Orders</div>
                        <div class="stat-value">{{ $stats['monthly_orders'] }}</div>
                        <div class="stat-hint">{{ now()->format('F Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="chart-panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="h6 fw-bold mb-1 chart-title">Thobe Status Chart</h3>
                        <p class="chart-copy mb-0">Status wise orders ka visual summary.</p>
                    </div>
                </div>
                <div style="height: 320px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="chart-meta">
                    <div class="chart-pill"><span class="chart-dot" style="background:#dc3545;"></span> Pending</div>
                    <div class="chart-pill"><span class="chart-dot" style="background:#e0b437;"></span> In Progress</div>
                    <div class="chart-pill"><span class="chart-dot" style="background:#1f9d68;"></span> Completed</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const revenueValue = document.getElementById('revenue-value');
            const toggleRevenue = document.getElementById('toggle-revenue');
            let revenueVisible = false;

            if (revenueValue && toggleRevenue) {
                toggleRevenue.addEventListener('click', () => {
                    revenueVisible = !revenueVisible;
                    revenueValue.textContent = revenueVisible ? revenueValue.dataset.revenue : '••••••';
                });
            }

            const chartData = @json($statusChart);
            const ctx = document.getElementById('statusChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => item.label),
                    datasets: [{
                        label: 'Orders',
                        data: chartData.map(item => item.count),
                        backgroundColor: ['#dc3545', '#e0b437', '#1f9d68'],
                        hoverBackgroundColor: ['#e35d6a', '#e7c25a', '#2ab37a'],
                        borderRadius: 14,
                        borderSkipped: false,
                        maxBarThickness: 68,
                        borderColor: ['#c52f3f', '#ca9f27', '#188454'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 900,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#fffaf2',
                            titleColor: '#222222',
                            bodyColor: '#3f3423',
                            borderColor: 'rgba(197, 150, 47, 0.45)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(197, 150, 47, 0.14)'
                            },
                            ticks: {
                                precision: 0,
                                color: '#6d5b39'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6d5b39'
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endsection
