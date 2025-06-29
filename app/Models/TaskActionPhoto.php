<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskActionPhoto extends Model
{
   protected $table = 'task_action_photos';

    protected $fillable = [
        'log_id',
        'file_path',
    ];

    public function log()
    {
        return $this->belongsTo(TaskActionLog::class, 'log_id');
    }
}
