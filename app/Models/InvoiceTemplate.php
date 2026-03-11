<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'preview_image',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Organizations using this template
     */
    public function organizationSettings()
    {
        return $this->hasMany(OrganizationInvoiceSetting::class, 'invoice_template_id');
    }

    /**
     * Accessor for preview image URL
     */
    public function getPreviewImageUrlAttribute()
    {
        return $this->preview_image
            ? asset('storage/'.$this->preview_image)
            : null;
    }



  
}