<?php

namespace App\Factories;

use App\Contracts\NewsSourceInterface;
use App\Exceptions\SourceNotFoundException;
use App\Services\Sources\NewsAPI;
use App\Services\Sources\NYTimes;
use App\Services\Sources\TheGuardian;

class NewsSourceFactory
{
    public static function create(string $name): NewsSourceInterface
    {
        return match (strtolower($name)) {
            'newsapi' => new NewsAPI,
            'guardian' => new TheGuardian,
            'nytimes' => new NYTimes,
            default => throw new SourceNotFoundException("source: {$name} not found"),
        };
    }
}
