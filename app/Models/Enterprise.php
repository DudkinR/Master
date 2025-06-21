<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    //
    protected $table = 'enterprise';
    protected $fillable = [
        'name',
        'description',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_enterprise')
                    ->withTimestamps();
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'enterprise_department')
                    ->withTimestamps();
    }
}
