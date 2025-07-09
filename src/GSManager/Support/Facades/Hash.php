<?php

namespace GSManager\Support\Facades;

/**
 * @method static \GSManager\Hashing\BcryptHasher createBcryptDriver()
 * @method static \GSManager\Hashing\ArgonHasher createArgonDriver()
 * @method static \GSManager\Hashing\Argon2IdHasher createArgon2idDriver()
 * @method static array info(string $hashedValue)
 * @method static string make(string $value, array $options = [])
 * @method static bool check(string $value, string $hashedValue, array $options = [])
 * @method static bool needsRehash(string $hashedValue, array $options = [])
 * @method static bool isHashed(string $value)
 * @method static string getDefaultDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \GSManager\Hashing\HashManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \GSManager\Contracts\Container\Container getContainer()
 * @method static \GSManager\Hashing\HashManager setContainer(\GSManager\Contracts\Container\Container $container)
 * @method static \GSManager\Hashing\HashManager forgetDrivers()
 *
 * @see \GSManager\Hashing\HashManager
 * @see \GSManager\Hashing\AbstractHasher
 */
class Hash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hash';
    }
}
