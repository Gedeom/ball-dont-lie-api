<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\User;
use \App\Models\Team;

class TeamTest extends TestCase
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
     * @group Team
     * Test if teams can be listed.
     */
    public function test_teams_can_be_listed(): void
    {
        $this->createTeam(10);
        $response = $this->get('api/teams');
        $response->assertSuccessful();

        $response->assertJsonCount(10, 'data')
            ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'externalId', 'conference', 'division', 'city', 'name', 'fullName', 'abbreviation', 'createdAt', 'updatedAt']
            ]
        ]);
    }

    /**
     * @group Team
     * Test if a team can be created.
     */
    public function test_a_team_can_be_created(): void
    {
        $data = [
            'externalId' => 12345678,
            "conference" => "East",
            "division" => "Southeast",
            "city" => "Atlanta",
            "name" => "Hawks",
            "fullName" => "Atlanta Hawks",
            "abbreviation" => "ATL"
        ];

        $response = $this->post('api/teams', $data);
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'externalId',
            'conference',
            'division',
            'city',
            'name',
            'fullName',
            'abbreviation',
            'createdAt',
            'updatedAt'
        ]]);

        $this->assertDatabaseHas('teams', ['external_id' => $data['externalId'], 'id' => $response->json('data.id')]);
    }

    /**
     * @group Team
     * Test if a team can be updated.
     */
    public function test_a_team_can_be_updated(): void
    {
        $team = $this->createTeam()->first();

        $data = [
            'externalId' => 87654321,
            'name' => 'Test'
        ];

        $response = $this->put("api/teams/{$team->id}",  $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [
            'id',
            'externalId',
            'conference',
            'division',
            'city',
            'name',
            'fullName',
            'abbreviation',
            'createdAt',
            'updatedAt'
        ]]);

        $response->assertJsonFragment(['externalId' => $data['externalId'], 'name' => $data['name']]);
        $this->assertDatabaseHas('teams', ['external_id' => $data['externalId'], 'name' => $data['name'], 'id' => $team->id]);
    }

    /**
     * @group Team
     * Test if a team can be deleted.
     */
    public function test_a_team_can_be_deleted(): void
    {
        $team = $this->createTeam()->first();
        $response = $this->delete("api/teams/{$team->id}");

        $response->assertSuccessful();
        $response->assertJson([]);
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'deleted_at' => null]);
    }

    private function createTeam($qty = 1)
    {
        return Team::factory()->count($qty)->create();
    }
}

