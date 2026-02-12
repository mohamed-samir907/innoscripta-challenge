<?php

use App\Services\Sources\TheGuardian;
use App\Support\NewsHttpClient;
use Illuminate\Http\Client\Response;
use Tests\TestCase;

uses(TestCase::class);

it('fetches articles from The Guardian', function () {
    $mockHttp = Mockery::mock(NewsHttpClient::class);
    $mockResponse = Mockery::mock(Response::class);

    $mockResponse->shouldReceive('json')->andReturn([
        'response' => [
            'results' => [
                [
                    'webTitle' => 'Article Title',
                    'sectionName' => 'Category',
                    'webUrl' => 'http://example.com',
                    'webPublicationDate' => '2023-01-01T00:00:00Z',
                    'fields' => ['body' => 'Content'],
                    'references' => [['author' => 'Author Name']],
                    'elements' => [['assets' => [['file' => 'http://example.com/image.jpg']]]]
                ]
            ]
        ]
    ]);

    $mockHttp->shouldReceive('get')
        ->once()
        ->with('guardian', Mockery::any(), Mockery::any())
        ->andReturn($mockResponse);

    $guardian = new TheGuardian($mockHttp);
    $articles = $guardian->fetch();

    expect($articles)->toHaveCount(1);
    expect($articles->first()->title)->toBe('Article Title');
    expect($articles->first()->category)->toBe('Category');
});
