<?php

namespace GSManager\Support\Facades;

use GSManager\Http\Client\Factory;

/**
 * @method static \GSManager\Http\Client\Factory globalMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\Factory globalRequestMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\Factory globalResponseMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\Factory globalOptions(\Closure|array $options)
 * @method static \GuzzleHttp\Promise\PromiseInterface response(array|string|null $body = null, int $status = 200, array $headers = [])
 * @method static \GuzzleHttp\Psr7\Response psr7Response(array|string|null $body = null, int $status = 200, array $headers = [])
 * @method static \GSManager\Http\Client\RequestException failedRequest(array|string|null $body = null, int $status = 200, array $headers = [])
 * @method static \Closure failedConnection(string|null $message = null)
 * @method static \GSManager\Http\Client\ResponseSequence sequence(array $responses = [])
 * @method static bool preventingStrayRequests()
 * @method static \GSManager\Http\Client\Factory allowStrayRequests()
 * @method static \GSManager\Http\Client\Factory record()
 * @method static void recordRequestResponsePair(\GSManager\Http\Client\Request $request, \GSManager\Http\Client\Response|null $response)
 * @method static void assertSent(callable|\Closure $callback)
 * @method static void assertSentInOrder(array $callbacks)
 * @method static void assertNotSent(callable|\Closure $callback)
 * @method static void assertNothingSent()
 * @method static void assertSentCount(int $count)
 * @method static void assertSequencesAreEmpty()
 * @method static \GSManager\Support\Collection recorded(\Closure|callable $callback = null)
 * @method static \GSManager\Http\Client\PendingRequest createPendingRequest()
 * @method static \GSManager\Contracts\Events\Dispatcher|null getDispatcher()
 * @method static array getGlobalMiddleware()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static \GSManager\Http\Client\PendingRequest baseUrl(string $url)
 * @method static \GSManager\Http\Client\PendingRequest withBody(\Psr\Http\Message\StreamInterface|string $content, string $contentType = 'application/json')
 * @method static \GSManager\Http\Client\PendingRequest asJson()
 * @method static \GSManager\Http\Client\PendingRequest asForm()
 * @method static \GSManager\Http\Client\PendingRequest attach(string|array $name, string|resource $contents = '', string|null $filename = null, array $headers = [])
 * @method static \GSManager\Http\Client\PendingRequest asMultipart()
 * @method static \GSManager\Http\Client\PendingRequest bodyFormat(string $format)
 * @method static \GSManager\Http\Client\PendingRequest withQueryParameters(array $parameters)
 * @method static \GSManager\Http\Client\PendingRequest contentType(string $contentType)
 * @method static \GSManager\Http\Client\PendingRequest acceptJson()
 * @method static \GSManager\Http\Client\PendingRequest accept(string $contentType)
 * @method static \GSManager\Http\Client\PendingRequest withHeaders(array $headers)
 * @method static \GSManager\Http\Client\PendingRequest withHeader(string $name, mixed $value)
 * @method static \GSManager\Http\Client\PendingRequest replaceHeaders(array $headers)
 * @method static \GSManager\Http\Client\PendingRequest withBasicAuth(string $username, string $password)
 * @method static \GSManager\Http\Client\PendingRequest withDigestAuth(string $username, string $password)
 * @method static \GSManager\Http\Client\PendingRequest withToken(string $token, string $type = 'Bearer')
 * @method static \GSManager\Http\Client\PendingRequest withUserAgent(string|bool $userAgent)
 * @method static \GSManager\Http\Client\PendingRequest withUrlParameters(array $parameters = [])
 * @method static \GSManager\Http\Client\PendingRequest withCookies(array $cookies, string $domain)
 * @method static \GSManager\Http\Client\PendingRequest maxRedirects(int $max)
 * @method static \GSManager\Http\Client\PendingRequest withoutRedirecting()
 * @method static \GSManager\Http\Client\PendingRequest withoutVerifying()
 * @method static \GSManager\Http\Client\PendingRequest sink(string|resource $to)
 * @method static \GSManager\Http\Client\PendingRequest timeout(int|float $seconds)
 * @method static \GSManager\Http\Client\PendingRequest connectTimeout(int|float $seconds)
 * @method static \GSManager\Http\Client\PendingRequest retry(array|int $times, \Closure|int $sleepMilliseconds = 0, callable|null $when = null, bool $throw = true)
 * @method static \GSManager\Http\Client\PendingRequest withOptions(array $options)
 * @method static \GSManager\Http\Client\PendingRequest withMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\PendingRequest withRequestMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\PendingRequest withResponseMiddleware(callable $middleware)
 * @method static \GSManager\Http\Client\PendingRequest beforeSending(callable $callback)
 * @method static \GSManager\Http\Client\PendingRequest throw(callable|null $callback = null)
 * @method static \GSManager\Http\Client\PendingRequest throwIf(callable|bool $condition)
 * @method static \GSManager\Http\Client\PendingRequest throwUnless(callable|bool $condition)
 * @method static \GSManager\Http\Client\PendingRequest dump()
 * @method static \GSManager\Http\Client\PendingRequest dd()
 * @method static \GSManager\Http\Client\Response get(string $url, array|string|null $query = null)
 * @method static \GSManager\Http\Client\Response head(string $url, array|string|null $query = null)
 * @method static \GSManager\Http\Client\Response post(string $url, array|\JsonSerializable|\GSManager\Contracts\Support\Arrayable $data = [])
 * @method static \GSManager\Http\Client\Response patch(string $url, array|\JsonSerializable|\GSManager\Contracts\Support\Arrayable $data = [])
 * @method static \GSManager\Http\Client\Response put(string $url, array|\JsonSerializable|\GSManager\Contracts\Support\Arrayable $data = [])
 * @method static \GSManager\Http\Client\Response delete(string $url, array|\JsonSerializable|\GSManager\Contracts\Support\Arrayable $data = [])
 * @method static array pool(callable $callback)
 * @method static \GSManager\Http\Client\Response send(string $method, string $url, array $options = [])
 * @method static \GuzzleHttp\Client buildClient()
 * @method static \GuzzleHttp\Client createClient(\GuzzleHttp\HandlerStack $handlerStack)
 * @method static \GuzzleHttp\HandlerStack buildHandlerStack()
 * @method static \GuzzleHttp\HandlerStack pushHandlers(\GuzzleHttp\HandlerStack $handlerStack)
 * @method static \Closure buildBeforeSendingHandler()
 * @method static \Closure buildRecorderHandler()
 * @method static \Closure buildStubHandler()
 * @method static \GuzzleHttp\Psr7\RequestInterface runBeforeSendingCallbacks(\GuzzleHttp\Psr7\RequestInterface $request, array $options)
 * @method static array mergeOptions(array ...$options)
 * @method static \GSManager\Http\Client\PendingRequest stub(callable $callback)
 * @method static \GSManager\Http\Client\PendingRequest async(bool $async = true)
 * @method static \GuzzleHttp\Promise\PromiseInterface|null getPromise()
 * @method static \GSManager\Http\Client\PendingRequest truncateExceptionsAt(int $length)
 * @method static \GSManager\Http\Client\PendingRequest dontTruncateExceptions()
 * @method static \GSManager\Http\Client\PendingRequest setClient(\GuzzleHttp\Client $client)
 * @method static \GSManager\Http\Client\PendingRequest setHandler(callable $handler)
 * @method static array getOptions()
 * @method static \GSManager\Http\Client\PendingRequest|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \GSManager\Http\Client\PendingRequest|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \GSManager\Http\Client\Factory
 */
