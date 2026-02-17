<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Services\SubscriptionService;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $plan = \App\Models\SubscriptionPlan::where('plan_name', 'Professional')->where('billing_cycle', 'monthly')->first();
        if (! $plan) {
            return;
        }

        $org = Organization::firstOrCreate(
            ['organization_code' => 'DEMO001'],
            [
                'company_name' => 'Demo Company Pvt Ltd',
                'legal_name' => 'Demo Company Private Limited',
                'email' => 'demo@example.com',
                'phone' => '9876543210',
                'gstin' => '29AABCU9603R1ZM',
                'address' => '123 Demo Street, City - 560001',
                'status' => 'active',
            ]
        );

        $orgOwnerRoleId = Role::where('slug', 'org_owner')->value('id');
        $owner = User::firstOrCreate(
            ['email' => 'owner@demo.com'],
            [
                'organization_id' => $org->id,
                'role_id' => $orgOwnerRoleId,
                'name' => 'Demo Owner',
                'phone' => '9876543211',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        if (! $org->activeSubscription) {
            app(SubscriptionService::class)->createSubscription($org, $plan);
        }
        if (! $org->settings) {
            $org->settings()->create([
                'email_enabled' => true,
                'payment_enabled' => false,
                'smtp_configured' => false,
                'payment_configured' => false,
            ]);
        }

        if (Customer::where('organization_id', $org->id)->count() < 2) {
            Customer::create([
                'organization_id' => $org->id,
                'name' => 'ABC Customer Ltd',
                'email' => 'abc@customer.com',
                'phone' => '9999888877',
                'gstin' => '27AABCU9603R1Z1',
                'billing_address' => '456 Customer Ave, Mumbai',
                'status' => 'active',
            ]);
            Customer::create([
                'organization_id' => $org->id,
                'name' => 'XYZ Traders',
                'email' => 'xyz@traders.com',
                'phone' => '8888777766',
                'billing_address' => '789 Trade Road, Delhi',
                'status' => 'active',
            ]);
        }

        if (Vendor::where('organization_id', $org->id)->count() < 2) {
            Vendor::create([
                'organization_id' => $org->id,
                'name' => 'Supplier One',
                'email' => 'supplier1@vendor.com',
                'phone' => '7777666655',
                'gstin' => '07AABCS1234A1Z5',
                'address' => '100 Industrial Area',
                'status' => 'active',
            ]);
            Vendor::create([
                'organization_id' => $org->id,
                'name' => 'Vendor Two',
                'email' => 'vendor2@example.com',
                'phone' => '6666555544',
                'address' => '200 Warehouse Lane',
                'status' => 'active',
            ]);
        }
    }
}
