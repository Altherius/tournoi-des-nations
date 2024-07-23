<?php

namespace App\Http\Requests\Team;

use App\Enum\Region;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

/**
 * @property string $name
 * @property string $countryCode
 * @property string $region
 */
#[OA\Schema(
    schema: 'TeamCreateInput',
    required: ['name', 'country_code', 'region'],
    properties: [
        new OA\Property(property: 'name', description: 'The name of the team', type: 'string', nullable: false),
        new OA\Property(property: 'countryCode', description: 'The country code of the team', type: 'string', nullable: false),
        new OA\Property(property: 'region', description: 'The region of the team', type: 'string', nullable: false),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for creating a team',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/TeamCreateInput'),
    ]
)]
class TeamCreateRequest extends FormRequest
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
            'countryCode' => 'required',
            'region' => ['required', Rule::enum(Region::class)],
        ];
    }
}
