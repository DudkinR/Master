<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskActionLog extends Model
{
     protected $table = 'task_action_logs';

    protected $fillable = [
        'task_id',
        'user_id',
        'action_type',     // enum або string: start, paused, end_day, complete, issue, missing_resource, machine_start, machine_end
        'comment',         // примітки або аналіз
        'location',        // GPS або описове місце
    ];

    // Зв'язок з Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Користувач, який виконав дію
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Фото
    public function photos()
    {
        return $this->hasMany(TaskActionPhoto::class, 'log_id');
    }

    // Відсутні ресурси (матеріали або обладнання)
    public function missingResources()
    {
        return $this->hasMany(TaskMissingResource::class, 'log_id');
    }
}
