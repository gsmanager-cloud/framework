<?php

namespace GSManager\Support\Facades;

use GSManager\Contracts\Auth\Access\Gate as GateContract;

/**
 * @method static bool has(string|array $ability)
 * @method static \GSManager\Auth\Access\Response allowIf(\GSManager\Auth\Access\Response|\Closure|bool $condition, string|null $message = null, string|null $code = null)
 * @method static \GSManager\Auth\Access\Response denyIf(\GSManager\Auth\Access\Response|\Closure|bool $condition, string|null $message = null, string|null $code = null)
 * @method static \GSManager\Auth\Access\Gate define(\UnitEnum|string $ability, callable|array|string $callback)
 * @method static \GSManager\Auth\Access\Gate resource(string $name, string $class, array|null $abilities = null)
 * @method static \GSManager\Auth\Access\Gate policy(string $class, string $policy)
 * @method static \GSManager\Auth\Access\Gate before(callable $callback)
 * @method static \GSManager\Auth\Access\Gate after(callable $callback)
 * @method static bool allows(iterable|\UnitEnum|string $ability, array|mixed $arguments = [])
 * @method static bool denies(iterable|\UnitEnum|string $ability, array|mixed $arguments = [])
 * @method static bool check(iterable|\UnitEnum|string $abilities, array|mixed $arguments = [])
 * @method static bool any(iterable|\UnitEnum|string $abilities, array|mixed $arguments = [])
 * @method static bool none(iterable|\UnitEnum|string $abilities, array|mixed $arguments = [])
 * @method static \GSManager\Auth\Access\Response authorize(\UnitEnum|string $ability, array|mixed $arguments = [])
 * @method static \GSManager\Auth\Access\Response inspect(\UnitEnum|string $ability, array|mixed $arguments = [])
 * @method static mixed raw(string $ability, array|mixed $arguments = [])
 * @method static mixed getPolicyFor(object|string $class)
 * @method static \GSManager\Auth\Access\Gate guessPolicyNamesUsing(callable $callback)
 * @method static mixed resolvePolicy(object|string $class)
 * @method static \GSManager\Auth\Access\Gate forUser(\GSManager\Contracts\Auth\Authenticatable|mixed $user)
 * @method static array abilities()
 * @method static array policies()
 * @method static \GSManager\Auth\Access\Gate defaultDenialResponse(\GSManager\Auth\Access\Response $response)
 * @method static \GSManager\Auth\Access\Gate setContainer(\GSManager\Contracts\Container\Container $container)
 * @method static \GSManager\Auth\Access\Response denyWithStatus(int $status, string|null $message = null, int|null $code = null)
 * @method static \GSManager\Auth\Access\Response denyAsNotFound(string|null $message = null, int|null $code = null)
 *
 * @see \GSManager\Auth\Access\Gate
 */
class Gate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GateContract::class;
    }
}
