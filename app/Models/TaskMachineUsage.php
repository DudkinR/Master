<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMachineUsage extends Model
{
      protected $table = 'task_machine_usages';

    protected $fillable = [
        'task_id',
        'machine_id',
        'start_time',
        'end_time',
        'performed_task', // bool або коментар
        'user_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
