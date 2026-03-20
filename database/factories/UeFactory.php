<?php

namespace Database\Factories;

use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UE>
 */
class UeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code_ue" => fake()->unique()->bothify('UE-###'),
            "label_ue" => fake()->words(2, true),
            "description_ue" => fake()->paragraph(),
            "code_niveau" => (Niveau::inRandomOrder()->first() ?? Niveau::factory()->create())->code_niveau,
        ];
    }
}
