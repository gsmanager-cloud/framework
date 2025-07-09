<?php

namespace GSManager\Foundation\Exceptions;

use GSManager\Support\Collection;
use GSManager\Support\Facades\View;

class RegisterErrorViewPaths
{
    /**
     * Register the error view paths.
     *
     * @return void
     */
    public function __invoke()
    {
        View::replaceNamespace('errors', (new Collection(config('view.paths')))->map(function ($path) {
            return "{$path}/errors";
        })->push(__DIR__.'/views')->all());
    }
}
