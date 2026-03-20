<?php

use App\Models\EC;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ECTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(Personnel::factory()->create());
    }

    public function test_get_ec(): void
    {
        $response = $this->get('/api/ec');
        $response->assertStatus(200);
    }

    public function test_create_ec(): void
    {
        $ec = EC::factory()->make();
        $response = $this->post('/api/ec', [
            "label_ec" => $ec->label_ec,
            "description_ec" => $ec->description_ec,
            "nb_heures_ec" => $ec->nb_heures_ec,
            "nb_credits_ec" => $ec->nb_credits_ec,
            "code_ue" => $ec->code_ue,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_ec(): void
    {
        $ec = EC::factory()->create();
        $response = $this->put('/api/ec/' . $ec->code_ec, [
            "label_ec" => "Updated Label",
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_ec(): void
    {
        $ec = EC::factory()->create();
        $response = $this->delete('/api/ec/' . $ec->code_ec);
        $response->assertStatus(200);
    }
}
