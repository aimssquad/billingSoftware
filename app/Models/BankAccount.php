<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'account_name',
        'bank_name',
        'account_holder_name',
        'account_number',
        'iban',
        'swift_code',
        'routing_number',
        'ifsc_code',
        'sort_code',
        'branch_name',
        'branch_address',
        'bank_country',
        'currency',
        'upi_id',
        'qr_code',
        'is_default',
        'status'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code ? asset('storage/'.$this->qr_code) : null;
    }
}