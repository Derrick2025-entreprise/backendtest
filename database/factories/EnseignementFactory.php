<?php

namespace Database\Factories;

use App\Models\EC;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enseignement>
 */
class EnseignementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code_personnel" => (Personnel::inRandomOrder()->first() ?? Personnel::factory()->create())->code_personnel,
            "code_ec" => (EC::inRandomOrder()->first() ?? EC::factory()->create())->code_ec,
        ];
    }
}
