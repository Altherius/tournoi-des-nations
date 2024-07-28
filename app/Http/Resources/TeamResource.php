<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Team',
    required: ['id', 'name', 'countryCode', 'region', 'rating'],
    properties: [
        new OA\Property(property: 'id', description: 'The ID of the team', type: 'integer', nullable: false),
        new OA\Property(property: 'name', description: 'The name of the team', type: 'string', nullable: false),
        new OA\Property(property: 'countryCode', description: 'The country code of the team', type: 'string', nullable: false),
        new OA\Property(property: 'region', description: 'The region of the team', type: 'string', nullable: false),
        new OA\Property(property: 'rating', description: 'The rating of the team', type: 'integer', nullable: false),
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'countryCode' => $this->country_code,
            'region' => $this->region->name(),
            'rating' => $this->rating,
        ];
    }
}
