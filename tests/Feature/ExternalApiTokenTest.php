<?php

namespace Tests\Feature;

use App\Models\ExternalApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;

class ExternalApiTokenTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->state(['is_admin' => true])->create();
        $this->actingAs($this->user);
    }

    /**
     * @group ExternalApiToken
     * Test if user can create token.
     */
    public function test_user_can_create_token(): void
    {
        $response = $this->post('api/external-api-tokens', ['name' => 'Test Token']);
        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'token',
            'createdAt',
            'updatedAt'
        ]]);

        $data = $response->json('data');

        $this->assertEquals('Test Token', $data['name']);
        $this->assertNotEmpty($data['token']);

        $this->assertDatabaseHas('external_api_tokens', ['name' => $data['name'], 'token' => $data['token']]);
    }

    /**
     * @group ExternalApiToken
     * Test if a token name can be updated.
     */
    public function test_a_token_name_can_be_updated(): void
    {
        $token = $this->createToken()->first();

        $data = [
            'name' => 'New Test'
        ];

        $response = $this->put("api/external-api-tokens/{$token->id}",  $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'token',
            'createdAt',
            'updatedAt'
        ]]);

        $response->assertJsonFragment(['name' => $data['name'], $response->json('data.token')]);
        $this->assertDatabaseHas('external_api_tokens', ['token' => $response->json('data.token'), 'name' => $data['name'], 'id' => $token->id]);
    }

    /**
     * @group ExternalApiToken
     * Test if a token can be deleted.
     */
    public function test_a_token_can_be_deleted(): void
    {
        $token = $this->createToken()->first();
        $response = $this->delete("api/external-api-tokens/{$token->id}");

        $response->assertSuccessful();
        $response->assertJson([]);
        $this->assertDatabaseMissing('teams', ['id' => $token->id, 'deleted_at' => null]);
    }

    private function createToken($qty = 1)
    {
        return ExternalApiToken::factory()->count($qty)->create();
    }
}

