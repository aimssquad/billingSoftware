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
        Schema::create('organization_payment_gateways', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('gateway', ['stripe', 'razorpay', 'paypal']);

            $table->string('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->string('webhook_secret')->nullable();

            $table->enum('mode', ['sandbox', 'live'])
                ->default('sandbox');

            $table->boolean('is_active')
                ->default(false)
                ->index();

            $table->timestamps();

            // Prevent duplicate gateway per organization
            $table->unique(['organization_id', 'gateway']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_payment_gateways');
    }
};
