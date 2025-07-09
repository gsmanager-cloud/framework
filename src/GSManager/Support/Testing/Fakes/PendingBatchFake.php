<?php

namespace GSManager\Support\Testing\Fakes;

use GSManager\Bus\PendingBatch;
use GSManager\Support\Collection;

class PendingBatchFake extends PendingBatch
{
    /**
     * The fake bus instance.
     *
     * @var \GSManager\Support\Testing\Fakes\BusFake
     */
    protected $bus;

    /**
     * Create a new pending batch instance.
     *
     * @param  \GSManager\Support\Testing\Fakes\BusFake  $bus
     * @param  \GSManager\Support\Collection  $jobs
     */
    public function __construct(BusFake $bus, Collection $jobs)
    {
        $this->bus = $bus;
        $this->jobs = $jobs;
    }

    /**
     * Dispatch the batch.
     *
     * @return \GSManager\Bus\Batch
     */
    public function dispatch()
    {
        return $this->bus->recordPendingBatch($this);
    }

    /**
     * Dispatch the batch after the response is sent to the browser.
     *
     * @return \GSManager\Bus\Batch
     */
    public function dispatchAfterResponse()
    {
        return $this->bus->recordPendingBatch($this);
    }
}
