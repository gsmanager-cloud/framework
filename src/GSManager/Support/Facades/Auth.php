<?php

namespace GSManager\Support\Facades;

use GSManager\Ui\UiServiceProvider;
use RuntimeException;

/**
 * @method static \GSManager\Contracts\Auth\Guard|\GSManager\Contracts\Auth\StatefulGuard guard(string|null $name = null)
 * @method static \GSManager\Auth\SessionGuard createSessionDriver(string $name, array $config)
 * @method static \GSManager\Auth\TokenGuard createTokenDriver(string $name, array $config)
 * @method static string getDefaultDriver()
 * @method static void shouldUse(string $name)
 * @method static void setDefaultDriver(string $name)
 * @method static \GSManager\Auth\AuthManager viaRequest(string $driver, callable $callback)
 * @method static \Closure userResolver()
 * @method static \GSManager\Auth\AuthManager resolveUsersUsing(\Closure $userResolver)
 * @method static \GSManager\Auth\AuthManager extend(string $driver, \Closure $callback)
 * @method static \GSManager\Auth\AuthManager provider(string $name, \Closure $callback)
 * @method static bool hasResolvedGuards()
 * @method static \GSManager\Auth\AuthManager forgetGuards()
 * @method static \GSManager\Auth\AuthManager setApplication(\GSManager\Contracts\Foundation\Application $app)
 * @method static \GSManager\Contracts\Auth\UserProvider|null createUserProvider(string|null $provider = null)
 * @method static string getDefaultUserProvider()
 * @method static bool check()
 * @method static bool guest()
 * @method static \GSManager\Contracts\Auth\Authenticatable|null user()
 * @method static int|string|null id()
 * @method static bool validate(array $credentials = [])
 * @method static bool hasUser()
 * @method static \GSManager\Contracts\Auth\Guard setUser(\GSManager\Contracts\Auth\Authenticatable $user)
 * @method static bool attempt(array $credentials = [], bool $remember = false)
 * @method static bool once(array $credentials = [])
 * @method static void login(\GSManager\Contracts\Auth\Authenticatable $user, bool $remember = false)
 * @method static \GSManager\Contracts\Auth\Authenticatable|false loginUsingId(mixed $id, bool $remember = false)
 * @method static \GSManager\Contracts\Auth\Authenticatable|false onceUsingId(mixed $id)
 * @method static bool viaRemember()
 * @method static void logout()
 * @method static \Symfony\Component\HttpFoundation\Response|null basic(string $field = 'email', array $extraConditions = [])
 * @method static \Symfony\Component\HttpFoundation\Response|null onceBasic(string $field = 'email', array $extraConditions = [])
 * @method static bool attemptWhen(array $credentials = [], array|callable|null $callbacks = null, bool $remember = false)
 * @method static void logoutCurrentDevice()
 * @method static \GSManager\Contracts\Auth\Authenticatable|null logoutOtherDevices(string $password)
 * @method static void attempting(mixed $callback)
 * @method static \GSManager\Contracts\Auth\Authenticatable getLastAttempted()
 * @method static string getName()
 * @method static string getRecallerName()
 * @method static \GSManager\Auth\SessionGuard setRememberDuration(int $minutes)
 * @method static \GSManager\Contracts\Cookie\QueueingFactory getCookieJar()
 * @method static void setCookieJar(\GSManager\Contracts\Cookie\QueueingFactory $cookie)
 * @method static \GSManager\Contracts\Events\Dispatcher getDispatcher()
 * @method static void setDispatcher(\GSManager\Contracts\Events\Dispatcher $events)
 * @method static \GSManager\Contracts\Session\Session getSession()
 * @method static \GSManager\Contracts\Auth\Authenticatable|null getUser()
 * @method static \Symfony\Component\HttpFoundation\Request getRequest()
 * @method static \GSManager\Auth\SessionGuard setRequest(\Symfony\Component\HttpFoundation\Request $request)
 * @method static \GSManager\Support\Timebox getTimebox()
 * @method static \GSManager\Contracts\Auth\Authenticatable authenticate()
 * @method static \GSManager\Auth\SessionGuard forgetUser()
 * @method static \GSManager\Contracts\Auth\UserProvider getProvider()
 * @method static void setProvider(\GSManager\Contracts\Auth\UserProvider $provider)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \GSManager\Auth\AuthManager
 * @see \GSManager\Auth\SessionGuard
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth';
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @param  array  $options
     * @return void
     *
     * @throws \RuntimeException
     */
    public static function routes(array $options = [])
    {
        if (! static::$app->providerIsLoaded(UiServiceProvider::class)) {
            throw new RuntimeException('In order to use the Auth::routes() method, please install the gsmanager/ui package.');
        }

        static::$app->make('router')->auth($options);
    }
}
