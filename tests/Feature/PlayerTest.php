<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;
use \App\Models\Player;
use \App\Models\Team;

class PlayerTest extends TestCase
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
     * @group Player
     * Test if players can be listed.
     */
    public function test_players_can_be_listed(): void
    {
        $this->createPlayer(10);
        $response = $this->get('api/players');
        $response->assertSuccessful();

        $response->assertJsonCount(10, 'data')
            ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'externalId',
                    'firstName',
                    'lastName',
                    'position',
                    'height',
                    'weight',
                    'jerseyNumber',
                    'college',
                    'country',
                    'draftYear',
                    'draftRound',
                    'draftNumber',
                    'teamId'
                ]
            ]
        ]);
    }

    /**
     * @group Player
     * Test if a player can be created.
     */
    public function test_a_player_can_be_created(): void
    {
        $team = $this->createTeam()->first();

        $data = [
            'externalId' => 12345678,
            'firstName' => 'Alex',
            'lastName' => 'Abrines',
            'position' => 'G',
            'height' => '6-6',
            'weight' => 190,
            'jerseyNumber' => 8,
            'college' => 'FC Barcelona',
            'country' => 'Spain',
            'draftYear' => 2013,
            'draftRound' => 2,
            'draftNumber' => 32,
            'teamId' => $team->id
        ];

        $response = $this->post('api/players', $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'externalId',
            'firstName',
            'lastName',
            'position',
            'height',
            'weight',
            'jerseyNumber',
            'college',
            'country',
            'draftYear',
            'draftRound',
            'draftNumber',
            'teamId',
        ]]);

        $this->assertDatabaseHas('players', ['external_id' => $data['externalId'], 'id' => $response->json('data.id')]);
    }

    /**
     * @group Player
     * Test if a player can be updated.
     */
    public function test_a_player_can_be_updated(): void
    {
        $player = $this->createPlayer()->first();

        $data = [
            'externalId' => 87654321,
            'firstName' => 'Test'
        ];

        $response = $this->put("api/players/{$player->id}",  $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'externalId',
            'firstName',
            'lastName',
            'position',
            'height',
            'weight',
            'jerseyNumber',
            'college',
            'country',
            'draftYear',
            'draftRound',
            'draftNumber',
            'teamId',
        ]]);

        $response->assertJsonFragment(['externalId' => $data['externalId'], 'firstName' => $data['firstName']]);
        $this->assertDatabaseHas('players', ['external_id' => $data['externalId'], 'first_Name' => $data['firstName'], 'id' => $player->id]);
    }

    /**
     * @group Player
     * Test if a player can be deleted.
     */
    public function test_a_player_can_be_deleted(): void
    {
        $player = $this->createPlayer()->first();
        $response = $this->delete("api/players/{$player->id}");

        $response->assertSuccessful();
        $response->assertJson([]);
        $this->assertDatabaseMissing('players', ['id' => $player->id, 'deleted_at' => null]);
    }

    private function createPlayer($qty = 1)
    {
        return Player::factory()->count($qty)->create();
    }

    private function createTeam($qty = 1)
    {
        return Team::factory()->count($qty)->create();
    }
}

