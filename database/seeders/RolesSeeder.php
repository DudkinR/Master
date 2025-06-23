<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
           /**
         * Admin
         * Engineer
         * Master
         * Brigadir
         * Worker
         * Guest
         * return only this roles
         */
        $roles = [
            ['Admin', 'Можно все'],
            ['Boss', 'Можно все кроме админки'],
            ['Engineer', 'Можно все кроме админки'],
            ['Master', 'Можно все кроме админки и инженера'],
            ['Brigadir', 'Можно все кроме админки, инженера и мастера'],
            ['Worker', 'Можно все кроме админки, инженера, мастера и бригадира'],
            ['Guest', 'Только просмотр']
        ];
        foreach ($roles as $role) {
            if (Role::where('name', $role[0])->exists()) {
                continue; // Skip if the role already exists
            }
            Role::create(['name' => $role[0], 'description' => $role[1]  ]);
        }
    }
}
