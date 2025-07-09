<?php

namespace GSManager\Routing\Matching;

use GSManager\Http\Request;
use GSManager\Routing\Route;

interface ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param  \GSManager\Routing\Route  $route
     * @param  \GSManager\Http\Request  $request
     * @return bool
     */
    public function matches(Route $route, Request $request);
}
