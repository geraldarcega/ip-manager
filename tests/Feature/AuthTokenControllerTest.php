<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;

class AuthTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    const API_URI = '/api/login';

    /**
     * A basic test for login.
     */
    public function test_login(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => $password
        ]);
        $response = $this->postJson(self::API_URI, [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.name', $user->name)
            ->assertJsonPath('user.email', $user->email)
            ->assertJson(['token' => true]);
    }

    /**
     * A basic test for login with invalid credentials.
     */
    public function test_login_with_invalid_credentials(): void
    {
        $password = 'password';
        $response = $this->postJson(self::API_URI, [
            'email' => 'test@test.com',
            'password' => $password,
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['email' => 'The provided credentials are incorrect.']);
    }

    /**
     * A basic test for login with invalid credentials.
     */
    #[DataProvider('loginDataProvider')]
    public function test_login_with_validation_error(
        string $email,
        string $password,
        array $validationErrors
    ): void
    {
        $response = $this->postJson(self::API_URI, [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(422)
            ->assertInvalid($validationErrors);
    }

    public static function loginDataProvider() : Generator
    {
        yield 'No email and no password' => [
            '',
            '',
            [
                'email' => 'The email field is required.',
                'password' => 'The password field is required.'
            ]
        ];

        yield 'With email and no password' => [
            'test@test.com',
            '',
            [
                'password' => 'The password field is required.'
            ]
        ];

        yield 'No email and with password' => [
            '',
            'password',
            [
                'email' => 'The email field is required.'
            ]
        ];

        yield 'invalid email and with password' => [
            'test',
            'password',
            [
                'email' => 'The email field must be a valid email address.',
            ]
        ];
    }

    public function test_logout() : void
    {
        $testUser = User::factory()->create();
        Sanctum::actingAs($testUser);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(204);
        $this->assertTrue($testUser->tokens()->count() === 0);
    }
}
