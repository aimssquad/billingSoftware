<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationCountryDetail extends Model
{
    protected $fillable = [
        'organization_id',
        'field_key',
        'field_value',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function field()
    {
        return $this->belongsTo(CountryField::class, 'field_key', 'field_key')
            ->whereColumn('country_fields.country', 'organizations.country');
    }
}