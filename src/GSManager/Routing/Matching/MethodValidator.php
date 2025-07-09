<?php

namespace GSManager\Routing\Matching;

use GSManager\Http\Request;
use GSManager\Routing\Route;

class MethodValidator implements ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param  \GSManager\Routing\Route  $route
     * @param  \GSManager\Http\Request  $request
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        return in_array($request->getMethod(), $route->methods());
    }
}
