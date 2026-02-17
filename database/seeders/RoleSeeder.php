<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Platform owner'],
            ['name' => 'Organization Owner', 'slug' => 'org_owner', 'description' => 'Company owner'],
            ['name' => 'Organization User', 'slug' => 'org_user', 'description' => 'Staff member'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
