<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @property int    $availableSeats
 * @property array  $guaranteedTeams
 * @property array  $ticketTeams
 */
#[OA\Schema(
    schema: 'TournamentGenerateRosterInputInput',
    required: ['availableSeats', 'guaranteedTeams', 'ticketTeams'],
    properties: [
        new OA\Property(property: 'availableSeats', description: 'The number of seats available to the tournament', type: 'integer', minimum: 1, nullable: false),
        new OA\Property(property: 'guaranteedTeams', description: 'The list of the teams that a guaranteed a seat', type: 'array', nullable: false),
        new OA\Property(property: 'ticketTeams', description: 'The list of the teams that may have a seat with their tickets', type: 'array', nullable: false),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for generating a tournament roster',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/TournamentGenerateRosterInput'),
    ]
)]
class TournamentGenerateRosterRequest extends FormRequest
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
            'availableSeats' => 'required|gt:0',
            'guaranteedTeams' => 'required',
            'ticketTeams' => 'required',
        ];
    }
}
