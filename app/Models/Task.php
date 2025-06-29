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
        //>enum('status', ['pending', 'in_progress', 'completed', 'paused'])
        'status', // New status column
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
    // users
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_tasks')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function master()
    {
        // Один майстер
        return $this->users()->wherePivot('role', 'master')->limit(1);
    }

    public function brigadir()
    {
        // Один бригадир
        return $this->users()->wherePivot('role', 'brigadir')->limit(1);
    }

    public function workers()
    {
        // Багато робітників
        return $this->users()->wherePivot('role', 'worker');
    }

    // All accept master
    public function acceptMaster()
    {
        return $this->users()
            ->wherePivot('role', 'worker')
            ->orWherePivot('role', 'brigadir');
    }
    // enterprises
    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class, 'enterprise_tasks')
                    ->withTimestamps();
    }
    // departments
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_tasks')
                    ->withTimestamps();
    }
    // TaskActionLogs
    public function actionLogs()
    {
        return $this->hasMany(TaskActionLog::class);
    }

    // MachineUsage
    public function machineUsages()
    {
        return $this->hasMany(TaskMachineUsage::class);
    }


}