class Http extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }

    /**
     * Register a stub callable that will intercept requests and be able to return stub responses.
     *
     * @param  \Closure|array  $callback
     * @return \GSManager\Http\Client\Factory
     */
    public static function fake($callback = null)
    {
        return tap(static::getFacadeRoot(), function ($fake) use ($callback) {
            static::swap($fake->fake($callback));
        });
    }

    /**
     * Register a response sequence for the given URL pattern.
     *
     * @param  string  $urlPattern
     * @return \GSManager\Http\Client\ResponseSequence
     */
    public static function fakeSequence(string $urlPattern = '*')
    {
        $fake = tap(static::getFacadeRoot(), function ($fake) {
            static::swap($fake);
        });

        return $fake->fakeSequence($urlPattern);
    }

    /**
     * Indicate that an exception should be thrown if any request is not faked.
     *
     * @param  bool  $prevent
     * @return \GSManager\Http\Client\Factory
     */
    public static function preventStrayRequests($prevent = true)
    {
        return tap(static::getFacadeRoot(), function ($fake) use ($prevent) {
            static::swap($fake->preventStrayRequests($prevent));
        });
    }

    /**
     * Stub the given URL using the given callback.
     *
     * @param  string  $url
     * @param  \GSManager\Http\Client\Response|\GuzzleHttp\Promise\PromiseInterface|callable  $callback
     * @return \GSManager\Http\Client\Factory
     */
    public static function stubUrl($url, $callback)
    {
        return tap(static::getFacadeRoot(), function ($fake) use ($url, $callback) {
            static::swap($fake->stubUrl($url, $callback));
        });
    }
}
