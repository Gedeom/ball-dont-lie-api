<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportTeamsJob;
use App\Jobs\ImportPlayersJob;
use App\Jobs\ImportGamesJob;
use App\Events\ImportProgress;
use Illuminate\Support\Facades\Bus;

class ImportFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-from-api {--type=all} {--runQueue=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from external API (type: teams, players, games, all)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $runQueue = filter_var($this->option('runQueue'), FILTER_VALIDATE_BOOLEAN);
    
        $this->listenToImportProgress();
        $this->deleteImportQueues();

        switch ($type) {
            case 'teams':
                $this->dispatchTeamsJob();
                $this->info('Teams import started.');
                break;
            case 'players':
                $this->dispatchPlayersJob();
                $this->info('Players import started.');
                break;
            case 'games':
                $this->dispatchGamesJob();
                $this->info('Games import started.');
                break;
                
            case 'all':
                Bus::chain([
                    new ImportTeamsJob(),
                    new ImportPlayersJob(),
                    new ImportGamesJob(),
                ])->onQueue('imports')->dispatch();
                $this->info('All imports were started.');
                break;
        }

        if( $runQueue ) {
            $this->call('queue:work', ['--stop-when-empty' => true, '--queue' => 'imports']);
        }
    }

    private function dispatchTeamsJob($delayedMinutes = 0)
    {
        ImportTeamsJob::dispatch()->onQueue('imports')->delay(now()->addMinutes($delayedMinutes));
    }

    private function dispatchPlayersJob($delayedMinutes = 0)
    {
        ImportPlayersJob::dispatch()->onQueue('imports')->delay(now()->addMinutes($delayedMinutes));
    }

    private function dispatchGamesJob($delayedMinutes = 0)
    {
        ImportGamesJob::dispatch()->onQueue('imports')->delay(now()->addMinutes($delayedMinutes));
    }

    private function listenToImportProgress()
    {
        \Event::listen(ImportProgress::class, function ($event) {
            $this->line($event->message);
        });
    }

    private function deleteImportQueues()
    {
        \DB::table('jobs')->where('queue', 'imports')->delete();
    }
}
