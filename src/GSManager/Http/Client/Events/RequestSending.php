<?php

namespace GSManager\Http\Client\Events;

use GSManager\Http\Client\Request;

class RequestSending
{
    /**
     * The request instance.
     *
     * @var \GSManager\Http\Client\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Client\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
