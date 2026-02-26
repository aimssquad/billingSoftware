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
        Schema::create('organization_mail_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('driver')->default('smtp'); // future: ses, sendgrid
            $table->string('host');
            $table->integer('port');
            $table->string('username');
            $table->text('password'); // encrypted
            $table->string('encryption')->nullable(); // tls / ssl

            $table->string('from_address');
            $table->string('from_name');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_mail_settings');
    }
};
