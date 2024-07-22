<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_request_a_token(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'Default',
        ], ['Accept' => 'application/json']);

        $response->assertOk();
    }

    public function test_users_can_not_request_token_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'Default',
        ], ['Accept' => 'application/json']);

        $response->assertUnauthorized();
    }
}
