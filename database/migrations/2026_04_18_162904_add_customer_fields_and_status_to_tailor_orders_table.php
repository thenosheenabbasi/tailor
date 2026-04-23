<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tailor_orders', function (Blueprint $table) {
            $table->string('customer_name')->after('assigned_user_id');
            $table->string('customer_phone', 30)->nullable()->after('customer_name');
            $table->string('status')->default('pending')->after('total_price');
            $table->timestamp('completed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tailor_orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'status',
                'completed_at',
            ]);
        });
    }
};
