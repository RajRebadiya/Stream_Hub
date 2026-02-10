<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL - modify the enum to include 'free' status
        DB::statement("ALTER TABLE `orders` CHANGE `payment_status` `payment_status` ENUM('pending','paid','failed','refunded','free') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `orders` CHANGE `payment_status` `payment_status` ENUM('pending','paid','failed','refunded') DEFAULT 'pending'");
    }
};
