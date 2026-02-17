<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'plan_name' => 'Starter',
                'billing_cycle' => 'monthly',
                'invoice_limit' => 10,
                'price' => 299,
                'email_feature' => false,
                'payment_feature' => false,
                'status' => 'active',
            ],
            [
                'plan_name' => 'Starter',
                'billing_cycle' => 'yearly',
                'invoice_limit' => 10,
                'price' => 2990,
                'email_feature' => false,
                'payment_feature' => false,
                'status' => 'active',
            ],
            [
                'plan_name' => 'Professional',
                'billing_cycle' => 'monthly',
                'invoice_limit' => 100,
                'price' => 799,
                'email_feature' => true,
                'payment_feature' => false,
                'status' => 'active',
            ],
            [
                'plan_name' => 'Professional',
                'billing_cycle' => 'yearly',
                'invoice_limit' => 100,
                'price' => 7990,
                'email_feature' => true,
                'payment_feature' => false,
                'status' => 'active',
            ],
            [
                'plan_name' => 'Enterprise',
                'billing_cycle' => 'monthly',
                'invoice_limit' => 9999,
                'price' => 1999,
                'email_feature' => true,
                'payment_feature' => true,
                'status' => 'active',
            ],
            [
                'plan_name' => 'Enterprise',
                'billing_cycle' => 'yearly',
                'invoice_limit' => 9999,
                'price' => 19990,
                'email_feature' => true,
                'payment_feature' => true,
                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(
                [
                    'plan_name' => $plan['plan_name'],
                    'billing_cycle' => $plan['billing_cycle'],
                ],
                $plan
            );
        }
    }
}
