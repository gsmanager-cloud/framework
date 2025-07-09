<?php

namespace GSManager\Support\Facades;

/**
 * @method static \Psr\Log\LoggerInterface build(array $config)
 * @method static \Psr\Log\LoggerInterface stack(array $channels, string|null $channel = null)
 * @method static \Psr\Log\LoggerInterface channel(string|null $channel = null)
 * @method static \Psr\Log\LoggerInterface driver(string|null $driver = null)
 * @method static \GSManager\Log\LogManager shareContext(array $context)
 * @method static array sharedContext()
 * @method static \GSManager\Log\LogManager withoutContext(string[]|null $keys = null)
 * @method static \GSManager\Log\LogManager flushSharedContext()
 * @method static string|null getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static \GSManager\Log\LogManager extend(string $driver, \Closure $callback)
 * @method static void forgetChannel(string|null $driver = null)
 * @method static array getChannels()
 * @method static void emergency(string|\Stringable $message, array $context = [])
 * @method static void alert(string|\Stringable $message, array $context = [])
 * @method static void critical(string|\Stringable $message, array $context = [])
 * @method static void error(string|\Stringable $message, array $context = [])
 * @method static void warning(string|\Stringable $message, array $context = [])
 * @method static void notice(string|\Stringable $message, array $context = [])
 * @method static void info(string|\Stringable $message, array $context = [])
 * @method static void debug(string|\Stringable $message, array $context = [])
 * @method static void log(mixed $level, string|\Stringable $message, array $context = [])
 * @method static \GSManager\Log\LogManager setApplication(\GSManager\Contracts\Foundation\Application $app)
 * @method static void write(string $level, \GSManager\Contracts\Support\Arrayable|\GSManager\Contracts\Support\Jsonable|\GSManager\Support\Stringable|array|string $message, array $context = [])
 * @method static \GSManager\Log\Logger withContext(array $context = [])
 * @method static void listen(\Closure $callback)
 * @method static \Psr\Log\LoggerInterface getLogger()
 * @method static \GSManager\Contracts\Events\Dispatcher getEventDispatcher()
 * @method static void setEventDispatcher(\GSManager\Contracts\Events\Dispatcher $dispatcher)
 * @method static \GSManager\Log\Logger|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \GSManager\Log\Logger|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \GSManager\Log\LogManager
 */
class Log extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'log';
    }
}
