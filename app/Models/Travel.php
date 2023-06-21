<?php

namespace App\Models;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory, Sluggable, HasUuids;
    protected $table = 'travels'; //by default laravel convert singular table name to plural, but for the travel the plural is travel, but for the sake of client we have to change it to travels.

    protected $fillable = ['name', 'is_public', 'description', 'no_of_days', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    function getNumberOfNightsAttribute() {
        return $this->no_of_days - 1;
    }

    function tours() : HasMany {
        return $this->hasMany(Tour::class);
    }
}
