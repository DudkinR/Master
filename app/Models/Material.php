<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    protected $table = 'materials'; // Specify the table name if it differs from the default
    protected $fillable = [
        'name',
        'description',
        'unit', // Unit of measurement, e.g., m2, pcs
    ];

}
