<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EloHistoryEntry',
    required: ['id', 'rating', 'date'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the entry', type: 'integer', nullable: false),
        new OA\Property(property: 'rating', description: 'The rating of the Team', type: 'integer', nullable: false),
        new OA\Property(property: 'date', description: 'The date of the entry', type: 'string', format: 'datetime', nullable: false),
    ]
)]
class EloHistoryEntryResource extends JsonResource
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
            'rating' => $this->rating,
            'date' => $this->created_at,
            'opposingTeam' => new TeamResource($this->opposing_team),
        ];
    }
}
