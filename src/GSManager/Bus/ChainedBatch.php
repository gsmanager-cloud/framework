<?php

namespace GSManager\Bus;

use GSManager\Container\Container;
use GSManager\Contracts\Bus\Dispatcher;
use GSManager\Contracts\Queue\ShouldQueue;
use GSManager\Foundation\Bus\Dispatchable;
use GSManager\Queue\InteractsWithQueue;
use GSManager\Support\Collection;
use Throwable;

class ChainedBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The collection of batched jobs.
     *
     * @var \GSManager\Support\Collection
     */
    public Collection $jobs;

    /**
     * The name of the batch.
     *
     * @var string
     */
    public string $name;

    /**
     * The batch options.
     *
     * @var array
     */
    public array $options;

    /**
     * Create a new chained batch instance.
     *
     * @param  \GSManager\Bus\PendingBatch  $batch
     */
    public function __construct(PendingBatch $batch)
    {
        $this->jobs = static::prepareNestedBatches($batch->jobs);

        $this->name = $batch->name;
        $this->options = $batch->options;
    }

    /**
     * Prepare any nested batches within the given collection of jobs.
     *
     * @param  \GSManager\Support\Collection  $jobs
     * @return \GSManager\Support\Collection
     */
    public static function prepareNestedBatches(Collection $jobs): Collection
    {
        return $jobs->map(fn ($job) => match (true) {
            is_array($job) => static::prepareNestedBatches(new Collection($job))->all(),
            $job instanceof Collection => static::prepareNestedBatches($job),
            $job instanceof PendingBatch => new ChainedBatch($job),
            default => $job,
        });
    }

    /**
     * Handle the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->attachRemainderOfChainToEndOfBatch(
            $this->toPendingBatch()
        )->dispatch();
    }

    /**
     * Convert the chained batch instance into a pending batch.
     *
     * @return \GSManager\Bus\PendingBatch
     */
    public function toPendingBatch()
    {
        $batch = Container::getInstance()->make(Dispatcher::class)->batch($this->jobs);

        $batch->name = $this->name;
        $batch->options = $this->options;

        if ($this->queue) {
            $batch->onQueue($this->queue);
        }

        if ($this->connection) {
            $batch->onConnection($this->connection);
        }

        foreach ($this->chainCatchCallbacks ?? [] as $callback) {
            $batch->catch(function (Batch $batch, ?Throwable $exception) use ($callback) {
                if (! $batch->allowsFailures()) {
                    $callback($exception);
                }
            });
        }

        return $batch;
    }

    /**
     * Move the remainder of the chain to a "finally" batch callback.
     *
     * @param  \GSManager\Bus\PendingBatch  $batch
     * @return \GSManager\Bus\PendingBatch
     */
    protected function attachRemainderOfChainToEndOfBatch(PendingBatch $batch)
    {
        if (! empty($this->chained)) {
            $next = unserialize(array_shift($this->chained));

            $next->chained = $this->chained;

            $next->onConnection($next->connection ?: $this->chainConnection);
            $next->onQueue($next->queue ?: $this->chainQueue);

            $next->chainConnection = $this->chainConnection;
            $next->chainQueue = $this->chainQueue;
            $next->chainCatchCallbacks = $this->chainCatchCallbacks;

            $batch->finally(function (Batch $batch) use ($next) {
                if (! $batch->cancelled()) {
                    Container::getInstance()->make(Dispatcher::class)->dispatch($next);
                }
            });

            $this->chained = [];
        }

        return $batch;
    }
}
