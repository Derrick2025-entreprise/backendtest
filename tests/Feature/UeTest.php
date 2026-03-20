<?php

use App\Models\UE;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UETest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_ue(): void
    {
        $response = $this->get('/api/ue');
        $response->assertStatus(200);
    }

    public function test_create_ue(): void
    {
        $ue = UE::factory()->make();
        $response = $this->post('/api/ue', [
            "code_ue" => $ue->code_ue,
            "label_ue" => $ue->label_ue,
            "description_ue" => $ue->description_ue,
            "code_niveau" => $ue->code_niveau,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_ue(): void
    {
        $ue = UE::factory()->create();
        $response = $this->put('/api/ue/' . $ue->code_ue, [
            "label_ue" => "Updated Label",
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_ue(): void
    {
        $ue = UE::factory()->create();
        $response = $this->delete('/api/ue/' . $ue->code_ue);
        $response->assertStatus(200);
    }
}
