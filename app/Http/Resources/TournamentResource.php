<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Tournament',
    required: ['id', 'name', 'countryCode', 'region', 'rating'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the tournament', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the tournament', type: 'string', nullable: false),
        new OA\Property(property: 'goldTeamId', description: 'The winning team of the tournament', ref: '#/components/schemas/Team', nullable: true),
        new OA\Property(property: 'silverTeamId', description: 'The second team of the tournament', ref: '#/components/schemas/Team', nullable: true),
        new OA\Property(property: 'bronzeTeamId', description: 'The third team of the tournament', ref: '#/components/schemas/Team', nullable: true),
        new OA\Property(property: 'startsAt', description: 'The starting date of the tournament', type: 'string', format: 'date', nullable: false),
        new OA\Property(property: 'endsAt', description: 'The ending date of the tournament', type: 'string', format: 'date', nullable: true),
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
            'goldTeam' => new TeamResource($this->whenLoaded('goldTeam')),
            'silverTeam' => new TeamResource($this->whenLoaded('silverTeam')),
            'bronzeTeam' => new TeamResource($this->whenLoaded('bronzeTeam')),
            'startsAt' => $this->starts_at,
            'endsAt' => $this->ends_at,
        ];
    }
}
