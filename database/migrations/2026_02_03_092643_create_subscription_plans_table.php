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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained()->onDelete('cascade');
            $table->string('name', 100); // e.g., "Mobile", "Basic", "Premium"
            $table->integer('duration_months'); // 1, 3, 6, 12
            $table->decimal('original_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->integer('max_screens')->default(1);
            $table->string('quality', 50)->nullable(); // SD, HD, 4K
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // Store features as JSON array
            $table->boolean('is_active')->default(1);
            $table->integer('stock_available')->default(0); // How many subscriptions you have
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index(['platform_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};