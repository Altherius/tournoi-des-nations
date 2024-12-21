<?php

namespace App\Services\RosterBuilder;

use Random\RandomException;

class RosterBuilder
{
    /**
     * @throws RandomException
     */
    public function buildRoster(array $guaranteedTeams, array $ticketTeams, int $availableSeats): array
    {
        if ($availableSeats <= 0) {
            throw new \InvalidArgumentException("A positive number of seats is required. You provided $availableSeats.");
        }

        if (($guaranteedTeamsCount = count($guaranteedTeams)) > $availableSeats) {
            throw new \InvalidArgumentException("There is only $availableSeats for $guaranteedTeamsCount teams guaranteed.");
        }

        $response = $guaranteedTeams;

        $cumulativeDensity = array_reduce($ticketTeams, static fn ($acc, $team) => $acc + $team->ticketsCount);
        $iterations = $availableSeats - count($guaranteedTeams);

        for ($i = 0 ; $i < $iterations ; ++$i) {

            if ($cumulativeDensity <= 0) {
                throw new \InvalidArgumentException("There is not enough teams to fill the tournament");
            }

            $randomSelection = random_int(1, $cumulativeDensity);
            $acc = 0;
            foreach ($ticketTeams as $team) {
                $acc += $team->ticketsCount;
                if ($acc >= $randomSelection) {
                    $response[] = $team->team;
                    $cumulativeDensity -= $team->ticketsCount;
                    break;
                }
            }
        }

        return $response;
    }
}
