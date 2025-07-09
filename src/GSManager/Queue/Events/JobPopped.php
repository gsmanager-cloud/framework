<?php

namespace GSManager\Queue\Events;

class JobPopped
{
    /**
     * Create a new event instance.
     *
     * @param  string  $connectionName  The connection name.
     * @param  \GSManager\Contracts\Queue\Job|null  $job  The job instance.
     */
    public function __construct(
        public $connectionName,
        public $job,
    ) {
    }
}
