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
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toModel(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'source' => $this->source,
            'category' => $this->category,
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'published_at' => $this->publishedAt,
        ];
    }
}
