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
        Schema::create('country_fields', function (Blueprint $table) {
            $table->id();
            $table->string('country'); // india, uk, usa
            $table->string('field_key'); // it_file, rti_file
            $table->string('field_label'); // IT File Number
            $table->string('field_type'); // text, file, number
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_fields');
    }
};
