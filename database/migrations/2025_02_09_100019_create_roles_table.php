<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Display name: "Super Admin", "Org Owner", "Org User"
            $table->string('slug', 50)->unique();  // Code: super_admin, org_owner, org_user
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Platform owner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Organization Owner', 'slug' => 'org_owner', 'description' => 'Company owner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Organization User', 'slug' => 'org_user', 'description' => 'Staff member', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
