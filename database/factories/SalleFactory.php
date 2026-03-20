<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salle>
 */
class SalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "num_salle" => fake()->unique()->numberBetween(1, 1000),
            "contenance" => fake()->numberBetween(20, 200),
            "status" => fake()->randomElement(['OCCUPEE', 'LIBRE']),
        ];
    }
}
