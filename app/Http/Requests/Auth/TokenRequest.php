<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @property string $email
 * @property string $password
 * @property string $device_name
 */
#[OA\Schema(
    schema: 'TokenCreateInput',
    required: ['email', 'password', 'device_name'],
    properties: [
        new OA\Property(property: 'email', description: 'The email of the user', type: 'string', format: 'email', nullable: false),
        new OA\Property(property: 'password', description: 'The plain password of the user', type: 'string', example: 'secret', nullable: false),
        new OA\Property(property: 'device_name', description: 'A descriptive name of the device linked to the token', type: 'string', example: 'Laptop', nullable: false),
    ]
)]
#[OA\RequestBody(
    description: 'Request body for creating a JWT token',
    required: true,
    content: [
        new OA\JsonContent(ref: '#/components/schemas/TokenCreateInput'),
    ]
)]
class TokenRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ];
    }
}
