<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Game',
    required: ['id', 'name', 'tournament', 'hostingTeam', 'receivingTeam', 'gameScoreTotal', 'gameScoreFirst', 'gameScoreSecond'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the game', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the game', type: 'string', nullable: false),
        new OA\Property(property: 'tournament', description: 'The tournament of the game', ref: '#/components/schemas/Tournament', nullable: false),
        new OA\Property(property: 'hostingTeam', description: 'The hosting team of the game', ref: '#/components/schemas/Team', nullable: false),
        new OA\Property(property: 'receivingTeam', description: 'The receiving team of the game', ref: '#/components/schemas/Team', nullable: false),
        new OA\Property(property: 'gameScoreTotal', description: 'The global score of the game', type: 'string', nullable: false),
        new OA\Property(property: 'gameScoreFirst', description: 'The score of the first subgame', type: 'string', nullable: false),
        new OA\Property(property: 'gameScoreSecond', description: 'The score of the second subgame', type: 'string', nullable: false),
    ]
)]
class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->hostingTeam->name.' - '.$this->receivingTeam->name,
            'tournament' => new TournamentResource($this->tournament),
            'hostingTeam' => new TeamResource($this->hostingTeam),
            'receivingTeam' => new TeamResource($this->receivingTeam),
            'gameScoreTotal' => ($this->host_score_1 + $this->host_score_2).' - '.($this->guest_score_1 + $this->guest_score_2),
            'gameScoreFirst' => $this->host_score_1.' - '.$this->guest_score_1,
            'gameScoreSecond' => $this->host_score_2.' - '.$this->guest_score_2,
        ];
    }
}
