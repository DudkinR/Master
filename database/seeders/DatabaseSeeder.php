<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Enterprise;
use App\Models\Department;
use Database\Seeders\RolesSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
    /*
        
   Error 

  Non-static method Database\Seeders\RolesSeeder::run() cannot be called statically

  */
        $this->call(RolesSeeder::class);
        $users = User::all();
        $roles=  Role::all();
        foreach ($users as $user) {
            if ($user->id!== 1) {
                
                $user->roles()->attach(
                    $roles->random(1)->pluck('id')->toArray()
                );
            }
            $user->enterprises()->attach(
                Enterprise::all()->random(1)->pluck('id')->toArray()
            );
            $user->departments()->attach(
                Department::all()->random(1)->pluck('id')->toArray()
            );
            $user->save();
        }
    }
}
