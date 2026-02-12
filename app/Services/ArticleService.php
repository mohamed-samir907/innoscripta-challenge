<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Pipelines\Articles\Filters\ByAuthor;
use App\Pipelines\Articles\Filters\ByCategory;
use App\Pipelines\Articles\Filters\ByDate;
use App\Pipelines\Articles\Filters\ByKeyword;
use App\Pipelines\Articles\Filters\BySource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Pipeline;

class ArticleService
{
    /**
     * Get paginated articles with filtering.
     */
    public function getArticles(array $filters): LengthAwarePaginator
    {
        $query = Article::query();

        return Pipeline::send($query)
            ->through([
                new ByKeyword($filters['keyword'] ?? null),
                new ByDate($filters['date_start'] ?? null, $filters['date_end'] ?? null),
                new ByCategory($filters['category'] ?? null),
                new BySource($filters['source'] ?? null),
                new ByAuthor($filters['author'] ?? null),
            ])
            ->thenReturn()
            ->orderByDesc('published_at')
            ->paginate(20);
    }

    /**
     * Get a single article by ID.
     */
    public function getArticle(string $id): Article
    {
        return Article::findOrFail($id);
    }
}
