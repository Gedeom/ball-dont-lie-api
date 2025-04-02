<?php

namespace App\Jobs\Base;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use App\Events\ImportProgress;

abstract class ImportBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $apiKey;
    protected string $url;
    protected int $cursor;
    protected string $type;


    public function __construct($cursor = 0)
    {
        $this->url = "https://api.balldontlie.io/v1/{$this->type}?per_page=100&seasons[]=2024";
        $this->apiKey = env("BALL_DONT_LIE_API_KEY", "5d91eadc-3c27-46ff-b604-c79da28fb28f");
        $this->cursor = $cursor;
    }

    /**
     * Check if the rate limit has been exceeded. If it has, release the job in 60 seconds.
     * Otherwise, increment the counter and set the TTL if it's not set.
     *
     * @return bool
     */
    public function checkRateLimit()
    {
        $key = 'import_requests';
        $limit = 30;
        $window = 60;

        while (true) {
            $current = Redis::get($key) ?: 0;

            if ($current < $limit) {
                Redis::incr($key);

                if (Redis::ttl($key) == -1) {
                    Redis::expire($key, $window);
                }
                return true;
            }

            sleep(5);
        }
    }

    /**
     * Makes a GET request to the API URL with the cursor.
     * 
     * If the rate limit has been exceeded, the request is not made and null is returned.
     * 
     * @return array|null
     */
    protected function makeRequest()
    {
        $this->checkRateLimit();
        $this->sendProgressMessage("$this->cursor, --- $this->type");

        $client = new \GuzzleHttp\Client();
        $this->url .=  "&cursor={$this->cursor}";

        $response = $client->request('GET', $this->url, [
            'headers' => [
                'Authorization' => $this->apiKey
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Schedules the next job with a delay to respect the rate limit.
     * 
     * @param int $nextCursor The cursor to use for the next job.
     * @return void
     */
    protected function dispatchNextPage($nextCursor)
    {
        if(!$nextCursor) {
            return;
        }

        $this->checkRateLimit();

        $jobClass = get_class($this);
        
        // Schedule the next job with a delay to respect the rate limit
        dispatch(new $jobClass($nextCursor))->onQueue('imports');
    }

    /**
     * Sends a message to the ImportProgress event, which can be listened to
     * in order to display progress to the user.
     *
     * @param string $msg The message to send
     * @return void
     */
    protected function sendProgressMessage($msg)
    {
        event(new ImportProgress($msg));
    }

    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
    }
}
