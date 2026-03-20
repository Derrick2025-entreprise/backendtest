<?php

use App\Models\Enseignement;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnseignementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_enseignement(): void
    {
        $response = $this->get('/api/enseignements');
        $response->assertStatus(200);
    }

    public function test_create_enseignement(): void
    {
        $enseignement = Enseignement::factory()->make();
        $response = $this->post('/api/enseignements', [
            "code_personnel" => $enseignement->code_personnel,
            "code_ec" => $enseignement->code_ec,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_enseignement(): void
    {
        $enseignement = Enseignement::factory()->create();
        $response = $this->put('/api/enseignements/' . $enseignement->id, [
            "code_personnel" => $enseignement->code_personnel,
            "code_ec" => $enseignement->code_ec,
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_enseignement(): void
    {
        $enseignement = Enseignement::factory()->create();
        $response = $this->delete('/api/enseignements/' . $enseignement->id);
        $response->assertStatus(200);
    }
}
