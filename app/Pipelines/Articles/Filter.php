<?php

namespace App\Pipelines\Articles;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function handle(Builder $query, Closure $next): mixed;
}
