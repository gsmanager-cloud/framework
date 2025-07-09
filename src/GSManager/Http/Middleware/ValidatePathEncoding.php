<?php

namespace GSManager\Http\Middleware;

use Closure;
use GSManager\Http\Exceptions\MalformedUrlException;
use GSManager\Http\Request;

class ValidatePathEncoding
{
    /**
     * Validate that the incoming request has a valid UTF-8 encoded path.
     *
     * @param  \GSManager\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $decodedPath = rawurldecode($request->path());

        if (! mb_check_encoding($decodedPath, 'UTF-8')) {
            throw new MalformedUrlException;
        }

        return $next($request);
    }
}
