<?php

use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PersonnelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_personnel(): void
    {
        $response = $this->get('/api/personnels');
        $response->assertStatus(200);
    }

    public function test_create_personnel(): void
    {
        $personnel = Personnel::factory()->make();
        $response = $this->post('/api/personnels', [
            "code_personnel" => $personnel->code_personnel,
            "nom_personnel" => $personnel->nom_personnel,
            "prenom_personnel" => $personnel->prenom_personnel,
            "sexe_personnel" => $personnel->sexe_personnel,
            "phone_personnel" => $personnel->phone_personnel,
            "login_personnel" => $personnel->login_personnel,
            "password_personnel" => "password123",
            "type_personnel" => $personnel->type_personnel,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_personnel(): void
    {
        $personnel = Personnel::factory()->create();
        $response = $this->put('/api/personnels/' . $personnel->code_personnel, [
            "nom_personnel" => "Updated Name",
            "prenom_personnel" => $personnel->prenom_personnel,
            "sexe_personnel" => $personnel->sexe_personnel,
            "phone_personnel" => $personnel->phone_personnel,
            "login_personnel" => $personnel->login_personnel,
            "type_personnel" => $personnel->type_personnel,
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_personnel(): void
    {
        $personnel = Personnel::factory()->create();
        $response = $this->delete('/api/personnels/' . $personnel->code_personnel);
        $response->assertStatus(200);
    }
}
