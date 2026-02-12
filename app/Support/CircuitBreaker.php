<?php

namespace App\Support;

use App\Exceptions\CircuitOpenException;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class CircuitBreaker
{
    /**
     * Max attempts allowed for the call.
     */
    private int $failureThreshold = 5;

    /**
     * Number of seconds that other calls need to wait after
     * reaching the failure threshold.
     */
    private int $openTimeout = 60;

    /**
     * @param string $key Circuit key.
     */
    public function __construct(
        private string $key,
    ) {}

    /**
     * Static factory.
     */
    public static function make(string $key): static
    {
        return new static($key);
    }

    public function call(callable $callback): mixed
    {
        if ($this->getState() == 'open') {
            throw new CircuitOpenException("Circuit is OPEN for {$this->key}");
        }

        try {
            $result = $callback();

            $this->reset();

            return $result;
        } catch (Throwable $e) {
            $this->recordFailure();
            throw $e;
        }
    }

    private function getState(): string
    {
        return Cache::get("cb:{$this->key}:state", 'closed');
    }

    private function reset(): void
    {
        Cache::forget("cb:{$this->key}:failures");
        Cache::put("cb:{$this->key}:state", 'closed');
    }

    private function recordFailure(): void
    {
        $failures = Cache::increment("cb:{$this->key}:failures");

        if ($failures >= $this->failureThreshold) {
            Cache::put("cb:{$this->key}:state", 'open', $this->openTimeout);
        }
    }
}
