<?php

namespace App\Services\Sources;

use Carbon\Carbon;
use App\DTOs\ArticleDTO;
use App\Support\NewsHttpClient;
use App\Contracts\NewsSourceInterface;
use Illuminate\Support\LazyCollection;

final class NYTimes implements NewsSourceInterface
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
            source: 'newsapi',
            url: config('services.nytimes.base_url') . "/svc/search/v2/articlesearch.json",
            query: [
                'api-key' => config('services.nytimes.key'),
                'page' => $params['page'] ?? 1,
            ],
        );

        return LazyCollection::make($response->json('response.docs'))
            ->map(fn($article) => new ArticleDTO(
                author: $article['byline']['original'] ?? null,
                source: 'New York Times',
                category: $article['section_name'],
                title: $article['headline']['main'] ?? '',
                content: $article['snippet'] ?? '',
                url: $article['web_url'],
                imageUrl: $article['multimedia']['default']['url'] ?? '',
                publishedAt: Carbon::parse($article['pub_date']),
            ));
    }
}
