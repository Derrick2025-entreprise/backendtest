<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personnel>
 */
class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code_personnel" => fake()->unique()->bothify('PER-###'),
            "nom_personnel" => fake()->lastName(),
            "prenom_personnel" => fake()->firstName(),
            "sexe_personnel" => fake()->randomElement(['M', 'F']),
            "phone_personnel" => fake()->phoneNumber(),
            "login_personnel" => fake()->unique()->userName(),
            "password_personnel" => bcrypt('password'),
            "type_personnel" => fake()->randomElement(['ENSEIGNANT', 'RESPONSABLE_ACADEMIQUE', 'RESPONSABLE_DISCIPLINE']),
        ];
    }
}
