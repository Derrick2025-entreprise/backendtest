<?php

namespace Database\Seeders;

use App\Models\EC;
use App\Models\Enseignement;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Personnel;
use App\Models\Programmation;
use App\Models\Salle;
use App\Models\UE;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Filiere::factory(200)->create();
        Niveau::factory(200)->create();
        UE::factory(200)->create();
        EC::factory()->withSupport()->count(200)->create();
        Personnel::factory(200)->create();

        // Créer des enseignements en gérant les doublons
        $created = 0;
        $maxAttempts = 500;
        $attempts = 0;

        while ($created < 200 && $attempts < $maxAttempts) {
            try {
                Enseignement::factory()->create();
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Ignorer les doublons et continuer
            }
            $attempts++;
        }

        if ($created < 200) {
            $this->command->warn("⚠️  Attention : Seulement {$created}/200 enseignements créés !");
        }

        Salle::factory(50)->create();

        // Créer des programmations en gérant les doublons
        $created = 0;
        $maxAttempts = 500;
        $attempts = 0;

        while ($created < 200 && $attempts < $maxAttempts) {
            try {
                Programmation::factory()->create();
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Ignorer les doublons et continuer
            }
            $attempts++;
        }

        if ($created < 200) {
            $this->command->warn("⚠️  Attention : Seulement {$created}/200 programmations créées !");
        }
    }
}
