<?php

namespace GSManager\Foundation\Bus;

use GSManager\Bus\UniqueLock;
use GSManager\Container\Container;
use GSManager\Contracts\Bus\Dispatcher;
use GSManager\Contracts\Cache\Repository as Cache;
use GSManager\Contracts\Queue\ShouldBeUnique;
use GSManager\Foundation\Queue\InteractsWithUniqueJobs;

class PendingDispatch
{
    use InteractsWithUniqueJobs;

    /**
     * The job.
     *
     * @var mixed
     */
    protected $job;

    /**
     * Indicates if the job should be dispatched immediately after sending the response.
     *
     * @var bool
     */
    protected $afterResponse = false;

    /**
     * Create a new pending job dispatch.
     *
     * @param  mixed  $job
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * Set the desired connection for the job.
     *
     * @param  \BackedEnum|string|null  $connection
     * @return $this
     */
    public function onConnection($connection)
    {
        $this->job->onConnection($connection);

        return $this;
    }

    /**
     * Set the desired queue for the job.
     *
     * @param  \BackedEnum|string|null  $queue
     * @return $this
     */
    public function onQueue($queue)
    {
        $this->job->onQueue($queue);

        return $this;
    }

    /**
     * Set the desired connection for the chain.
     *
     * @param  \BackedEnum|string|null  $connection
     * @return $this
     */
    public function allOnConnection($connection)
    {
        $this->job->allOnConnection($connection);

        return $this;
    }

    /**
     * Set the desired queue for the chain.
     *
     * @param  \BackedEnum|string|null  $queue
     * @return $this
     */
    public function allOnQueue($queue)
    {
        $this->job->allOnQueue($queue);

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
        $this->job->delay($delay);

        return $this;
    }

    /**
     * Set the delay for the job to zero seconds.
     *
     * @return $this
     */
    public function withoutDelay()
    {
        $this->job->withoutDelay();

        return $this;
    }

    /**
     * Indicate that the job should be dispatched after all database transactions have committed.
     *
     * @return $this
     */
    public function afterCommit()
    {
        $this->job->afterCommit();

        return $this;
    }

    /**
     * Indicate that the job should not wait until database transactions have been committed before dispatching.
     *
     * @return $this
     */
    public function beforeCommit()
    {
        $this->job->beforeCommit();

        return $this;
    }

    /**
     * Set the jobs that should run if this job is successful.
     *
     * @param  array  $chain
     * @return $this
     */
    public function chain($chain)
    {
        $this->job->chain($chain);

        return $this;
    }

    /**
     * Indicate that the job should be dispatched after the response is sent to the browser.
     *
     * @return $this
     */
    public function afterResponse()
    {
        $this->afterResponse = true;

        return $this;
    }

    /**
     * Determine if the job should be dispatched.
     *
     * @return bool
     */
    protected function shouldDispatch()
    {
        if (! $this->job instanceof ShouldBeUnique) {
            return true;
        }

        return (new UniqueLock(Container::getInstance()->make(Cache::class)))
            ->acquire($this->job);
    }

    /**
     * Get the underlying job instance.
     *
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Dynamically proxy methods to the underlying job.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        $this->job->{$method}(...$parameters);

        return $this;
    }

    /**
     * Handle the object's destruction.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->addUniqueJobInformationToContext($this->job);

        if (! $this->shouldDispatch()) {
            $this->removeUniqueJobInformationFromContext($this->job);

            return;
        } elseif ($this->afterResponse) {
            app(Dispatcher::class)->dispatchAfterResponse($this->job);
        } else {
            app(Dispatcher::class)->dispatch($this->job);
        }

        $this->removeUniqueJobInformationFromContext($this->job);
    }
}
