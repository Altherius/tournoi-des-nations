<?php

namespace Tests\Feature\Tournament;

use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_tournaments_index_is_available(): void
    {
        $response = $this->get('/api/tournaments');
        $response->assertStatus(200);
    }

    public function test_tournament_show_is_available(): void
    {
        $tournament = Tournament::factory()->create();

        $response = $this->get("/api/tournaments/$tournament->id");
        $response->assertStatus(200);
    }

    public function test_tournaments_can_be_created(): void
    {
        $response = $this->post('/api/tournaments', [
            'name' => 'Tournoi des nations',
            'startsAt' => '1970-01-01',
            'major' => true,
            'balancing' => false,
            'eloMultiplier' => 1,
        ]);

        $response->assertStatus(201);
    }

    public function test_tournaments_can_be_updated(): void
    {
        $tournament = Tournament::factory()->create();

        $response = $this->put("/api/tournaments/$tournament->id", [
            'name' => 'Tournoi des nations',
            'startsAt' => '1970-01-01',
            'endsAt' => '1970-01-01',
        ]);

        $response->assertStatus(200);
    }
}
