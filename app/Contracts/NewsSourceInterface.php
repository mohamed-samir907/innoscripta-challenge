<?php

namespace App\Contracts;

use App\DTOs\ArticleDTO;
use Illuminate\Support\LazyCollection;

interface NewsSourceInterface
{
    /**
     * @param array<string, mixed> $params
     *
     * @return LazyCollection<int, ArticleDTO>
     */
    public function fetch(array $params = []): LazyCollection;
}
