<?php

namespace GSManager\Routing;

use GSManager\Http\RedirectResponse;
use GSManager\Http\Request;
use GSManager\Support\Collection;
use GSManager\Support\Str;

class RedirectController extends Controller
{
    /**
     * Invoke the controller method.
     *
     * @param  \GSManager\Http\Request  $request
     * @param  \GSManager\Routing\UrlGenerator  $url
     * @return \GSManager\Http\RedirectResponse
     */
    public function __invoke(Request $request, UrlGenerator $url)
    {
        $parameters = new Collection($request->route()->parameters());

        $status = $parameters->get('status');

        $destination = $parameters->get('destination');

        $parameters->forget('status')->forget('destination');

        $route = (new Route('GET', $destination, [
            'as' => 'gsmanager_route_redirect_destination',
        ]))->bind($request);

        $parameters = $parameters->only(
            $route->getCompiled()->getPathVariables()
        )->all();

        $url = $url->toRoute($route, $parameters, false);

        if (! str_starts_with($destination, '/') && str_starts_with($url, '/')) {
            $url = Str::after($url, '/');
        }

        return new RedirectResponse($url, $status);
    }
}
