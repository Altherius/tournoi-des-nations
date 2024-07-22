<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\TokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class ApiTokenController extends Controller
{
    /**
     * @throws ValidationException In case of invalid credentials.
     */
    #[OA\Post(path: '/token', summary: 'Get JWT', tags: ['Auth'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TokenRequest')]
    #[OA\Response(response: '200', description: 'The requested token', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'token', description: 'A JWT to use for authentication', type: 'string'),
    ]))]
    #[OA\Response(response: '401', description: 'Invalid credentials', content: new OA\JsonContent(ref: '#/components/schemas/Error'))]
    public function token(TokenRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $exception = ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);

            $exception->status = 401;

            throw $exception;
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
