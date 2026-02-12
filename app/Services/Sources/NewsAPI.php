<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\DTOs\ArticleDTO;
use App\Support\NewsHttpClient;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;

final class NewsAPI implements NewsSourceInterface
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
            url: config('services.news_api.base_url') . "/v2/top-headlines",
            query: [
                'apiKey' => config('services.news_api.key'),
                'country' => 'us',
                'pageSize' => 100,
                'page' => $params['page'] ?? 1,
            ],
        );

        return LazyCollection::make($response->json()['articles'])
            ->map(fn($article) => new ArticleDTO(
                author: $article['author'] ?? null,
                source: $article['source']['name'] ?? 'NewsAPI',
                category: null,
                title: $article['title'],
                content: $article['content'] ?? '',
                url: $article['url'],
                imageUrl: $article['urlToImage'],
                publishedAt: Carbon::parse($article['publishedAt']),
            ));
    }
}
