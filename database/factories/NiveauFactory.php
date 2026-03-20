<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Niveau>
 */
class NiveauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // récupérer une filière existante OU en créer une
        $filiere = Filiere::inRandomOrder()->first() ?? Filiere::factory()->create();

        return [
            "label_niveau" => fake()->words(2, true),
            "description_niveau" => fake()->paragraph(),
            "code_filiere" => $filiere->code_filiere,
        ];
    }
}
