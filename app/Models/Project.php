<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects'; // Specify the table name if it differs from the default
    protected $fillable = [
        'name',
        'location',
        'manager',
        'start_date',
        'end_date',
        'budget',
        'description',
        'status',
    ];

}

