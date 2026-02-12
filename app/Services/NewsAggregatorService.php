<?php

namespace App\Services;

use App\Factories\NewsSourceFactory;
use App\Repositories\ArticleRepository;

final class NewsAggregatorService
{
    public function __construct(
        private ArticleRepository $articleRepository,
    ) {
    }

    public function fetchAndStore(string $sourceName, int $page = 1): int
    {
        $source = NewsSourceFactory::create($sourceName);

        $result = 0;

        $source->fetch(['page' => $page])
            ->map(fn($dto) => $dto->toModel())
            ->chunk(10)
            ->each(function ($chunk) use (&$result) {
                $result += $this->articleRepository->upsert($chunk->toArray());
            });

        return $result;
    }
}
