<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Material;
use App\Models\Machine;
use App\Models\Equipment;   

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
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projects_tasks')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    // Materials

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'tasks_materials')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }
    // Machines
    public function machines()
    {
        return $this->belongsToMany(Machine::class, 'tasks_machines')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    
    }
    // equipments 
    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'tasks_equipments')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }
}