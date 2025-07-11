<?php

namespace GSManager\Routing\Matching;

use GSManager\Http\Request;
use GSManager\Routing\Route;

class HostValidator implements ValidatorInterface
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
        $hostRegex = $route->getCompiled()->getHostRegex();

        if (is_null($hostRegex)) {
            return true;
        }

        return preg_match($hostRegex, $request->getHost());
    }
}
