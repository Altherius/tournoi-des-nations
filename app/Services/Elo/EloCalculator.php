<?php

namespace App\Services\Elo;

use App\Enum\GameResult;

class EloCalculator
{
    /*
    The K factor, used to potentially multiply the number of exchanged points.
    */
    private const K = 80.;

    public function getExchangedPoints(int $subjectRating, int $opponentRating, int $goalsDiff, float $tournamentMultiplier = 1.0): int
    {
        if ($goalsDiff === 0) {
            $result = GameResult::DRAW;
        } else {
            $result = $goalsDiff > 0 ? GameResult::WIN : GameResult::LOSS;
        }

        $baseExchangedPoints = $this->getBaseExchangedPoints($subjectRating, $opponentRating, $result);
        $goalsDiffMultiplier = $this->getGoalDiffMultiplier(abs($goalsDiff));

        return (int) ($baseExchangedPoints * $goalsDiffMultiplier * $tournamentMultiplier);
    }

    private function getResultFactor(GameResult $result): float
    {
        return match ($result) {
            GameResult::LOSS => 0.,
            GameResult::DRAW => .5,
            GameResult::WIN => 1.,
        };
    }

    private function getBaseExchangedPoints(int $subjectRating, int $opponentRating, GameResult $result): int
    {
        return (int) (self::K * ($this->getResultFactor($result) - $this->getWinProbability($subjectRating, $opponentRating)));
    }

    private function getGoalDiffMultiplier(int $goalsDiff): float
    {
        if ($goalsDiff < 4) {
            return 1.;
        }

        if ($goalsDiff < 6) {
            return 1.5;
        }

        if ($goalsDiff < 8) {
            return 1.75;
        }

        return 1.75 + (ceil((.5 * $goalsDiff - 7)) * 0.125);
    }

    private function getWinProbability(int $subjectRating, int $opponentRating): float
    {
        $eloDiff = $subjectRating - $opponentRating;
        if (abs($eloDiff) > 400) {
            $eloDiff = $eloDiff > 0 ? 400 : -400;
        }

        return 1 / (1 + (10 ** (-$eloDiff / 400)));
    }
}
