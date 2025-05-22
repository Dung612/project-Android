<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // Tạo user với is_verified = true
        $user = User::create([
            'full_name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true
        ]);

        $loginData = [
            'email' => 'nguyenvana@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'full_name',
                    'email',
                    'is_verified',
                    'created_at',
                    'updated_at'
                ],
                'access_token',
                'token_type'
            ])
            ->assertJson([
                'message' => 'Đăng nhập thành công'
            ]);
    }

    public function test_user_cannot_login_when_not_verified()
    {
        // Tạo user với is_verified = false
        $user = User::create([
            'full_name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => false
        ]);

        $loginData = [
            'email' => 'nguyenvana@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Tài khoản chưa được xác thực'
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Tạo user với is_verified = true
        $user = User::create([
            'full_name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true
        ]);

        $loginData = [
            'email' => 'nguyenvana@example.com',
            'password' => 'wrong_password'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Thông tin đăng nhập không chính xác'
            ]);
    }

    public function test_user_cannot_login_with_nonexistent_email()
    {
        $loginData = [
            'email' => 'khongtontai@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Thông tin đăng nhập không chính xác'
            ]);
    }

    public function test_user_cannot_login_without_required_fields()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_user_cannot_login_with_invalid_email_format()
    {
        $loginData = [
            'email' => 'email-khong-hop-le',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}