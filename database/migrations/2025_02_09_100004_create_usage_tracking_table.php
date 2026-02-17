<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained('organization_subscriptions')->cascadeOnDelete();
            $table->enum('usage_type', ['invoice', 'purchase']);
            $table->unsignedBigInteger('reference_id');
            $table->char('usage_month', 7); // YYYY-MM
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_tracking');
    }
};
