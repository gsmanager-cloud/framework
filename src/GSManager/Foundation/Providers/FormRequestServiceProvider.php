<?php

namespace GSManager\Foundation\Providers;

use GSManager\Contracts\Validation\ValidatesWhenResolved;
use GSManager\Foundation\Http\FormRequest;
use GSManager\Routing\Redirector;
use GSManager\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(FormRequest::class, function ($request, $app) {
            $request = FormRequest::createFrom($app['request'], $request);

            $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        });
    }
}
