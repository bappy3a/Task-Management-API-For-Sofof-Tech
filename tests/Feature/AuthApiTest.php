<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Md Allrafi Islam (Bappy)',
            'email' => 'bappy@dev.local',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_at',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ],
            'metadata'
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals(200, $response['status']);
        $this->assertDatabaseHas('users', [
            'email' => 'bappy@dev.local',
        ]);
    }

    public function test_user_can_login()
    {
         User::factory()->create([
            'name' => 'Md Allrafi Islam (Bappy)',
            'email' => 'bappy@dev.local',
            'password' => bcrypt('password123'),
            'role' => 'employee',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'bappy@dev.local',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_at',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'role',
                    'created_at',
                    'updated_at',
                ],
            ],
            'metadata',
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('bappy@dev.local', $response['data']['user']['email']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email' => 'logout-test@dev.local',
            'password' => bcrypt('password123'),
        ]);

        // Simulate user login using Sanctum
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'status_code' => 200,
            'status' => 200,
            'message' => 'Successfully!',
            'data' => true,
            'metadata' => null,
        ]);
    }

    public function test_user_can_refresh_token()
    {
        $user = User::factory()->create([
            'email' => 'refresh@dev.local',
            'password' => bcrypt('password'),
        ]);

        // Simulate login using Sanctum
        $this->actingAs($user, 'sanctum');

        // Call the refresh token endpoint
        $response = $this->postJson('/api/v1/auth/refresh-token');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type',
            ],
            'metadata',
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('Bearer', $response['data']['token_type']);
    }

    public function test_authenticated_user_can_be_fetched()
    {
        $user = User::factory()->create([
            'name' => 'Md Allrafi Islam (Bappy)',
            'email' => 'bappy@dev.local',
            'role' => 'employee',
        ]);

        // Simulate authenticated user
        $this->actingAs($user, 'sanctum');

        // Send GET request to /api/user or your endpoint
        $response = $this->getJson('/api/v1/auth/user');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'role',
                'created_at',
                'updated_at',
            ],
            'metadata',
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('bappy@dev.local', $response['data']['email']);
    }
}
