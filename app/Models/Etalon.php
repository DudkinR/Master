<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etalon extends Model
{
    //
    protected $table = 'etalons';
    protected $fillable = [
        'id_user',
        'name',
        'description',
        'estimated_hours',
        'actual_hours',      
        'quantity',
        'unit',
    ];
}
