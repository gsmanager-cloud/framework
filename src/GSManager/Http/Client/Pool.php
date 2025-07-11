<?php

namespace GSManager\Http\Client;

use GuzzleHttp\Utils;

/**
 * @mixin \GSManager\Http\Client\Factory
 */
class Pool
{
    /**
     * The factory instance.
     *
     * @var \GSManager\Http\Client\Factory
     */
    protected $factory;

    /**
     * The handler function for the Guzzle client.
     *
     * @var callable
     */
    protected $handler;

    /**
     * The pool of requests.
     *
     * @var array<array-key, \GSManager\Http\Client\PendingRequest>
     */
    protected $pool = [];

    /**
     * Create a new requests pool.
     *
     * @param  \GSManager\Http\Client\Factory|null  $factory
     */
    public function __construct(?Factory $factory = null)
    {
        $this->factory = $factory ?: new Factory();
        $this->handler = Utils::chooseHandler();
    }

    /**
     * Add a request to the pool with a key.
     *
     * @param  string  $key
     * @return \GSManager\Http\Client\PendingRequest
     */
    public function as(string $key)
    {
        return $this->pool[$key] = $this->asyncRequest();
    }

    /**
     * Retrieve a new async pending request.
     *
     * @return \GSManager\Http\Client\PendingRequest
     */
    protected function asyncRequest()
    {
        return $this->factory->setHandler($this->handler)->async();
    }

    /**
     * Retrieve the requests in the pool.
     *
     * @return array<array-key, \GSManager\Http\Client\PendingRequest>
     */
    public function getRequests()
    {
        return $this->pool;
    }

    /**
     * Add a request to the pool with a numeric index.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \GSManager\Http\Client\PendingRequest|\GuzzleHttp\Promise\Promise
     */
    public function __call($method, $parameters)
    {
        return $this->pool[] = $this->asyncRequest()->$method(...$parameters);
    }
}
