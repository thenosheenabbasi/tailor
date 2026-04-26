<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTailorOrderRequest;
use App\Models\TailorOrder;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TailorOrderController extends Controller
{
    public function index(Request $request): View|Response
    {
        $user = $request->user();

        abort_unless($user?->canAccessReports(), 403, 'You are not authorized to access this section.');

        $canManageSettings = $user?->canManageOrderSettings() ?? false;
        $canAccessReport = $user?->canAccessReports() ?? false;
        $requestedMode = $request->string('view')->toString();
        $pageMode = $user?->isUser()
            ? 'report'
            : ($canAccessReport && $requestedMode === 'report' ? 'report' : 'invoices');

        $filters = [
            'view' => $pageMode,
            'search' => $request->string('search')->toString(),
            'assigned_user_id' => $user?->isUser() ? (string) $user->id : $request->string('assigned_user_id')->toString(),
            'thobe_category' => $request->string('thobe_category')->toString(),
            'invoice_number' => $request->string('invoice_number')->toString(),
            'fatora_number' => $request->string('fatora_number')->toString(),
            'date_from' => $request->string('date_from')->toString(),
            'date_to' => $request->string('date_to')->toString(),
        ];

        $selectedAssignedUser = $user?->isUser()
            ? $user
            : ($filters['assigned_user_id'] !== ''
            ? User::query()
                ->where('role', User::ROLE_USER)
                ->find($filters['assigned_user_id'], ['id', 'name'])
            : null);

        $filteredOrdersQuery = TailorOrder::query()
            ->with(['creator', 'assignedUser'])
            ->when($user?->isUser(), fn ($query) => $query->where('assigned_user_id', $user->id))
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('invoice_number', 'like', '%' . $search . '%')
                        ->orWhere('fatora_number', 'like', '%' . $search . '%')
                        ->orWhere('tailor_name', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['assigned_user_id'] !== '', fn ($query) => $query->where('assigned_user_id', $filters['assigned_user_id']))
            ->when($filters['thobe_category'] !== '', fn ($query) => $query->where('thobe_category', $filters['thobe_category']))
            ->when($filters['invoice_number'] !== '', fn ($query) => $query->where('invoice_number', 'like', '%' . $filters['invoice_number'] . '%'))
            ->when($filters['fatora_number'] !== '', fn ($query) => $query->where('fatora_number', 'like', '%' . $filters['fatora_number'] . '%'))
            ->when($filters['date_from'] !== '', fn ($query) => $query->whereDate('order_date', '>=', $filters['date_from']))
            ->when($filters['date_to'] !== '', fn ($query) => $query->whereDate('order_date', '<=', $filters['date_to']));

        $ordersQuery = (clone $filteredOrdersQuery)->latestFirst();

        if ($request->get('export') === 'pdf') {
            abort_unless($canAccessReport, 403, 'You are not authorized to access this section.');

            return $this->downloadPdf(clone $ordersQuery, $filters, $selectedAssignedUser);
        }

        return view('admin.orders.index', [
            'orders' => $ordersQuery->paginate(10)->withQueryString(),
            'assignableUsers' => $user?->isUser()
                ? User::query()->whereKey($user->id)->get(['id', 'name'])
                : User::query()
                    ->where('role', User::ROLE_USER)
                    ->orderBy('name')
                    ->get(['id', 'name']),
            'filters' => $filters,
            'pageMode' => $pageMode,
            'selectedAssignedUser' => $selectedAssignedUser,
            'reportStats' => [
                'orders' => (clone $filteredOrdersQuery)->count(),
                'thobes' => (clone $filteredOrdersQuery)->sum('quantity'),
                'revenue' => (clone $filteredOrdersQuery)->revenueTotal(),
            ],
            'reportCategorySummaries' => $this->buildCategorySummaries(clone $filteredOrdersQuery, $filters['thobe_category']),
            'categories' => TailorOrder::categories(),
            'canManageSettings' => $canManageSettings,
            'canFilterTailors' => ! $user?->isUser(),
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->canCreateOrders(), 403, 'You are not authorized to access this section.');

        return view('admin.orders.create', [
            'nextInvoiceNumber' => TailorOrder::nextInvoiceNumber(),
            'order' => null,
            'assignableUsers' => User::query()
                ->where('role', User::ROLE_USER)
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
            'categoryOptions' => TailorOrder::categoryOptions(),
            'categoryPrices' => collect(TailorOrder::categories())->mapWithKeys(
                fn (array $category, string $key) => [$key => $category['price']]
            )->all(),
        ]);
    }

    public function edit(TailorOrder $tailorOrder): View
    {
        abort_unless(auth()->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        return view('admin.orders.create', [
            'nextInvoiceNumber' => $tailorOrder->invoice_number,
            'order' => $tailorOrder,
            'assignableUsers' => User::query()
                ->where('role', User::ROLE_USER)
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
            'categoryOptions' => TailorOrder::categoryOptions(),
            'categoryPrices' => collect(TailorOrder::categories())->mapWithKeys(
                fn (array $category, string $key) => [$key => $category['price']]
            )->all(),
        ]);
    }

    public function store(StoreTailorOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $unitPrice = TailorOrder::unitPriceFor($validated['thobe_category']);
        $quantity = (int) $validated['quantity'];
        $assignedUser = User::query()->findOrFail($validated['assigned_user_id']);

        TailorOrder::create([
            'user_id' => $request->user()->id,
            'assigned_user_id' => $validated['assigned_user_id'],
            'tailor_name' => $assignedUser->name,
            'invoice_number' => TailorOrder::nextInvoiceNumber(),
            'fatora_number' => $validated['fatora_number'],
            'thobe_category' => $validated['thobe_category'],
            'quantity' => $quantity,
            'order_date' => $validated['order_date'],
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'status' => TailorOrder::STATUS_PENDING,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('admin.orders.index')
            ->with('status', 'Tailor order added successfully.');
    }

    public function update(StoreTailorOrderRequest $request, TailorOrder $tailorOrder): RedirectResponse
    {
        abort_unless($request->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        $validated = $request->validated();
        $unitPrice = TailorOrder::unitPriceFor($validated['thobe_category']);
        $quantity = (int) $validated['quantity'];
        $assignedUser = User::query()->findOrFail($validated['assigned_user_id']);

        $tailorOrder->update([
            'assigned_user_id' => $validated['assigned_user_id'],
            'tailor_name' => $assignedUser->name,
            'fatora_number' => $validated['fatora_number'],
            'thobe_category' => $validated['thobe_category'],
            'quantity' => $quantity,
            'order_date' => $validated['order_date'],
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('admin.orders.index', ['view' => 'invoices'])
            ->with('status', 'Tailor order updated successfully.');
    }

    public function complete(TailorOrder $tailorOrder): RedirectResponse
    {
        abort_unless(auth()->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        $tailorOrder->update([
            'status' => TailorOrder::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Order marked as completed.');
    }

    public function updateStatus(Request $request, TailorOrder $tailorOrder): RedirectResponse
    {
        abort_unless($request->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(TailorOrder::statuses()))],
        ]);

        $tailorOrder->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === TailorOrder::STATUS_COMPLETED ? ($tailorOrder->completed_at ?? now()) : null,
        ]);

        return redirect()->back()->with('status', 'Order status updated successfully.');
    }

    public function destroy(TailorOrder $tailorOrder): RedirectResponse
    {
        abort_unless(auth()->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        $tailorOrder->delete();

        return redirect()->back()->with('status', 'Invoice deleted successfully.');
    }

    public function receipt(TailorOrder $tailorOrder): View
    {
        abort_unless(auth()->user()?->canManageOrderSettings(), 403, 'You are not authorized to access this section.');

        return view('admin.orders.receipt-pdf', [
            'order' => $tailorOrder->load(['creator', 'assignedUser']),
            'generatedAt' => now(),
        ]);
    }

    protected function downloadPdf($ordersQuery, array $filters, ?User $selectedAssignedUser): Response
    {
        $orders = $ordersQuery->get();
        $reportStats = [
            'orders' => $orders->count(),
            'thobes' => (int) $orders->sum('quantity'),
            'revenue' => (float) $orders->sum(fn (TailorOrder $order) => (float) $order->unit_price * (int) $order->quantity),
        ];

        $pdf = Pdf::loadView('admin.orders.pdf', [
            'orders' => $orders,
            'filters' => $filters,
            'selectedAssignedUser' => $selectedAssignedUser,
            'reportStats' => $reportStats,
            'reportCategorySummaries' => $this->buildCategorySummariesFromCollection($orders, $filters['thobe_category']),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('tailor-report-' . now()->format('Y-m-d-His') . '.pdf');
    }

    protected function buildCategorySummaries(Builder $ordersQuery, string $selectedCategory = ''): array
    {
        $groupedSummaries = (clone $ordersQuery)
            ->selectRaw('thobe_category, SUM(quantity) as quantity_total, SUM(total_price) as amount_total')
            ->groupBy('thobe_category')
            ->get()
            ->keyBy('thobe_category');

        return $this->mapCategorySummaries($groupedSummaries, $selectedCategory);
    }

    protected function buildCategorySummariesFromCollection($orders, string $selectedCategory = ''): array
    {
        $groupedSummaries = $orders
            ->groupBy('thobe_category')
            ->map(function ($categoryOrders, string $categoryKey) {
                return (object) [
                    'thobe_category' => $categoryKey,
                    'quantity_total' => (int) $categoryOrders->sum('quantity'),
                    'amount_total' => (float) $categoryOrders->sum('total_price'),
                ];
            });

        return $this->mapCategorySummaries($groupedSummaries, $selectedCategory);
    }

    protected function mapCategorySummaries($groupedSummaries, string $selectedCategory = ''): array
    {
        $categories = TailorOrder::categories();
        $categoryKeys = $selectedCategory !== '' ? [$selectedCategory] : array_keys($categories);

        return collect($categoryKeys)
            ->map(function (string $categoryKey) use ($categories, $groupedSummaries) {
                $summary = $groupedSummaries->get($categoryKey);
                $category = $categories[$categoryKey] ?? [
                    'label' => ucfirst(str_replace('_', ' ', $categoryKey)),
                    'price' => 0,
                ];

                return [
                    'key' => $categoryKey,
                    'label' => $category['label'],
                    'quantity' => (int) ($summary->quantity_total ?? 0),
                    'amount' => (float) ($summary->amount_total ?? 0),
                    'unit_price' => (float) ($category['price'] ?? 0),
                ];
            })
            ->values()
            ->all();
    }
}
