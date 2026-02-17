<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function show(Request $request)
    {
        $organization = $request->attributes->get('organization');
        $sub = app(SubscriptionService::class)->getActiveSubscription($organization);
        if (! $sub) {
            return response()->json(['message' => 'No active subscription.', 'subscription' => null], 404);
        }
        return response()->json([
            'subscription' => [
                'id' => $sub->id,
                'start_date' => $sub->start_date->format('Y-m-d'),
                'end_date' => $sub->end_date->format('Y-m-d'),
                'status' => $sub->status,
                'plan' => [
                    'id' => $sub->plan->id,
                    'plan_name' => $sub->plan->plan_name,
                    'billing_cycle' => $sub->plan->billing_cycle,
                    'invoice_limit' => $sub->plan->invoice_limit,
                ],
            ],
        ]);
    }
}
