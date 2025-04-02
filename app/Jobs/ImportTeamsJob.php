<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Base\ImportBaseJob;
use App\Services\TeamService;
use App\Parsers\TeamParser;

class ImportTeamsJob extends ImportBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $type = 'teams';
    private TeamService $service;
    private TeamParser $parser;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->service = new TeamService();
        $this->parser = new TeamParser();

        try {
            if (!$this->cursor) {
                $this->sendProgressMessage('Starting team import...');
            }

            $response = $this->makeRequest();
            
            if (!$response) {
                return;
            }

            $teams = $response['data'];
            $meta = $response['meta'] ?? ['prev_cursor' => $this->cursor, 'next_cursor' => 0];
            $next = $meta['next_cursor'] ?? 0;
            $total = ($meta['prev_cursor'] ?? 0) + count($teams);
            
            foreach ($teams as $team) {
                $this->handleTeam($team);
            }

            $this->sendProgressMessage("Processed {$total} (Teams)");

            if(!$next) {
                $this->sendProgressMessage('Team import completed!');
            }
            
            $this->dispatchNextPage($next);
        } catch (\Exception $e) {
            $this->sendProgressMessage('Error importing teams: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleTeam(array $team): void
    {
        $data = $this->parser->parseFields($team, 'import');
        $teamModel = $this->service->getByExternalId($data['externalId']);

        if ($teamModel) {
            $this->updateTeam($teamModel->id,  $data);
            return;
        }

        $this->insertTeam( $data);
    }

    private function insertTeam(array $teamData): void
    {
        $this->service->create($teamData);
    }

    private function updateTeam(int $id, array $teamData): void
    {
        $this->service->update($id, $teamData);
    }
}
