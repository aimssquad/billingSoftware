<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Services\UsageTrackingService;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $summary = app(UsageTrackingService::class)->getUsageSummary($organizationId);
        $subscription = $request->attributes->get('organization')->activeSubscription;
        $limit = $subscription ? $subscription->plan->invoice_limit : 0;
        return response()->json([
            'usage' => $summary,
            'invoice_limit' => $limit,
            'invoice_remaining' => max(0, $limit - ($summary['invoice_count'] ?? 0)),
        ]);
    }
}
