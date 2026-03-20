<?php

use App\Models\Programmation;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProgrammationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_programmation(): void
    {
        $response = $this->get('/api/programmations');
        $response->assertStatus(200);
    }

    public function test_create_programmation(): void
    {
        $programmation = Programmation::factory()->make();
        $response = $this->post('/api/programmations', [
            "num_salle" => $programmation->num_salle,
            "code_ec" => $programmation->code_ec,
            "code_personnel" => $programmation->code_personnel,
            "nbre_heures" => $programmation->nbre_heures,
            "date" => $programmation->date,
            "heure_debut" => $programmation->heure_debut,
            "heure_fin" => $programmation->heure_fin,
            "status" => $programmation->status,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_programmation(): void
    {
        $programmation = Programmation::factory()->create();
        $response = $this->put('/api/programmations/' . $programmation->id, [
            "status" => "TERMINÉ",
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_programmation(): void
    {
        $programmation = Programmation::factory()->create();
        $response = $this->delete('/api/programmations/' . $programmation->id);
        $response->assertStatus(200);
    }
}
