<?php

namespace GSManager\Routing\Contracts;

use GSManager\Routing\Route;

interface CallableDispatcher
{
    /**
     * Dispatch a request to a given callable.
     *
     * @param  \GSManager\Routing\Route  $route
     * @param  callable  $callable
     * @return mixed
     */
    public function dispatch(Route $route, $callable);
}
