<?php

namespace App\Services;
use App\Models\OrganizationPaymentGateway;
use Carbon\Carbon;

class PaymentGatewayService
{
    public function getActiveGateway($organization_id)
    {
        return OrganizationPaymentGateway::where('organization_id', $organization_id)
            ->where('is_active', true)
            ->firstOrFail();
    }
}