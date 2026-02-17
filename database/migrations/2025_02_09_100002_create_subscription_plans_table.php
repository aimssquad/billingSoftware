<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->unsignedInteger('invoice_limit');
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('email_feature')->default(false);
            $table->boolean('payment_feature')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
