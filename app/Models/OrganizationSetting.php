<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationSetting extends Model
{
    protected $fillable = [
        'organization_id',
        'email_enabled',
        'payment_enabled',
        'smtp_configured',
        'payment_configured',
    ];

    protected function casts(): array
    {
        return [
            'email_enabled' => 'boolean',
            'payment_enabled' => 'boolean',
            'smtp_configured' => 'boolean',
            'payment_configured' => 'boolean',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
