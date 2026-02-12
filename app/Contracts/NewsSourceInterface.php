<?php

namespace App\Contracts;

use App\DTOs\ArticleDTO;

interface NewsSourceInterface
{
    /**
     * @param array<string, mixed> $params
     *
     * @return ArticleDTO[]
     */
    public function fetch(array $params = []): array;
}
