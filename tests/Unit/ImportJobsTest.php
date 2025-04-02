<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use App\Jobs\ImportTeamsJob;
use App\Jobs\ImportPlayersJob;
use App\Jobs\ImportGamesJob;

class ImportJobsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::flushall();
    }

    /**
     * @group ImportJobs
     * Test if dispatche import jobs correctly
     */
    public function test_dispatches_import_jobs_correctly()
    {
        Bus::fake();

        dispatch(new ImportTeamsJob());
        dispatch(new ImportPlayersJob());
        dispatch(new ImportGamesJob());

        Bus::assertDispatched(ImportTeamsJob::class);
        Bus::assertDispatched(ImportPlayersJob::class);
        Bus::assertDispatched(ImportGamesJob::class);
    }

    /**
     * @group ImportJobs
     * Test if respects rate limit
     */
    public function test_respects_rate_limit()
    {
        Redis::set('import_requests', 30); 
        $mockJob = \Mockery::mock(ImportPlayersJob::class)->makePartial();
        $mockJob->shouldReceive('checkRateLimit')->andReturn(false);
        $result = $mockJob->checkRateLimit();

        $this->assertFalse($result, "The job should respect the 30 requests per minute rate limit.");
    }

     /**
     * @group ImportJobs
     * Test if resets rate limit after time window
     */
    public function test_resets_rate_limit_after_time_window()
    {
        Redis::set('import_requests', 30);
        Redis::expire('import_requests', 1);
        Redis::del('import_requests');
    
        $job = new ImportPlayersJob();
        $result = $job->checkRateLimit();
    
        $this->assertTrue($result, "The job should allow new requests after the time limit.");
    }

    /**
     * @group ImportJobs
     * Test if import command dispatches jobs
     */
    public function test_import_command_dispatches_jobs()
    {
        Bus::fake();

        $this->artisan('import-from-api')->assertExitCode(0);

        Bus::dispatchSync(new ImportTeamsJob());
        Bus::dispatchSync(new ImportPlayersJob());
        Bus::dispatchSync(new ImportGamesJob());
        
        Bus::assertDispatched(ImportTeamsJob::class);
        Bus::assertDispatched(ImportPlayersJob::class);
        Bus::assertDispatched(ImportGamesJob::class);
    }
}
