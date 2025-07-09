<?php

namespace GSManager\Routing\Middleware;

use Closure;
use GSManager\Contracts\Routing\Registrar;
use GSManager\Database\Eloquent\ModelNotFoundException;

class SubstituteBindings
{
    /**
     * The router instance.
     *
     * @var \GSManager\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new bindings substitutor.
     *
     * @param  \GSManager\Contracts\Routing\Registrar  $router
     */
    public function __construct(Registrar $router)
    {
        $this->router = $router;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \GSManager\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();

        try {
            $this->router->substituteBindings($route);
            $this->router->substituteImplicitBindings($route);
        } catch (ModelNotFoundException $exception) {
            if ($route->getMissing()) {
                return $route->getMissing()($request, $exception);
            }

            throw $exception;
        }

        return $next($request);
    }
}
