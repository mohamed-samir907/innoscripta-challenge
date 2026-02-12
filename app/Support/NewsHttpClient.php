<?php

namespace App\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class NewsHttpClient
{
    private const HTTP_TIMEOUT = 5;

    private const RETRY_ATTEMPTS = 3;

    private const RETRY_ATTEMPTS_WAIT = 200;

    /**
     * Send GET request protected by circuit breaker.
     */
    public function get(string $source, string $url, array|string|null $query = []): Response
    {
        $circuit = CircuitBreaker::make($source);

        return $circuit->call(
            fn() => Http::timeout(seconds: self::HTTP_TIMEOUT)
                ->retry(
                    times: self::RETRY_ATTEMPTS,
                    sleepMilliseconds: self::RETRY_ATTEMPTS_WAIT,
                    when: fn($e, $req): bool => $e instanceof \Illuminate\Http\Client\RequestException,
                )
                ->get($url, $query)
                ->throw()
        );
    }
}
