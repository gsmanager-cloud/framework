<?php

namespace GSManager\Foundation\Routing;

use GSManager\Routing\CallableDispatcher;
use GSManager\Routing\Route;

class PrecognitionCallableDispatcher extends CallableDispatcher
{
    /**
     * Dispatch a request to a given callable.
     *
     * @param  \GSManager\Routing\Route  $route
     * @param  callable  $callable
     * @return mixed
     */
    public function dispatch(Route $route, $callable)
    {
        $this->resolveParameters($route, $callable);

        abort(204, headers: ['Precognition-Success' => 'true']);
    }
}
