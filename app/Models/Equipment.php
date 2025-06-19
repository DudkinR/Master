<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    //table name
    protected $table = 'equipments';
    //fillable attributes
    protected $fillable = [
        'name',
        'description',
    ];  
    // tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'tasks_equipments')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }
    // projects

}
