<?php

use App\Services\Sources\NewsAPI;
use App\Support\NewsHttpClient;
use Illuminate\Http\Client\Response;
use Tests\TestCase;

uses(TestCase::class);

it('fetches articles from NewsAPI', function () {
    $mockHttp = Mockery::mock(NewsHttpClient::class);
    $mockResponse = Mockery::mock(Response::class);

    $mockResponse->shouldReceive('json')->andReturn([
        'articles' => [
            [
                'author' => 'Author Name',
                'source' => ['name' => 'Source Name'],
                'title' => 'Article Title',
                'description' => 'Description',
                'url' => 'http://example.com',
                'urlToImage' => 'http://example.com/image.jpg',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'content' => 'Content'
            ]
        ]
    ]);

    $mockHttp->shouldReceive('get')
        ->once()
        ->with('newsapi', Mockery::any(), Mockery::any())
        ->andReturn($mockResponse);

    $newsApi = new NewsAPI($mockHttp);
    $articles = $newsApi->fetch();

    expect($articles)->toHaveCount(1);
    expect($articles->first()->title)->toBe('Article Title');
    expect($articles->first()->author)->toBe('Author Name');
});
