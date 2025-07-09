<?php

namespace GSManager\Http\Client\Events;

use GSManager\Http\Client\ConnectionException;
use GSManager\Http\Client\Request;

class ConnectionFailed
{
    /**
     * The request instance.
     *
     * @var \GSManager\Http\Client\Request
     */
    public $request;

    /**
     * The exception instance.
     *
     * @var \GSManager\Http\Client\ConnectionException
     */
    public $exception;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Client\Request  $request
     * @param  \GSManager\Http\Client\ConnectionException  $exception
     */
    public function __construct(Request $request, ConnectionException $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }
}
