<?php

namespace Tests\Feature\EloHistoryEntry;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EloHistoryEntryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_team_elo_history_can_be_viewed(): void
    {
        $team = Team::factory()->create();

        $response = $this->get("/api/teams/{$team->id}/elo-history");

        $response->assertStatus(200);
    }
}
