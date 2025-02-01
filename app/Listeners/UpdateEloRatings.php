<?php

namespace App\Listeners;

use App\Events\GameCreated;
use App\Models\EloHistoryEntry;
use App\Services\Elo\EloCalculator;

class UpdateEloRatings
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GameCreated $event): void
    {
        $eloCalculator = new EloCalculator;
        $subjectRating = $event->game->hostingTeam->rating;
        $opponentRating = $event->game->receivingTeam->rating;
        $goalsDiff = ($event->game->host_score_1 + $event->game->host_score_2) - ($event->game->guest_score_1 + $event->game->guest_score_2);

        $exchangedPoints = $eloCalculator->getExchangedPoints($subjectRating, $opponentRating, $goalsDiff, $event->game->tournament->elo_multiplier);

        $event->game->hostingTeam->rating += $exchangedPoints;
        $event->game->receivingTeam->rating -= $exchangedPoints;

        $hostingTeamEloHistoryEntry = new EloHistoryEntry;
        $hostingTeamEloHistoryEntry->team_id = $event->game->hostingTeam->id;
        $hostingTeamEloHistoryEntry->opposing_team_id = $event->game->receivingTeam->id;
        $hostingTeamEloHistoryEntry->rating = $event->game->hostingTeam->rating;

        $receivingTeamEloHistoryEntry = new EloHistoryEntry;
        $receivingTeamEloHistoryEntry->team_id = $event->game->receivingTeam->id;
        $receivingTeamEloHistoryEntry->opposing_team_id = $event->game->hostingTeam->id;
        $receivingTeamEloHistoryEntry->rating = $event->game->receivingTeam->rating;

        $hostingTeamEloHistoryEntry->save();
        $receivingTeamEloHistoryEntry->save();

        $event->game->hostingTeam->save();
        $event->game->receivingTeam->save();
    }
}
