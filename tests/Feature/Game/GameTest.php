<?php

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_games_index_is_available(): void
    {
        $response = $this->get('/api/games');

        $response->assertStatus(200);
    }

    public function test_game_show_is_available(): void
    {
        $game = Game::factory()->create();
        $response = $this->get("/api/games/$game->id");

        $response->assertStatus(200);
    }

    public function test_games_can_be_created(): void
    {
        $hostingTeam = Team::factory()->create();
        $receivingTeam = Team::factory()->create();

        $response = $this->post('/api/games', [
            'hostingTeamId' => $hostingTeam->id,
            'receivingTeamId' => $receivingTeam->id,
            'hostScore1' => 0,
            'hostScore2' => 0,
            'guestScore1' => 0,
            'guestScore2' => 0,
        ]);

        $response->assertStatus(201);
    }
}
