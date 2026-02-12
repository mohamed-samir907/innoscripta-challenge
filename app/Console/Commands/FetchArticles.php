<?php

namespace App\Console\Commands;

use App\Jobs\FetchArticlesFromSource;
use App\Services\NewsAggregatorService;
use Illuminate\Console\Command;

final class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch {source? : The name of the source to fetch from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from news sources and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = $this->argument('source');

        if ($source) {
            $this->info("Dispatching fetch job for source: {$source}");
            FetchArticlesFromSource::dispatch($source);
            return self::SUCCESS;
        }

        $this->info('Dispatching fetch jobs for all sources...');

        foreach (['guardian', 'nytimes', 'newsapi'] as $sourceName) {
            $this->line(" - Dispatching for {$sourceName}");
            FetchArticlesFromSource::dispatch($sourceName);
        }

        $this->info('All jobs dispatched successfully!');

        return self::SUCCESS;
    }
}
