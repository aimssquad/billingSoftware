<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryField extends Model
{
    protected $fillable = [
        'country',
        'field_key',
        'field_label',
        'field_type',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
    ];
}