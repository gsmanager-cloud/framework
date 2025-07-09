<?php

namespace GSManager\Support;

use GSManager\Support\Defer\DeferredCallback;
use GSManager\Support\Defer\DeferredCallbackCollection;
use Symfony\Component\Process\PhpExecutableFinder;

if (! function_exists('GSManager\Support\defer')) {
    /**
     * Defer execution of the given callback.
     *
     * @param  callable|null  $callback
     * @param  string|null  $name
     * @param  bool  $always
     * @return \GSManager\Support\Defer\DeferredCallback
     */
    function defer(?callable $callback = null, ?string $name = null, bool $always = false)
    {
        if ($callback === null) {
            return app(DeferredCallbackCollection::class);
        }

        return tap(
            new DeferredCallback($callback, $name, $always),
            fn ($deferred) => app(DeferredCallbackCollection::class)[] = $deferred
        );
    }
}

if (! function_exists('GSManager\Support\php_binary')) {
    /**
     * Determine the PHP Binary.
     *
     * @return string
     */
    function php_binary()
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
    }
}

if (! function_exists('GSManager\Support\gsm_binary')) {
    /**
     * Determine the proper Artisan executable.
     *
     * @return string
     */
    function gsm_binary()
    {
        return defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'gsm';
    }
}
