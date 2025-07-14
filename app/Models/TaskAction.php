<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAction extends Model
{
    /*
                'start', початок завдання
                'start_day', початок робочого дня
                'pause', призупинення завдання
                'start_work', початок роботи
                'end_day', кінець робочого дня
                'complete', завершення завдання
                'issue', проблема з завданням
                'missing_resource', відсутній ресурс
                'machine_start', початок роботи машини

                'machine_pause', призупинення роботи машини
                'machine_end'    , кінець роботи машини              
          
    */
    protected $table = 'task_actions';
    protected $fillable = [
        'task_id',
        'user_id',
        'machine_id',
        'action_type',
        'comment',
    ];
    protected $casts = [
        'machine_id' => 'integer',
        'action_type' => 'string',
        'comment' => 'string',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
    public function scopeOfTask($query, $taskId)
    {
        return $query->where('task_id', $taskId);
    }
}
