<?php

namespace GSManager\Foundation\Providers;

use GSManager\Contracts\Support\DeferrableProvider;
use GSManager\Database\MigrationServiceProvider;
use GSManager\Support\AggregateServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
