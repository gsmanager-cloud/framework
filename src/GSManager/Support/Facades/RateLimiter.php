<?php

namespace GSManager\Support\Facades;

/**
 * @method static \GSManager\Cache\RateLimiter for(\BackedEnum|\UnitEnum|string $name, \Closure $callback)
 * @method static \Closure|null limiter(\BackedEnum|\UnitEnum|string $name)
 * @method static mixed attempt(string $key, int $maxAttempts, \Closure $callback, \DateTimeInterface|\DateInterval|int $decaySeconds = 60)
 * @method static bool tooManyAttempts(string $key, int $maxAttempts)
 * @method static int hit(string $key, \DateTimeInterface|\DateInterval|int $decaySeconds = 60)
 * @method static int increment(string $key, \DateTimeInterface|\DateInterval|int $decaySeconds = 60, int $amount = 1)
 * @method static int decrement(string $key, \DateTimeInterface|\DateInterval|int $decaySeconds = 60, int $amount = 1)
 * @method static mixed attempts(string $key)
 * @method static mixed resetAttempts(string $key)
 * @method static int remaining(string $key, int $maxAttempts)
 * @method static int retriesLeft(string $key, int $maxAttempts)
 * @method static void clear(string $key)
 * @method static int availableIn(string $key)
 * @method static string cleanRateLimiterKey(string $key)
 *
 * @see \GSManager\Cache\RateLimiter
 */
class RateLimiter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \GSManager\Cache\RateLimiter::class;
    }
}
