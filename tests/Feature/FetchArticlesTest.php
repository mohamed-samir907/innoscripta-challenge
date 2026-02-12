<?php

use App\Console\Commands\FetchArticles;
use App\Contracts\NewsSourceInterface;
use App\DTOs\ArticleDTO;
use App\Jobs\FetchArticlesFromSource;
use App\Repositories\ArticleRepository;
use App\Services\NewsAggregatorService;
use App\Services\Sources\NewsAPI;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;

test('news aggregator service fetches and stores articles', function () {
    // 1. Mock the NewsSource (NewsAPI)
    $mockSource = Mockery::mock(NewsAPI::class);
    $articleDTO = new ArticleDTO(
        author: 'John Doe',
        source: 'NewsAPI',
        category: 'Tech',
        title: 'Test Article',
        content: 'Content',
        url: 'http://example.com/article',
        imageUrl: 'http://example.com/image.jpg',
        publishedAt: Carbon::now()
    );

    // Return a LazyCollection of DTOs
    $mockSource->shouldReceive('fetch')
        ->once()
        ->with(['page' => 1])
        ->andReturn(LazyCollection::make(function () use ($articleDTO) {
            yield $articleDTO;
        }));

    // Bind the mock to the container so the Factory picks it up
    $this->app->instance(NewsAPI::class, $mockSource);

    // 2. Mock the ArticleRepository
    $mockRepo = Mockery::mock(ArticleRepository::class);
    $mockRepo->shouldReceive('upsert')
        ->once()
        ->with(Mockery::on(function ($arg) {
            return count($arg) === 1 && $arg[0]['title'] === 'Test Article';
        }))
        ->andReturn(1);

    // 3. Instantiate the service with the mocked repo
    $service = new NewsAggregatorService($mockRepo);

    // 4. Call the method
    $count = $service->fetchAndStore('newsapi');

    // 5. Assertions
    expect($count)->toBe(1);
});

test('fetch articles from source job calls service', function () {
    // Mock the Service
    $mockService = Mockery::mock(NewsAggregatorService::class);
    $mockService->shouldReceive('fetchAndStore')
        ->with('newsapi', 1)
        ->once()
        ->andReturn(10);

    // We also need to handle the loop in the job. 
    // The job loops until count is 0. So let's make the second call return 0.
    $mockService->shouldReceive('fetchAndStore')
        ->with('newsapi', 2)
        ->once()
        ->andReturn(0);

    // Instantiate the job
    $job = new FetchArticlesFromSource('newsapi');

    // Execute the job's handle method with the mocked service
    $job->handle($mockService);
});

test('fetch articles command dispatches jobs', function () {
    Bus::fake();

    // Run the command
    $this->artisan('news:fetch')
        ->assertExitCode(0);

    // Assert jobs were dispatched for all sources
    Bus::assertDispatched(FetchArticlesFromSource::class, function ($job) {
        return $job->sourceName === 'guardian';
    });
    Bus::assertDispatched(FetchArticlesFromSource::class, function ($job) {
        return $job->sourceName === 'nytimes';
    });
    Bus::assertDispatched(FetchArticlesFromSource::class, function ($job) {
        return $job->sourceName === 'newsapi';
    });
});

test('fetch articles command dispatches job for specific source', function () {
    Bus::fake();

    $this->artisan('news:fetch', ['source' => 'guardian'])
        ->assertExitCode(0);

    Bus::assertDispatched(FetchArticlesFromSource::class, function ($job) {
        return $job->sourceName === 'guardian';
    });

    Bus::assertNotDispatched(FetchArticlesFromSource::class, function ($job) {
        return $job->sourceName === 'nytimes';
    });
});
