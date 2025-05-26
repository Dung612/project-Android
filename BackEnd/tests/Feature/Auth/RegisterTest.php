<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register()
    {
        $userData = [
            'full_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'full_name',
                    'email',
                    'roles' => [
                        '*' => [
                            'id',
                            'name',
                            'description'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ],
                'access_token',
                'token_type'
            ]);

        $this->assertDatabaseHas('users', [
            'full_name' => $userData['full_name'],
            'email' => $userData['email']
        ]);
    }

    public function test_register_validation()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['full_name', 'email', 'password']);
    }

    public function test_register_duplicate_email()
    {
        $user = User::factory()->create();

        $userData = [
            'full_name' => $this->faker->name,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}