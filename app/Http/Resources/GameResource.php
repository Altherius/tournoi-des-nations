<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Game',
    required: ['id', 'name', 'countryCode', 'region', 'rating'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the team', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the team', type: 'string', nullable: false),
        new OA\Property(property: 'countryCode', description: 'The country code of the team', type: 'string', nullable: false),
        new OA\Property(property: 'region', description: 'The region of the team', type: 'string', nullable: false),
        new OA\Property(property: 'rating', description: 'The rating of the team', type: 'integer', nullable: false),
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
            'hostingTeam' => new TeamResource($this->hostingTeam),
            'receivingTeam' => new TeamResource($this->receivingTeam),
            'gameScoreTotal' => ($this->host_score_1 + $this->host_score_2).' - '.($this->guest_score_1 + $this->guest_score_2),
            'gameScoreFirst' => $this->host_score_1.' - '.$this->guest_score_1,
            'gameScoreSecond' => $this->host_score_2.' - '.$this->guest_score_2,
        ];
    }
}
