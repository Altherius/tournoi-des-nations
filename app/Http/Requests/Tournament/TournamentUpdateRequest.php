<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @property string $name
 * @property float $eloMultiplier
 * @property string $startsAt
 */
#[OA\Schema(
    schema: 'TournamentUpdateInput',
    required: ['name', 'startsAt'],
    properties: [
        new OA\Property(property: 'name', description: 'The name of the tournament', type: 'string', nullable: false),
        new OA\Property(property: 'major', description: 'Is the tournament a major tournament', type: 'boolean', default: true, nullable: false),
        new OA\Property(property: 'balancing', description: 'Is the tournament a balancing tournament', type: 'boolean', default: false, nullable: false),
        new OA\Property(property: 'elo_multiplier', description: 'The elo multiplier of the tournament', type: 'float', minimum: 0, nullable: false),
        new OA\Property(property: 'goldTeamId', description: 'The ID of the winning team of the tournament', type: 'integer', nullable: true),
        new OA\Property(property: 'silverTeamId', description: 'The ID of the second team of the tournament', type: 'integer', nullable: true),
        new OA\Property(property: 'bronzeTeamId', description: 'The ID of the third team of the tournament', type: 'integer', nullable: true),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for updating a tournament',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/TournamentUpdateInput'),
    ]
)]
class TournamentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'eloMultiplier' => 'required|min:0',
            'major' => 'required|boolean',
            'balancing' => 'required|boolean',
            'goldTeamId' => 'exists:teams,id',
            'silverTeamId' => 'exists:teams,id',
            'bronzeTeamId' => 'exists:teams,id',
        ];
    }
}
