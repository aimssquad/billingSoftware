<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_invoice_settings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('invoice_template_id')
                ->constrained('invoice_templates')
                ->cascadeOnDelete();
            $table->string('invoice_prefix')->nullable();

            $table->timestamps();

            $table->unique('organization_id'); // one setting per organization
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_invoice_settings');
    }
};