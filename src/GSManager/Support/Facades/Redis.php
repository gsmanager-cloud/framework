<?php

namespace GSManager\Support\Facades;

/**
 * @method static \GSManager\Redis\Connections\Connection connection(string|null $name = null)
 * @method static \GSManager\Redis\Connections\Connection resolve(string|null $name = null)
 * @method static array connections()
 * @method static void enableEvents()
 * @method static void disableEvents()
 * @method static void setDriver(string $driver)
 * @method static void purge(string|null $name = null)
 * @method static \GSManager\Redis\RedisManager extend(string $driver, \Closure $callback)
 * @method static void createSubscription(array|string $channels, \Closure $callback, string $method = 'subscribe')
 * @method static \GSManager\Redis\Limiters\ConcurrencyLimiterBuilder funnel(string $name)
 * @method static \GSManager\Redis\Limiters\DurationLimiterBuilder throttle(string $name)
 * @method static mixed client()
 * @method static void subscribe(array|string $channels, \Closure $callback)
 * @method static void psubscribe(array|string $channels, \Closure $callback)
 * @method static mixed command(string $method, array $parameters = [])
 * @method static void listen(\Closure $callback)
 * @method static string|null getName()
 * @method static \GSManager\Redis\Connections\Connection setName(string $name)
 * @method static \GSManager\Contracts\Events\Dispatcher getEventDispatcher()
 * @method static void setEventDispatcher(\GSManager\Contracts\Events\Dispatcher $events)
 * @method static void unsetEventDispatcher()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 *
 * @see \GSManager\Redis\RedisManager
 */
class Redis extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'redis';
    }
}
