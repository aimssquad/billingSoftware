<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('organization_id')->constrained('roles')->cascadeOnDelete();
        });

        $roles = DB::table('roles')->pluck('id', 'slug');
        foreach (['super_admin', 'org_owner', 'org_user'] as $slug) {
            $roleId = $roles[$slug] ?? null;
            if ($roleId) {
                DB::table('users')->where('role', $slug)->update(['role_id' => $roleId]);
            }
        }
        DB::table('users')->whereNull('role_id')->update(['role_id' => $roles['org_user']]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'org_owner', 'org_user'])->default('org_user')->after('organization_id');
        });
        $roles = DB::table('roles')->pluck('slug', 'id');
        foreach ($roles as $id => $slug) {
            DB::table('users')->where('role_id', $id)->update(['role' => $slug]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
