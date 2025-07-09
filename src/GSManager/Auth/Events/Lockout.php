<?php

namespace GSManager\Auth\Events;

use GSManager\Http\Request;

class Lockout
{
    /**
     * The throttled request.
     *
     * @var \GSManager\Http\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
