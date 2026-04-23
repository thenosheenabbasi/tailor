<?php

namespace Database\Seeders;

use App\Models\TailorOrder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@tailor.test'],
            [
                'name' => 'Tailor Admin',
                'role' => User::ROLE_ADMIN,
                'password' => 'password',
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@tailor.test'],
            [
                'name' => 'Tailor Manager',
                'role' => User::ROLE_MANAGER,
                'password' => 'password',
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@tailor.test'],
            [
                'name' => 'Tailor User',
                'role' => User::ROLE_USER,
                'password' => 'password',
            ]
        );

        TailorOrder::updateOrCreate(
            ['invoice_number' => 'INV-1001'],
            [
                'user_id' => $admin->id,
                'assigned_user_id' => $user->id,
                'tailor_name' => 'Al Noor Tailors',
                'thobe_category' => TailorOrder::CATEGORY_SIMPLE,
                'fatora_number' => '092328',
                'quantity' => 2,
                'order_date' => now()->toDateString(),
                'unit_price' => TailorOrder::unitPriceFor(TailorOrder::CATEGORY_SIMPLE),
                'total_price' => TailorOrder::unitPriceFor(TailorOrder::CATEGORY_SIMPLE) * 2,
                'status' => TailorOrder::STATUS_PENDING,
                'note' => 'Sample record for dashboard preview.',
            ]
        );

        TailorOrder::updateOrCreate(
            ['invoice_number' => 'INV-1002'],
            [
                'user_id' => $manager->id,
                'assigned_user_id' => $user->id,
                'tailor_name' => 'Royal Stitch',
                'thobe_category' => TailorOrder::CATEGORY_DESIGN,
                'fatora_number' => '092329',
                'quantity' => 1,
                'order_date' => now()->subDay()->toDateString(),
                'unit_price' => TailorOrder::unitPriceFor(TailorOrder::CATEGORY_DESIGN),
                'total_price' => TailorOrder::unitPriceFor(TailorOrder::CATEGORY_DESIGN),
                'status' => TailorOrder::STATUS_COMPLETED,
                'completed_at' => now()->subHours(4),
                'note' => 'Custom collar and sleeve notes.',
            ]
        );

        $user->touch();
    }
}
