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
            ['email' => 'hindham.business@gmail.com'],
            [
                'name' => 'Tailor Admin',
                'role' => User::ROLE_ADMIN,
                'password' => 'Aa112233--@',
            ]
        );
    }
}
