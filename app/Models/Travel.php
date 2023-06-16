<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;
    protected $table = 'travels'; //by default laravel convert singular table name to plural, but for the travel the plural is travel, but for the sake of client we have to change it to travels.
}
