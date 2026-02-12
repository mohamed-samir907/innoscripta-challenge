<?php

namespace App\Services\Sources;

use App\Contracts\NewsSourceInterface;
use App\DTOs\ArticleDTO;

final class TheGuardian implements NewsSourceInterface
{
    /**
     * @return ArticleDTO[]
     */
    public function fetch(array $params = []): array
    {
        return [];
    }
}
