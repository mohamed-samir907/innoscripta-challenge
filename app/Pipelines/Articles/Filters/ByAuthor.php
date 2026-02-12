<?php

namespace App\Pipelines\Articles\Filters;

use App\Pipelines\Articles\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByAuthor implements Filter
{
    public function __construct(protected ?string $author)
    {
    }

    public function handle(Builder $query, Closure $next): mixed
    {
        if (!empty($this->author)) {
            $query->where('author', $this->author);
        }

        return $next($query);
    }
}
