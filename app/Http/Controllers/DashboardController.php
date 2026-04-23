<?php

namespace App\Http\Controllers;

use App\Models\TailorOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $ordersQuery = TailorOrder::query();

        if ($user->isUser()) {
            $ordersQuery->where('assigned_user_id', $user->id);
        }

        $monthlyOrdersQuery = (clone $ordersQuery)
            ->whereYear('order_date', now()->year)
            ->whereMonth('order_date', now()->month);

        $stats = [
            'orders' => (int) (clone $ordersQuery)->count(),
            'thobes' => (int) (clone $ordersQuery)->completedWork()->sum('quantity'),
            'today_orders' => (int) (clone $ordersQuery)->whereDate('order_date', now()->toDateString())->count(),
            'revenue' => (clone $ordersQuery)->revenueTotal(),
            'monthly_orders' => (int) $monthlyOrdersQuery->count(),
        ];
        $statusChart = collect(TailorOrder::statuses())
            ->map(function (string $label, string $status) use ($ordersQuery) {
                $statusCountQuery = clone $ordersQuery;

                if ($status === TailorOrder::STATUS_PENDING) {
                    $statusCountQuery->where(function ($query) use ($status) {
                        $query
                            ->where('status', $status)
                            ->orWhere('status', '')
                            ->orWhereNull('status');
                    });
                } else {
                    $statusCountQuery->where('status', $status);
                }

                return [
                    'label' => $label,
                    'count' => (int) $statusCountQuery->count(),
                ];
            })
            ->values();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'statusChart' => $statusChart,
            'isScopedToAssignedUser' => $user->isUser(),
        ]);
    }
}
