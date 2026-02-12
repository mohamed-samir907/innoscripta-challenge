<?php

use App\Services\Sources\NYTimes;
use App\Support\NewsHttpClient;
use Illuminate\Http\Client\Response;
use Tests\TestCase;

uses(TestCase::class);

it('fetches articles from NYTimes', function () {
    $mockHttp = Mockery::mock(NewsHttpClient::class);
    $mockResponse = Mockery::mock(Response::class);

    $mockResponse->shouldReceive('json')
        ->with('response.docs')
        ->andReturn([
            [
                'headline' => ['main' => 'Article Title'],
                'section_name' => 'Category',
                'web_url' => 'http://example.com',
                'pub_date' => '2023-01-01T00:00:00Z',
                'snippet' => 'Content',
                'byline' => ['original' => 'Author Name'],
                'multimedia' => ['default' => ['url' => 'http://example.com/image.jpg']]
            ]
        ]);

    $mockHttp->shouldReceive('get')
        ->once()
        ->with('newsapi', Mockery::any(), Mockery::any())
        ->andReturn($mockResponse);

    $nyTimes = new NYTimes($mockHttp);
    $articles = $nyTimes->fetch();

    expect($articles)->toHaveCount(1);
    expect($articles->first()->title)->toBe('Article Title');
    expect($articles->first()->source)->toBe('New York Times');
});
