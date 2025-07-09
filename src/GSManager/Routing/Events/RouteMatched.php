<?php

namespace GSManager\Routing\Events;

class RouteMatched
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Routing\Route  $route  The route instance.
     * @param  \GSManager\Http\Request  $request  The request instance.
     */
    public function __construct(
        public $route,
        public $request,
    ) {
    }
}
