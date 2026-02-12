<?php

namespace App\Pipelines\Articles\Filters;

use App\Pipelines\Articles\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByCategory implements Filter
{
    public function __construct(protected ?string $category)
    {
    }

    public function handle(Builder $query, Closure $next): mixed
    {
        if (!empty($this->category)) {
            $query->where('category', $this->category);
        }

        return $next($query);
    }
}
