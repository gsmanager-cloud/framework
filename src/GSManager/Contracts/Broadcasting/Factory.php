<?php

namespace GSManager\Contracts\Broadcasting;

interface Factory
{
    /**
     * Get a broadcaster implementation by name.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Broadcasting\Broadcaster
     */
    public function connection($name = null);
}
