<?php

use App\Models\Personnel;
use App\Models\Salle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class SalleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_salle(): void
    {
        $response = $this->get('/api/salles');
        $response->assertStatus(200);
    }

    public function test_create_salle(): void
    {
        $salle = Salle::factory()->make();
        $response = $this->post('/api/salles', [
            "num_salle" => $salle->num_salle,
            "contenance" => $salle->contenance,
            "status" => $salle->status,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_salle(): void
    {
        $salle = Salle::factory()->create();
        $response = $this->put('/api/salles/' . $salle->num_salle, [
            "contenance" => 100,
            "status" => "OCCUPEE",
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_salle(): void
    {
        $salle = Salle::factory()->create();
        $response = $this->delete('/api/salles/' . $salle->num_salle);
        $response->assertStatus(200);
    }
}
