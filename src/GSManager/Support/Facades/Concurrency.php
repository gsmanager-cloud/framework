<?php

namespace GSManager\Support\Facades;

use GSManager\Concurrency\ConcurrencyManager;

/**
 * @method static mixed driver(string|null $name = null)
 * @method static \GSManager\Concurrency\ProcessDriver createProcessDriver(array $config)
 * @method static \GSManager\Concurrency\ForkDriver createForkDriver(array $config)
 * @method static \GSManager\Concurrency\SyncDriver createSyncDriver(array $config)
 * @method static string getDefaultInstance()
 * @method static void setDefaultInstance(string $name)
 * @method static array getInstanceConfig(string $name)
 * @method static mixed instance(string|null $name = null)
 * @method static \GSManager\Concurrency\ConcurrencyManager forgetInstance(array|string|null $name = null)
 * @method static void purge(string|null $name = null)
 * @method static \GSManager\Concurrency\ConcurrencyManager extend(string $name, \Closure $callback)
 * @method static \GSManager\Concurrency\ConcurrencyManager setApplication(\GSManager\Contracts\Foundation\Application $app)
 * @method static array run(\Closure|array $tasks)
 * @method static \GSManager\Support\Defer\DeferredCallback defer(\Closure|array $tasks)
 *
 * @see \GSManager\Concurrency\ConcurrencyManager
 */
class Concurrency extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ConcurrencyManager::class;
    }
}
