<?php

namespace Tests\Feature\Team;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_teams_index_is_available(): void
    {
        $response = $this->get('/api/teams');
        $response->assertStatus(200);
    }

    public function test_team_show_is_available(): void
    {
        $team = Team::factory()->create();

        $response = $this->get("/api/teams/$team->id");
        $response->assertStatus(200);
    }

    public function test_teams_can_be_created(): void
    {
        $response = $this->post('/api/teams', [
            'name' => 'France',
            'countryCode' => 'FR',
            'region' => 'europe'
        ]);

        $response->assertStatus(201);
    }

    public function test_teams_can_be_updated(): void
    {
        $team = Team::factory()->create();

        $response = $this->put("/api/teams/$team->id", [
            'name' => 'France',
            'countryCode' => 'FR',
            'region' => 'europe'
        ]);

        $response->assertStatus(200);
    }
}
