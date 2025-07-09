<?php

namespace GSManager\Foundation\Http\Events;

class RequestHandled
{
    /**
     * The request instance.
     *
     * @var \GSManager\Http\Request
     */
    public $request;

    /**
     * The response instance.
     *
     * @var \GSManager\Http\Response
     */
    public $response;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Request  $request
     * @param  \GSManager\Http\Response  $response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
