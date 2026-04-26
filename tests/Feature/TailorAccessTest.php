<?php

namespace Tests\Feature;

use App\Models\TailorOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TailorAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_manager_can_access_invoice_workspace_but_not_admin_only_order_actions(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER]);
        $order = TailorOrder::create([
            'user_id' => $manager->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-MANAGER-1',
            'fatora_number' => 'FAT-100',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 40,
            'status' => TailorOrder::STATUS_PENDING,
        ]);

        $this->actingAs($manager)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Create New Invoice')
            ->assertSee('Report')
            ->assertDontSee('Actions')
            ->assertDontSee('Download PDF');

        $this->actingAs($manager)
            ->get(route('admin.orders.create'))
            ->assertOk();

        $this->actingAs($manager)
            ->get(route('admin.orders.index', ['view' => 'report']))
            ->assertOk()
            ->assertSee('Reports')
            ->assertSee('Tailor Wise')
            ->assertSee('Download PDF')
            ->assertDontSee('Create New Invoice');

        $this->actingAs($manager)
            ->get(route('admin.orders.edit', $order))
            ->assertForbidden();

        $this->actingAs($manager)
            ->get(route('admin.orders.receipt', $order))
            ->assertForbidden();
    }

    public function test_manager_can_create_invoice_and_total_is_calculated_from_category_price(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER]);
        $orderDate = now()->setTime(14, 30, 0);

        $response = $this->actingAs($manager)->post(route('admin.orders.store'), [
            'assigned_user_id' => $assignedUser->id,
            'fatora_number' => 'FAT-201',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 3,
            'order_date' => $orderDate->format('Y-m-d\\TH:i'),
            'note' => 'Manager entry',
        ]);

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseHas('tailor_orders', [
            'user_id' => $manager->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'fatora_number' => 'FAT-201',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 3,
            'order_date' => $orderDate->format('Y-m-d H:i:s'),
            'unit_price' => 30,
            'total_price' => 90,
            'status' => TailorOrder::STATUS_PENDING,
        ]);
    }

    public function test_user_dashboard_shows_only_assigned_orders(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $userOne = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User One']);
        $userTwo = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User Two']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userOne->id,
            'tailor_name' => $userOne->name,
            'invoice_number' => 'INV-U1',
            'fatora_number' => 'FAT-U1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userTwo->id,
            'tailor_name' => $userTwo->name,
            'invoice_number' => 'INV-U2',
            'fatora_number' => 'FAT-U2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 3,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 90,
        ]);

        $response = $this->actingAs($userOne)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('My Completed Thobes');
        $response->assertSee('Monthly Orders');
        $response->assertSee('Status wise orders ka visual summary.');
        $response->assertDontSee('INV-U2');
    }

    public function test_user_can_access_only_own_report_data(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $userOne = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User One']);
        $userTwo = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User Two']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userOne->id,
            'tailor_name' => $userOne->name,
            'invoice_number' => 'INV-USER-REPORT-1',
            'fatora_number' => 'FAT-UR1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->setTime(18, 15, 0),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userTwo->id,
            'tailor_name' => $userTwo->name,
            'invoice_number' => 'INV-USER-REPORT-2',
            'fatora_number' => 'FAT-UR2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 1,
            'order_date' => now()->setTime(19, 0, 0),
            'unit_price' => 30,
            'total_price' => 30,
        ]);

        $this->actingAs($userOne)
            ->get(route('admin.orders.index', ['view' => 'report', 'assigned_user_id' => $userTwo->id]))
            ->assertOk()
            ->assertSee('Report')
            ->assertSee('Download PDF')
            ->assertSee('INV-USER-REPORT-1')
            ->assertDontSee('INV-USER-REPORT-2')
            ->assertDontSee('Tailor Invoice')
            ->assertDontSee('Tailor Wise');
    }

    public function test_user_can_download_only_own_report_pdf(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Scoped User']);
        $otherUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Other User']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $user->id,
            'tailor_name' => $user->name,
            'invoice_number' => 'INV-UPDF-1',
            'fatora_number' => 'FAT-UPDF1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->setTime(20, 2, 0),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $otherUser->id,
            'tailor_name' => $otherUser->name,
            'invoice_number' => 'INV-UPDF-2',
            'fatora_number' => 'FAT-UPDF2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 1,
            'order_date' => now()->setTime(21, 0, 0),
            'unit_price' => 30,
            'total_price' => 30,
        ]);

        $response = $this->actingAs($user)->get(route('admin.orders.index', [
            'view' => 'report',
            'assigned_user_id' => $otherUser->id,
            'export' => 'pdf',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition');
    }

    public function test_dashboard_completed_thobes_uses_completed_quantity_total(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Tailor One']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-DASH-1',
            'fatora_number' => 'FAT-D1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 4,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 80,
            'status' => TailorOrder::STATUS_COMPLETED,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-DASH-2',
            'fatora_number' => 'FAT-D2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 3,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 90,
            'status' => TailorOrder::STATUS_COMPLETED,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-DASH-3',
            'fatora_number' => 'FAT-D3',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 40,
            'status' => TailorOrder::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Total Stitched Thobes');
        $response->assertSee('7');
    }

    public function test_dashboard_counts_all_records_and_treats_empty_status_as_pending(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Legacy Tailor']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-LEG-1',
            'fatora_number' => 'FAT-L1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
            'status' => TailorOrder::STATUS_PENDING,
            'hidden_from_dashboard' => false,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-LEG-2',
            'fatora_number' => 'FAT-L2',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
            'status' => '',
            'hidden_from_dashboard' => false,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-LEG-3',
            'fatora_number' => 'FAT-L3',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
            'status' => TailorOrder::STATUS_IN_PROGRESS,
            'hidden_from_dashboard' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('3');
        $response->assertSee('"label":"Pending","count":2', false);
        $response->assertSee('"label":"In Progress","count":1', false);
    }

    public function test_admin_can_filter_reports_by_user_and_invoice_number(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $userOne = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User One']);
        $userTwo = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'User Two']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userOne->id,
            'tailor_name' => $userOne->name,
            'invoice_number' => 'INV-USER-1',
            'fatora_number' => 'FAT-A',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $userTwo->id,
            'tailor_name' => $userTwo->name,
            'invoice_number' => 'INV-USER-2',
            'fatora_number' => 'FAT-B',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 60,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'view' => 'report',
            'assigned_user_id' => $userOne->id,
            'invoice_number' => 'INV-USER-1',
        ]));

        $response->assertOk();
        $response->assertSee('INV-USER-1');
        $response->assertDontSee('INV-USER-2');
    }

    public function test_admin_can_filter_reports_by_category(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Category User']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-CAT-ADMIN-1',
            'fatora_number' => 'FAT-CAT-1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-CAT-ADMIN-2',
            'fatora_number' => 'FAT-CAT-2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 30,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'view' => 'report',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
        ]));

        $response->assertOk();
        $response->assertSee('INV-CAT-ADMIN-2');
        $response->assertDontSee('INV-CAT-ADMIN-1');
    }

    public function test_report_shows_category_wise_quantity_and_amount_summary_for_all_categories(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Bilal Tailor']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-SUM-1',
            'fatora_number' => 'FAT-SUM-1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-SUM-2',
            'fatora_number' => 'FAT-SUM-2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 3,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 90,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-SUM-3',
            'fatora_number' => 'FAT-SUM-3',
            'thobe_category' => TailorOrder::CATEGORY_EMBROIDERY,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 25,
            'total_price' => 50,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'view' => 'report',
            'assigned_user_id' => $assignedUser->id,
        ]));

        $response->assertOk();
        $response->assertSee('All Categories Summary');
        $response->assertSee('data-category-summary="simple"', false);
        $response->assertSee('data-category-quantity="2"', false);
        $response->assertSee('data-category-amount="40.00"', false);
        $response->assertSee('data-category-summary="design"', false);
        $response->assertSee('data-category-quantity="3"', false);
        $response->assertSee('data-category-amount="90.00"', false);
        $response->assertSee('data-category-summary="embroidery"', false);
        $response->assertSee('data-category-amount="50.00"', false);
        $response->assertSee('Total Thobes');
        $response->assertSee('7');
    }

    public function test_manager_can_filter_invoices_by_category(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Manager Category User']);

        TailorOrder::create([
            'user_id' => $manager->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-CAT-MANAGER-1',
            'fatora_number' => 'FAT-MGR-1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
        ]);

        TailorOrder::create([
            'user_id' => $manager->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-CAT-MANAGER-2',
            'fatora_number' => 'FAT-MGR-2',
            'thobe_category' => TailorOrder::CATEGORY_EMBROIDERY,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 25,
            'total_price' => 25,
        ]);

        $response = $this->actingAs($manager)->get(route('admin.orders.index', [
            'view' => 'invoices',
            'thobe_category' => TailorOrder::CATEGORY_EMBROIDERY,
        ]));

        $response->assertOk();
        $response->assertSee('INV-CAT-MANAGER-2');
        $response->assertDontSee('INV-CAT-MANAGER-1');
    }

    public function test_user_can_filter_own_reports_by_category(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Scoped Category User']);
        $otherUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Other Category User']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $user->id,
            'tailor_name' => $user->name,
            'invoice_number' => 'INV-CAT-USER-1',
            'fatora_number' => 'FAT-USER-1',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $user->id,
            'tailor_name' => $user->name,
            'invoice_number' => 'INV-CAT-USER-2',
            'fatora_number' => 'FAT-USER-2',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 30,
        ]);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $otherUser->id,
            'tailor_name' => $otherUser->name,
            'invoice_number' => 'INV-CAT-OTHER-1',
            'fatora_number' => 'FAT-OTHER-1',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 30,
            'total_price' => 30,
        ]);

        $response = $this->actingAs($user)->get(route('admin.orders.index', [
            'view' => 'report',
            'thobe_category' => TailorOrder::CATEGORY_DESIGN,
        ]));

        $response->assertOk();
        $response->assertSee('INV-CAT-USER-2');
        $response->assertDontSee('INV-CAT-USER-1');
        $response->assertDontSee('INV-CAT-OTHER-1');
    }

    public function test_invoice_and_report_pages_include_details_modal_with_status_update(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Tailor Detail']);
        $orderDate = now()->setTime(9, 45, 0);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-DETAIL-1',
            'fatora_number' => 'FAT-DETAIL',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => $orderDate,
            'unit_price' => 20,
            'total_price' => 40,
            'note' => 'Stitch cuffs carefully',
            'status' => TailorOrder::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('View Details')
            ->assertSee($orderDate->format('d M Y h:i A'))
            ->assertSee('Status Update')
            ->assertSee('Update Status');

        $this->actingAs($admin)
            ->get(route('admin.orders.index', ['view' => 'report']))
            ->assertOk()
            ->assertSee('View Details')
            ->assertSee('Status Update')
            ->assertSee('Update Status');
    }

    public function test_admin_can_download_filtered_report_as_pdf(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'PDF User']);

        TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-PDF-1',
            'fatora_number' => 'FAT-PDF',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', [
            'view' => 'report',
            'assigned_user_id' => $assignedUser->id,
            'export' => 'pdf',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition');
    }

    public function test_manager_can_download_filtered_report_as_pdf(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER, 'name' => 'Manager PDF User']);

        TailorOrder::create([
            'user_id' => $manager->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-MPDF-1',
            'fatora_number' => 'FAT-MPDF',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 2,
            'order_date' => now()->setTime(20, 2, 0),
            'unit_price' => 20,
            'total_price' => 40,
        ]);

        $response = $this->actingAs($manager)->get(route('admin.orders.index', [
            'view' => 'report',
            'assigned_user_id' => $assignedUser->id,
            'export' => 'pdf',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition');
    }

    public function test_admin_can_update_order_status_manually(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER]);
        $order = TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-COMP-1',
            'fatora_number' => 'FAT-COMP',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
            'status' => TailorOrder::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => TailorOrder::STATUS_IN_PROGRESS,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tailor_orders', [
            'id' => $order->id,
            'status' => TailorOrder::STATUS_IN_PROGRESS,
        ]);
    }

    public function test_admin_can_delete_order_from_invoice_table(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $assignedUser = User::factory()->create(['role' => User::ROLE_USER]);
        $order = TailorOrder::create([
            'user_id' => $admin->id,
            'assigned_user_id' => $assignedUser->id,
            'tailor_name' => $assignedUser->name,
            'invoice_number' => 'INV-DEL-1',
            'fatora_number' => 'FAT-DEL',
            'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
            'quantity' => 1,
            'order_date' => now()->toDateString(),
            'unit_price' => 20,
            'total_price' => 20,
            'status' => TailorOrder::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tailor_orders', [
            'id' => $order->id,
        ]);
    }

    public function test_admin_can_create_users_with_any_role(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('admin.users.create'))
            ->assertOk();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Manager',
            'email' => 'new-manager@example.com',
            'role' => User::ROLE_MANAGER,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'New Manager',
            'email' => 'new-manager@example.com',
            'role' => User::ROLE_MANAGER,
        ]);
    }

    public function test_admin_can_update_account_profile_and_password_from_access_control(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $managedUser = User::factory()->create([
            'role' => User::ROLE_USER,
            'email' => 'old-user@example.com',
            'password' => 'old-password',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => 'updated-user@example.com',
            'role' => User::ROLE_MANAGER,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $managedUser->refresh();

        $this->assertSame('Updated User', $managedUser->name);
        $this->assertSame('updated-user@example.com', $managedUser->email);
        $this->assertSame(User::ROLE_MANAGER, $managedUser->role);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $managedUser->password));
    }

    public function test_admin_can_search_access_control_table_by_name_email_and_role(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        User::factory()->create(['name' => 'Bilal User', 'email' => 'bilal@example.com', 'role' => User::ROLE_USER]);
        User::factory()->create(['name' => 'Manager One', 'email' => 'manager-one@example.com', 'role' => User::ROLE_MANAGER]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['search' => 'bilal']))
            ->assertOk()
            ->assertSee('Bilal User')
            ->assertDontSee('Manager One');

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['search' => 'manager-one@example.com']))
            ->assertOk()
            ->assertSee('Manager One')
            ->assertDontSee('Bilal User');

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['search' => User::ROLE_MANAGER]))
            ->assertOk()
            ->assertSee('Manager One')
            ->assertDontSee('Bilal User');
    }

    public function test_manager_cannot_access_user_management(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->actingAs($manager)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}
