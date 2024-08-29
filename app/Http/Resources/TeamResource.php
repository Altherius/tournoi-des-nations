<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

#[OA\Schema(
    schema: 'Team',
    required: ['id', 'name', 'countryCode', 'region', 'rating', 'gamesCount', 'winsCount', 'lossCount', 'drawCount', 'lastResults'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the team', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the team', type: 'string', nullable: false),
        new OA\Property(property: 'countryCode', description: 'The country code of the team', type: 'string', nullable: false),
        new OA\Property(property: 'region', description: 'The region of the team', type: 'string', nullable: false),
        new OA\Property(property: 'rating', description: 'The rating of the team', type: 'integer', nullable: false),
        new OA\Property(property: 'gamesCount', description: 'The total number of games of the team', type: 'integer', nullable: false),
        new OA\Property(property: 'winsCount', description: 'The total number of games won by the team', type: 'integer', nullable: false),
        new OA\Property(property: 'lossCount', description: 'The total number of games lost by the team', type: 'integer', nullable: false),
        new OA\Property(property: 'drawCount', description: 'The total number of games drewn by the team', type: 'integer', nullable: false),
        new OA\Property(property: 'lastResults', description: 'A short description of the last results of the team', type: 'array', items: new Items(ref: '#/components/schemas/LastResult', nullable: false), nullable: false),
    ]
)]
#[OA\Schema(
    schema: 'LastResult',
    required: ['name', 'result'],
    properties: [
        new Property(property: 'name', description: 'The name of the game', type: 'string', nullable: false),
        new Property(property: 'result', description: 'The result of the game', type: 'string', nullable: false, enum: ['loss', 'draw', 'win']),
    ]
)]
class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $gamesHosting = $this->gamesHosting;
        $gamesReceiving = $this->gamesReceiving;

        $winsCount = $gamesHosting->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) > ($game->guest_score_1 + $game->guest_score_2);
        })->count();
        $winsCount += $gamesReceiving->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) < ($game->guest_score_1 + $game->guest_score_2);
        })->count();
        $lossCount = $gamesHosting->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) < ($game->guest_score_1 + $game->guest_score_2);
        })->count();
        $lossCount += $gamesReceiving->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) > ($game->guest_score_1 + $game->guest_score_2);
        })->count();
        $drawCount = $gamesHosting->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) === ($game->guest_score_1 + $game->guest_score_2);
        })->count();
        $drawCount += $gamesReceiving->filter(function ($game) {
            return ($game->host_score_1 + $game->host_score_2) === ($game->guest_score_1 + $game->guest_score_2);
        })->count();

        $lastGames = $gamesHosting->merge($gamesReceiving);
        $lastGames->sortBy('id', SORT_DESC);
        $lastGames->slice(0, 5);
        $lastResults = [];

        foreach ($lastGames as $game) {

            $goalsDiff = ($game->host_score_1 + $game->host_score_2) <=> ($game->guest_score_1 + $game->guest_score_2);
            switch ($goalsDiff) {
                case -1:
                    $result = $game->hostingTeam->id === $this->id ? 'win' : 'loss';
                    break;
                case 1:
                    $result = $game->receivingTeam->id === $this->id ? 'win' : 'loss';
                    break;
                case 0:
                    $result = 'draw';
                    break;
                default:
                    throw new LogicException("This default block should not be reached. (goalsDiff = $goalsDiff)");
            }

            $lastResults[] = [
                'name' => $game->hostingTeam->name.' - '.$game->receivingTeam->name,
                'result' => $result,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'countryCode' => $this->country_code,
            'region' => $this->region->name(),
            'rating' => $this->rating,
            'gameCount' => $this->games_hosting_count + $this->games_receiving_count,
            'winsCount' => $winsCount,
            'lossCount' => $lossCount,
            'drawCount' => $drawCount,
            'lastResults' => $lastResults,
        ];
    }
}
