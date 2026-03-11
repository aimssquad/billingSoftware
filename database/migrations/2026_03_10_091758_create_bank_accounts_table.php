<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {

            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            $table->string('account_name')->nullable();
            $table->string('bank_name');
            $table->string('account_holder_name');
            $table->string('account_number');

            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('sort_code')->nullable();

            $table->string('branch_name')->nullable();
            $table->text('branch_address')->nullable();
            $table->string('bank_country')->nullable();
            $table->string('currency')->nullable();

            $table->string('upi_id')->nullable();
            $table->string('qr_code')->nullable();

            $table->boolean('is_default')->default(false);

            $table->enum('status', ['active','inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};