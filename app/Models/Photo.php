<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $table = 'photos'; // Specify the table name if it differs from the default
    protected $fillable = [
        'url', // URL of the photo
    ];
    /**
     * Get the URL of the photo.
     *
     * @return string
     */
    public function getUrlAttribute($value)
    {
        return asset('storage/' . $value); // Assuming the URL is stored as a relative path
    }
    // photo_task photo_problem photo_action
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'photo_task')
                    ->withTimestamps();
    }
    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'photo_problem')
                    ->withTimestamps();
    }
    public function actions()
    {
        return $this->belongsToMany(TaskAction::class, 'photo_action')
                    ->withTimestamps();
    }
}
