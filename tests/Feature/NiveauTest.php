<?php

use App\Models\Niveau;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NiveauTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_niveau(): void
    {
        $response = $this->get('/api/niveaux');
        $response->assertStatus(200);
    }

    public function test_create_niveau(): void
    {
        $niveau = Niveau::factory()->make();
        $response = $this->post('/api/niveaux', [
            "code_filiere" => $niveau->code_filiere,
            "label_niveau" => $niveau->label_niveau,
            "description_niveau" => $niveau->description_niveau,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_niveau(): void
    {
        $niveau = Niveau::factory()->create();
        $response = $this->put('/api/niveaux/' . $niveau->code_niveau, [
            "code_filiere" => $niveau->code_filiere,
            "label_niveau" => "Updated Label",
            "description_niveau" => $niveau->description_niveau,
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_niveau(): void
    {
        $niveau = Niveau::factory()->create();
        $response = $this->delete('/api/niveaux/' . $niveau->code_niveau);
        $response->assertStatus(200);
    }
}
