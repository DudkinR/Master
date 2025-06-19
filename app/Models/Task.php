<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table = 'tasks'; // Specify the table name if it differs from the default
    protected $fillable = [
        'order',
        'name',
        'description',
        'start_date',
        'end_date',
        'estimated_hours',
        'actual_hours',
        'progress',
        'quantity',
        'unit',
        'project_id',
    ];
}
