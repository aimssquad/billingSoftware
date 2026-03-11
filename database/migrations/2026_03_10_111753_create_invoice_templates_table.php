<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_templates', function (Blueprint $table) {

            $table->id();
            $table->string('name'); // Default, Classic, Minimal
            $table->string('slug')->unique(); // default, classic
            $table->string('preview_image')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_templates');
    }
};