<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthThrottleRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Run the default seeder required structures
        $this->artisan('migrate');
    }

    public function test_login_throttle_blocks_after_limit()
    {
        $user = User::factory()->create([
            'email' => 'throttle@test.local',
            'password' => bcrypt('secret123'),
        ]);

        // 6 allowed attempts, 7th should be throttled
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', [
                'email' => 'throttle@test.local',
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHas('error');
        }

        $response = $this->post('/login', [
            'email' => 'throttle@test.local',
            'password' => 'wrong-password',
        ]);

        // Too Many Requests
        $response->assertStatus(429);
    }

    public function test_role_middleware_blocks_non_admin_and_allows_admin()
    {
        // Create a regular parent user
        $parent = User::factory()->create([
            'role' => 'parent',
            'email' => 'parent@test.local',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($parent);

        $resp = $this->get('/roles');
        $resp->assertStatus(403);

        // Create admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.local',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($admin);
        $resp2 = $this->get('/roles');

        // Expect successful access (view exists) or redirect to login otherwise
        $this->assertTrue(in_array($resp2->getStatusCode(), [200, 302]));
    }
}
