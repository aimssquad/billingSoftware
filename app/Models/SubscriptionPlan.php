<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'plan_name',
        'billing_cycle',
        'invoice_limit',
        'price',
        'email_feature',
        'payment_feature',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'email_feature' => 'boolean',
            'payment_feature' => 'boolean',
        ];
    }

    public function organizationSubscriptions(): HasMany
    {
        return $this->hasMany(OrganizationSubscription::class, 'subscription_plan_id');
    }
}
