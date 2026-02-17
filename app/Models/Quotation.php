<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'organization_id',
        'customer_id',
        'quotation_no',
        'quotation_date',
        'valid_till',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quotation_date' => 'date',
            'valid_till' => 'date',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
