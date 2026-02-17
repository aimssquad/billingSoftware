<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRoleId = Role::where('slug', 'super_admin')->value('id');
        User::firstOrCreate(
            ['email' => 'superadmin@billing.test'],
            [
                'organization_id' => null,
                'role_id' => $superAdminRoleId,
                'name' => 'Super Admin',
                'phone' => null,
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );
    }
}
