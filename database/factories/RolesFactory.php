<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->randomElement([
                'Admin',
                'Engineer',
                'Master',
                'Brigadir',
                'Worker',
                'Guest'
            ]),
            'description' => fake()->sentence(6, true) . ' ' . fake()->randomElement([
                'Можно все',
                'Можно все кроме админки',
                'Можно все кроме админки и инженера',
                'Можно все кроме админки, инженера и мастера',
                'Можно все кроме админки, инженера, мастера и бригадира',
                'Только просмотр'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
