<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Filiere>
 */
class FiliereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code_filiere" => fake()->unique()->bothify('FIL-###'),
            "label_filiere" => fake()->words(2, true),
            "description_filiere" => fake()->paragraph(),
        ];
    }
}
