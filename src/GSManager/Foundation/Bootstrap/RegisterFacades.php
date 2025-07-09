<?php

namespace GSManager\Foundation\Bootstrap;

use GSManager\Contracts\Foundation\Application;
use GSManager\Foundation\AliasLoader;
use GSManager\Foundation\PackageManifest;
use GSManager\Support\Facades\Facade;

class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param  \GSManager\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);

        AliasLoader::getInstance(array_merge(
            $app->make('config')->get('app.aliases', []),
            $app->make(PackageManifest::class)->aliases()
        ))->register();
    }
}
