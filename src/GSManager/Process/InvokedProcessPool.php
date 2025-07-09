<?php

namespace GSManager\Process;

use Countable;
use GSManager\Support\Collection;

class InvokedProcessPool implements Countable
{
    /**
     * The array of invoked processes.
     *
     * @var array
     */
    protected $invokedProcesses;

    /**
     * Create a new invoked process pool.
     *
     * @param  array  $invokedProcesses
     */
    public function __construct(array $invokedProcesses)
    {
        $this->invokedProcesses = $invokedProcesses;
    }

    /**
     * Send a signal to each running process in the pool, returning the processes that were signalled.
     *
     * @param  int  $signal
     * @return \GSManager\Support\Collection
     */
    public function signal(int $signal)
    {
        return $this->running()->each->signal($signal);
    }

    /**
     * Stop all processes that are still running.
     *
     * @param  float  $timeout
     * @param  int|null  $signal
     * @return \GSManager\Support\Collection
     */
    public function stop(float $timeout = 10, ?int $signal = null)
    {
        return $this->running()->each->stop($timeout, $signal);
    }

    /**
     * Get the processes in the pool that are still currently running.
     *
     * @return \GSManager\Support\Collection
     */
    public function running()
    {
        return (new Collection($this->invokedProcesses))->filter->running()->values();
    }

    /**
     * Wait for the processes to finish.
     *
     * @return \GSManager\Process\ProcessPoolResults
     */
    public function wait()
    {
        return new ProcessPoolResults((new Collection($this->invokedProcesses))->map->wait()->all());
    }

    /**
     * Get the total number of processes.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->invokedProcesses);
    }
}
