<?php

namespace App\Listeners;

use App\Events\GameCreated;
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

        $exchangedPoints = $eloCalculator->getExchangedPoints($subjectRating, $opponentRating, $goalsDiff);

        $event->game->hostingTeam->rating += $exchangedPoints;
        $event->game->receivingTeam->rating -= $exchangedPoints;

        $event->game->hostingTeam->save();
        $event->game->receivingTeam->save();
    }
}
