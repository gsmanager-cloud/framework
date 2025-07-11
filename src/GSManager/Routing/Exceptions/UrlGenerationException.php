<?php

namespace GSManager\Routing\Exceptions;

use Exception;
use GSManager\Routing\Route;
use GSManager\Support\Str;

class UrlGenerationException extends Exception
{
    /**
     * Create a new exception for missing route parameters.
     *
     * @param  \GSManager\Routing\Route  $route
     * @param  array  $parameters
     * @return static
     */
    public static function forMissingParameters(Route $route, array $parameters = [])
    {
        $parameterLabel = Str::plural('parameter', count($parameters));

        $message = sprintf(
            'Missing required %s for [Route: %s] [URI: %s]',
            $parameterLabel,
            $route->getName(),
            $route->uri()
        );

        if (count($parameters) > 0) {
            $message .= sprintf(' [Missing %s: %s]', $parameterLabel, implode(', ', $parameters));
        }

        $message .= '.';

        return new static($message);
    }
}
