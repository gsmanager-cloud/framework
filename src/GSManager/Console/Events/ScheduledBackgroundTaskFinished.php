<?php

namespace GSManager\Console\Events;

use GSManager\Console\Scheduling\Event;

class ScheduledBackgroundTaskFinished
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Console\Scheduling\Event  $task  The scheduled event that ran.
     */
    public function __construct(
        public Event $task,
    ) {
    }
}
