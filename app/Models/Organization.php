<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    protected $fillable = [
        'organization_code',
        'company_name',
        'legal_name',
        'email',
        'phone',
        'gstin',
        'address',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(OrganizationSubscription::class, 'organization_id');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(OrganizationSubscription::class, 'organization_id')
            ->where('status', 'active')
            ->latest();
    }

    public function settings(): HasOne
    {
        return $this->hasOne(OrganizationSetting::class, 'organization_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'organization_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'organization_id');
    }
}
