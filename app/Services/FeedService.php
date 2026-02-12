<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FeedService
{
    /**
     * Get personalized feed for the user.
     */
    public function getFeed(User $user): LengthAwarePaginator
    {
        $preferences = $user->preferences;

        if ($preferences->isEmpty()) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }

        $sources = $preferences->where('type', 'source')->pluck('value');
        $categories = $preferences->where('type', 'category')->pluck('value');
        $authors = $preferences->where('type', 'author')->pluck('value');

        $query = Article::query();

        $query->where(function ($q) use ($sources, $categories, $authors) {
            if ($sources->isNotEmpty()) {
                $q->orWhereIn('source', $sources);
            }
            if ($categories->isNotEmpty()) {
                $q->orWhereIn('category', $categories);
            }
            if ($authors->isNotEmpty()) {
                $q->orWhereIn('author', $authors);
            }
        });

        return $query->orderByDesc('published_at')->paginate(20);
    }
}
