<?php

use App\Models\Filiere;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FiliereTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_filiere(): void
    {
        $response = $this->get('/api/filieres');
        $response->assertStatus(200);
    }

    public function test_create_filiere(): void
    {
        $filiere = Filiere::factory()->make();
        $response = $this->post('/api/filieres', [
            "code_filiere" => $filiere->code_filiere,
            "label_filiere" => $filiere->label_filiere,
            "description_filiere" => $filiere->description_filiere,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_filiere(): void
    {
        $filiere = Filiere::factory()->create();
        $response = $this->put('/api/filieres/' . $filiere->code_filiere, [
            "label_filiere" => "Updated Label",
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_filiere(): void
    {
        $filiere = Filiere::factory()->create();
        $response = $this->delete('/api/filieres/' . $filiere->code_filiere);
        $response->assertStatus(200);
    }
}
