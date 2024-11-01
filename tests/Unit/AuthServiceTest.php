<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /** @test */
    public function it_can_register_an_admin()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->authService->register($data);

        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('token_type', $response);
        $this->assertEquals(201, $response['status']);
        $this->assertDatabaseHas('admins', ['email' => $data['email']]);
    }

    /** @test */
    public function it_fails_to_register_with_invalid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ];

        $response = $this->authService->register($data);

        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals(422, $response['status']);
    }

    /** @test */
    public function it_can_login_an_admin()
    {
        $admin = Admin::factory()->create(['password' => bcrypt('password123')]);

        $data = [
            'email' => $admin->email,
            'password' => 'password123',
        ];

        $response = $this->authService->login($data);

        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('token_type', $response);
        $this->assertEquals(200, $response['status']);
    }

    /** @test */
    public function it_fails_to_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->authService->login($data);

        $this->assertEquals('Invalid credentials', $response['message']);
        $this->assertEquals(401, $response['status']);
    }
}
