<?php

namespace GSManager\Contracts\Routing;

interface Registrar
{
    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function get($uri, $action);

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function post($uri, $action);

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function put($uri, $action);

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function delete($uri, $action);

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function patch($uri, $action);

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function options($uri, $action);

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return \GSManager\Routing\Route
     */
    public function match($methods, $uri, $action);

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \GSManager\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = []);

    /**
     * Create a route group with shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure|string  $routes
     * @return void
     */
    public function group(array $attributes, $routes);

    /**
     * Substitute the route bindings onto the route.
     *
     * @param  \GSManager\Routing\Route  $route
     * @return \GSManager\Routing\Route
     */
    public function substituteBindings($route);

    /**
     * Substitute the implicit Eloquent model bindings for the route.
     *
     * @param  \GSManager\Routing\Route  $route
     * @return void
     */
    public function substituteImplicitBindings($route);
}
