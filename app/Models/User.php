<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withTimestamps(); 
    }
    // tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'users_tasks')
                    ->withPivot('role') // Include the role in the pivot table
                    ->withTimestamps(); // Include timestamps for the pivot table
    }
    // enterprises
    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class, 'user_enterprise')
                    ->withTimestamps();
    }
    // departments
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_department')
                    ->withTimestamps();
    }
   // Начальник → його підлеглі
    public function children()
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id', 'related_user_id')->withTimestamps();
    }

    // Підлеглий → його начальники
    public function supervisors()
    {
        return $this->belongsToMany(User::class, 'user_user', 'related_user_id', 'user_id')->withTimestamps();
    }
    // role
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
