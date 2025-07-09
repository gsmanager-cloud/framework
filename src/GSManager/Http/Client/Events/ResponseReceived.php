<?php

namespace GSManager\Http\Client\Events;

use GSManager\Http\Client\Request;
use GSManager\Http\Client\Response;

class ResponseReceived
{
    /**
     * The request instance.
     *
     * @var \GSManager\Http\Client\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \GSManager\Http\Client\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Client\Request  $request
     * @param  \GSManager\Http\Client\Response  $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
