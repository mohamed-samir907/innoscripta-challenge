<?php

namespace App\Pipelines\Articles\Filters;

use App\Pipelines\Articles\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByDate implements Filter
{
    public function __construct(protected ?string $dateStart, protected ?string $dateEnd)
    {
    }

    public function handle(Builder $query, Closure $next): mixed
    {
        if ($this->dateStart) {
            $query->whereDate('published_at', '>=', $this->dateStart);
        }

        if ($this->dateEnd) {
            $query->whereDate('published_at', '<=', $this->dateEnd);
        }

        return $next($query);
    }
}
