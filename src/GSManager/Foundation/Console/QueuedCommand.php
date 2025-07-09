<?php

namespace GSManager\Foundation\Console;

use GSManager\Bus\Queueable;
use GSManager\Contracts\Console\Kernel as KernelContract;
use GSManager\Contracts\Queue\ShouldQueue;
use GSManager\Foundation\Bus\Dispatchable;

class QueuedCommand implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * The data to pass to the Artisan command.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param  array  $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Handle the job.
     *
     * @param  \GSManager\Contracts\Console\Kernel  $kernel
     * @return void
     */
    public function handle(KernelContract $kernel)
    {
        $kernel->call(...array_values($this->data));
    }

    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName()
    {
        return array_values($this->data)[0];
    }
}
