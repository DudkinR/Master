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
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'projects_tasks')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    // enterprises
    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class, 'enterprise_projects')
                    ->withTimestamps(); 
    }
    // departments
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_projects')
                    ->withTimestamps(); 
    }


}

