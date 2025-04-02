<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;
use \App\Models\Game;
use \App\Models\Team;

class GameTest extends TestCase
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
     * @group Game
     * Test if games can be listed.
     */
    public function test_game_can_be_listed(): void
    {
        $this->createGame(10);
        $response = $this->get('api/games');
        $response->assertSuccessful();

        $response->assertJsonCount(10, 'data')
            ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'externalId',
                    'date',
                    'datetime',
                    'season',
                    'status',
                    'period',
                    'time',
                    'postseason',
                    'homeTeamScore',
                    'visitorTeamScore',
                    'homeTeamId',
                    'visitorTeamId',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ]);
    }

    /**
     * @group Game
     * Test if a game can be created.
     */
    public function test_a_game_can_be_created(): void
    {
        $teams = $this->createTeam(2);
        $homeTeam = $teams->first();
        $visitorTeam =  $teams->last();

        $data = [
            'externalId' => 12345678,
            'date' => now(),
            'season' => 2025,
            'status' => 'Final',
            'period' => 4,
            'time' => null,
            'postseason' => false,
            'homeTeamScore' => 66,
            'visitorTeamScore' => 68,
            'datetime' => now()->format('Y-m-d\TH:i:s.u\Z'),
            'homeTeamId' => $homeTeam->id,
            'visitorTeamId' => $visitorTeam->id,
        ];

        $response = $this->post('api/games', $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'externalId',
            'date',
            'datetime',
            'season',
            'status',
            'period',
            'time',
            'postseason',
            'homeTeamScore',
            'visitorTeamScore',
            'homeTeamId',
            'visitorTeamId',
            'createdAt',
            'updatedAt'
        ]]);

        $this->assertDatabaseHas('games', ['external_id' => $data['externalId'], 'id' => $response->json('data.id')]);
    }

    /**
     * @group Game
     * Test if a game can be updated.
     */
    public function test_a_game_can_be_updated(): void
    {
        $game = $this->createGame()->first();

        $data = [
            'externalId' => 87654321,
            'homeTeamScore' => '999'
        ];

        $response = $this->put("api/games/{$game->id}",  $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
           'id',
            'externalId',
            'date',
            'datetime',
            'season',
            'status',
            'period',
            'time',
            'postseason',
            'homeTeamScore',
            'visitorTeamScore',
            'homeTeamId',
            'visitorTeamId',
            'createdAt',
            'updatedAt'
        ]]);

        $response->assertJsonFragment(['externalId' => $data['externalId'], 'homeTeamScore' => $data['homeTeamScore']]);
        $this->assertDatabaseHas('games', ['external_id' => $data['externalId'], 'home_team_score' => $data['homeTeamScore'], 'id' => $game->id]);
    }

    /**
     * @group Game
     * Test if a game can be deleted.
     */
    public function test_a_game_can_be_deleted(): void
    {
        $game = $this->createGame()->first();
        $response = $this->delete("api/games/{$game->id}");

        $response->assertSuccessful();
        $response->assertJson([]);
        $this->assertDatabaseMissing('games', ['id' => $game->id, 'deleted_at' => null]);
    }

    private function createGame($qty = 1)
    {
        return Game::factory()->count($qty)->create();
    }

    private function createTeam($qty = 1)
    {
        return Team::factory()->count($qty)->create();
    }
}

