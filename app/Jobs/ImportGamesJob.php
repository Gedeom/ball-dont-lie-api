<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Base\ImportBaseJob;
use App\Services\GameService;
use App\Services\TeamService;
use App\Parsers\GameParser;
use App\Parsers\TeamParser;

class ImportGamesJob extends ImportBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $type = 'games';
    private GameService $gameService;
    private TeamService $teamService;
    private GameParser $gameParser;
    private TeamParser $teamParser;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->gameService = new GameService();
        $this->gameParser = new GameParser();
        $this->teamService = new TeamService();
        $this->teamParser = new TeamParser();

        try {
            if (!$this->cursor) {
                $this->sendProgressMessage('Starting games import...');
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
                $this->handleGame($item);
            }

            $this->sendProgressMessage("Processed {$total} (Games)");

            if(!$next) {
                $this->sendProgressMessage('Game import completed!');
            }
            
            $this->dispatchNextPage($next);

        } catch (\Exception $e) {
            $this->sendProgressMessage('Error importing games: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleGame($game): void
    {
        $homeTeamModel = $this->teamService->getByExternalId($game['home_team']['id']);
        $visitorTeamModel = $this->teamService->getByExternalId($game['visitor_team']['id']);

        if (!$homeTeamModel) {
            $homeTeamModel = $this->insertTeam($game['home_team']);
        }

        if (!$visitorTeamModel) {
            $visitorTeamModel = $this->insertTeam($game['visitor_team']);
        }

        $game['home_team_id'] = $homeTeamModel->id;
        $game['visitor_team_id'] = $visitorTeamModel->id;

        $data = $this->gameParser->parseFields($game, 'import');
        $gameModel = $this->gameService->getByExternalId($data['externalId']);

        if ($gameModel) {
            $this->updateGame($gameModel->id,  $data);
            return;
        }

        $this->insertGame($data);
    }

    private function insertGame(array $data): void
    {
        $this->gameService->create($data);
    }

    private function updateGame(int $id, array $data): void
    {
        $this->gameService->update($id, $data);
    }

    private function insertTeam(array $data): object
    {
        $data = $this->teamParser->parseFields($data, 'import');
        return $this->teamService->create($data);
    }
}
