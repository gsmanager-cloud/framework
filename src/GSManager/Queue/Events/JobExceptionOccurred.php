<?php

namespace GSManager\Queue\Events;

class JobExceptionOccurred
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \GSManager\Contracts\Queue\Job  $job  The job instance.
     * @param  \Throwable  $exception  The exception instance.
     */
    public function __construct(
        public $connectionName,
        public $job,
        public $exception,
    ) {
    }
}
