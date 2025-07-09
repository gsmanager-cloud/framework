<?php

namespace GSManager\Process;

use GSManager\Support\Collection;
use InvalidArgumentException;

/**
 * @mixin \GSManager\Process\Factory
 * @mixin \GSManager\Process\PendingProcess
 */
class Pool
{
    /**
     * The process factory instance.
     *
     * @var \GSManager\Process\Factory
     */
    protected $factory;

    /**
     * The callback that resolves the pending processes.
     *
     * @var callable
     */
    protected $callback;

    /**
     * The array of pending processes.
     *
     * @var array
     */
    protected $pendingProcesses = [];

    /**
     * Create a new process pool.
     *
     * @param  \GSManager\Process\Factory  $factory
     * @param  callable  $callback
     */
    public function __construct(Factory $factory, callable $callback)
    {
        $this->factory = $factory;
        $this->callback = $callback;
    }

    /**
     * Add a process to the pool with a key.
     *
     * @param  string  $key
     * @return \GSManager\Process\PendingProcess
     */
    public function as(string $key)
    {
        return tap($this->factory->newPendingProcess(), function ($pendingProcess) use ($key) {
            $this->pendingProcesses[$key] = $pendingProcess;
        });
    }

    /**
     * Start all of the processes in the pool.
     *
     * @param  callable|null  $output
     * @return \GSManager\Process\InvokedProcessPool
     */
    public function start(?callable $output = null)
    {
        call_user_func($this->callback, $this);

        return new InvokedProcessPool(
            (new Collection($this->pendingProcesses))
                ->each(function ($pendingProcess) {
                    if (! $pendingProcess instanceof PendingProcess) {
                        throw new InvalidArgumentException('Process pool must only contain pending processes.');
                    }
                })
                ->mapWithKeys(function ($pendingProcess, $key) use ($output) {
                    return [$key => $pendingProcess->start(output: $output ? function ($type, $buffer) use ($key, $output) {
                        $output($type, $buffer, $key);
                    } : null)];
                })
                ->all()
        );
    }

    /**
     * Start and wait for the processes to finish.
     *
     * @return \GSManager\Process\ProcessPoolResults
     */
    public function run()
    {
        return $this->wait();
    }

    /**
     * Start and wait for the processes to finish.
     *
     * @return \GSManager\Process\ProcessPoolResults
     */
    public function wait()
    {
        return $this->start()->wait();
    }

    /**
     * Dynamically proxy methods calls to a new pending process.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \GSManager\Process\PendingProcess
     */
    public function __call($method, $parameters)
    {
        return tap($this->factory->{$method}(...$parameters), function ($pendingProcess) {
            $this->pendingProcesses[] = $pendingProcess;
        });
    }
}
