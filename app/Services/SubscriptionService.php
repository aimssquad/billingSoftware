<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Create a new subscription for an organization (e.g. after signup or plan change).
     */
    public function createSubscription(
        Organization $organization,
        SubscriptionPlan $plan,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): OrganizationSubscription {
        $startDate = $startDate ?? Carbon::today();
        $endDate = $endDate ?? $this->calculateEndDate($startDate, $plan->billing_cycle);

        // Mark any current active subscription as cancelled or expired
        OrganizationSubscription::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        return OrganizationSubscription::create([
            'organization_id' => $organization->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    }

    public function calculateEndDate(Carbon $start, string $billingCycle): Carbon
    {
        if ($billingCycle === 'yearly') {
            return $start->copy()->addYear()->subDay();
        }
        return $start->copy()->addMonth()->subDay();
    }

    /**
     * Get active subscription for organization (or null).
     */
    public function getActiveSubscription(Organization $organization): ?OrganizationSubscription
    {
        return OrganizationSubscription::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::today())
            ->with('plan')
            ->first();
    }

    /**
     * Check if organization has an active subscription.
     */
    public function hasActiveSubscription(Organization $organization): bool
    {
        return $this->getActiveSubscription($organization) !== null;
    }
}
