<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @group Auth
     * @group Login
     * Test if a user can login
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->state(['password' => Hash::make('12345678')])->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['token']);
        $this->assertNotEmpty($response->json('token'));
    }

    /**
     * @group Auth
     * @group Logout
     * Test if a user can logout
     */
    public function test_a_user_can_logout(): void
    {
        $user = User::factory()->state(['password' => Hash::make('12345678')])->create();

        $token = $this->actingAs($user)
            ->post('api/login', [
                'email' => $user->email,
                'password' => '12345678'
            ])->json('token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->post('api/logout');

        $response->assertSuccessful();
    }
}

