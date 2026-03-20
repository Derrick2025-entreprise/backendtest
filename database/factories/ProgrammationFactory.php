<?php

namespace Database\Factories;

use App\Models\EC;
use App\Models\Personnel;
use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programmation>
 */
class ProgrammationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "num_salle" => (Salle::inRandomOrder()->first() ?? Salle::factory()->create())->num_salle,
            "code_ec" => (EC::inRandomOrder()->first() ?? EC::factory()->create())->code_ec,
            "code_personnel" => (Personnel::inRandomOrder()->first() ?? Personnel::factory()->create())->code_personnel,
            "nbre_heures" => fake()->numberBetween(4, 200),
            "date" => fake()->date(),
            "heure_debut" => fake()->time(),
            "heure_fin" => fake()->time(),
            "status" => fake()->randomElement(["PLANIFIÉ", "TERMINÉ", "ANNULÉ", "REPORTÉ"]),
        ];

    }
}
