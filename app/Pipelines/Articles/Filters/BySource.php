<?php

namespace App\Pipelines\Articles\Filters;

use App\Pipelines\Articles\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class BySource implements Filter
{
    public function __construct(protected ?string $source)
    {
    }

    public function handle(Builder $query, Closure $next): mixed
    {
        if (!empty($this->source)) {
            $query->where('source', $this->source);
        }

        return $next($query);
    }
}
