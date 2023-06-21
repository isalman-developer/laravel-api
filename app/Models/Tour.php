<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];

    function travels() : HasMany {
        return $this->hasMany(Travel::class);
    }

    // latest syntax for get and set attribute
    function price() : Attribute {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100,
        );
    }
}
