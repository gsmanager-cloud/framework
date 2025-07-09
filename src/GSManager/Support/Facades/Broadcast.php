<?php

namespace GSManager\Support\Facades;

use GSManager\Contracts\Broadcasting\Factory as BroadcastingFactoryContract;

/**
 * @method static void routes(array|null $attributes = null)
 * @method static void userRoutes(array|null $attributes = null)
 * @method static void channelRoutes(array|null $attributes = null)
 * @method static string|null socket(\GSManager\Http\Request|null $request = null)
 * @method static \GSManager\Broadcasting\AnonymousEvent on(\GSManager\Broadcasting\Channel|array|string $channels)
 * @method static \GSManager\Broadcasting\AnonymousEvent private(string $channel)
 * @method static \GSManager\Broadcasting\AnonymousEvent presence(string $channel)
 * @method static \GSManager\Broadcasting\PendingBroadcast event(mixed|null $event = null)
 * @method static void queue(mixed $event)
 * @method static mixed connection(string|null $driver = null)
 * @method static mixed driver(string|null $name = null)
 * @method static \Pusher\Pusher pusher(array $config)
 * @method static \Ably\AblyRest ably(array $config)
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static void purge(string|null $name = null)
 * @method static \GSManager\Broadcasting\BroadcastManager extend(string $driver, \Closure $callback)
 * @method static \GSManager\Contracts\Foundation\Application getApplication()
 * @method static \GSManager\Broadcasting\BroadcastManager setApplication(\GSManager\Contracts\Foundation\Application $app)
 * @method static \GSManager\Broadcasting\BroadcastManager forgetDrivers()
 * @method static mixed auth(\GSManager\Http\Request $request)
 * @method static mixed validAuthenticationResponse(\GSManager\Http\Request $request, mixed $result)
 * @method static void broadcast(array $channels, string $event, array $payload = [])
 * @method static array|null resolveAuthenticatedUser(\GSManager\Http\Request $request)
 * @method static void resolveAuthenticatedUserUsing(\Closure $callback)
 * @method static \GSManager\Broadcasting\Broadcasters\Broadcaster channel(\GSManager\Contracts\Broadcasting\HasBroadcastChannel|string $channel, callable|string $callback, array $options = [])
 * @method static \GSManager\Support\Collection getChannels()
 *
 * @see \GSManager\Broadcasting\BroadcastManager
 * @see \GSManager\Broadcasting\Broadcasters\Broadcaster
 */
class Broadcast extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BroadcastingFactoryContract::class;
    }
}
