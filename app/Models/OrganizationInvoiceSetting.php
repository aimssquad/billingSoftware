<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationInvoiceSetting extends Model
{
    protected $fillable = [
        'organization_id',
        'invoice_template_id',
        'invoice_prefix'
    ];

    public function template()
    {
        return $this->belongsTo(InvoiceTemplate::class,'invoice_template_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}