<?php

namespace App\Http\Requests\Game;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @property int $hostingTeamId
 * @property int $receivingTeamId
 * @property ?int $winningTeamId
 * @property int $hostScore1
 * @property int $guestScore1
 * @property int $hostScore2
 * @property int $guestScore2
 */
#[OA\Schema(
    schema: 'GameCreateInput',
    required: ['hostingTeamId', 'receivingTeamId', 'hostScore1', 'hostScore2', 'guestScore1', 'guestScore2'],
    properties: [
        new OA\Property(property: 'hostingTeamId', description: 'The ID of the hosting team', type: 'integer', nullable: false),
        new OA\Property(property: 'receivingTeamId', description: 'The ID of the receiving team', type: 'integer', nullable: false),
        new OA\Property(property: 'winningTeamId', description: 'The ID of the winning team', type: 'integer', nullable: true),
        new OA\Property(property: 'hostScore1', description: 'The score of the hosting team in the first game', type: 'integer', nullable: false),
        new OA\Property(property: 'guestScore1', description: 'The score of the receiving team in the first game', type: 'integer', nullable: false),
        new OA\Property(property: 'hostScore2', description: 'The score of the hosting team in the second game', type: 'integer', nullable: false),
        new OA\Property(property: 'guestScore2', description: 'The score of the receiving team in the second game', type: 'integer', nullable: false),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for creating a game',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/GameCreateInput'),
    ]
)]
class GameCreateRequest extends FormRequest
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
            'hostingTeamId' => 'required',
            'receivingTeamId' => 'required',
            'hostScore1' => 'required',
            'guestScore1' => 'required',
            'hostScore2' => 'required',
            'guestScore2' => 'required',
        ];
    }
}
