<?php

namespace App\Pipelines\Articles\Filters;

use App\Pipelines\Articles\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByKeyword implements Filter
{
    public function __construct(protected ?string $keyword)
    {
    }

    public function handle(Builder $query, Closure $next): mixed
    {
        if (empty($this->keyword)) {
            return $next($query);
        }

        $query->whereFullText(['title', 'content'], $this->keyword);

        return $next($query);
    }
}
