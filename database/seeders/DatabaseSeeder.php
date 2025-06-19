<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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
            $user->roles()->attach(
                        /**
         * Admin
         * Ingenier
         * Master
         * Brigadir
         * Worker
         * Guest
         * return only this roles
         */
                $roles->random(1)->pluck('id')->toArray()
            );

        }
    }
}
