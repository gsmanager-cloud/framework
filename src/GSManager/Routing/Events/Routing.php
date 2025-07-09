<?php

namespace GSManager\Routing\Events;

class Routing
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Http\Request  $request  The request instance.
     */
    public function __construct(
        public $request,
    ) {
    }
}
