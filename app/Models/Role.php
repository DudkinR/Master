<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'roles'; // Specify the table name if it differs from the default
    protected $fillable = [
        'name',
        'description',
    ];
}

