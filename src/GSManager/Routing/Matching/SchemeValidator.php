<?php

namespace GSManager\Routing\Matching;

use GSManager\Http\Request;
use GSManager\Routing\Route;

class SchemeValidator implements ValidatorInterface
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
        if ($route->httpOnly()) {
            return ! $request->secure();
        } elseif ($route->secure()) {
            return $request->secure();
        }

        return true;
    }
}
