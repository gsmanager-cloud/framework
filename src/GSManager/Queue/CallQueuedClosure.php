<?php

namespace GSManager\Queue;

use Closure;
use GSManager\Bus\Batchable;
use GSManager\Bus\Queueable;
use GSManager\Contracts\Container\Container;
use GSManager\Contracts\Queue\ShouldQueue;
use GSManager\Foundation\Bus\Dispatchable;
use GSManager\SerializableClosure\SerializableClosure;
use ReflectionFunction;

class CallQueuedClosure implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The serializable Closure instance.
     *
     * @var \GSManager\SerializableClosure\SerializableClosure
     */
    public $closure;

    /**
     * The name assigned to the job.
     *
     * @var string|null
     */
    public $name = null;

    /**
     * The callbacks that should be executed on failure.
     *
     * @var array
     */
    public $failureCallbacks = [];

    /**
     * Indicate if the job should be deleted when models are missing.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param  \GSManager\SerializableClosure\SerializableClosure  $closure
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Create a new job instance.
     *
     * @param  \Closure  $job
     * @return self
     */
    public static function create(Closure $job)
    {
        return new self(new SerializableClosure($job));
    }

    /**
     * Execute the job.
     *
     * @param  \GSManager\Contracts\Container\Container  $container
     * @return void
     */
    public function handle(Container $container)
    {
        $container->call($this->closure->getClosure(), ['job' => $this]);
    }

    /**
     * Add a callback to be executed if the job fails.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function onFailure($callback)
    {
        $this->failureCallbacks[] = $callback instanceof Closure
            ? new SerializableClosure($callback)
            : $callback;

        return $this;
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function failed($e)
    {
        foreach ($this->failureCallbacks as $callback) {
            $callback($e);
        }
    }

    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName()
    {
        $reflection = new ReflectionFunction($this->closure->getClosure());

        $prefix = is_null($this->name) ? '' : "{$this->name} - ";

        return $prefix.'Closure ('.basename($reflection->getFileName()).':'.$reflection->getStartLine().')';
    }

    /**
     * Assign a name to the job.
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }
}
