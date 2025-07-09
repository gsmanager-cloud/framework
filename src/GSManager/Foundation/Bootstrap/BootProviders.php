<?php

namespace GSManager\Foundation\Bootstrap;

use GSManager\Contracts\Foundation\Application;

class BootProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \GSManager\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->boot();
    }
}
