<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryChallan extends Model
{
    protected $fillable = [
        'organization_id',
        'customer_id',
        'challan_no',
        'challan_date',
        'status',
    ];

    protected function casts(): array
    {
        return ['challan_date' => 'date'];
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
