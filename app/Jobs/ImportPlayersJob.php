<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Base\ImportBaseJob;
use App\Services\PlayerService;
use App\Services\TeamService;
use App\Parsers\PlayerParser;
use App\Parsers\TeamParser;

class ImportPlayersJob extends ImportBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $type = 'players';
    private PlayerService $playerService;
    private TeamService $teamService;
    private PlayerParser $playerParser;
    private TeamParser $teamParser;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->playerService = new PlayerService();
        $this->teamService = new TeamService();
        $this->playerParser = new PlayerParser();
        $this->teamParser = new TeamParser();

        try {
            if (!$this->cursor) {
                $this->sendProgressMessage('Starting players import...');
            }

            $response = $this->makeRequest();
            
            if (!$response) {
                return;
            }

            $items = $response['data'];
            $meta = $response['meta'] ?? [];
            $next = $meta['next_cursor'] ?? 0;
            $total = ($meta['prev_cursor'] ?? $this->cursor) + count($items);
            
            foreach ($items as $item) {
                $this->handlePlayer($item);
            }

            $this->sendProgressMessage("Processed {$total} (Players)");

            if(!$next) {
                $this->sendProgressMessage('Player import completed!');
            }
            
            $this->dispatchNextPage($next);

        } catch (\Exception $e) {
            $this->sendProgressMessage('Error importing players: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handlePlayer(array $player): void
    {
        $teamModel = $this->teamService->getByExternalId($player['team']['id']);

        if (!$teamModel) {
            $teamModel = $this->insertTeam($player['team']);
        }

        $player['team_id'] = $teamModel->id;
        $data = $this->playerParser->parseFields($player, 'import');
        $playerModel = $this->playerService->getByExternalId($data['externalId']);

        if ($playerModel) {
            $this->updatePlayer($playerModel->id,  $data);
            return;
        }

        $this->insertPlayer($data);
    }

    private function insertPlayer(array $data): void
    {
        $this->playerService->create($data);
    }

    private function updatePlayer(int $id, array $data): void
    {
        $this->playerService->update($id, $data);
    }

    private function insertTeam(array $data): object
    {
        $data = $this->teamParser->parseFields($data, 'import');
        return $this->teamService->create($data);
    }
}

