<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $table = 'problems';

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'status',
        'user_id',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photo_problem')
                    ->withTimestamps();
    }
    public function scopeOpen($query)
    {
        return $query->where('status', '!=', 'closed');
    }
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
