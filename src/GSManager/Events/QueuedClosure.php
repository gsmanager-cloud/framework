<?php

namespace GSManager\Events;

use Closure;
use GSManager\Support\Collection;
use GSManager\SerializableClosure\SerializableClosure;

use function GSManager\Support\enum_value;

class QueuedClosure
{
    /**
     * The underlying Closure.
     *
     * @var \Closure
     */
    public $closure;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue;

    /**
     * The number of seconds before the job should be made available.
     *
     * @var \DateTimeInterface|\DateInterval|int|null
     */
    public $delay;

    /**
     * All of the "catch" callbacks for the queued closure.
     *
     * @var array
     */
    public $catchCallbacks = [];

    /**
     * Create a new queued closure event listener resolver.
     *
     * @param  \Closure  $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * Set the desired connection for the job.
     *
     * @param  \UnitEnum|string|null  $connection
     * @return $this
     */
    public function onConnection($connection)
    {
        $this->connection = enum_value($connection);

        return $this;
    }

    /**
     * Set the desired queue for the job.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return $this
     */
    public function onQueue($queue)
    {
        $this->queue = enum_value($queue);

        return $this;
    }

    /**
     * Set the desired delay in seconds for the job.
     *
     * @param  \DateTimeInterface|\DateInterval|int|null  $delay
     * @return $this
     */
    public function delay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Specify a callback that should be invoked if the queued listener job fails.
     *
     * @param  \Closure  $closure
     * @return $this
     */
    public function catch(Closure $closure)
    {
        $this->catchCallbacks[] = $closure;

        return $this;
    }

    /**
     * Resolve the actual event listener callback.
     *
     * @return \Closure
     */
    public function resolve()
    {
        return function (...$arguments) {
            dispatch(new CallQueuedListener(InvokeQueuedClosure::class, 'handle', [
                'closure' => new SerializableClosure($this->closure),
                'arguments' => $arguments,
                'catch' => (new Collection($this->catchCallbacks))
                    ->map(fn ($callback) => new SerializableClosure($callback))
                    ->all(),
            ]))->onConnection($this->connection)->onQueue($this->queue)->delay($this->delay);
        };
    }
}
