<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Models\OrganizationSubscription;
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

    public function store(Request $request)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'nullable|date',
        ]);

        $organization = $request->attributes->get('organization');

        $plan = \App\Models\SubscriptionPlan::findOrFail($request->subscription_plan_id);

        $subscription = app(\App\Services\SubscriptionService::class)
            ->createSubscription(
                $organization,
                $plan,
                $request->start_date ? \Carbon\Carbon::parse($request->start_date) : null
            );

        return response()->json([
            'message' => 'Subscription created successfully',
            'subscription_id' => $subscription->id
        ]);
    }

    public function edit(Request $request, $id)
    {   
        $request->validate([
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:active,cancelled,expired'
        ]);

        $organization = $request->attributes->get('organization');
        //dd($organization->id, $id);
        $subscription = \App\Models\OrganizationSubscription::where('subscription_plan_id', $id)
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        $subscription->update($request->only(['end_date', 'status']));

        return response()->json([
            'message' => 'Subscription updated successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $organization = $request->attributes->get('organization');

        $subscription = \App\Models\OrganizationSubscription::where('id', $id)
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        // Cancel old subscription
        $subscription->update([
            'status' => 'cancelled'
        ]);

        $plan = \App\Models\SubscriptionPlan::findOrFail($request->subscription_plan_id);

        $newSubscription = app(\App\Services\SubscriptionService::class)
            ->createSubscription($organization, $plan);

        return response()->json([
            'message' => 'Subscription updated successfully',
            'new_subscription_id' => $newSubscription->id
        ]);
    }
}
