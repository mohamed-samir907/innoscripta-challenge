<?php

namespace App\Repositories;

use App\Models\Article;

final class ArticleRepository
{
    public function find(int $id): ?Article
    {
        return Article::find($id);
    }

    public function upsert(array $articles): int
    {
        return Article::upsert(
            $articles,
            ['url'],
            ['title', 'content', 'author', 'source', 'category', 'image_url', 'published_at']
        );
    }
}
