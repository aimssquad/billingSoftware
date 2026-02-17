<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageTracking extends Model
{
    public $timestamps = false;

    protected $table = 'usage_tracking';

    protected $fillable = [
        'organization_id',
        'subscription_id',
        'usage_type',
        'reference_id',
        'usage_month',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(OrganizationSubscription::class, 'subscription_id');
    }
}
