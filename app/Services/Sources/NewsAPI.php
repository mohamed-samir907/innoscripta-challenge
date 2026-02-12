<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\DTOs\ArticleDTO;

final class NewsAPI implements NewsSourceInterface
{
    /**
     * @return ArticleDTO[]
     */
    public function fetch(array $params = []): array
    {
        return [];
    }
}
