<?php

namespace GSManager\Contracts\Queue;

interface Factory
{
    /**
     * Resolve a queue connection instance.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Queue\Queue
     */
    public function connection($name = null);
}
