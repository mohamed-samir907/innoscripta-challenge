<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\DTOs\ArticleDTO;
use App\Support\NewsHttpClient;
use Carbon\Carbon;

final class NewsAPI implements NewsSourceInterface
{
    public function __construct(
        private NewsHttpClient $http,
    ) {}

    /**
     * @return ArticleDTO[]
     */
    public function fetch(array $params = []): array
    {
        $response = $this->http->get(
            source: 'newsapi',
            url: config('services.news_api.base_url') . "/v2/top-headlines",
            query: [
                'apiKey'    => config('services.news_api.key'),
                'country'   => 'us',
                'pageSize'  => 100,
                'page'      => $params['page'] ?? 1,
            ],
        );

        return collect($response->json()['articles'])
            ->map(fn($article) => new ArticleDTO(
                author: $article['author'] ?? null,
                source: $article['source']['name'] ?? 'NewsAPI',
                category: null,
                title: $article['title'],
                content: $article['content'] ?? '',
                url: $article['url'],
                imageUrl: $article['urlToImage'],
                publishedAt: Carbon::parse($article['publishedAt']),
            ))
            ->toArray();
    }
}
