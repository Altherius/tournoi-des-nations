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
    schema: 'TournamentCreateInput',
    required: ['name', 'eloMultiplier', 'startsAt'],
    properties: [
        new OA\Property(property: 'name', description: 'The name of the tournament', type: 'string', nullable: false),
        new OA\Property(property: 'elo_multiplier', description: 'The elo multiplier of the tournament', type: 'float', minimum: 0, nullable: false),
        new OA\Property(property: 'startsAt', description: 'The starting date of the tournament', type: 'string', format: 'date', nullable: false),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for creating a tournament',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/TournamentCreateInput'),
    ]
)]
class TournamentCreateRequest extends FormRequest
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
            'startsAt' => 'required|date',
        ];
    }
}
