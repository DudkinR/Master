<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMissingResource extends Model
{
    //
      protected $table = 'task_missing_resources';

    protected $fillable = [
        'log_id',
        'resource_type',   // 'material' або 'equipment'
        'resource_id',
        'quantity',
    ];

    public function log()
    {
        return $this->belongsTo(TaskActionLog::class, 'log_id');
    }
}
