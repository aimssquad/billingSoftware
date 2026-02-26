<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OrganizationPaymentGateway extends Model
{
    protected $fillable = [
        'organization_id',
        'gateway',
        'public_key',
        'secret_key',
        'webhook_secret',
        'mode',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Encrypt / Decrypt Secret Key
    |--------------------------------------------------------------------------
    */

    public function setSecretKeyAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['secret_key'] = Crypt::encryptString($value);
        }
    }

    public function getSecretKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isStripe()
    {
        return $this->gateway === 'stripe';
    }

    public function isRazorpay()
    {
        return $this->gateway === 'razorpay';
    }

    public function isPaypal()
    {
        return $this->gateway === 'paypal';
    }

    public function isLive()
    {
        return $this->mode === 'live';
    }
}