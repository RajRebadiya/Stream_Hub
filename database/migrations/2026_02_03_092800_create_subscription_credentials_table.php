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
        Schema::create('subscription_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained();
            $table->string('email')->nullable();
            $table->text('password')->nullable(); // Encrypted
            $table->string('profile_name', 100)->nullable();
            $table->text('pin')->nullable(); // Encrypted
            $table->enum('status', ['available', 'assigned', 'expired', 'blocked'])->default('available');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'subscription_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_credentials');
    }
};