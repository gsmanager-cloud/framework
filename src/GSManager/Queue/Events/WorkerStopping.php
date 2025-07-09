<?php

namespace GSManager\Queue\Events;

class WorkerStopping
{
    /**
     * Create a new event instance.
     *
     * @param  int  $status  The worker exit status.
     * @param  \GSManager\Queue\WorkerOptions|null  $workerOptions  The worker options.
     */
    public function __construct(
        public $status = 0,
        public $workerOptions = null
    ) {
    }
}
