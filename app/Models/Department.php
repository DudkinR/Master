<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'department';
    protected $fillable = [
        'name',
        'description',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department')
                    ->withTimestamps();
    }
    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class, 'enterprise_department')
                    ->withTimestamps();
    }
}
