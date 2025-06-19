<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    //
    protected $table = 'machines';
    protected $fillable = [
        'name',
        'description',
        'type',
        'capacity',

    ];
    // tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'tasks_machines')
                 //   ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    
    }       
 }
