<?php

namespace App\Services\Sources;

use Carbon\Carbon;
use App\DTOs\ArticleDTO;
use App\Support\NewsHttpClient;
use App\Contracts\NewsSourceInterface;
use Illuminate\Support\LazyCollection;

final class TheGuardian implements NewsSourceInterface
{
    public function __construct(
        private NewsHttpClient $http,
    ) {
    }

    /**
     * @return LazyCollection<int, ArticleDTO>
     */
    public function fetch(array $params = []): LazyCollection
    {
        $response = $this->http->get(
            source: 'guardian',
            url: config('services.theguardian.base_url') . "/search",
            query: [
                'api-key' => config('services.theguardian.key'),
                'page-size' => 100,
                'page' => $params['page'] ?? 1,
                'show-fields' => 'body',
                'show-references' => 'author',
                'show-elements' => 'image',
            ],
        );

        return LazyCollection::make($response->json()['response']['results'])
            ->map(fn($article) => new ArticleDTO(
                author: $article['references'][0]['author'] ?? null,
                source: 'The Guardian',
                category: $article['sectionName'],
                title: $article['webTitle'],
                content: strip_tags($article['fields']['body']) ?? '',
                url: $article['webUrl'],
                imageUrl: $article['elements'][0]['assets'][0]['file'] ?? '',
                publishedAt: Carbon::parse($article['webPublicationDate']),
            ));
    }
}
