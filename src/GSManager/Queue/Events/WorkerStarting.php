<?php

namespace GSManager\Queue\Events;

class WorkerStarting
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  \GSManager\Queue\WorkerOptions  $options
     */
    public function __construct(
        public $connectionName,
        public $queue,
        public $workerOptions
    ) {
    }
}
