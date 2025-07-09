<?php

namespace GSManager\Console\Events;

use GSManager\Console\Scheduling\Event;

class ScheduledTaskStarting
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Console\Scheduling\Event  $task  The scheduled event being run.
     */
    public function __construct(
        public Event $task,
    ) {
    }
}
