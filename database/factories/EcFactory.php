<?php

namespace Database\Factories;

use App\Models\UE;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EC>
 */
class EcFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "label_ec" => fake()->words(2, true),
            "description_ec" => fake()->paragraph(),
            "nb_heures_ec" => fake()->numberBetween(10, 100),
            "nb_credits_ec" => fake()->numberBetween(1, 10),
            "code_ue" => (UE::inRandomOrder()->first() ?? UE::factory()->create())->code_ue,
            "support_cours" => null,
             "support_cours_url" => null,
        ];
    }

    /**
     * État avec un fichier PDF copié depuis un template
     */
    public function withSupport(): static
    {
        return $this->state(function (array $attributes) {
            // Chemin du template
            $templatePath = storage_path('app/public/templates/sample-support.pdf');

            // Si le template n'existe pas, créer un exemple simple
            if (!File::exists($templatePath)) {
                return [
                'support_cours' => null,
                'support_cours_url' => null
            ];
            }

            $labelSlug = Str::slug($attributes['label_ec'] ?? fake()->words(2, true));
            $timestamp = time() + rand(0, 1000);
            $filename = "support-{$labelSlug}-{$timestamp}.pdf";
            $destinationPath = "supports_cours/{$filename}";

            // Copier le fichier template
            Storage::disk('public')->put(
                $destinationPath,
                File::get($templatePath)
            );

            // ✅ Générer l'URL complète
            $baseUrl = config('app.url', 'http://localhost:8000');
            $supportUrl = $baseUrl . '/storage/' . $destinationPath;

            return [
                'support_cours' => $destinationPath,
                'support_cours_url' => $supportUrl
            ];
        });
    }

}
