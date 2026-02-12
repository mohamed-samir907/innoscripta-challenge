<?php

namespace App\Jobs;

use App\Services\NewsAggregatorService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class FetchArticlesFromSource implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $sourceName,
    ) {
    }

    public function handle(NewsAggregatorService $service): void
    {
        // Get the latest page number from cache if the job failed, or page 1 for the new fetch
        $page = Cache::get("{$this->sourceName}_page", 1);

        try {
            while (true) {
                $count = $service->fetchAndStore($this->sourceName, $page);

                if ($count === 0) {
                    break;
                }

                Log::info("Fetched articles from [{$this->sourceName}] page {$page} with {$count} articles.");

                // Update the latest page number in cache for the next fetch
                $page++;
                Cache::put("{$this->sourceName}_page", $page, now()->addDay());
            }
        } catch (Throwable $e) {
            Log::error("Failed to fetch articles from [{$this->sourceName}]: " . $e->getMessage());
        }

        // Remove the page number from cache after the job is completed
        Cache::forget("{$this->sourceName}_page");
    }
}
