<?php

namespace GSManager\Concurrency;

use Closure;
use GSManager\Contracts\Concurrency\Driver;
use GSManager\Support\Collection;
use GSManager\Support\Defer\DeferredCallback;

use function GSManager\Support\defer;

class SyncDriver implements Driver
{
    /**
     * Run the given tasks concurrently and return an array containing the results.
     */
    public function run(Closure|array $tasks): array
    {
        return Collection::wrap($tasks)->map(
            fn ($task) => $task()
        )->all();
    }

    /**
     * Start the given tasks in the background after the current task has finished.
     */
    public function defer(Closure|array $tasks): DeferredCallback
    {
        return defer(fn () => Collection::wrap($tasks)->each(fn ($task) => $task()));
    }
}
