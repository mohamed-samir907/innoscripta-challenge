<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class ArticleDTO
{
    public function __construct(
        public ?string $author,
        public ?string $source,
        public ?string $category,
        public string $title,
        public string $content,
        public string $url,
        public string $imageUrl,
        public Carbon $publishedAt,
    ) {}
}
