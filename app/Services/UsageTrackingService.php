<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationSubscription;
use App\Models\UsageTracking;
use Carbon\Carbon;

class UsageTrackingService
{
    /**
     * Record one usage (e.g. when an invoice is created).
     */
    public function record(
        int $organizationId,
        int $subscriptionId,
        string $usageType,
        int $referenceId
    ): UsageTracking {
        return UsageTracking::create([
            'organization_id' => $organizationId,
            'subscription_id' => $subscriptionId,
            'usage_type' => $usageType,
            'reference_id' => $referenceId,
            'usage_month' => Carbon::now()->format('Y-m'),
        ]);
    }

    /**
     * Get current month invoice count for organization (for limit check).
     */
    public function getInvoiceCountForCurrentMonth(int $organizationId): int
    {
        return UsageTracking::where('organization_id', $organizationId)
            ->where('usage_type', 'invoice')
            ->where('usage_month', Carbon::now()->format('Y-m'))
            ->count();
    }

    /**
     * Check if organization can create more invoices this month (within plan limit).
     */
    public function canCreateInvoice(Organization $organization): bool
    {
        $subscriptionService = app(SubscriptionService::class);
        $activeSub = $subscriptionService->getActiveSubscription($organization);
        if (! $activeSub) {
            return false;
        }
        $limit = $activeSub->plan->invoice_limit;
        $used = $this->getInvoiceCountForCurrentMonth($organization->id);
        return $used < $limit;
    }

    /**
     * Get usage summary for current month (for API response).
     */
    public function getUsageSummary(int $organizationId): array
    {
        $month = Carbon::now()->format('Y-m');
        $invoiceCount = UsageTracking::where('organization_id', $organizationId)
            ->where('usage_type', 'invoice')
            ->where('usage_month', $month)
            ->count();
        $purchaseCount = UsageTracking::where('organization_id', $organizationId)
            ->where('usage_type', 'purchase')
            ->where('usage_month', $month)
            ->count();

        return [
            'usage_month' => $month,
            'invoice_count' => $invoiceCount,
            'purchase_count' => $purchaseCount,
        ];
    }
}
