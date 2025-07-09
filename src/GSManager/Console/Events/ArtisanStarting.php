<?php

namespace GSManager\Console\Events;

use GSManager\Console\Application;

class ArtisanStarting
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Console\Application  $gsm  The Artisan application instance.
     */
    public function __construct(
        public Application $gsm,
    ) {
    }
}
