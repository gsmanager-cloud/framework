<?php

namespace GSManager\Concurrency;

use Closure;
use Exception;
use GSManager\Console\Application;
use GSManager\Contracts\Concurrency\Driver;
use GSManager\Process\Factory as ProcessFactory;
use GSManager\Process\Pool;
use GSManager\Support\Arr;
use GSManager\Support\Defer\DeferredCallback;
use GSManager\SerializableClosure\SerializableClosure;

use function GSManager\Support\defer;

class ProcessDriver implements Driver
{
    /**
     * Create a new process based concurrency driver.
     */
    public function __construct(protected ProcessFactory $processFactory)
    {
        //
    }

    /**
     * Run the given tasks concurrently and return an array containing the results.
     */
    public function run(Closure|array $tasks): array
    {
        $command = Application::formatCommandString('invoke-serialized-closure');

        $results = $this->processFactory->pool(function (Pool $pool) use ($tasks, $command) {
            foreach (Arr::wrap($tasks) as $key => $task) {
                $pool->as($key)->path(base_path())->env([
                    'GSMANAGER_INVOKABLE_CLOSURE' => serialize(new SerializableClosure($task)),
                ])->command($command);
            }
        })->start()->wait();

        return $results->collect()->mapWithKeys(function ($result, $key) {
            if ($result->failed()) {
                throw new Exception('Concurrent process failed with exit code ['.$result->exitCode().']. Message: '.$result->errorOutput());
            }

            $result = json_decode($result->output(), true);

            if (! $result['successful']) {
                throw new $result['exception'](
                    ...(! empty(array_filter($result['parameters']))
                        ? $result['parameters']
                        : [$result['message']])
                );
            }

            return [$key => unserialize($result['result'])];
        })->all();
    }

    /**
     * Start the given tasks in the background after the current task has finished.
     */
    public function defer(Closure|array $tasks): DeferredCallback
    {
        $command = Application::formatCommandString('invoke-serialized-closure');

        return defer(function () use ($tasks, $command) {
            foreach (Arr::wrap($tasks) as $task) {
                $this->processFactory->path(base_path())->env([
                    'GSMANAGER_INVOKABLE_CLOSURE' => serialize(new SerializableClosure($task)),
                ])->run($command.' 2>&1 &');
            }
        });
    }
}
