<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Tournament',
    required: ['id', 'name', 'eloMultiplier', 'major', 'balancing'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the tournament', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the tournament', type: 'string', nullable: false),
        new OA\Property(property: 'eloMultiplier', description: 'The elo multiplier of the tournament', type: 'float',
            minimum: 0, nullable: false),
        new OA\Property(property: 'major', description: 'Is the tournament a major tournament ?', type: 'bool',
            default: true, example: true, nullable: false),
        new OA\Property(property: 'balancing', description: 'Is the tournament a balancing tournament', type: 'boolean',
            default: false, nullable: false),
        new OA\Property(property: 'goldTeamId', ref: '#/components/schemas/Team',
            description: 'The winning team of the tournament', nullable: true),
        new OA\Property(property: 'silverTeamId', ref: '#/components/schemas/Team',
            description: 'The second team of the tournament', nullable: true),
        new OA\Property(property: 'bronzeTeamId', ref: '#/components/schemas/Team',
            description: 'The third team of the tournament', nullable: true),
    ]
)]
class TournamentResource extends JsonResource
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
            'name' => $this->name,
            'eloMultiplier' => (float) $this->elo_multiplier,
            'major' => $this->major,
            'balancing' => $this->balancing,
            'goldTeam' => new TeamResource($this->whenLoaded('goldTeam')),
            'silverTeam' => new TeamResource($this->whenLoaded('silverTeam')),
            'bronzeTeam' => new TeamResource($this->whenLoaded('bronzeTeam')),
        ];
    }
}
